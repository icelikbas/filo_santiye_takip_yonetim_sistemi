<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\FuelModel;
use App\Models\MaintenanceModel;
use App\Models\Assignment;
use App\Models\Company;

class Reports extends Controller
{
    private $vehicleModel;
    private $driverModel;
    private $fuelModel;
    private $maintenanceModel;
    private $assignmentModel;
    private $companyModel;
    private $db;

    public function __construct()
    {
        // Oturum kontrolü
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        try {
            // Modelleri yükle
            $this->vehicleModel = $this->model('Vehicle');
            $this->driverModel = $this->model('Driver');
            $this->fuelModel = $this->model('FuelModel');
            $this->maintenanceModel = $this->model('MaintenanceModel');
            $this->assignmentModel = $this->model('Assignment');
            $this->companyModel = $this->model('Company');
            $this->db = new Database();
        } catch (\Exception $e) {
            error_log('Model yükleme hatası: ' . $e->getMessage());
            die('Sistem hatası: Modeller yüklenemedi.');
        }
    }

    // Ana sayfa - Tüm rapor türlerine genel bakış
    public function index()
    {
        // Genel istatistikleri al
        $vehicleStats = [
            'total' => $this->vehicleModel->getTotalVehicleCount(),
            'active' => $this->vehicleModel->getVehicleCountByStatus('Aktif'),
            'inactive' => $this->vehicleModel->getVehicleCountByStatus('Pasif'),
            'maintenance' => $this->vehicleModel->getVehicleCountByStatus('Bakımda')
        ];

        $driverStats = [
            'total' => $this->driverModel->getTotalDriverCount(),
            'active' => $this->driverModel->getDriverCountByStatus('Aktif'),
            'inactive' => $this->driverModel->getDriverCountByStatus('Pasif'),
            'onLeave' => $this->driverModel->getDriverCountByStatus('İzinli')
        ];

        $assignmentStats = [
            'total' => $this->assignmentModel->getTotalAssignmentCount(),
            'active' => $this->assignmentModel->getAssignmentCountByStatus('Aktif'),
            'completed' => $this->assignmentModel->getAssignmentCountByStatus('Tamamlandı'),
            'cancelled' => $this->assignmentModel->getAssignmentCountByStatus('İptal')
        ];

        $fuelStats = $this->fuelModel->getTotalFuelStats();
        $maintenanceStats = $this->maintenanceModel->getTotalMaintenanceStats();

        $data = [
            'title' => 'Raporlar',
            'vehicleStats' => $vehicleStats,
            'driverStats' => $driverStats,
            'assignmentStats' => $assignmentStats,
            'fuelStats' => $fuelStats,
            'maintenanceStats' => $maintenanceStats
        ];

        $this->view('reports/index', $data);
    }

    // Araç raporları
    public function vehicles()
    {
        // Filtre parametrelerini al
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        $type = isset($_GET['type']) ? trim($_GET['type']) : '';
        $year = isset($_GET['year']) ? intval($_GET['year']) : 0;

        // Tarihlere göre filtreleme için
        $startDate = isset($_GET['start_date']) ? trim($_GET['start_date']) : '';
        $endDate = isset($_GET['end_date']) ? trim($_GET['end_date']) : '';

        // Araçları filtrelere göre getir
        $vehicles = $this->vehicleModel->getVehiclesByFilters($status, $type, $year);

        // Her araç için yakıt ve bakım bilgilerini topla
        foreach ($vehicles as $vehicle) {
            $vehicle->fuelStats = $this->fuelModel->getVehicleFuelConsumption($vehicle->id);
            $vehicle->maintenanceCost = $this->maintenanceModel->getTotalMaintenanceCostByVehicle($vehicle->id);
            $vehicle->assignment_count = $this->assignmentModel->getAssignmentCountByVehicle($vehicle->id);
        }

        // İstatistikler
        $vehicleStats = [
            'total' => $this->vehicleModel->getTotalVehicleCount(),
            'active' => $this->vehicleModel->getVehicleCountByStatus('Aktif'),
            'inactive' => $this->vehicleModel->getVehicleCountByStatus('Pasif'),
            'maintenance' => $this->vehicleModel->getVehicleCountByStatus('Bakımda')
        ];

        // Araç tipleri için dağılım
        $vehicleTypeDistribution = $this->vehicleModel->getVehicleCountByType();

        $data = [
            'title' => 'Araç Raporları',
            'vehicles' => $vehicles,
            'vehicleStats' => $vehicleStats,
            'vehicleTypeDistribution' => $vehicleTypeDistribution,
            'filters' => [
                'status' => $status,
                'type' => $type,
                'year' => $year,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ];

        $this->view('reports/vehicles', $data);
    }

    // Sürücü raporları
    public function drivers()
    {
        // Filtre parametrelerini al
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        $licenseType = isset($_GET['license_type']) ? trim($_GET['license_type']) : '';
        $assignmentStatus = isset($_GET['assignment_status']) ? trim($_GET['assignment_status']) : '';

        // Sürücüleri filtrelere göre getir
        $drivers = $this->driverModel->getDriversByFilters($status, $licenseType, $assignmentStatus !== '' ? $assignmentStatus : null);

        // Her sürücü için görevlendirme bilgilerini topla
        foreach ($drivers as $driver) {
            $driver->assignment_count = $this->assignmentModel->getAssignmentCountByDriver($driver->id);
            $driver->current_assignment = $this->assignmentModel->getActiveAssignmentByDriver($driver->id);
        }

        // Aktif görevlendirmesi olan sürücü sayısını bul
        $this->db->query('SELECT COUNT(DISTINCT driver_id) as count FROM vehicle_assignments WHERE status = "Aktif"');
        $assignedDriverCount = $this->db->single()->count;

        // İstatistikler
        $driverStats = [
            'total' => $this->driverModel->getTotalDriverCount(),
            'active' => $this->driverModel->getDriverCountByStatus('Aktif'),
            'inactive' => $this->driverModel->getDriverCountByStatus('Pasif'),
            'onLeave' => $this->driverModel->getDriverCountByStatus('İzinli'),
            'assigned' => $assignedDriverCount,
            'total_assignments' => $this->assignmentModel->getTotalAssignmentCount()
        ];

        // Ehliyet tiplerine göre dağılım
        $licenseTypeDistribution = $this->driverModel->getDriverCountByLicenseType();

        $data = [
            'title' => 'Sürücü Raporları',
            'drivers' => $drivers,
            'driverStats' => $driverStats,
            'licenseDistribution' => $licenseTypeDistribution,
            'filters' => [
                'status' => $status,
                'license_type' => $licenseType,
                'assignment_status' => $assignmentStatus
            ]
        ];

        $this->view('reports/drivers', $data);
    }

    // Yakıt raporları
    public function fuel()
    {
        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-1 month'));
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
        $fuelType = isset($_GET['fuel_type']) ? $_GET['fuel_type'] : '';
        $vehicleId = isset($_GET['vehicle_id']) ? $_GET['vehicle_id'] : '';
        $driverId = isset($_GET['driver_id']) ? $_GET['driver_id'] : '';

        // Filtreleri uygula
        $filters = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'fuel_type' => $fuelType,
            'vehicle_id' => $vehicleId,
            'driver_id' => $driverId
        ];

        $filteredData = $this->fuelModel->getFilteredFuelRecords($filters);
        $records = $filteredData['records'];

        // Toplam yakıt tüketimi
        $totalFuel = 0;
        $recordCount = count($records);

        // fuelTypeStatsAgg değişkenini başlat
        $fuelTypeStatsAgg = [];

        // Verileri işle
        foreach ($records as $record) {
            $totalFuel += $record->amount;

            // Yakıt Türü İstatistikleri
            if (!isset($fuelTypeStatsAgg[$record->fuel_type])) {
                $fuelTypeStatsAgg[$record->fuel_type] = ['total_amount' => 0, 'record_count' => 0];
            }
            $fuelTypeStatsAgg[$record->fuel_type]['total_amount'] += $record->amount;
            $fuelTypeStatsAgg[$record->fuel_type]['record_count']++;
        }

        // Yakıt türlerine göre istatistikleri hesapla
        $fuelTypeStats = [];
        foreach ($fuelTypeStatsAgg as $type => $stats) {
            $fuelTypeStats[] = (object)[
                'fuel_type' => $type,
                'total_amount' => $stats['total_amount'],
                'record_count' => $stats['record_count']
            ];
        }

        // Yakıt türü dağılımı oluşturma
        $fuelTypeDistribution = [];

        // Standart yakıt tiplerini tanımla
        $standardFuelTypes = ['Benzin', 'Dizel'];

        // Mevcut yakıt tiplerini diziye ekle
        $existingFuelTypes = [];
        foreach ($fuelTypeStats as $type) {
            $existingFuelTypes[] = $type->fuel_type;
            $fuelTypeDistribution[] = (object)[
                'fuel_type' => $type->fuel_type,
                'total_liters' => $type->total_amount
            ];
        }

        // Eksik yakıt tiplerini 0 değeriyle ekle
        foreach ($standardFuelTypes as $fuelType) {
            if (!in_array($fuelType, $existingFuelTypes)) {
                $fuelTypeDistribution[] = (object)[
                    'fuel_type' => $fuelType,
                    'total_liters' => 0
                ];
            }
        }

        // Yakıt tiplerini alfabetik olarak sırala
        usort($fuelTypeDistribution, function ($a, $b) {
            return strcmp($a->fuel_type, $b->fuel_type);
        });

        // Araç ve sürücü listeleri (Filtre dropdownları için)
        $vehicles = $this->vehicleModel->getAllVehicles();
        $drivers = $this->driverModel->getAllDrivers();

        $data = [
            'title' => 'Yakıt Raporları',
            'fuelRecords' => $records,
            'fuelStats' => (object)[
                'total_fuel' => $totalFuel,
                'record_count' => $recordCount
            ],
            'fuelTypeStats' => $fuelTypeStats,
            'fuelTypeDistribution' => $fuelTypeDistribution,
            'vehicles' => $vehicles,
            'drivers' => $drivers,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'fuel_type' => $fuelType,
                'vehicle_id' => $vehicleId,
                'driver_id' => $driverId
            ]
        ];

        $this->view('reports/fuel', $data);
    }

    // Bakım raporları
    public function maintenance()
    {
        // Filtre parametrelerini al
        $vehicleId = isset($_GET['vehicle_id']) ? intval($_GET['vehicle_id']) : 0;
        $maintenanceType = isset($_GET['maintenance_type']) ? trim($_GET['maintenance_type']) : '';
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        $startDate = isset($_GET['start_date']) ? trim($_GET['start_date']) : '';
        $endDate = isset($_GET['end_date']) ? trim($_GET['end_date']) : '';

        // Bakım kayıtlarını filtrelere göre getir
        $maintenanceRecords = [];
        if (!empty($startDate) && !empty($endDate)) {
            $maintenanceRecords = $this->maintenanceModel->getMaintenanceRecordsByDateRange($startDate, $endDate);
        } elseif ($vehicleId > 0) {
            $maintenanceRecords = $this->maintenanceModel->getMaintenanceRecordsByVehicle($vehicleId);
        } elseif (!empty($maintenanceType)) {
            $maintenanceRecords = $this->maintenanceModel->getMaintenanceRecordsByType($maintenanceType);
        } else {
            $maintenanceRecords = $this->maintenanceModel->getMaintenanceRecords();
        }

        // Duruma göre filtreleme
        if (!empty($status)) {
            $filteredRecords = [];
            foreach ($maintenanceRecords as $record) {
                if ($record->status == $status) {
                    $filteredRecords[] = $record;
                }
            }
            $maintenanceRecords = $filteredRecords;
        }

        // Bakım istatistikleri
        $maintenanceStats = $this->maintenanceModel->getTotalMaintenanceStats();

        // Devam eden bakım sayısı
        $ongoingCount = $this->maintenanceModel->getMaintenanceCountByStatus('Devam Ediyor');
        $maintenanceStats->ongoing = $ongoingCount;

        // Bakım türlerine göre istatistikler
        $maintenanceTypeStats = $this->maintenanceModel->getMaintenanceStatsByType();

        // Yaklaşan bakımlar
        $upcomingMaintenances = $this->maintenanceModel->getUpcomingMaintenances();

        // Araç listesi
        $vehicles = $this->vehicleModel->getAllVehicles();

        $data = [
            'title' => 'Bakım Raporları',
            'maintenanceRecords' => $maintenanceRecords,
            'maintenanceStats' => $maintenanceStats,
            'maintenanceTypeStats' => $maintenanceTypeStats,
            'upcomingMaintenances' => $upcomingMaintenances,
            'vehicles' => $vehicles,
            'filters' => [
                'vehicle_id' => $vehicleId,
                'maintenance_type' => $maintenanceType,
                'status' => $status,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ];

        $this->view('reports/maintenance', $data);
    }

    // Görevlendirme raporları
    public function assignments()
    {
        // Filtre parametrelerini al
        $vehicleId = isset($_GET['vehicle_id']) ? intval($_GET['vehicle_id']) : 0;
        $driverId = isset($_GET['driver_id']) ? intval($_GET['driver_id']) : 0;
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        $startDate = isset($_GET['start_date']) ? trim($_GET['start_date']) : '';
        $endDate = isset($_GET['end_date']) ? trim($_GET['end_date']) : '';

        // Görevlendirmeleri filtrelere göre getir
        $assignments = [];
        if ($vehicleId > 0 && $driverId > 0) {
            $assignments = $this->assignmentModel->getAssignmentsByVehicleAndDriver($vehicleId, $driverId);
        } elseif ($vehicleId > 0) {
            $assignments = $this->assignmentModel->getAssignmentsByVehicle($vehicleId);
        } elseif ($driverId > 0) {
            $assignments = $this->assignmentModel->getAssignmentsByDriver($driverId);
        } else {
            $assignments = $this->assignmentModel->getAllAssignments();
        }

        // Duruma göre filtreleme
        if (!empty($status)) {
            $filteredAssignments = [];
            foreach ($assignments as $assignment) {
                if ($assignment->status == $status) {
                    $filteredAssignments[] = $assignment;
                }
            }
            $assignments = $filteredAssignments;
        }

        // Tarihe göre filtreleme
        if (!empty($startDate) && !empty($endDate)) {
            $filteredAssignments = [];
            foreach ($assignments as $assignment) {
                if (
                    strtotime($assignment->start_date) >= strtotime($startDate) &&
                    (empty($assignment->end_date) || strtotime($assignment->end_date) <= strtotime($endDate))
                ) {
                    $filteredAssignments[] = $assignment;
                }
            }
            $assignments = $filteredAssignments;
        }

        // Görevlendirme istatistikleri
        $assignmentStats = [
            'total' => $this->assignmentModel->getTotalAssignmentCount(),
            'active' => $this->assignmentModel->getAssignmentCountByStatus('Aktif'),
            'completed' => $this->assignmentModel->getAssignmentCountByStatus('Tamamlandı'),
            'cancelled' => $this->assignmentModel->getAssignmentCountByStatus('İptal')
        ];

        // En çok görevlendirilen araçları ve sürücüleri al
        $topVehicles = $this->assignmentModel->getTopVehicles(5);
        $topDrivers = $this->assignmentModel->getTopDrivers(5);

        // Araç ve sürücü listeleri
        $vehicles = $this->vehicleModel->getAllVehicles();
        $drivers = $this->driverModel->getAllDrivers();

        $data = [
            'title' => 'Görevlendirme Raporları',
            'assignments' => $assignments,
            'assignmentStats' => $assignmentStats,
            'vehicles' => $vehicles,
            'drivers' => $drivers,
            'topVehicles' => $topVehicles,
            'topDrivers' => $topDrivers,
            'filters' => [
                'vehicle_id' => $vehicleId,
                'driver_id' => $driverId,
                'status' => $status,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ];

        $this->view('reports/assignments', $data);
    }

    // Özel rapor oluşturma
    public function custom()
    {
        // Form gönderildi mi kontrol et
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini al
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $reportType = trim($_POST['report_type']);
            $startDate = trim($_POST['start_date']);
            $endDate = trim($_POST['end_date']);
            $groupBy = isset($_POST['group_by']) ? trim($_POST['group_by']) : '';

            // Rapor türüne göre işlem yap
            switch ($reportType) {
                case 'fuel':
                    redirect('reports/fuel?start_date=' . $startDate . '&end_date=' . $endDate);
                    break;
                case 'maintenance':
                    redirect('reports/maintenance?start_date=' . $startDate . '&end_date=' . $endDate);
                    break;
                case 'assignments':
                    redirect('reports/assignments?start_date=' . $startDate . '&end_date=' . $endDate);
                    break;
                default:
                    flash('report_message', 'Geçersiz rapor türü', 'alert alert-danger');
                    redirect('reports/custom');
                    break;
            }
        } else {
            $data = [
                'title' => 'Özel Rapor Oluştur'
            ];

            $this->view('reports/custom', $data);
        }
    }

    /**
     * Araç geçmişini gösterir (yakıt, bakım, görevlendirme kayıtları)
     * 
     * @param int $id Araç ID'si
     * @return void
     */
    public function vehicle_history($id = null)
    {
        // Debug bilgileri
        error_log("vehicle_history metodu çağrıldı - ID: " . $id);
        error_log("URI: " . $_SERVER['REQUEST_URI']);
    
        // Temel değişkenler
        $vehicle = null;
        $company = null;
        $fuelRecords = [];
        $assignments = [];
        $history = [];

        // ID kontrolü
        if (!$id || !is_numeric($id)) {
            error_log("Geçersiz ID: " . print_r($id, true));
            flash('vehicle_error', 'Geçersiz araç ID\'si', 'alert alert-danger');
            redirect('reports/vehicles');
            return;
        }

        try {
            // 1. ADIM: Araç verilerini al
            try {
                $vehicle = $this->vehicleModel->getVehicleById($id);
                
                if (!$vehicle) {
                    error_log("Araç bulunamadı - ID: " . $id);
                    flash('vehicle_error', 'Araç bulunamadı', 'alert alert-danger');
                    redirect('reports/vehicles');
                    return;
                }
                
                error_log("Araç bulundu: " . print_r($vehicle, true));
            } catch (\Exception $e) {
                error_log("Araç bilgisi alınırken hata: " . $e->getMessage());
                throw $e;
            }

            // 2. ADIM: Şirket bilgisini al
            try {
                if (!empty($vehicle->company_id)) {
                    $company = $this->companyModel->getCompanyById($vehicle->company_id);
                }
            } catch (\Exception $e) {
                error_log("Şirket bilgisi alınırken hata: " . $e->getMessage());
                // Şirket verisi olmadan devam edebiliriz
            }

            // 3. ADIM: Yakıt kayıtlarını al
            try {
                $fuelRecords = $this->fuelModel->getFuelRecordsByVehicle($id);
                error_log("Yakıt kayıtları alındı: " . count($fuelRecords) . " kayıt");
            } catch (\Exception $e) {
                error_log("Yakıt kayıtları alınırken hata: " . $e->getMessage());
                $fuelRecords = []; // Boş dizi ile devam et
            }

            // 4. ADIM: Görevlendirmeleri al
            try {
                $assignments = $this->assignmentModel->getAssignmentsByVehicle($id);
                error_log("Görevlendirmeler alındı: " . count($assignments) . " kayıt");
            } catch (\Exception $e) {
                error_log("Görevlendirmeler alınırken hata: " . $e->getMessage());
                $assignments = []; // Boş dizi ile devam et
            }

            // 5. ADIM: Geçmiş verileri oluştur
            try {
                // Yakıt kayıtlarını ekle
                foreach ($fuelRecords as $fuel) {
                    $history[] = (object)[
                        'date' => $fuel->date,
                        'type' => 'fuel',
                        'details' => $fuel
                    ];
                }
                
                // Görevlendirmeleri ekle
                foreach ($assignments as $assignment) {
                    $duration = null;
                    if (!empty($assignment->end_date) && !empty($assignment->start_date)) {
                        try {
                            $start = new \DateTime($assignment->start_date);
                            $end = new \DateTime($assignment->end_date);
                            $diff = $start->diff($end);
                            $duration = $diff->days;
                        } catch (\Exception $e) {
                            error_log("Tarih hesaplaması hatası: " . $e->getMessage());
                        }
                    }
                    
                    $history[] = (object)[
                        'date' => $assignment->start_date,
                        'type' => 'assignment',
                        'details' => $assignment,
                        'duration' => $duration
                    ];
                }
                
                // Tarihe göre sırala
                if (!empty($history)) {
                    usort($history, function($a, $b) {
                        return strtotime($b->date) - strtotime($a->date);
                    });
                }
            } catch (\Exception $e) {
                error_log("Geçmiş verileri işlenirken hata: " . $e->getMessage());
                $history = []; // Boş dizi ile devam et
            }

            // 6. ADIM: Verileri görünüme gönder
            $data = [
                'title' => 'Araç Geçmişi: ' . $vehicle->plate_number,
                'vehicle' => $vehicle,
                'company' => $company,
                'fuelRecords' => $fuelRecords,
                'assignments' => $assignments,
                'history' => $history
            ];
            
            error_log("Görünüme gönderilecek veriler: " . print_r(array_keys($data), true));

            // 7. ADIM: Görünümü yükle
            error_log("vehicle_history görünümü yükleniyor...");
            $this->view('reports/vehicle_history', $data);
            error_log("Görünüm yüklendi");

        } catch (\Exception $e) {
            error_log("*** KRITIK HATA ***: " . $e->getMessage());
            error_log("Hata detayı: " . $e->getTraceAsString());
            
            // Basit bir veri hazırla
            $data = [
                'title' => 'Araç Geçmişi - Hata',
                'error' => $e->getMessage(),
                'vehicle' => null
            ];
            
            // Hata görünümünü yükle ya da vehicles'a yönlendir
            try {
                $this->view('reports/vehicle_history', $data);
            } catch (\Exception $viewError) {
                error_log("Hata görünümü yüklenirken ikinci bir hata: " . $viewError->getMessage());
                flash('vehicle_error', 'Araç geçmişi görüntülenirken bir hata oluştu: ' . $e->getMessage(), 'alert alert-danger');
                redirect('reports/vehicles');
            }
        }
    }

    /**
     * Test metodu - vehicle_history hatasını test etmek için
     */
    public function test_vehicle()
    {
        // Çok basit bir sayfa göster
        echo '<html><head><title>Test Sayfası</title></head><body>';
        echo '<h1>Test Sayfası</h1>';
        echo '<p>Bu sayfa açılıyorsa Reports controller çalışıyor demektir.</p>';
        echo '<p>Şimdi vehicle_history sayfasını test edelim:</p>';
        echo '<a href="' . URLROOT . '/reports/vehicle_history/14">Araç Geçmişini Görüntüle (ID: 14)</a>';
        echo '</body></html>';
        exit;
    }
}
