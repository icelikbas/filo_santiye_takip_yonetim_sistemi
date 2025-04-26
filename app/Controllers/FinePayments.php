<?php

namespace App\Controllers;

use App\Core\Controller;

class FinePayments extends Controller
{
    private $finePaymentModel;
    private $trafficFineModel;
    private $userModel;

    public function __construct()
    {
        // Oturum kontrolü
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // Model sınıflarını yükle
        $this->finePaymentModel = $this->model('FinePayment');
        if (!$this->finePaymentModel) {
            die('FinePayment modeli yüklenemedi');
        }
        
        $this->trafficFineModel = $this->model('TrafficFine');
        if (!$this->trafficFineModel) {
            die('TrafficFine modeli yüklenemedi');
        }
        
        $this->userModel = $this->model('User');
        if (!$this->userModel) {
            die('User modeli yüklenemedi');
        }
    }

    // Tüm ödemeleri gösterme
    public function index()
    {
        // Tüm ödemeleri getir
        $payments = $this->finePaymentModel->getAllPayments();

        $data = [
            'title' => 'Ceza Ödemeleri Listesi',
            'payments' => $payments
        ];

        $this->view('finepayments/index', $data);
    }

    // Ödeme detayını gösterme
    public function show($id)
    {
        // Ödeme bilgilerini getir
        $payment = $this->finePaymentModel->getPaymentById($id);

        if (!$payment) {
            flash('error', 'Ödeme kaydı bulunamadı');
            redirect('trafficfines');
        }

        // İlişkili ceza bilgilerini getir
        $trafficFine = $this->trafficFineModel->getTrafficFineById($payment->fine_id);

        // Ödemeyi oluşturan kullanıcıyı getir
        $user = $this->userModel->getUserById($payment->created_by);

        $data = [
            'title' => 'Ödeme Detayı',
            'payment' => $payment,
            'trafficFine' => $trafficFine,
            'user' => $user
        ];

        $this->view('finepayments/show', $data);
    }

    // Ödeme düzenleme sayfası
    public function edit($id)
    {
        // Ödeme bilgilerini getir
        $payment = $this->finePaymentModel->getPaymentById($id);

        if (!$payment) {
            flash('error', 'Ödeme kaydı bulunamadı');
            redirect('trafficfines');
        }

        // İlişkili ceza bilgilerini getir
        $trafficFine = $this->trafficFineModel->getTrafficFineById($payment->fine_id);

        // POST isteği kontrol edilir
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Form verilerini al
            $data = [
                'id' => $id,
                'fine_id' => $payment->fine_id,
                'payment_date' => trim($_POST['payment_date']),
                'amount' => trim($_POST['amount']),
                'payment_method' => trim($_POST['payment_method']),
                'receipt_number' => !empty($_POST['receipt_number']) ? trim($_POST['receipt_number']) : null,
                'notes' => !empty($_POST['notes']) ? trim($_POST['notes']) : null,
                'payment_date_err' => '',
                'amount_err' => '',
                'payment_method_err' => '',
                'trafficFine' => $trafficFine
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
                // Önceki ödeme tutarını al
                $oldAmount = $payment->amount;
                
                // Veritabanına kaydet
                if ($this->finePaymentModel->updatePayment($data)) {
                    // Ödeme durumunu güncelle
                    $this->updateFinePaymentStatus($data['fine_id'], $oldAmount, floatval(str_replace(',', '.', $data['amount'])));
                    
                    flash('payment_message', 'Ödeme kaydı başarıyla güncellendi');
                    redirect('trafficfines/show/' . $data['fine_id']);
                } else {
                    flash('payment_message', 'Ödeme kaydı güncellenirken bir hata oluştu', 'alert alert-danger');
                    $this->view('finepayments/edit', $data);
                }
            } else {
                // Hatalarla birlikte form sayfasını göster
                $this->view('finepayments/edit', $data);
            }
        } else {
            // Formu mevcut verilerle doldur
            $data = [
                'id' => $id,
                'fine_id' => $payment->fine_id,
                'payment_date' => $payment->payment_date,
                'amount' => $payment->amount,
                'payment_method' => $payment->payment_method,
                'receipt_number' => $payment->receipt_number,
                'notes' => $payment->notes,
                'payment_date_err' => '',
                'amount_err' => '',
                'payment_method_err' => '',
                'trafficFine' => $trafficFine
            ];

            $this->view('finepayments/edit', $data);
        }
    }

    // Ödeme silme
    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Ödeme bilgilerini getir
            $payment = $this->finePaymentModel->getPaymentById($id);
            
            if (!$payment) {
                flash('error', 'Ödeme kaydı bulunamadı');
                redirect('trafficfines');
            }
            
            $fineId = $payment->fine_id;
            $paymentAmount = $payment->amount;
            
            // Ödemeyi sil
            if ($this->finePaymentModel->deletePayment($id)) {
                // Cezanın ödeme durumunu güncelle
                $this->updateFinePaymentStatusAfterDelete($fineId, $paymentAmount);
                
                flash('payment_message', 'Ödeme kaydı başarıyla silindi');
            } else {
                flash('payment_message', 'Ödeme kaydı silinirken bir hata oluştu', 'alert alert-danger');
            }
            
            redirect('trafficfines/show/' . $fineId);
        } else {
            redirect('trafficfines');
        }
    }

    // Ödeme durumunu güncelleme (düzenleme sonrası)
    private function updateFinePaymentStatus($fineId, $oldAmount, $newAmount)
    {
        // Trafik cezasını getir
        $trafficFine = $this->trafficFineModel->getTrafficFineById($fineId);
        
        // Tüm ödemeleri getir
        $payments = $this->finePaymentModel->getPaymentsByFineId($fineId);
        
        // Toplam ödenen tutarı hesapla (değişiklik öncesi toplam - eski tutar + yeni tutar)
        $totalPaid = 0;
        foreach ($payments as $payment) {
            $totalPaid += $payment->amount;
        }
        
        // Eski tutarı çıkar, yeni tutarı ekle
        $totalPaid = $totalPaid - $oldAmount + $newAmount;
        
        // Ödeme durumunu belirle
        $paymentStatus = 'Ödenmedi';
        if ($totalPaid >= $trafficFine->fine_amount) {
            $paymentStatus = 'Ödendi';
        } elseif ($totalPaid > 0) {
            $paymentStatus = 'Taksitli Ödeme';
        }
        
        // Ceza bilgilerini güncelle
        $updateData = [
            'id' => $fineId,
            'payment_status' => $paymentStatus,
            'payment_amount' => $totalPaid
        ];
        
        return $this->trafficFineModel->updatePaymentStatus($updateData);
    }

    // Ödeme durumunu güncelleme (silme sonrası)
    private function updateFinePaymentStatusAfterDelete($fineId, $deletedAmount)
    {
        // Trafik cezasını getir
        $trafficFine = $this->trafficFineModel->getTrafficFineById($fineId);
        
        // Tüm ödemeleri getir
        $payments = $this->finePaymentModel->getPaymentsByFineId($fineId);
        
        // Toplam ödenen tutarı hesapla
        $totalPaid = 0;
        foreach ($payments as $payment) {
            $totalPaid += $payment->amount;
        }
        
        // Ödeme durumunu belirle
        $paymentStatus = 'Ödenmedi';
        if ($totalPaid >= $trafficFine->fine_amount) {
            $paymentStatus = 'Ödendi';
        } elseif ($totalPaid > 0) {
            $paymentStatus = 'Taksitli Ödeme';
        }
        
        // Ceza bilgilerini güncelle
        $updateData = [
            'id' => $fineId,
            'payment_status' => $paymentStatus,
            'payment_amount' => $totalPaid
        ];
        
        return $this->trafficFineModel->updatePaymentStatus($updateData);
    }
} 