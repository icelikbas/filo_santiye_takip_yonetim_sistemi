<?php

namespace App\Controllers;

use App\Core\Controller;
use \Exception;

class Fuel extends Controller
{
    private $fuelModel;
    private $vehicleModel;
    private $driverModel;
    private $tankModel;
    private $userModel;
    private $assignmentModel;

    public function __construct()
    {
        // Oturum kontrolü
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // Model sınıflarını yükle
        try {
            $this->vehicleModel = $this->model('Vehicle');
            $this->driverModel = $this->model('Driver');
            $this->userModel = $this->model('User');
            
            try {
                $this->fuelModel = $this->model('FuelModel');
            } catch (\Exception $e) {
                error_log('FuelModel yüklenemedi: ' . $e->getMessage());
                flash('error', 'Yakıt modülü yüklenirken bir hata oluştu. Sistem yöneticisiyle görüşün.');
                $this->fuelModel = null;
            }
            
            try {
                $this->tankModel = $this->model('FuelTank');
            } catch (\Exception $e) {
                error_log('FuelTank modeli yüklenemedi: ' . $e->getMessage());
                $this->tankModel = null;
            }
            
            try {
                $this->assignmentModel = $this->model('Assignment');
            } catch (\Exception $e) {
                error_log('Assignment modeli yüklenemedi: ' . $e->getMessage());
                $this->assignmentModel = null;
            }
        } catch (\Exception $e) {
            error_log('Fuel controller modelleri yüklenirken hata: ' . $e->getMessage());
            flash('error', 'Sistem modülleri yüklenirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.');
        }
    }

    /**
     * Yakıt listesi
     */
    public function index()
    {
        // Kullanıcı yetkisi kontrolü
        if (!isLoggedIn()) {
            redirect('');
        }
        
        // FuelModel yüklenemedi ise hata göster
        if ($this->fuelModel === null) {
            flash('error', 'Yakıt verileri yüklenemedi. Sistem yöneticisiyle görüşün.');
            redirect('dashboard');
            return;
        }
        
        if ($this->vehicleModel === null || $this->driverModel === null || 
            $this->tankModel === null || $this->userModel === null) {
            flash('error', 'Gerekli model bileşenleri yüklenemedi. Sistem yöneticisiyle görüşün.');
            redirect('dashboard');
            return;
        }

        try {
            // Sayfa numarası ve limit parametrelerini al
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;

            // Geçerli bir sayfa numarası için kontrol
            if ($page < 1) $page = 1;
            if ($limit < 1) $limit = 20;

            // Yakıt kayıtlarını al
            try {
                $fuelData = $this->fuelModel->getFuelRecords($page, $limit);
            } catch (\Exception $e) {
                error_log('Yakıt kayıtları alınamadı: ' . $e->getMessage());
                $fuelData = [
                    'records' => [],
                    'total_records' => 0,
                    'current_page' => $page,
                    'total_pages' => 0,
                    'limit' => $limit
                ];
            }

            // Araçların yakıt tüketim özetini getir
            try {
                $vehicleConsumption = $this->fuelModel->getVehicleFuelConsumptionSummary();
            } catch (\Exception $e) {
                error_log('Araç yakıt tüketim özeti alınamadı: ' . $e->getMessage());
                $vehicleConsumption = [];
            }

            // Yakıt türüne göre toplam tüketimi getir
            try {
                $fuelConsumptionByType = $this->fuelModel->getFuelConsumptionByType(12); // Son 12 ay
            } catch (\Exception $e) {
                error_log('Yakıt türüne göre tüketim verisi alınamadı: ' . $e->getMessage());
                $fuelConsumptionByType = [];
            }

            // Toplam yakıt miktarını al
            try {
                $totalFuelStats = $this->fuelModel->getTotalFuelStats();
                $totalAmount = isset($totalFuelStats->total_amount) ? $totalFuelStats->total_amount : 0;
            } catch (\Exception $e) {
                error_log('Toplam yakıt istatistikleri alınamadı: ' . $e->getMessage());
                $totalAmount = 0;
            }

            // Bu ayki yakıt miktarını al
            try {
                $currentMonth = date('Y-m-01');
                $lastDayOfMonth = date('Y-m-t');
                $monthlyStats = $this->fuelModel->getFuelStatsByDateRange($currentMonth, $lastDayOfMonth);
                $monthlyAmount = isset($monthlyStats->total_amount) ? $monthlyStats->total_amount : 0;
            } catch (\Exception $e) {
                error_log('Aylık yakıt istatistikleri alınamadı: ' . $e->getMessage());
                $monthlyAmount = 0;
            }

            // Toplam araç sayısını al
            try {
                $totalVehicles = $this->vehicleModel->getTotalVehicleCount();
            } catch (\Exception $e) {
                error_log('Toplam araç sayısı alınamadı: ' . $e->getMessage());
                $totalVehicles = 0;
            }
            
            // Tüm aktif araçları getir
            try {
                $vehicles = $this->fuelModel->getActiveVehiclesForSelect();
            } catch (\Exception $e) {
                error_log('Aktif araçlar listesi alınamadı: ' . $e->getMessage());
                $vehicles = [];
            }
            
            // Tüm aktif sürücüleri getir
            try {
                $drivers = $this->fuelModel->getActiveDriversForSelect();
            } catch (\Exception $e) {
                error_log('Aktif sürücüler listesi alınamadı: ' . $e->getMessage());
                $drivers = [];
            }
            
            // Tüm aktif tankları getir
            try {
                $tanks = $this->tankModel ? $this->tankModel->getActiveTanks() : [];
            } catch (\Exception $e) {
                error_log('Aktif tanklar listesi alınamadı: ' . $e->getMessage());
                $tanks = [];
            }
            
            // Tüm kullanıcıları getir
            try {
                $users = $this->userModel->getUsers();
            } catch (\Exception $e) {
                error_log('Kullanıcılar listesi alınamadı: ' . $e->getMessage());
                $users = [];
            }
            
            // Yakıt türlerini getir
            try {
                $fuelTypes = $this->fuelModel->getFuelTypes();
            } catch (\Exception $e) {
                error_log('Yakıt türleri alınamadı: ' . $e->getMessage());
                $fuelTypes = [];
            }

            $data = [
                'records' => $fuelData['records'],
                'total_records' => $fuelData['total_records'],
                'current_page' => $fuelData['current_page'],
                'total_pages' => $fuelData['total_pages'],
                'limit' => $fuelData['limit'],
                'title' => 'Yakıt Kayıtları',
                'vehicle_consumption' => $vehicleConsumption,
                'fuel_consumption_by_type' => $fuelConsumptionByType,
                'vehicles' => $vehicles,
                'drivers' => $drivers,
                'tanks' => $tanks,
                'users' => $users,
                'fuel_types' => $fuelTypes,
                'serviceProviders' => [], // Servis sağlayıcılar (henüz uygulanmadığı için boş)
                'filters' => [
                    'vehicle_id' => '',
                    'driver_id' => '',
                    'dispenser_id' => '',
                    'tank_id' => '',
                    'fuel_type' => '',
                    'start_date' => '',
                    'end_date' => ''
                ],
                'totalAmount' => $totalAmount,
                'monthlyAmount' => $monthlyAmount,
                'totalVehicles' => $totalVehicles
            ];

            $this->view('fuel/index', $data);
        } catch (Exception $e) {
            error_log('Yakıt sayfası yüklenirken hata: ' . $e->getMessage());
            flash('error', 'Yakıt sayfası yüklenirken bir hata oluştu');
            redirect('dashboard');
        }
    }

    // Yakıt kaydı detaylarını görüntüleme
    public function show($id)
    {
        // FuelModel yüklenemedi ise hata göster
        if ($this->fuelModel === null) {
            flash('error', 'Yakıt verileri yüklenemedi. Sistem yöneticisiyle görüşün.');
            redirect('dashboard');
            return;
        }
        
        try {
            // ID'ye göre yakıt kaydını getir
            $record = $this->fuelModel->getFuelRecordById($id);

            if (!$record) {
                flash('error', 'Kayıt bulunamadı');
                redirect('fuel');
                return;
            }

            $data = [
                'title' => 'Yakıt Kaydı Detayı',
                'record' => $record
            ];

            $this->view('fuel/show', $data);
        } catch (Exception $e) {
            error_log('Yakıt kaydı detayları alınamadı: ' . $e->getMessage());
            flash('error', 'Yakıt kaydı gösterilirken bir hata oluştu');
            redirect('fuel');
        }
    }

    // Yeni yakıt kaydı ekleme
    public function add()
    {
        // Model kontrolleri
        if ($this->fuelModel === null || $this->vehicleModel === null || 
            $this->driverModel === null || $this->tankModel === null || 
            $this->userModel === null) {
            flash('error', 'Gerekli modüller yüklenemedi. Sistem yöneticisiyle görüşün.');
            redirect('dashboard');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Form gönderildi, işle
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                // Veriyi hazırla
                $data = [
                    'vehicle_id' => trim($_POST['vehicle_id']),
                    'driver_id' => !empty($_POST['driver_id']) ? trim($_POST['driver_id']) : null,
                    'tank_id' => trim($_POST['tank_id']),
                    'dispenser_id' => !empty($_POST['dispenser_id']) ? trim($_POST['dispenser_id']) : null,
                    'fuel_type' => trim($_POST['fuel_type']),
                    'amount' => trim($_POST['amount']),
                    'km_reading' => !empty($_POST['km_reading']) ? trim($_POST['km_reading']) : null,
                    'hour_reading' => !empty($_POST['hour_reading']) ? trim($_POST['hour_reading']) : null,
                    'date' => trim($_POST['date']),
                    'time' => !empty($_POST['time']) ? trim($_POST['time']) : null,
                    'notes' => !empty($_POST['notes']) ? trim($_POST['notes']) : null,
                    'vehicles' => $this->vehicleModel->getVehiclesForSelect(),
                    'drivers' => $this->driverModel->getDriversForSelect(),
                    'tanks' => $this->tankModel->getTanksForSelect(),
                    'users' => $this->userModel->getUsers(),
                    'fuel_types' => $this->fuelModel->getFuelTypes(),
                    'vehicle_id_err' => '',
                    'tank_id_err' => '',
                    'fuel_type_err' => '',
                    'amount_err' => '',
                    'date_err' => ''
                ];
                
                // Validate vehicle
                if (empty($data['vehicle_id'])) {
                    $data['vehicle_id_err'] = 'Lütfen bir araç seçin';
                }

                // Validate tank
                if (empty($data['tank_id'])) {
                    $data['tank_id_err'] = 'Lütfen bir yakıt tankı seçin';
                }

                // Validate fuel type
                if (empty($data['fuel_type'])) {
                    $data['fuel_type_err'] = 'Yakıt türü boş olamaz';
                }

                // Validate amount
                if (empty($data['amount'])) {
                    $data['amount_err'] = 'Lütfen yakıt miktarını girin';
                } elseif ($data['amount'] <= 0) {
                    $data['amount_err'] = 'Yakıt miktarı 0\'dan büyük olmalıdır';
                } else {
                    // Check if amount is valid for the tank
                    $tank = $this->tankModel->getTankById($data['tank_id']);
                    if ($tank && $data['amount'] > $tank->current_amount) {
                        $data['amount_err'] = 'Tankta yeterli yakıt bulunmuyor. Mevcut yakıt miktarı: ' . $tank->current_amount . ' lt';
                    }
                }

                // Validate date
                if (empty($data['date'])) {
                    $data['date_err'] = 'Lütfen tarih seçin';
                }

                // Make sure no errors
                if (
                    empty($data['vehicle_id_err']) && empty($data['tank_id_err']) &&
                    empty($data['fuel_type_err']) && empty($data['amount_err']) &&
                    empty($data['date_err'])
                ) {
                    try {
                        // Add fuel record - tank update işlemi artık model içinde yapılıyor
                        if ($this->fuelModel->addFuelRecord($data)) {
                            flash('success', 'Yakıt kaydı başarıyla eklendi');
                            redirect('fuel');
                        } else {
                            // Model tarafında bir hata oluşmuş
                            $tank = $this->tankModel->getTankById($data['tank_id']);
                            if ($tank) {
                                flash('error', 'Yakıt kaydı eklenirken bir hata oluştu. Tanktaki yetersiz yakıt miktarı: ' . $tank->current_amount . ' litre. İstenen miktar: ' . $data['amount'] . ' litre.');
                            } else {
                                flash('error', 'Yakıt kaydı eklenirken bir hata oluştu. Seçilen tank bulunamadı.');
                            }
                            $this->view('fuel/add', $data);
                        }
                    } catch (Exception $e) {
                        error_log('Yakıt kaydı ekleme hatası: ' . $e->getMessage());
                        flash('error', 'Sistem hatası: ' . $e->getMessage());
                        $this->view('fuel/add', $data);
                    }
                } else {
                    // Load view with errors
                    $this->view('fuel/add', $data);
                }
            } catch (Exception $e) {
                error_log('Yakıt kaydı ekleme işlemi sırasında hata: ' . $e->getMessage());
                flash('error', 'İşlem sırasında bir hata oluştu');
                redirect('fuel');
            }
        } else {
            try {
                // Form yükleniyor, verileri hazırla
                $data = [
                    'vehicle_id' => '',
                    'driver_id' => '',
                    'tank_id' => '',
                    'dispenser_id' => '',
                    'fuel_type' => '',
                    'amount' => '',
                    'km_reading' => '',
                    'hour_reading' => '',
                    'date' => date('Y-m-d'),
                    'time' => date('H:i'),
                    'notes' => '',
                    'vehicles' => $this->vehicleModel->getVehiclesForSelect(),
                    'drivers' => $this->driverModel->getDriversForSelect(),
                    'tanks' => $this->tankModel->getTanksForSelect(),
                    'users' => $this->userModel->getUsers(),
                    'fuel_types' => $this->fuelModel->getFuelTypes(),
                    'vehicle_id_err' => '',
                    'tank_id_err' => '',
                    'fuel_type_err' => '',
                    'amount_err' => '',
                    'date_err' => ''
                ];

                $this->view('fuel/add', $data);
            } catch (Exception $e) {
                error_log('Yakıt kaydı ekleme formu yüklenirken hata: ' . $e->getMessage());
                flash('error', 'Form yüklenirken bir hata oluştu');
                redirect('fuel');
            }
        }
    }

    // Yakıt kaydı düzenleme
    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form gönderildi, işle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Veriyi hazırla
            $data = [
                'id' => $id,
                'vehicle_id' => trim($_POST['vehicle_id']),
                'driver_id' => !empty($_POST['driver_id']) ? trim($_POST['driver_id']) : null,
                'tank_id' => trim($_POST['tank_id']),
                'dispenser_id' => !empty($_POST['dispenser_id']) ? trim($_POST['dispenser_id']) : null,
                'fuel_type' => trim($_POST['fuel_type']),
                'amount' => trim($_POST['amount']),
                'km_reading' => !empty($_POST['km_reading']) ? trim($_POST['km_reading']) : null,
                'hour_reading' => !empty($_POST['hour_reading']) ? trim($_POST['hour_reading']) : null,
                'date' => trim($_POST['date']),
                'notes' => !empty($_POST['notes']) ? trim($_POST['notes']) : null,
                'vehicles' => $this->vehicleModel->getVehiclesForSelect(),
                'drivers' => $this->driverModel->getDriversForSelect(),
                'tanks' => $this->tankModel->getTanksForSelect(),
                'users' => $this->userModel->getUsers(),
                'vehicle_id_err' => '',
                'tank_id_err' => '',
                'fuel_type_err' => '',
                'amount_err' => '',
                'date_err' => '',
                'driver_id_err' => '',
                'dispenser_id_err' => '',
                'km_reading_err' => '',
                'hour_reading_err' => ''
            ];

            // Validate vehicle
            if (empty($data['vehicle_id'])) {
                $data['vehicle_id_err'] = 'Lütfen bir araç seçin';
            }

            // Validate tank
            if (empty($data['tank_id'])) {
                $data['tank_id_err'] = 'Lütfen bir yakıt tankı seçin';
            }

            // Validate fuel type
            if (empty($data['fuel_type'])) {
                $data['fuel_type_err'] = 'Yakıt türü boş olamaz';
            }

            // Validate amount
            if (empty($data['amount'])) {
                $data['amount_err'] = 'Lütfen yakıt miktarını girin';
            } elseif ($data['amount'] <= 0) {
                $data['amount_err'] = 'Yakıt miktarı 0\'dan büyük olmalıdır';
            }

            // Validate date
            if (empty($data['date'])) {
                $data['date_err'] = 'Lütfen tarih seçin';
            }

            // Make sure no errors
            if (
                empty($data['vehicle_id_err']) && empty($data['tank_id_err']) &&
                empty($data['fuel_type_err']) && empty($data['amount_err']) &&
                empty($data['date_err'])
            ) {
                try {
                    // Update fuel record
                    if ($this->fuelModel->updateFuelRecord($data)) {
                        flash('success', 'Yakıt kaydı başarıyla güncellendi');
                        redirect('fuel');
                    } else {
                        flash('error', 'Yakıt kaydı güncellenirken bir hata oluştu');
                        $this->view('fuel/edit', $data);
                    }
                } catch (Exception $e) {
                    error_log('Yakıt kaydı güncelleme hatası: ' . $e->getMessage());
                    flash('error', 'Sistem hatası: ' . $e->getMessage());
                    $this->view('fuel/edit', $data);
                }
            } else {
                // Load view with errors
                $this->view('fuel/edit', $data);
            }
        } else {
            // Get fuel record
            $record = $this->fuelModel->getFuelRecordById($id);

            if ($record) {
                // Form yükleniyor, verileri hazırla
                $data = [
                    'id' => $id,
                    'vehicle_id' => $record->vehicle_id,
                    'driver_id' => $record->driver_id,
                    'tank_id' => $record->tank_id,
                    'dispenser_id' => $record->dispenser_id,
                    'fuel_type' => $record->fuel_type,
                    'amount' => $record->amount,
                    'km_reading' => $record->km_reading,
                    'hour_reading' => $record->hour_reading,
                    'date' => date('Y-m-d', strtotime($record->date)),
                    'notes' => $record->notes,
                    'vehicles' => $this->vehicleModel->getVehiclesForSelect(),
                    'drivers' => $this->driverModel->getDriversForSelect(),
                    'tanks' => $this->tankModel->getTanksForSelect(),
                    'users' => $this->userModel->getUsers(),
                    'vehicle_id_err' => '',
                    'tank_id_err' => '',
                    'fuel_type_err' => '',
                    'amount_err' => '',
                    'date_err' => '',
                    'driver_id_err' => '',
                    'dispenser_id_err' => '',
                    'km_reading_err' => '',
                    'hour_reading_err' => ''
                ];

                $this->view('fuel/edit', $data);
            } else {
                redirect('fuel');
            }
        }
    }

    // Yakıt kaydı silme
    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Kaydı getir
            $record = $this->fuelModel->getFuelRecordById($id);

            if (!$record) {
                flash('error', 'Kayıt bulunamadı');
                redirect('fuel');
            }

            // Kaydı sil
            if ($this->fuelModel->deleteFuelRecord($id)) {
                flash('success', 'Yakıt kaydı başarıyla silindi');
            } else {
                flash('error', 'Kayıt silinemedi');
            }

            redirect('fuel');
        } else {
            redirect('fuel');
        }
    }

    // Araca göre yakıt kayıtları
    public function vehicle($vehicleId)
    {
        // Araç bilgilerini al
        $vehicle = $this->vehicleModel->getVehicleById($vehicleId);

        if (!$vehicle) {
            flash('error', 'Araç bulunamadı');
            redirect('fuel');
        }

        // Araca ait yakıt kayıtlarını getir
        $records = $this->fuelModel->getFuelRecordsByVehicle($vehicleId);

        // Toplam tüketim istatistikleri
        $stats = $this->fuelModel->getVehicleFuelConsumption($vehicleId);

        $data = [
            'title' => 'Araç Yakıt Kayıtları: ' . $vehicle->brand . ' ' . $vehicle->model . ' (' . $vehicle->plate_number . ')',
            'vehicle' => $vehicle,
            'fuelRecords' => $records,
            'stats' => $stats
        ];

        $this->view('fuel/vehicle_report', $data);
    }

    // Sürücüye göre yakıt kayıtları
    public function driver($driverId)
    {
        // Sürücü bilgilerini al
        $driver = $this->driverModel->getDriverById($driverId);

        if (!$driver) {
            flash('error', 'Sürücü bulunamadı');
            redirect('fuel');
        }

        // Sürücüye ait yakıt kayıtlarını getir
        $records = $this->fuelModel->getFuelRecordsByDriver($driverId);

        $data = [
            'title' => 'Sürücü Yakıt Kayıtları: ' . $driver->name . ' ' . $driver->surname,
            'driver' => $driver,
            'records' => $records
        ];

        $this->view('fuel/driver', $data);
    }

    // Tanka göre yakıt kayıtları
    public function tank($tankId)
    {
        // Tank bilgilerini al
        $tank = $this->tankModel->getTankById($tankId);

        if (!$tank) {
            flash('error', 'Tank bulunamadı');
            redirect('fuel');
        }

        // Bu tanka ait yakıt kayıtlarını getir
        $records = $this->fuelModel->getFuelRecordsByTank($tankId);

        $data = [
            'title' => 'Tank Yakıt Dağıtım Kayıtları: ' . $tank->name,
            'tank' => $tank,
            'records' => $records
        ];

        $this->view('fuel/tank', $data);
    }

    // Yakıt istatistikleri
    public function stats()
    {
        // Genel yakıt istatistikleri
        $totalStats = $this->fuelModel->getTotalFuelStats();

        // Yakıt tipine göre istatistikler
        $typeStats = $this->fuelModel->getFuelStatsByType();

        $data = [
            'title' => 'Yakıt İstatistikleri',
            'totalStats' => $totalStats,
            'typeStats' => $typeStats
        ];

        $this->view('fuel/stats', $data);
    }

    // API endpoint to get last driver for vehicle
    public function getLastDriverForVehicle($vehicleId = null)
    {
        // JSON çıktısı için içerik tipini ayarla
        header('Content-Type: application/json');

        // API yanıtlarını hazırla
        $response = [];

        try {
            // Araç ID kontrolü
            if (!$vehicleId) {
                $response = ['success' => false, 'message' => 'Araç ID eksik'];
                echo json_encode($response);
                return;
            }

            // Doğrudan aktif görevlendirmeden sürücüyü kontrol et
            if (isset($this->assignmentModel) && method_exists($this->assignmentModel, 'getCurrentDriverForVehicle')) {
                $assignment = $this->assignmentModel->getCurrentDriverForVehicle($vehicleId);

                if ($assignment && isset($assignment->driver_id)) {
                    $response = ['success' => true, 'driver_id' => $assignment->driver_id, 'source' => 'assignments'];
                } else {
                    $response = ['success' => false, 'message' => 'Bu araç için aktif görevlendirme bulunamadı'];
                }
            } else {
                $response = ['success' => false, 'message' => 'Assignment modeli mevcut değil veya metot bulunamadı'];
            }
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Sunucu hatası: ' . $e->getMessage(),
                'error' => true
            ];
        }

        // JSON çıktısını oluşturmadan önce çıktı tamponunu temizle
        if (ob_get_length()) ob_clean();

        // JSON çıktısını gönder
        echo json_encode($response);
        exit(); // Çıktıdan sonra çalışmayı durdur
    }

    // Kayıtları filtrele
    public function filter()
    {
        // Form gönderilmiş mi kontrol et
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Sayfa numarası ve limit parametrelerini al
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;

            // Geçerli bir sayfa numarası için kontrol
            if ($page < 1) $page = 1;
            if ($limit < 1) $limit = 20;

            // Filtreleme parametrelerini hazırla
            $filters = [
                'vehicle_id' => !empty($_POST['vehicle_id']) ? trim($_POST['vehicle_id']) : '',
                'driver_id' => !empty($_POST['driver_id']) ? trim($_POST['driver_id']) : '',
                'dispenser_id' => !empty($_POST['dispenser_id']) ? trim($_POST['dispenser_id']) : '',
                'tank_id' => !empty($_POST['tank_id']) ? trim($_POST['tank_id']) : '',
                'fuel_type' => !empty($_POST['fuel_type']) ? trim($_POST['fuel_type']) : '',
                'start_date' => !empty($_POST['start_date']) ? trim($_POST['start_date']) : '',
                'end_date' => !empty($_POST['end_date']) ? trim($_POST['end_date']) : ''
            ];

            // Filtrelenmiş kayıtları getir
            $filteredData = $this->fuelModel->getFilteredFuelRecords($filters, $page, $limit);

            // Araçların yakıt tüketim özetini getir
            $vehicleConsumption = $this->fuelModel->getVehicleFuelConsumptionSummary();

            // Yakıt türüne göre toplam tüketimi getir
            $fuelConsumptionByType = $this->fuelModel->getFuelConsumptionByType(12); // Son 12 ay

            // Toplam yakıt miktarını al
            $totalFuelStats = $this->fuelModel->getTotalFuelStats();
            $totalAmount = isset($totalFuelStats->total_amount) ? $totalFuelStats->total_amount : 0;

            // Bu ayki yakıt miktarını al
            $currentMonth = date('Y-m-01');
            $lastDayOfMonth = date('Y-m-t');
            $monthlyStats = $this->fuelModel->getFuelStatsByDateRange($currentMonth, $lastDayOfMonth);
            $monthlyAmount = isset($monthlyStats->total_amount) ? $monthlyStats->total_amount : 0;

            // Toplam araç sayısını al
            $totalVehicles = $this->vehicleModel->getTotalVehicleCount();

            // Filtre durumunu session'a kaydet
            $_SESSION['fuel_filters'] = $filters;

            $data = [
                'title' => 'Yakıt Kayıtları - Filtrelenmiş',
                'records' => $filteredData['records'],
                'total_records' => $filteredData['total_records'],
                'current_page' => $filteredData['current_page'],
                'total_pages' => $filteredData['total_pages'],
                'limit' => $filteredData['limit'],
                'vehicle_consumption' => $vehicleConsumption,
                'fuel_consumption_by_type' => $fuelConsumptionByType,
                'vehicles' => $this->fuelModel->getActiveVehiclesForSelect(),
                'drivers' => $this->fuelModel->getActiveDriversForSelect(),
                'tanks' => $this->tankModel->getActiveTanks(),
                'users' => $this->userModel->getUsers(),
                'fuel_types' => $this->fuelModel->getFuelTypes(),
                'serviceProviders' => [], // Servis sağlayıcılar (henüz uygulanmadığı için boş)
                'filters' => $filters,
                'totalAmount' => $totalAmount,
                'monthlyAmount' => $monthlyAmount,
                'totalVehicles' => $totalVehicles
            ];

            $this->view('fuel/index', $data);
        } else {
            redirect('fuel');
        }
    }

    // Yakıt türüne göre kayıtları görüntüleme
    public function type($fuelType)
    {
        // URL'den gelen yakıt tipini temizle
        $fuelType = filter_var($fuelType, FILTER_SANITIZE_STRING);

        // Yakıt türüne göre kayıtları getir
        $records = $this->fuelModel->getFuelRecordsByType($fuelType);

        $data = [
            'title' => $fuelType . ' Yakıt Kayıtları',
            'records' => $records,
            'fuel_type' => $fuelType
        ];

        $this->view('fuel/type', $data);
    }
}
