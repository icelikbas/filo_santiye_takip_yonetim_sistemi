<?php

namespace App\Controllers;

use App\Core\Controller;

class TrafficFines extends Controller
{
    private $trafficFineModel;
    private $fineTypeModel;
    private $finePaymentModel;
    private $vehicleModel;
    private $driverModel;
    private $userModel;

    public function __construct()
    {
        // Oturum kontrolü
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // Model sınıflarını yükle
        $this->trafficFineModel = $this->model('TrafficFine');
        $this->fineTypeModel = $this->model('FineType');
        $this->finePaymentModel = $this->model('FinePayment');
        $this->vehicleModel = $this->model('Vehicle');
        $this->driverModel = $this->model('Driver');
        $this->userModel = $this->model('User');
    }

    // Trafik cezaları listesini görüntüleme
    public function index()
    {
        // Tüm trafik cezalarını getir
        $trafficFines = $this->trafficFineModel->getTrafficFines();

        // İstatistik verileri
        $totalFines = count($trafficFines);
        $totalAmount = 0;
        $unpaidAmount = 0;
        $paidAmount = 0;

        // Ceza istatistiklerini hesapla
        foreach ($trafficFines as $fine) {
            $totalAmount += $fine->fine_amount;
            
            if ($fine->payment_status == 'Ödendi') {
                $paidAmount += $fine->fine_amount;
            } else {
                $unpaidAmount += $fine->fine_amount;
            }
        }
        
        // Ceza tiplerini getir (filtreleme için)
        $fineTypes = $this->fineTypeModel->getActiveFineTypes();

        // Görünüm verilerini hazırla
        $data = [
            'title' => 'Trafik Cezaları Listesi',
            'trafficFines' => $trafficFines,
            'totalFines' => $totalFines,
            'totalAmount' => $totalAmount,
            'unpaidAmount' => $unpaidAmount,
            'paidAmount' => $paidAmount,
            'fineTypes' => $fineTypes
        ];

        $this->view('trafficfines/index', $data);
    }

    // Trafik cezası detayını gösterme
    public function show($id)
    {
        // Ceza bilgilerini getir
        $trafficFine = $this->trafficFineModel->getTrafficFineById($id);

        if (!$trafficFine) {
            flash('error', 'Trafik cezası bulunamadı');
            redirect('trafficfines');
        }

        // Araç bilgilerini getir
        $vehicle = $this->vehicleModel->getVehicleById($trafficFine->vehicle_id);

        // Sürücü bilgilerini getir (eğer varsa)
        $driver = null;
        if (!empty($trafficFine->driver_id)) {
            $driver = $this->driverModel->getDriverById($trafficFine->driver_id);
        }

        // Ceza ödemelerini getir
        $payments = $this->finePaymentModel->getPaymentsByFineId($id);

        // Toplam ödenen tutarı hesapla
        $totalPaid = 0;
        foreach ($payments as $payment) {
            $totalPaid += $payment->amount;
        }

        // Kalan ödeme tutarını hesapla
        $remainingAmount = $trafficFine->fine_amount - $totalPaid;

        $data = [
            'title' => 'Trafik Cezası Detayı',
            'trafficFine' => $trafficFine,
            'vehicle' => $vehicle,
            'driver' => $driver,
            'payments' => $payments,
            'totalPaid' => $totalPaid,
            'remainingAmount' => $remainingAmount
        ];

        $this->view('trafficfines/show', $data);
    }

    // Yeni trafik cezası ekleme sayfası
    public function add()
    {
        // Araçları getir (select için)
        $vehicles = $this->vehicleModel->getVehiclesForSelect();
        
        // Sürücüleri getir (select için)
        $drivers = $this->driverModel->getDriversForSelect();
        
        // Ceza tiplerini getir
        $fineTypes = $this->fineTypeModel->getActiveFineTypes();

        // POST isteği kontrol edilir
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Form verilerini al
            $data = [
                'fine_number' => trim($_POST['fine_number']),
                'vehicle_id' => !empty($_POST['vehicle_id']) ? trim($_POST['vehicle_id']) : null,
                'driver_id' => !empty($_POST['driver_id']) ? trim($_POST['driver_id']) : null,
                'fine_date' => trim($_POST['fine_date']),
                'fine_time' => !empty($_POST['fine_time']) ? trim($_POST['fine_time']) : null,
                'fine_amount' => trim($_POST['fine_amount']),
                'fine_type_id' => trim($_POST['fine_type_id']),
                'fine_location' => !empty($_POST['fine_location']) ? trim($_POST['fine_location']) : null,
                'description' => !empty($_POST['description']) ? trim($_POST['description']) : null,
                'payment_status' => trim($_POST['payment_status']),
                'payment_date' => !empty($_POST['payment_date']) ? trim($_POST['payment_date']) : null,
                'payment_amount' => !empty($_POST['payment_amount']) ? trim($_POST['payment_amount']) : null,
                'document_url' => !empty($_POST['document_url']) ? trim($_POST['document_url']) : null,
                'fine_number_err' => '',
                'vehicle_id_err' => '',
                'fine_date_err' => '',
                'fine_amount_err' => '',
                'fine_type_id_err' => '',
                'payment_status_err' => '',
                'vehicles' => $vehicles,
                'drivers' => $drivers,
                'fineTypes' => $fineTypes
            ];

            // Ceza numarası doğrulama
            if (empty($data['fine_number'])) {
                $data['fine_number_err'] = 'Lütfen ceza numarasını giriniz';
            } elseif ($this->trafficFineModel->findTrafficFineByNumber($data['fine_number'])) {
                $data['fine_number_err'] = 'Bu ceza numarası zaten kayıtlı';
            }

            // Araç ID doğrulama
            if (empty($data['vehicle_id'])) {
                $data['vehicle_id_err'] = 'Lütfen araç seçiniz';
            }

            // Ceza tarihi doğrulama
            if (empty($data['fine_date'])) {
                $data['fine_date_err'] = 'Lütfen ceza tarihini giriniz';
            }

            // Ceza tutarı doğrulama
            if (empty($data['fine_amount'])) {
                $data['fine_amount_err'] = 'Lütfen ceza tutarını giriniz';
            } elseif (!is_numeric(str_replace(',', '.', $data['fine_amount']))) {
                $data['fine_amount_err'] = 'Geçerli bir tutar giriniz';
            }

            // Ceza tipi doğrulama
            if (empty($data['fine_type_id'])) {
                $data['fine_type_id_err'] = 'Lütfen ceza tipini seçiniz';
            }

            // Ödeme durumu doğrulama
            if (empty($data['payment_status'])) {
                $data['payment_status_err'] = 'Lütfen ödeme durumunu seçiniz';
            }

            // Hata yoksa işleme devam et
            if (
                empty($data['fine_number_err']) &&
                empty($data['vehicle_id_err']) &&
                empty($data['fine_date_err']) &&
                empty($data['fine_amount_err']) &&
                empty($data['fine_type_id_err']) &&
                empty($data['payment_status_err'])
            ) {
                // Veritabanına kaydet
                if ($this->trafficFineModel->addTrafficFine($data)) {
                    flash('traffic_fine_message', 'Trafik cezası başarıyla eklendi');
                    redirect('trafficfines');
                } else {
                    flash('traffic_fine_message', 'Trafik cezası eklenirken bir hata oluştu', 'alert alert-danger');
                    $this->view('trafficfines/add', $data);
                }
            } else {
                // Hatalarla birlikte form sayfasını göster
                $this->view('trafficfines/add', $data);
            }
        } else {
            // GET isteği - form sayfasını göster
            $data = [
                'fine_number' => '',
                'vehicle_id' => '',
                'driver_id' => '',
                'fine_date' => date('Y-m-d'),
                'fine_time' => '',
                'fine_amount' => '',
                'fine_type_id' => '',
                'fine_location' => '',
                'description' => '',
                'payment_status' => 'Ödenmedi',
                'payment_date' => '',
                'payment_amount' => '',
                'document_url' => '',
                'fine_number_err' => '',
                'vehicle_id_err' => '',
                'fine_date_err' => '',
                'fine_amount_err' => '',
                'fine_type_id_err' => '',
                'payment_status_err' => '',
                'vehicles' => $vehicles,
                'drivers' => $drivers,
                'fineTypes' => $fineTypes
            ];

            $this->view('trafficfines/add', $data);
        }
    }

    // Trafik cezası düzenleme sayfası
    public function edit($id)
    {
        // Araçları getir (select için)
        $vehicles = $this->vehicleModel->getVehiclesForSelect();
        
        // Sürücüleri getir (select için)
        $drivers = $this->driverModel->getDriversForSelect();
        
        // Ceza tiplerini getir
        $fineTypes = $this->fineTypeModel->getActiveFineTypes();

        // POST isteği kontrol edilir
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Form verilerini al
            $data = [
                'id' => $id,
                'fine_number' => trim($_POST['fine_number']),
                'vehicle_id' => !empty($_POST['vehicle_id']) ? trim($_POST['vehicle_id']) : null,
                'driver_id' => !empty($_POST['driver_id']) ? trim($_POST['driver_id']) : null,
                'fine_date' => trim($_POST['fine_date']),
                'fine_time' => !empty($_POST['fine_time']) ? trim($_POST['fine_time']) : null,
                'fine_amount' => trim($_POST['fine_amount']),
                'fine_type_id' => trim($_POST['fine_type_id']),
                'fine_location' => !empty($_POST['fine_location']) ? trim($_POST['fine_location']) : null,
                'description' => !empty($_POST['description']) ? trim($_POST['description']) : null,
                'payment_status' => trim($_POST['payment_status']),
                'payment_date' => !empty($_POST['payment_date']) ? trim($_POST['payment_date']) : null,
                'payment_amount' => !empty($_POST['payment_amount']) ? trim($_POST['payment_amount']) : null,
                'document_url' => !empty($_POST['document_url']) ? trim($_POST['document_url']) : null,
                'fine_number_err' => '',
                'vehicle_id_err' => '',
                'fine_date_err' => '',
                'fine_amount_err' => '',
                'fine_type_id_err' => '',
                'payment_status_err' => '',
                'vehicles' => $vehicles,
                'drivers' => $drivers,
                'fineTypes' => $fineTypes
            ];

            // Ceza numarası doğrulama
            if (empty($data['fine_number'])) {
                $data['fine_number_err'] = 'Lütfen ceza numarasını giriniz';
            } else {
                // Aynı numaraya sahip başka bir ceza var mı kontrol et (kendi ID'si hariç)
                $existingFine = $this->trafficFineModel->findTrafficFineByNumber($data['fine_number']);
                if ($existingFine && $existingFine->id != $id) {
                    $data['fine_number_err'] = 'Bu ceza numarası zaten başka bir ceza için kayıtlı';
                }
            }

            // Araç ID doğrulama
            if (empty($data['vehicle_id'])) {
                $data['vehicle_id_err'] = 'Lütfen araç seçiniz';
            }

            // Ceza tarihi doğrulama
            if (empty($data['fine_date'])) {
                $data['fine_date_err'] = 'Lütfen ceza tarihini giriniz';
            }

            // Ceza tutarı doğrulama
            if (empty($data['fine_amount'])) {
                $data['fine_amount_err'] = 'Lütfen ceza tutarını giriniz';
            } elseif (!is_numeric(str_replace(',', '.', $data['fine_amount']))) {
                $data['fine_amount_err'] = 'Geçerli bir tutar giriniz';
            }

            // Ceza tipi doğrulama
            if (empty($data['fine_type_id'])) {
                $data['fine_type_id_err'] = 'Lütfen ceza tipini seçiniz';
            }

            // Ödeme durumu doğrulama
            if (empty($data['payment_status'])) {
                $data['payment_status_err'] = 'Lütfen ödeme durumunu seçiniz';
            }

            // Hata yoksa işleme devam et
            if (
                empty($data['fine_number_err']) &&
                empty($data['vehicle_id_err']) &&
                empty($data['fine_date_err']) &&
                empty($data['fine_amount_err']) &&
                empty($data['fine_type_id_err']) &&
                empty($data['payment_status_err'])
            ) {
                // Veritabanına kaydet
                if ($this->trafficFineModel->updateTrafficFine($data)) {
                    flash('traffic_fine_message', 'Trafik cezası başarıyla güncellendi');
                    redirect('trafficfines/show/' . $id);
                } else {
                    flash('traffic_fine_message', 'Trafik cezası güncellenirken bir hata oluştu', 'alert alert-danger');
                    $this->view('trafficfines/edit', $data);
                }
            } else {
                // Hatalarla birlikte form sayfasını göster
                $this->view('trafficfines/edit', $data);
            }
        } else {
            // Ceza bilgilerini getir
            $trafficFine = $this->trafficFineModel->getTrafficFineById($id);

            if (!$trafficFine) {
                flash('error', 'Trafik cezası bulunamadı');
                redirect('trafficfines');
            }

            // Formu mevcut verilerle doldur
            $data = [
                'id' => $id,
                'fine_number' => $trafficFine->fine_number,
                'vehicle_id' => $trafficFine->vehicle_id,
                'driver_id' => $trafficFine->driver_id,
                'fine_date' => $trafficFine->fine_date,
                'fine_time' => $trafficFine->fine_time,
                'fine_amount' => $trafficFine->fine_amount,
                'fine_type_id' => $trafficFine->fine_type_id,
                'fine_location' => $trafficFine->fine_location,
                'description' => $trafficFine->description,
                'payment_status' => $trafficFine->payment_status,
                'payment_date' => $trafficFine->payment_date,
                'payment_amount' => $trafficFine->payment_amount,
                'document_url' => $trafficFine->document_url,
                'fine_number_err' => '',
                'vehicle_id_err' => '',
                'fine_date_err' => '',
                'fine_amount_err' => '',
                'fine_type_id_err' => '',
                'payment_status_err' => '',
                'vehicles' => $vehicles,
                'drivers' => $drivers,
                'fineTypes' => $fineTypes
            ];

            $this->view('trafficfines/edit', $data);
        }
    }

    // Trafik cezası silme
    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Önce ceza ile ilişkili ödemeleri sil
            $this->finePaymentModel->deletePaymentsByFineId($id);
            
            // Sonra cezayı sil
            if ($this->trafficFineModel->deleteTrafficFine($id)) {
                flash('traffic_fine_message', 'Trafik cezası başarıyla silindi');
            } else {
                flash('traffic_fine_message', 'Trafik cezası silinirken bir hata oluştu', 'alert alert-danger');
            }
            
            redirect('trafficfines');
        } else {
            redirect('trafficfines');
        }
    }

    // Yeni ödeme ekleme
    public function addPayment($fineId)
    {
        // Ceza bilgilerini getir
        $trafficFine = $this->trafficFineModel->getTrafficFineById($fineId);

        if (!$trafficFine) {
            flash('error', 'Trafik cezası bulunamadı');
            redirect('trafficfines');
        }

        // Mevcut ödemeleri getir
        $payments = $this->finePaymentModel->getPaymentsByFineId($fineId);
        
        // Toplam ödenen tutarı hesapla
        $totalPaid = 0;
        foreach ($payments as $payment) {
            $totalPaid += $payment->amount;
        }
        
        // Kalan ödeme tutarını hesapla
        $remainingAmount = $trafficFine->fine_amount - $totalPaid;

        // POST isteği kontrol edilir
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Form verilerini al
            $data = [
                'fine_id' => $fineId,
                'payment_date' => trim($_POST['payment_date']),
                'amount' => trim($_POST['amount']),
                'payment_method' => trim($_POST['payment_method']),
                'receipt_number' => !empty($_POST['receipt_number']) ? trim($_POST['receipt_number']) : null,
                'notes' => !empty($_POST['notes']) ? trim($_POST['notes']) : null,
                'created_by' => $_SESSION['user_id'],
                'payment_date_err' => '',
                'amount_err' => '',
                'payment_method_err' => '',
                'trafficFine' => $trafficFine,
                'payments' => $payments,
                'totalPaid' => $totalPaid,
                'remainingAmount' => $remainingAmount
            ];

            // Ödeme tarihi doğrulama
            if (empty($data['payment_date'])) {
                $data['payment_date_err'] = 'Lütfen ödeme tarihini giriniz';
            }

            // Tutar doğrulama
            if (empty($data['amount'])) {
                $data['amount_err'] = 'Lütfen ödeme tutarını giriniz';
            } elseif (!is_numeric(str_replace(',', '.', $data['amount']))) {
                $data['amount_err'] = 'Geçerli bir tutar giriniz';
            } elseif (floatval(str_replace(',', '.', $data['amount'])) > $remainingAmount) {
                $data['amount_err'] = 'Ödeme tutarı kalan tutardan büyük olamaz';
            }

            // Ödeme yöntemi doğrulama
            if (empty($data['payment_method'])) {
                $data['payment_method_err'] = 'Lütfen ödeme yöntemini seçiniz';
            }

            // Hata yoksa işleme devam et
            if (
                empty($data['payment_date_err']) &&
                empty($data['amount_err']) &&
                empty($data['payment_method_err'])
            ) {
                // Veritabanına kaydet
                if ($this->finePaymentModel->addPayment($data)) {
                    // Toplam ödenmiş tutarı güncelle
                    $newTotalPaid = $totalPaid + floatval(str_replace(',', '.', $data['amount']));
                    
                    // Cezanın ödeme durumunu güncelle
                    $updateData = [
                        'id' => $fineId,
                        'payment_status' => ($newTotalPaid >= $trafficFine->fine_amount) ? 'Ödendi' : 'Taksitli Ödeme',
                        'payment_amount' => $newTotalPaid
                    ];
                    
                    $this->trafficFineModel->updatePaymentStatus($updateData);
                    
                    flash('traffic_fine_message', 'Ödeme başarıyla eklendi');
                    redirect('trafficfines/show/' . $fineId);
                } else {
                    flash('traffic_fine_message', 'Ödeme eklenirken bir hata oluştu', 'alert alert-danger');
                    $this->view('trafficfines/addPayment', $data);
                }
            } else {
                // Hatalarla birlikte form sayfasını göster
                $this->view('trafficfines/addPayment', $data);
            }
        } else {
            // GET isteği - form sayfasını göster
            $data = [
                'fine_id' => $fineId,
                'payment_date' => date('Y-m-d'),
                'amount' => $remainingAmount,
                'payment_method' => '',
                'receipt_number' => '',
                'notes' => '',
                'created_by' => $_SESSION['user_id'],
                'payment_date_err' => '',
                'amount_err' => '',
                'payment_method_err' => '',
                'trafficFine' => $trafficFine,
                'payments' => $payments,
                'totalPaid' => $totalPaid,
                'remainingAmount' => $remainingAmount
            ];

            $this->view('trafficfines/addPayment', $data);
        }
    }
} 