<?php
   namespace App\Controllers;

   use App\Core\Controller;
   
class Maintenance extends Controller {
    private $maintenanceModel;
    private $vehicleModel;

    public function __construct() {
        // Oturum kontrolü
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // Model sınıflarını yükle
        $this->maintenanceModel = $this->model('MaintenanceModel');
        $this->vehicleModel = $this->model('Vehicle');
    }

    // Bakım kayıtları ana sayfası
    public function index() {
        // Oturum kontrolü ve yetki kontrolü
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        $perPage = 25;
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($currentPage - 1) * $perPage;
        
        // Filtre parametrelerini al
        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';
        $vehicleId = isset($_GET['vehicle_id']) ? $_GET['vehicle_id'] : '';
        $maintenanceType = isset($_GET['maintenance_type']) ? $_GET['maintenance_type'] : '';
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        
        // Filtre parametrelerini modele gönder
        $filterData = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'vehicle_id' => $vehicleId,
            'maintenance_type' => $maintenanceType,
            'status' => $status,
            'search' => $search,
            'limit' => $perPage,
            'offset' => $offset
        ];
        
        // Kayıtların toplam sayısını al
        $totalRecords = $this->maintenanceModel->getTotalMaintenanceRecords($filterData);
        $totalPages = ceil($totalRecords / $perPage);
        
        // Kayıtları getir
        $maintenanceRecords = $this->maintenanceModel->getFilteredMaintenanceRecords($filterData);
        
        // Bakım kayıtlarını al
        $maintenances = $this->maintenanceModel->getMaintenances();
        
        // Yaklaşan bakımları al
        $upcomingMaintenances = $this->maintenanceModel->getUpcomingMaintenances();
        
        // Yaklaşan kilometre bakımlarını al
        $upcomingKmMaintenances = $this->maintenanceModel->getUpcomingKmMaintenances();
        
        // Yaklaşan saat bakımlarını al
        $upcomingHourMaintenances = $this->maintenanceModel->getUpcomingHourMaintenances();
        
        // Durumlara göre bakım sayılarını al
        $statusCounts = $this->maintenanceModel->getMaintenanceCountsByStatus();
        
        // Tiplere göre bakım sayılarını al
        $typeCounts = $this->maintenanceModel->getMaintenanceCountsByType();
        
        // Bakım tipi dağılımını al
        $typeDistribution = $this->maintenanceModel->getMaintenanceTypeDistribution();
        
        // Toplam bakım maliyetini al
        $totalCost = $this->maintenanceModel->getTotalMaintenanceCost();
        
        // Servis sağlayıcıları al
        $serviceProviders = $this->maintenanceModel->getServiceProviderUsage();
        
        // View data
        $data = [
            'records' => $maintenanceRecords,
            'upcoming' => $upcomingMaintenances,
            'vehicles' => $this->vehicleModel->getAllVehicles(),
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
            'total_records' => $totalRecords,
            'filter' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'vehicle_id' => $vehicleId,
                'maintenance_type' => $maintenanceType,
                'status' => $status,
                'search' => $search
            ],
            'maintenance_types' => $this->maintenanceModel->getMaintenanceCountsByType(),
            'status_counts' => $statusCounts,
            'type_counts' => $typeCounts,
            'typeDistribution' => $typeDistribution,
            'totalCost' => $totalCost,
            'serviceProviders' => $serviceProviders,
            'upcomingMaintenances' => $upcomingMaintenances,
            'upcomingKmMaintenances' => $upcomingKmMaintenances,
            'upcomingHourMaintenances' => $upcomingHourMaintenances
        ];
        
        $this->view('maintenance/index', $data);
    }

    // Bakım kaydı detaylarını görüntüleme
    public function show($id) {
        // ID'ye göre bakım kaydını getir
        $record = $this->maintenanceModel->getMaintenanceRecordById($id);

        if (!$record) {
            flash('error', 'Kayıt bulunamadı');
            redirect('maintenance');
        }

        $data = [
            'title' => 'Bakım Kaydı Detayı',
            'record' => $record
        ];

        $this->view('maintenance/show', $data);
    }

    // Yeni bakım kaydı ekleme
    public function add() {
        // Form gönderilmiş mi kontrol et
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Verileri hazırla
            $data = [
                'title' => 'Bakım Planlaması',
                'vehicle_id' => trim($_POST['vehicle_id']),
                'maintenance_type' => trim($_POST['maintenance_type']),
                'description' => trim($_POST['description']),
                'planning_date' => isset($_POST['planning_date']) ? trim($_POST['planning_date']) : '',
                'km_reading' => isset($_POST['km_reading']) ? trim($_POST['km_reading']) : '',
                'hour_reading' => isset($_POST['hour_reading']) ? trim($_POST['hour_reading']) : null,
                'status' => 'Planlandı', // Her zaman Planlandı olarak başlıyor
                'notes' => isset($_POST['notes']) ? trim($_POST['notes']) : '',
                'vehicle_id_err' => '',
                'maintenance_type_err' => '',
                'description_err' => '',
                'planning_date_err' => '',
                'km_reading_err' => '',
                'hour_reading_err' => '',
                'vehicles' => $this->maintenanceModel->getActiveVehiclesForSelect()
            ];

            // Verileri doğrula
            if (empty($data['vehicle_id'])) {
                $data['vehicle_id_err'] = 'Lütfen araç seçin';
            }

            if (empty($data['maintenance_type'])) {
                $data['maintenance_type_err'] = 'Lütfen bakım türünü seçin';
            }

            if (empty($data['description'])) {
                $data['description_err'] = 'Lütfen bakım açıklamasını girin';
            }

            if (empty($data['planning_date'])) {
                $data['planning_date_err'] = 'Lütfen planlama tarihini girin';
            }

            // Kilometre veya saat bilgisinden en az biri gerekli olabilir
            if (empty($data['km_reading']) && empty($data['hour_reading'])) {
                $data['km_reading_err'] = 'Lütfen kilometre veya çalışma saati bilgisinden en az birini girin';
            }

            // Kilometre kontrolü
            if (!empty($data['km_reading']) && (!is_numeric($data['km_reading']) || $data['km_reading'] < 0)) {
                $data['km_reading_err'] = 'Kilometre bilgisi geçerli bir sayı olmalıdır';
            }

            // Saat kontrolü
            if (!empty($data['hour_reading']) && (!is_numeric($data['hour_reading']) || $data['hour_reading'] < 0)) {
                $data['hour_reading_err'] = 'Çalışma saati bilgisi geçerli bir sayı olmalıdır';
            }

            // Hata yoksa kaydet
            if (empty($data['vehicle_id_err']) && empty($data['maintenance_type_err']) && 
                empty($data['description_err']) && empty($data['planning_date_err']) && 
                empty($data['km_reading_err']) && empty($data['hour_reading_err'])) {
                
                // Aracı bakım planlandı durumuna güncelle
                $this->maintenanceModel->updateVehicleMaintenanceStatus($data['vehicle_id'], 'Bakım Planlandı');
                
                // Hata ayıklama için
                flash('debug', 'Planning date: ' . $data['planning_date'] . ' | KM: ' . $data['km_reading'] . ' | Hour: ' . $data['hour_reading']);
                
                // Varsayılan değerleri ayarla
                $maintenanceData = [
                    'vehicle_id' => $data['vehicle_id'],
                    'maintenance_type' => $data['maintenance_type'],
                    'description' => $data['description'],
                    'planning_date' => $data['planning_date'],
                    'start_date' => $data['planning_date'], // Başlangıçta planlama tarihi ile aynı atayalım
                    'end_date' => null,
                    'cost' => 0,
                    'km_reading' => !empty($data['km_reading']) ? $data['km_reading'] : 0,
                    'hour_reading' => !empty($data['hour_reading']) ? $data['hour_reading'] : null,
                    'status' => 'Planlandı',
                    'notes' => !empty($data['notes']) ? $data['notes'] : '',
                    'service_provider' => '',
                    'next_maintenance_date' => null,
                    'next_maintenance_km' => null,
                    'next_maintenance_hours' => null
                ];
                
                // Kaydı ekle
                if ($this->maintenanceModel->addMaintenanceRecord($maintenanceData)) {
                    flash('success', 'Bakım planlaması başarıyla kaydedildi');
                    redirect('maintenance');
                } else {
                    flash('error', 'Bir şeyler yanlış gitti');
                    $this->view('maintenance/add', $data);
                }
            } else {
                // Hata varsa formu yeniden göster
                $this->view('maintenance/add', $data);
            }
        } else {
            // GET isteği - formu göster
            $data = [
                'title' => 'Bakım Planlaması',
                'vehicle_id' => '',
                'maintenance_type' => '',
                'description' => '',
                'planning_date' => date('Y-m-d'),
                'km_reading' => '',
                'hour_reading' => '',
                'notes' => '',
                'vehicle_id_err' => '',
                'maintenance_type_err' => '',
                'description_err' => '',
                'planning_date_err' => '',
                'km_reading_err' => '',
                'hour_reading_err' => '',
                'vehicles' => $this->maintenanceModel->getActiveVehiclesForSelect()
            ];

            $this->view('maintenance/add', $data);
        }
    }

    // Bakım kaydı düzenleme
    public function edit($id) {
        // Kaydı getir
        $record = $this->maintenanceModel->getMaintenanceRecordById($id);

        if (!$record) {
            flash('error', 'Kayıt bulunamadı');
            redirect('maintenance');
        }

        // Form gönderilmiş mi kontrol et
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Verileri hazırla
            $data = [
                'title' => 'Bakım Kaydı Düzenle',
                'id' => $id,
                'vehicle_id' => trim($_POST['vehicle_id']),
                'maintenance_type' => trim($_POST['maintenance_type']),
                'description' => trim($_POST['description']),
                'planning_date' => isset($_POST['planning_date']) ? trim($_POST['planning_date']) : $record->planning_date,
                'start_date' => trim($_POST['start_date']),
                'end_date' => trim($_POST['end_date']),
                'cost' => trim($_POST['cost']),
                'km_reading' => trim($_POST['km_reading']),
                'hour_reading' => isset($_POST['hour_reading']) ? trim($_POST['hour_reading']) : $record->hour_reading,
                'status' => trim($_POST['status']),
                'notes' => trim($_POST['notes']),
                'service_provider' => isset($_POST['service_provider']) ? trim($_POST['service_provider']) : '',
                'next_maintenance_date' => isset($_POST['next_maintenance_date']) ? trim($_POST['next_maintenance_date']) : '',
                'next_maintenance_km' => isset($_POST['next_maintenance_km']) ? trim($_POST['next_maintenance_km']) : '',
                'vehicle_id_err' => '',
                'maintenance_type_err' => '',
                'description_err' => '',
                'planning_date_err' => '',
                'start_date_err' => '',
                'cost_err' => '',
                'km_reading_err' => '',
                'status_err' => '',
                'vehicles' => $this->maintenanceModel->getActiveVehiclesForSelect()
            ];

            // Verileri doğrula
            if (empty($data['vehicle_id'])) {
                $data['vehicle_id_err'] = 'Lütfen araç seçin';
            }

            if (empty($data['maintenance_type'])) {
                $data['maintenance_type_err'] = 'Lütfen bakım türünü seçin';
            }

            if (empty($data['description'])) {
                $data['description_err'] = 'Lütfen bakım açıklamasını girin';
            }
            
            if (empty($data['planning_date'])) {
                $data['planning_date_err'] = 'Lütfen planlama tarihini girin';
            }

            if (empty($data['start_date'])) {
                $data['start_date_err'] = 'Lütfen başlangıç tarihini girin';
            }

            if (empty($data['status'])) {
                $data['status_err'] = 'Lütfen bakım durumunu seçin';
            }
            
            // Status'a göre farklı doğrulama kuralları uygula
            if ($data['status'] == 'Planlandı') {
                // Planlama aşamasında maliyet ve km opsiyonel olabilir
                // km_reading aracın mevcut kilometresi olacağı için zorunlu olmalı
                if (empty($data['km_reading'])) {
                    $data['km_reading_err'] = 'Lütfen kilometre bilgisini girin';
                } elseif (!is_numeric($data['km_reading']) || $data['km_reading'] < 0) {
                    $data['km_reading_err'] = 'Kilometre bilgisi geçerli bir sayı olmalıdır';
                }
                
                // Maliyet planlandı durumunda opsiyonel, varsayılan 0
                if (empty($data['cost'])) {
                    $data['cost'] = '0';
                } elseif (!is_numeric($data['cost']) || $data['cost'] < 0) {
                    $data['cost_err'] = 'Maliyet geçerli bir sayı olmalıdır';
                }
            } else {
                // Diğer durumlarda tüm alanlar zorunlu
                if (empty($data['cost'])) {
                    $data['cost_err'] = 'Lütfen bakım maliyetini girin';
                } elseif (!is_numeric($data['cost']) || $data['cost'] < 0) {
                    $data['cost_err'] = 'Maliyet geçerli bir sayı olmalıdır';
                }

                if (empty($data['km_reading'])) {
                    $data['km_reading_err'] = 'Lütfen kilometre bilgisini girin';
                } elseif (!is_numeric($data['km_reading']) || $data['km_reading'] < 0) {
                    $data['km_reading_err'] = 'Kilometre bilgisi geçerli bir sayı olmalıdır';
                }
            }

            // Hata yoksa güncelle
            if (empty($data['vehicle_id_err']) && empty($data['maintenance_type_err']) && 
                empty($data['description_err']) && empty($data['planning_date_err']) && 
                empty($data['start_date_err']) && empty($data['cost_err']) && 
                empty($data['km_reading_err']) && empty($data['status_err'])) {
                
                // Bakım durumu değiştiyse araç durumunu güncelle
                if ($data['status'] != $record->status) {
                    if ($data['status'] == 'Tamamlandı' || $data['status'] == 'İptal') {
                        // Aracın başka devam eden bakımı var mı kontrol et
                        $otherMaintenances = $this->maintenanceModel->getActiveMaintenancesForVehicleExcept($data['vehicle_id'], $id);
                        
                        if (empty($otherMaintenances)) {
                            // Başka devam eden bakım yoksa aracı aktif yap
                            $this->maintenanceModel->updateVehicleMaintenanceStatus($data['vehicle_id'], 'Aktif');
                        }
                    } else {
                        // Bakım devam ediyor veya planlandı ise aracı bakımda olarak güncelle
                        $this->maintenanceModel->updateVehicleMaintenanceStatus($data['vehicle_id'], 'Bakımda');
                    }
                }
                
                // Kaydı güncelle
                if ($this->maintenanceModel->updateMaintenanceRecord($data)) {
                    flash('success', 'Bakım kaydı başarıyla güncellendi');
                    redirect('maintenance/show/' . $id);
                } else {
                    flash('error', 'Bir şeyler yanlış gitti');
                    $this->view('maintenance/edit', $data);
                }
            } else {
                // Hata varsa formu yeniden göster
                $this->view('maintenance/edit', $data);
            }
        } else {
            // GET isteği - formu göster
            $data = [
                'title' => 'Bakım Kaydı Düzenle',
                'id' => $id,
                'vehicle_id' => $record->vehicle_id,
                'maintenance_type' => $record->maintenance_type,
                'description' => $record->description,
                'planning_date' => $record->planning_date,
                'start_date' => $record->start_date,
                'end_date' => $record->end_date,
                'cost' => $record->cost,
                'km_reading' => $record->km_reading,
                'status' => $record->status,
                'notes' => $record->notes,
                'service_provider' => $record->service_provider,
                'next_maintenance_date' => $record->next_maintenance_date,
                'next_maintenance_km' => $record->next_maintenance_km,
                'vehicle_id_err' => '',
                'maintenance_type_err' => '',
                'description_err' => '',
                'planning_date_err' => '',
                'start_date_err' => '',
                'cost_err' => '',
                'km_reading_err' => '',
                'status_err' => '',
                'vehicles' => $this->maintenanceModel->getActiveVehiclesForSelect()
            ];

            $this->view('maintenance/edit', $data);
        }
    }

    // Bakım kaydı silme
    public function delete($id) {
        // Sadece POST isteklerini kabul et
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('maintenance');
        }

        // Kaydı getir
        $record = $this->maintenanceModel->getMaintenanceRecordById($id);
        
        if (!$record) {
            flash('error', 'Kayıt bulunamadı');
            redirect('maintenance');
        }
        
        // Kaydı sil
        if ($this->maintenanceModel->deleteMaintenanceRecord($id)) {
            flash('success', 'Bakım kaydı başarıyla silindi');
        } else {
            flash('error', 'Bakım kaydı silinemedi');
        }
        
        redirect('maintenance');
    }
    
    // Silme onay sayfasını göster
    public function confirmDelete($id) {
        // Kaydı getir
        $record = $this->maintenanceModel->getMaintenanceRecordById($id);
        
        if (!$record) {
            flash('error', 'Kayıt bulunamadı');
            redirect('maintenance');
        }
        
        $data = [
            'title' => 'Bakım Kaydını Sil',
            'record' => $record
        ];
        
        // Onay sayfasını göster
        $this->view('maintenance/confirm_delete', $data);
    }

    // Araç bakım raporları
    public function vehicleReport($vehicle_id) {
        // Aracı kontrol et
        $vehicle = $this->vehicleModel->getVehicleById($vehicle_id);
        
        if (!$vehicle) {
            flash('error', 'Araç bulunamadı');
            redirect('maintenance');
        }

        // Araç bakım kayıtlarını getir
        $maintenanceRecords = $this->maintenanceModel->getMaintenanceRecordsByVehicle($vehicle_id);
        
        // Toplam bakım maliyetini getir
        $totalCost = $this->maintenanceModel->getTotalMaintenanceCostByVehicle($vehicle_id);

        $data = [
            'vehicle' => $vehicle,
            'maintenanceRecords' => $maintenanceRecords,
            'totalCost' => $totalCost
        ];

        $this->view('maintenance/vehicle_report', $data);
    }

    // Bakım tipine göre analiz raporu
    public function analysis() {
        // Bakım tiplerine göre maliyet analizi
        $costAnalysis = $this->maintenanceModel->getMaintenanceCostAnalysis();
        
        // Duruma göre bakım sayıları
        $plannedCount = $this->maintenanceModel->getMaintenanceCountByStatus('Planlandı');
        $inProgressCount = $this->maintenanceModel->getMaintenanceCountByStatus('Devam Ediyor');
        $completedCount = $this->maintenanceModel->getMaintenanceCountByStatus('Tamamlandı');
        $canceledCount = $this->maintenanceModel->getMaintenanceCountByStatus('İptal');
        
        // Toplam bakım maliyeti
        $totalCost = $this->maintenanceModel->getTotalMaintenanceCost();

        $data = [
            'title' => 'Bakım Analizi',
            'costAnalysis' => $costAnalysis,
            'plannedCount' => $plannedCount,
            'inProgressCount' => $inProgressCount,
            'completedCount' => $completedCount,
            'canceledCount' => $canceledCount,
            'totalCost' => $totalCost
        ];

        $this->view('maintenance/analysis', $data);
    }

    // Servis işlemleri
    public function service($id) {
        // Bakım kaydını getir
        $record = $this->maintenanceModel->getMaintenanceRecordById($id);

        if (!$record) {
            flash('error', 'Bakım kaydı bulunamadı');
            redirect('maintenance');
        }

        // Bakım durumu zaten Tamamlandı ise uyarı göster
        if ($record->status == 'Tamamlandı') {
            flash('error', 'Bu bakım zaten tamamlanmış durumda');
            redirect('maintenance/show/' . $id);
        }

        // Form gönderilmiş mi kontrol et
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Verileri hazırla
            $data = [
                'title' => 'Servis İşlemleri',
                'id' => $id,
                'vehicle_id' => $record->vehicle_id,
                'maintenance_type' => $record->maintenance_type,
                'description' => $record->description,
                'planning_date' => $record->planning_date,
                'start_date' => trim($_POST['start_date']),
                'end_date' => trim($_POST['end_date']),
                'cost' => trim($_POST['cost']),
                'km_reading' => trim($_POST['km_reading']),
                'hour_reading' => isset($_POST['hour_reading']) ? trim($_POST['hour_reading']) : $record->hour_reading,
                'status' => trim($_POST['status']),
                'notes' => trim($_POST['notes']),
                'service_provider' => trim($_POST['service_provider']),
                'next_maintenance_date' => trim($_POST['next_maintenance_date']),
                'next_maintenance_km' => trim($_POST['next_maintenance_km']),
                'next_maintenance_hours' => isset($_POST['next_maintenance_hours']) ? trim($_POST['next_maintenance_hours']) : null,
                'start_date_err' => '',
                'cost_err' => '',
                'km_reading_err' => '',
                'status_err' => '',
                'record' => $record
            ];

            // Verileri doğrula
            if (empty($data['start_date'])) {
                $data['start_date_err'] = 'Lütfen başlangıç tarihini girin';
            }

            if (empty($data['cost'])) {
                $data['cost_err'] = 'Lütfen maliyeti girin';
            } elseif (!is_numeric($data['cost']) || $data['cost'] < 0) {
                $data['cost_err'] = 'Maliyet geçerli bir sayı olmalıdır';
            }

            if (empty($data['km_reading']) && empty($data['hour_reading'])) {
                $data['km_reading_err'] = 'Lütfen kilometre veya çalışma saati bilgisini girin';
            }

            if (empty($data['status'])) {
                $data['status_err'] = 'Lütfen durumu seçin';
            }

            // Hata yoksa kaydet
            if (empty($data['start_date_err']) && empty($data['cost_err']) && 
                empty($data['km_reading_err']) && empty($data['status_err'])) {
                
                // Bakım durumuna göre araç durumunu güncelle
                if ($data['status'] == 'Tamamlandı') {
                    $this->maintenanceModel->updateVehicleMaintenanceStatus($data['vehicle_id'], 'Aktif');
                } elseif ($data['status'] == 'Devam Ediyor') {
                    $this->maintenanceModel->updateVehicleMaintenanceStatus($data['vehicle_id'], 'Bakımda');
                }
                
                // Kaydı güncelle
                if ($this->maintenanceModel->updateMaintenanceRecord($data)) {
                    flash('success', 'Servis bilgileri başarıyla güncellendi');
                    redirect('maintenance/show/' . $id);
                } else {
                    flash('error', 'Bir şeyler yanlış gitti');
                    $this->view('maintenance/service', $data);
                }
            } else {
                // Hata varsa formu yeniden göster
                $this->view('maintenance/service', $data);
            }
        } else {
            // GET isteği - formu göster
            $data = [
                'title' => 'Servis İşlemleri',
                'id' => $id,
                'record' => $record,
                'start_date_err' => '',
                'cost_err' => '',
                'km_reading_err' => '',
                'status_err' => ''
            ];

            $this->view('maintenance/service', $data);
        }
    }

    // Belirli bir duruma göre bakımları filtrele
    public function filter($status = null) {
        // Status parametresini kontrol et
        if (!in_array($status, ['Planlandı', 'Devam Ediyor', 'Tamamlandı', 'İptal'])) {
            flash('error', 'Geçersiz durum parametresi', 'alert alert-danger');
            redirect('maintenance');
        }

        // MaintenanceModel'i yükle
        $maintenanceModel = $this->model('MaintenanceModel');
        
        // Duruma göre filtrelenmiş bakım kayıtlarını al
        $records = $maintenanceModel->getMaintenanceRecordsByStatus($status);
        
        // İstatistikler için verileri al
        $statusCounts = $maintenanceModel->getMaintenanceCountsByStatus();
        $totalCost = $maintenanceModel->getTotalMaintenanceCost();
        $typeDistribution = $maintenanceModel->getMaintenanceTypeDistribution();
        $serviceProviders = $maintenanceModel->getServiceProviderUsage();
        $upcomingMaintenances = $maintenanceModel->getUpcomingMaintenances();
        $upcomingKmMaintenances = $maintenanceModel->getUpcomingKmMaintenances();
        $upcomingHourMaintenances = $maintenanceModel->getUpcomingHourMaintenances();
        
        // View için verileri hazırla
        $data = [
            'title' => $status . ' Durumdaki Bakımlar',
            'records' => $records,
            'statusCounts' => $statusCounts,
            'totalCost' => $totalCost,
            'typeDistribution' => $typeDistribution,
            'serviceProviders' => $serviceProviders,
            'upcomingMaintenances' => $upcomingMaintenances,
            'upcomingKmMaintenances' => $upcomingKmMaintenances,
            'upcomingHourMaintenances' => $upcomingHourMaintenances,
            'filteredStatus' => $status
        ];
        
        $this->view('maintenance/index', $data);
    }
} 