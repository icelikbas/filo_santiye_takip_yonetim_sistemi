<?php

namespace App\Controllers;

use App\Core\Controller;

class FineTypes extends Controller
{
    private $fineTypeModel;
    private $userModel;

    public function __construct()
    {
        // Oturum kontrolü
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // Model sınıflarını yükle
        $this->fineTypeModel = $this->model('FineType');
        $this->userModel = $this->model('User');
    }

    // Ceza tipleri listesini görüntüleme
    public function index()
    {
        // Tüm ceza tiplerini getir
        $fineTypes = $this->fineTypeModel->getFineTypes();

        // Aktif ve pasif ceza tiplerini sayma
        $activeCount = 0;
        $inactiveCount = 0;
        
        foreach ($fineTypes as $type) {
            if ($type->active == 1) {
                $activeCount++;
            } else {
                $inactiveCount++;
            }
        }

        $data = [
            'title' => 'Ceza Tipleri Listesi',
            'fineTypes' => $fineTypes,
            'activeCount' => $activeCount,
            'inactiveCount' => $inactiveCount,
            'totalCount' => count($fineTypes)
        ];

        $this->view('finetypes/index', $data);
    }

    // Ceza tipi detayını gösterme
    public function show($id)
    {
        // Ceza tipi bilgilerini getir
        $fineType = $this->fineTypeModel->getFineTypeById($id);

        if (!$fineType) {
            flash('error', 'Ceza tipi bulunamadı');
            redirect('finetypes');
        }

        // Debug: Tüm ceza tiplerini kontrol et
        error_log("====== FineTypes Debug - ID: $id ======");
        error_log("Current Fine Type: " . print_r($fineType, true));

        // Bu ceza tipine ait cezaları doğrudan manuel SQL sorgusu ile getirelim
        $db = new \App\Core\Database();
        
        // Sadece fine_type_id alanını kullanacağız
        $query = '
            SELECT 
                tf.id, 
                tf.fine_date, 
                tf.fine_amount as amount, 
                tf.payment_status, 
                v.plate_number, 
                d.name as driver_name, 
                d.surname as driver_surname 
            FROM traffic_fines tf
            LEFT JOIN vehicles v ON tf.vehicle_id = v.id
            LEFT JOIN drivers d ON tf.driver_id = d.id
            WHERE tf.fine_type_id = :id
            ORDER BY tf.fine_date DESC
        ';
        
        $db->query($query);
        $db->bind(':id', $id);
        error_log("FineTypes Show - Query for fine_type_id: " . $query . " with ID: " . $id);
        
        $fines = $db->resultSet();
        error_log("FineTypes Show - Direct SQL query results count: " . count($fines));
        
        // İlk sonucu loglama 
        if (!empty($fines)) {
            error_log("FineTypes Show - First fine result: " . print_r($fines[0], true));
        }

        $data = [
            'title' => 'Ceza Tipi Detayı: ' . $fineType->name,
            'fineType' => $fineType,
            'fines' => $fines
        ];

        $this->view('finetypes/show', $data);
    }

    // Yeni ceza tipi ekleme sayfası
    public function add()
    {
        // POST isteği kontrol edilir
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Form verilerini al
            $data = [
                'code' => trim($_POST['code']),
                'name' => trim($_POST['name']),
                'legal_article' => !empty($_POST['legal_article']) ? trim($_POST['legal_article']) : null,
                'description' => !empty($_POST['description']) ? trim($_POST['description']) : null,
                'default_amount' => trim($_POST['default_amount']),
                'points' => !empty($_POST['points']) ? trim($_POST['points']) : null,
                'active' => isset($_POST['active']) ? 1 : 0,
                'code_err' => '',
                'name_err' => '',
                'default_amount_err' => '',
                'points_err' => ''
            ];

            // Kod doğrulama
            if (empty($data['code'])) {
                $data['code_err'] = 'Lütfen ceza kodu giriniz';
            } elseif ($this->fineTypeModel->findFineTypeByCode($data['code'])) {
                $data['code_err'] = 'Bu ceza kodu zaten kayıtlı';
            }

            // İsim doğrulama
            if (empty($data['name'])) {
                $data['name_err'] = 'Lütfen ceza adı giriniz';
            }

            // Varsayılan tutar doğrulama
            if (empty($data['default_amount'])) {
                $data['default_amount_err'] = 'Lütfen varsayılan ceza tutarını giriniz';
            } elseif (!is_numeric(str_replace(',', '.', $data['default_amount']))) {
                $data['default_amount_err'] = 'Geçerli bir tutar giriniz';
            }

            // Puan doğrulama
            if (!empty($data['points']) && !is_numeric($data['points'])) {
                $data['points_err'] = 'Geçerli bir puan giriniz';
            }

            // Hata yoksa işleme devam et
            if (
                empty($data['code_err']) &&
                empty($data['name_err']) &&
                empty($data['default_amount_err']) &&
                empty($data['points_err'])
            ) {
                // Veritabanına kaydet
                if ($this->fineTypeModel->addFineType($data)) {
                    flash('fine_type_message', 'Ceza tipi başarıyla eklendi');
                    redirect('finetypes');
                } else {
                    flash('fine_type_message', 'Ceza tipi eklenirken bir hata oluştu', 'alert alert-danger');
                    $this->view('finetypes/add', $data);
                }
            } else {
                // Hatalarla birlikte form sayfasını göster
                $this->view('finetypes/add', $data);
            }
        } else {
            // GET isteği - form sayfasını göster
            $data = [
                'code' => '',
                'name' => '',
                'legal_article' => '',
                'description' => '',
                'default_amount' => '',
                'points' => '',
                'active' => 1,
                'code_err' => '',
                'name_err' => '',
                'default_amount_err' => '',
                'points_err' => ''
            ];

            $this->view('finetypes/add', $data);
        }
    }

    // Ceza tipi düzenleme sayfası
    public function edit($id)
    {
        // POST isteği kontrol edilir
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Form verilerini al
            $data = [
                'id' => $id,
                'code' => trim($_POST['code']),
                'name' => trim($_POST['name']),
                'legal_article' => !empty($_POST['legal_article']) ? trim($_POST['legal_article']) : null,
                'description' => !empty($_POST['description']) ? trim($_POST['description']) : null,
                'default_amount' => trim($_POST['default_amount']),
                'points' => !empty($_POST['points']) ? trim($_POST['points']) : null,
                'active' => isset($_POST['active']) ? 1 : 0,
                'code_err' => '',
                'name_err' => '',
                'default_amount_err' => '',
                'points_err' => ''
            ];

            // Kod doğrulama
            if (empty($data['code'])) {
                $data['code_err'] = 'Lütfen ceza kodu giriniz';
            } else {
                // Aynı koda sahip başka bir ceza tipi var mı kontrol et (kendi ID'si hariç)
                $existingType = $this->fineTypeModel->findFineTypeByCode($data['code']);
                if ($existingType && $existingType->id != $id) {
                    $data['code_err'] = 'Bu ceza kodu zaten başka bir ceza tipi için kayıtlı';
                }
            }

            // İsim doğrulama
            if (empty($data['name'])) {
                $data['name_err'] = 'Lütfen ceza adı giriniz';
            }

            // Varsayılan tutar doğrulama
            if (empty($data['default_amount'])) {
                $data['default_amount_err'] = 'Lütfen varsayılan ceza tutarını giriniz';
            } elseif (!is_numeric(str_replace(',', '.', $data['default_amount']))) {
                $data['default_amount_err'] = 'Geçerli bir tutar giriniz';
            }

            // Puan doğrulama
            if (!empty($data['points']) && !is_numeric($data['points'])) {
                $data['points_err'] = 'Geçerli bir puan giriniz';
            }

            // Hata yoksa işleme devam et
            if (
                empty($data['code_err']) &&
                empty($data['name_err']) &&
                empty($data['default_amount_err']) &&
                empty($data['points_err'])
            ) {
                // Veritabanına kaydet
                if ($this->fineTypeModel->updateFineType($data)) {
                    flash('fine_type_message', 'Ceza tipi başarıyla güncellendi');
                    redirect('finetypes');
                } else {
                    flash('fine_type_message', 'Ceza tipi güncellenirken bir hata oluştu', 'alert alert-danger');
                    $this->view('finetypes/edit', $data);
                }
            } else {
                // Hatalarla birlikte form sayfasını göster
                $this->view('finetypes/edit', $data);
            }
        } else {
            // Ceza tipi bilgilerini getir
            $fineType = $this->fineTypeModel->getFineTypeById($id);

            if (!$fineType) {
                flash('error', 'Ceza tipi bulunamadı');
                redirect('finetypes');
            }

            // Formu mevcut verilerle doldur
            $data = [
                'id' => $id,
                'code' => $fineType->code,
                'name' => $fineType->name,
                'legal_article' => $fineType->legal_article,
                'description' => $fineType->description,
                'default_amount' => $fineType->default_amount,
                'points' => $fineType->points,
                'active' => $fineType->active,
                'code_err' => '',
                'name_err' => '',
                'default_amount_err' => '',
                'points_err' => ''
            ];

            $this->view('finetypes/edit', $data);
        }
    }

    // Ceza tipi silme
    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Ceza tipini kullanılıp kullanılmadığını kontrol et
            if ($this->fineTypeModel->isTypeInUse($id)) {
                flash('fine_type_message', 'Bu ceza tipi cezalarda kullanıldığı için silinemez', 'alert alert-danger');
                redirect('finetypes');
                return;
            }
            
            // Ceza tipini sil
            if ($this->fineTypeModel->deleteFineType($id)) {
                flash('fine_type_message', 'Ceza tipi başarıyla silindi');
            } else {
                flash('fine_type_message', 'Ceza tipi silinirken bir hata oluştu', 'alert alert-danger');
            }
            
            redirect('finetypes');
        } else {
            redirect('finetypes');
        }
    }

    // Ceza tipini etkinleştirme/devre dışı bırakma
    public function toggleStatus($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Ceza tipini getir
            $fineType = $this->fineTypeModel->getFineTypeById($id);
            
            if (!$fineType) {
                flash('error', 'Ceza tipi bulunamadı');
                redirect('finetypes');
            }
            
            // Durumu değiştir
            $newStatus = ($fineType->active == 1) ? 0 : 1;
            
            // Güncelle
            if ($this->fineTypeModel->updateStatus($id, $newStatus)) {
                $statusText = ($newStatus == 1) ? 'etkinleştirildi' : 'devre dışı bırakıldı';
                flash('fine_type_message', 'Ceza tipi başarıyla ' . $statusText);
            } else {
                flash('fine_type_message', 'Ceza tipi durumu güncellenirken bir hata oluştu', 'alert alert-danger');
            }
            
            redirect('finetypes');
        } else {
            redirect('finetypes');
        }
    }
} 