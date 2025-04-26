<?php
   namespace App\Controllers;

   use App\Core\Controller;

   class Dashboard extends Controller {
    private $vehicleModel;
    private $driverModel;
    private $companyModel;
    private $fuelModel;
    private $maintenanceModel;

    public function __construct() {
        // Oturum kontrolü
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // Model sınıflarını yükle
        try {
            $this->vehicleModel = $this->model('Vehicle');
            $this->driverModel = $this->model('Driver');
            $this->companyModel = $this->model('Company');
            
            // FuelModel ve MaintenanceModel için hata kontrolü
            try {
                $this->fuelModel = $this->model('FuelModel');
            } catch (\Exception $e) {
                error_log('FuelModel yüklenemedi: ' . $e->getMessage());
                $this->fuelModel = null;
            }
            
            try {
                $this->maintenanceModel = $this->model('MaintenanceModel');
            } catch (\Exception $e) {
                error_log('MaintenanceModel yüklenemedi: ' . $e->getMessage());
                $this->maintenanceModel = null;
            }
        } catch (\Exception $e) {
            error_log('Dashboard modelleri yüklenirken hata: ' . $e->getMessage());
        }
    }

    public function index() {
        // Toplam araç ve sürücü sayıları
        $totalVehicles = 0;
        $activeDrivers = 0;
        $totalCompanies = 0;
        
        try {
            $totalVehicles = $this->vehicleModel->getTotalVehicleCount();
        } catch (\Exception $e) {
            error_log('Toplam araç sayısı alınamadı: ' . $e->getMessage());
        }
        
        try {
            $activeDrivers = $this->driverModel->getActiveDriverCount();
        } catch (\Exception $e) {
            error_log('Aktif sürücü sayısı alınamadı: ' . $e->getMessage());
        }
        
        try {
            $totalCompanies = $this->companyModel->getTotalCompaniesCount();
        } catch (\Exception $e) {
            error_log('Toplam şirket sayısı alınamadı: ' . $e->getMessage());
        }

        // Yaklaşan bakımlar ve muayeneler
        $upcomingMaintenances = [];
        $upcomingMaintenanceCount = 0;
        
        if ($this->maintenanceModel !== null) {
            try {
                $upcomingMaintenances = $this->maintenanceModel->getUpcomingMaintenances();
                $upcomingMaintenanceCount = is_countable($upcomingMaintenances) ? count($upcomingMaintenances) : 0;
            } catch (\Exception $e) {
                error_log('Bakım bilgileri alınamadı: ' . $e->getMessage());
            }
        }
        
        // Yaklaşan muayeneler için hata kontrolü
        $upcomingInspections = [];
        try {
            $upcomingInspections = $this->vehicleModel->getUpcomingInspections();
            if (!is_array($upcomingInspections)) {
                error_log('Muayene bilgileri dizi formatında değil');
                $upcomingInspections = [];
            }
        } catch (\Exception $e) {
            error_log('Muayene bilgileri alınamadı: ' . $e->getMessage());
        }

        // Yakıt tüketim verileri
        $fuelConsumptionMonths = [];
        $fuelConsumptionWeeks = [];
        $fuelConsumptionByType = [];
        
        if ($this->fuelModel !== null) {
            try {
                $fuelConsumptionMonths = $this->fuelModel->getMonthlyFuelConsumption();
                if (!is_array($fuelConsumptionMonths)) {
                    error_log('Aylık yakıt tüketim verileri dizi formatında değil');
                    $fuelConsumptionMonths = [];
                }
            } catch (\Exception $e) {
                error_log('Aylık yakıt tüketimi verileri alınamadı: ' . $e->getMessage());
            }
            
            try {
                // Haftalık yakıt tüketimi verilerini getir
                $fuelConsumptionWeeks = $this->fuelModel->getWeeklyFuelConsumption();
                if (!is_array($fuelConsumptionWeeks)) {
                    error_log('Haftalık yakıt tüketim verileri dizi formatında değil');
                    $fuelConsumptionWeeks = [];
                }
            } catch (\Exception $e) {
                error_log('Haftalık yakıt tüketimi verileri alınamadı: ' . $e->getMessage());
            }
            
            try {
                $fuelConsumptionByType = $this->fuelModel->getFuelConsumptionByType();
                if (!is_array($fuelConsumptionByType)) {
                    error_log('Yakıt tipine göre tüketim verileri dizi formatında değil');
                    $fuelConsumptionByType = [];
                }
            } catch (\Exception $e) {
                error_log('Yakıt tipine göre tüketim verileri alınamadı: ' . $e->getMessage());
            }
        }

        // Araç tipleri dağılımı - Özel kontroller ekledik
        $vehicleTypeDistribution = [];
        try {
            // Veritabanı sorgusu çalıştır
            $vehicleTypeDistribution = $this->vehicleModel->getVehicleTypeDistribution();
            
            // Sonucun geçerli olup olmadığını kontrol et (Vehicle modeli içinde zaten güvenli veri döndürür)
            if (!is_array($vehicleTypeDistribution) && !is_object($vehicleTypeDistribution)) {
                error_log('Araç tipi dağılımı geçerli bir veri türü döndürmedi: ' . gettype($vehicleTypeDistribution));
                $vehicleTypeDistribution = [];
            }
        } catch (\Exception $e) {
            error_log('Araç tipi dağılımı alınamadı: ' . $e->getMessage());
            $vehicleTypeDistribution = [];
        }

        // Son eklenen kayıtlar
        $recentCompanies = [];
        try {
            $recentCompanies = $this->companyModel->getRecentCompanies();
            if (!is_array($recentCompanies)) {
                error_log('Son eklenen şirketler dizi formatında değil');
                $recentCompanies = [];
            }
        } catch (\Exception $e) {
            error_log('Son eklenen şirketler alınamadı: ' . $e->getMessage());
        }
        
        // Yakıt tankı bilgilerini al
        $fuelTanks = [];
        try {
            if ($this->fuelModel !== null) {
                // FuelTank modelini oluştur
                $fuelTankModel = $this->model('FuelTank');
                $fuelTanks = $fuelTankModel->getActiveTanks();
                if (!is_array($fuelTanks)) {
                    error_log('Yakıt tankları dizi formatında değil');
                    $fuelTanks = [];
                }
            }
        } catch (\Exception $e) {
            error_log('Yakıt tankları alınamadı: ' . $e->getMessage());
        }
        
        $recentVehicles = [];
        try {
            $recentVehicles = $this->vehicleModel->getRecentVehicles();
            if (!is_array($recentVehicles)) {
                error_log('Son eklenen araçlar dizi formatında değil');
                $recentVehicles = [];
            }
        } catch (\Exception $e) {
            error_log('Son eklenen araçlar alınamadı: ' . $e->getMessage());
        }
        
        $recentDrivers = [];
        try {
            $recentDrivers = $this->driverModel->getRecentDrivers();
            if (!is_array($recentDrivers)) {
                error_log('Son eklenen sürücüler dizi formatında değil');
                $recentDrivers = [];
            }
        } catch (\Exception $e) {
            error_log('Son eklenen sürücüler alınamadı: ' . $e->getMessage());
        }
        
        $recentMaintenance = [];
        if ($this->maintenanceModel !== null) {
            try {
                $recentMaintenance = $this->maintenanceModel->getRecentMaintenanceRecords();
                if (!is_array($recentMaintenance)) {
                    error_log('Son eklenen bakım kayıtları dizi formatında değil');
                    $recentMaintenance = [];
                }
            } catch (\Exception $e) {
                error_log('Son eklenen bakım kayıtları alınamadı: ' . $e->getMessage());
            }
        }

        $data = [
            'title' => 'Dashboard',
            'total_vehicles' => $totalVehicles,
            'active_drivers' => $activeDrivers,
            'total_companies' => $totalCompanies,
            'upcoming_maintenance_count' => $upcomingMaintenanceCount,
            'upcoming_maintenance' => $upcomingMaintenances,
            'upcoming_inspections' => $upcomingInspections,
            'fuel_consumption_months' => $fuelConsumptionMonths,
            'fuel_consumption_weeks' => $fuelConsumptionWeeks,
            'fuel_consumption_by_type' => $fuelConsumptionByType,
            'vehicle_type_distribution' => $vehicleTypeDistribution,
            'recent_companies' => $recentCompanies,
            'recent_vehicles' => $recentVehicles,
            'recent_drivers' => $recentDrivers,
            'recent_maintenance' => $recentMaintenance,
            'fuel_tanks' => $fuelTanks
        ];

        $this->view('dashboard/index', $data);
    }
} 