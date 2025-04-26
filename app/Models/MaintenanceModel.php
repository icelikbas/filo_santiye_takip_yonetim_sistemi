<?php

namespace App\Models;

use App\Core\Database;
use \PDO;
use \PDOException;
use \Exception;
use \DateTime;

class MaintenanceModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // Tüm bakım kayıtlarını al
    public function getMaintenanceRecords()
    {
        $this->db->query('
            SELECT m.*, v.plate_number, v.brand, v.model
            FROM maintenance_records m
            LEFT JOIN vehicles v ON m.vehicle_id = v.id 
            ORDER BY m.start_date DESC
        ');

        return $this->db->resultSet();
    }

    // ID'ye göre bakım kaydını al
    public function getMaintenanceRecordById($id)
    {
        $this->db->query('
            SELECT m.*, v.plate_number, v.brand, v.model, u.name as user_name
            FROM maintenance_records m 
            LEFT JOIN vehicles v ON m.vehicle_id = v.id
            LEFT JOIN users u ON m.created_by = u.id
            WHERE m.id = :id
        ');

        $this->db->bind(':id', $id);

        return $this->db->single();
    }

    // Araca göre bakım kayıtlarını al
    public function getMaintenanceRecordsByVehicle($vehicleId)
    {
        $this->db->query('
            SELECT m.*, v.plate_number, v.brand, v.model
            FROM maintenance_records m
            LEFT JOIN vehicles v ON m.vehicle_id = v.id
            WHERE m.vehicle_id = :vehicle_id
            ORDER BY m.start_date DESC
        ');

        $this->db->bind(':vehicle_id', $vehicleId);

        return $this->db->resultSet();
    }

    // Bakım türüne göre kayıtları al
    public function getMaintenanceRecordsByType($type)
    {
        $this->db->query('
            SELECT m.*, v.plate_number, v.brand, v.model
            FROM maintenance_records m
            LEFT JOIN vehicles v ON m.vehicle_id = v.id
            WHERE m.maintenance_type = :type
            ORDER BY m.start_date DESC
        ');

        $this->db->bind(':type', $type);

        return $this->db->resultSet();
    }

    // Tarihe göre bakım kayıtlarını al (belirli tarih aralığında)
    public function getMaintenanceRecordsByDateRange($startDate, $endDate)
    {
        $this->db->query('
            SELECT m.*, v.plate_number, v.brand, v.model
            FROM maintenance_records m
            LEFT JOIN vehicles v ON m.vehicle_id = v.id
            WHERE m.start_date BETWEEN :start_date AND :end_date
            ORDER BY m.start_date DESC
        ');

        $this->db->bind(':start_date', $startDate);
        $this->db->bind(':end_date', $endDate);

        return $this->db->resultSet();
    }

    // Bakım kaydı ekle
    public function addMaintenanceRecord($data)
    {
        try {
            $this->db->query('
                INSERT INTO maintenance_records (vehicle_id, maintenance_type, description, planning_date, start_date, end_date, cost, km_reading, hour_reading, status, notes, created_by, service_provider, next_maintenance_date, next_maintenance_km, next_maintenance_hours)
                VALUES (:vehicle_id, :maintenance_type, :description, :planning_date, :start_date, :end_date, :cost, :km_reading, :hour_reading, :status, :notes, :created_by, :service_provider, :next_maintenance_date, :next_maintenance_km, :next_maintenance_hours)
            ');

            // Bağlama işlemleri
            $this->db->bind(':vehicle_id', $data['vehicle_id']);
            $this->db->bind(':maintenance_type', $data['maintenance_type']);
            $this->db->bind(':description', $data['description']);
            $this->db->bind(':planning_date', !empty($data['planning_date']) ? $data['planning_date'] : null);
            $this->db->bind(':start_date', !empty($data['start_date']) ? $data['start_date'] : null);
            $this->db->bind(':end_date', !empty($data['end_date']) ? $data['end_date'] : null);
            $this->db->bind(':cost', isset($data['cost']) ? $data['cost'] : 0);
            $this->db->bind(':km_reading', !empty($data['km_reading']) ? $data['km_reading'] : 0);
            $this->db->bind(':hour_reading', !empty($data['hour_reading']) ? $data['hour_reading'] : null);
            $this->db->bind(':status', isset($data['status']) ? $data['status'] : 'Planlandı');
            $this->db->bind(':notes', !empty($data['notes']) ? $data['notes'] : null);
            $this->db->bind(':created_by', $_SESSION['user_id']);
            $this->db->bind(':service_provider', !empty($data['service_provider']) ? $data['service_provider'] : '');
            $this->db->bind(':next_maintenance_date', !empty($data['next_maintenance_date']) ? $data['next_maintenance_date'] : null);
            $this->db->bind(':next_maintenance_km', !empty($data['next_maintenance_km']) ? $data['next_maintenance_km'] : null);
            $this->db->bind(':next_maintenance_hours', !empty($data['next_maintenance_hours']) ? $data['next_maintenance_hours'] : null);

            // Çalıştır
            if ($this->db->execute()) {
                // Aracın mevcut kilometresini güncelle
                if (!empty($data['km_reading'])) {
                    $this->updateVehicleCurrentKm($data['vehicle_id'], $data['km_reading']);
                }

                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            // Hata mesajını loglama
            error_log('Maintenance Record Add Error: ' . $e->getMessage());
            return false;
        }
    }

    // Bakım kaydını güncelle
    public function updateMaintenanceRecord($data)
    {
        $this->db->query('
            UPDATE maintenance_records 
            SET vehicle_id = :vehicle_id, 
                maintenance_type = :maintenance_type, 
                description = :description,
                planning_date = :planning_date,
                cost = :cost, 
                km_reading = :km_reading,
                hour_reading = :hour_reading,
                start_date = :start_date, 
                end_date = :end_date,
                status = :status, 
                notes = :notes, 
                service_provider = :service_provider,
                next_maintenance_date = :next_maintenance_date,
                next_maintenance_km = :next_maintenance_km,
                next_maintenance_hours = :next_maintenance_hours,
                updated_at = NOW()
            WHERE id = :id
        ');

        // Bağlama işlemleri
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':vehicle_id', $data['vehicle_id']);
        $this->db->bind(':maintenance_type', $data['maintenance_type']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':planning_date', !empty($data['planning_date']) ? $data['planning_date'] : null);
        $this->db->bind(':cost', $data['cost']);
        $this->db->bind(':km_reading', !empty($data['km_reading']) ? $data['km_reading'] : 0);
        $this->db->bind(':hour_reading', !empty($data['hour_reading']) ? $data['hour_reading'] : null);
        $this->db->bind(':start_date', !empty($data['start_date']) ? $data['start_date'] : null);
        $this->db->bind(':end_date', !empty($data['end_date']) ? $data['end_date'] : null);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':notes', !empty($data['notes']) ? $data['notes'] : null);
        $this->db->bind(':service_provider', !empty($data['service_provider']) ? $data['service_provider'] : null);
        $this->db->bind(':next_maintenance_date', !empty($data['next_maintenance_date']) ? $data['next_maintenance_date'] : null);
        $this->db->bind(':next_maintenance_km', !empty($data['next_maintenance_km']) ? $data['next_maintenance_km'] : null);
        $this->db->bind(':next_maintenance_hours', !empty($data['next_maintenance_hours']) ? $data['next_maintenance_hours'] : null);

        // Çalıştır
        if ($this->db->execute()) {
            // Aracın mevcut kilometresini güncelle
            if (!empty($data['km_reading'])) {
                $this->updateVehicleCurrentKm($data['vehicle_id'], $data['km_reading']);
            }

            return true;
        } else {
            return false;
        }
    }

    // Bakım kaydını sil
    public function deleteMaintenanceRecord($id)
    {
        $this->db->query('DELETE FROM maintenance_records WHERE id = :id');

        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Yaklaşan bakımları al
    public function getUpcomingMaintenances($daysThreshold = 30, $kmThreshold = 1000)
    {
        $this->db->query('
            SELECT m.*, v.plate_number, v.brand, v.model
            FROM maintenance_records m
            LEFT JOIN vehicles v ON m.vehicle_id = v.id
            WHERE m.status = "Planlandı"
            ORDER BY m.planning_date ASC
            LIMIT 10
        ');

        return $this->db->resultSet();
    }

    // Yaklaşan kilometre bakımlarını al
    public function getUpcomingKmMaintenances($kmThreshold = 1000)
    {
        $this->db->query('
            SELECT m.*, v.plate_number, v.brand, v.model
            FROM maintenance_records m
            LEFT JOIN vehicles v ON m.vehicle_id = v.id
            WHERE m.next_maintenance_km IS NOT NULL 
              AND m.next_maintenance_km - m.km_reading <= :km_threshold 
              AND m.next_maintenance_km - m.km_reading > 0
            ORDER BY (m.next_maintenance_km - m.km_reading) ASC
            LIMIT 10
        ');

        $this->db->bind(':km_threshold', $kmThreshold);

        return $this->db->resultSet();
    }

    // Yaklaşan saat bakımlarını al
    public function getUpcomingHourMaintenances($hourThreshold = 50)
    {
        $this->db->query('
            SELECT m.*, v.plate_number, v.brand, v.model
            FROM maintenance_records m
            LEFT JOIN vehicles v ON m.vehicle_id = v.id
            WHERE m.next_maintenance_hours IS NOT NULL 
              AND m.next_maintenance_hours - m.hour_reading <= :hour_threshold 
              AND m.next_maintenance_hours - m.hour_reading > 0
            ORDER BY (m.next_maintenance_hours - m.hour_reading) ASC
            LIMIT 10
        ');

        $this->db->bind(':hour_threshold', $hourThreshold);

        return $this->db->resultSet();
    }

    // Geçmiş bakımları kontrol et
    public function getOverdueMaintenances()
    {
        $today = date('Y-m-d');

        $this->db->query('
            SELECT m.*, v.plate_number, v.brand, v.model
            FROM maintenance_records m
            LEFT JOIN vehicles v ON m.vehicle_id = v.id
            WHERE m.start_date < :today AND m.status != "Tamamlandı"
            ORDER BY m.start_date ASC
        ');

        $this->db->bind(':today', $today);

        return $this->db->resultSet();
    }

    // Araç başına toplam bakım maliyeti
    public function getTotalMaintenanceCostByVehicle($vehicleId)
    {
        $this->db->query('
            SELECT SUM(cost) as total_cost
            FROM maintenance_records
            WHERE vehicle_id = :vehicle_id
        ');

        $this->db->bind(':vehicle_id', $vehicleId);

        $result = $this->db->single();
        return $result->total_cost;
    }

    // Bakım türlerine göre istatistikler
    public function getMaintenanceStatsByType()
    {
        $this->db->query('
            SELECT 
                maintenance_type,
                COUNT(*) as record_count,
                SUM(cost) as total_cost,
                AVG(cost) as avg_cost
            FROM maintenance_records
            GROUP BY maintenance_type
            ORDER BY record_count DESC
        ');

        return $this->db->resultSet();
    }

    // Toplam bakım istatistikleri
    public function getTotalMaintenanceStats()
    {
        $this->db->query('
            SELECT 
                COUNT(*) as record_count,
                SUM(cost) as total_cost,
                AVG(cost) as avg_cost,
                COUNT(DISTINCT vehicle_id) as vehicle_count
            FROM maintenance_records
        ');

        return $this->db->single();
    }

    // Toplam bakım kaydı sayısı
    public function getMaintenanceRecordCount()
    {
        $this->db->query('SELECT COUNT(*) as count FROM maintenance_records');
        $row = $this->db->single();
        return $row->count;
    }

    // Son bakım kayıtlarını getir
    public function getRecentMaintenanceRecords($limit = 5)
    {
        $this->db->query('
            SELECT m.*, v.plate_number, v.brand, v.model, c.company_name
            FROM maintenance_records m
            LEFT JOIN vehicles v ON m.vehicle_id = v.id
            LEFT JOIN companies c ON v.company_id = c.id
            ORDER BY m.created_at DESC
            LIMIT :limit
        ');

        $this->db->bind(':limit', $limit, PDO::PARAM_INT);

        return $this->db->resultSet();
    }

    // Bakım türlerine göre dağılımı getir
    public function getMaintenanceTypeDistribution()
    {
        $this->db->query('
            SELECT 
                maintenance_type,
                COUNT(*) as count,
                SUM(cost) as total_cost
            FROM 
                maintenance_records
            GROUP BY 
                maintenance_type
            ORDER BY 
                count DESC
        ');

        return $this->db->resultSet();
    }

    // Aktif araçları select için getir
    public function getActiveVehiclesForSelect()
    {
        $this->db->query('
            SELECT v.id, v.plate_number, CONCAT(v.brand, " ", v.model) as vehicle_name
            FROM vehicles v
            WHERE v.status = "Aktif" OR v.status = "Bakımda"
            ORDER BY v.plate_number ASC
        ');

        return $this->db->resultSet();
    }

    // Araç bakım durumunu güncelle
    public function updateVehicleMaintenanceStatus($vehicle_id, $status)
    {
        $this->db->query('UPDATE vehicles SET status = :status WHERE id = :vehicle_id');
        $this->db->bind(':status', $status);
        $this->db->bind(':vehicle_id', $vehicle_id);
        return $this->db->execute();
    }

    // Bir aracın aktif bakımlarını getir
    public function getActiveMaintenancesForVehicle($vehicle_id)
    {
        $this->db->query('
            SELECT * FROM maintenance_records 
            WHERE vehicle_id = :vehicle_id 
            AND (status = "Devam Ediyor" OR status = "Planlandı")
        ');

        $this->db->bind(':vehicle_id', $vehicle_id);

        return $this->db->resultSet();
    }

    // Bir araç için belirli bir kayıt dışındaki aktif bakımları getir
    public function getActiveMaintenancesForVehicleExcept($vehicle_id, $record_id)
    {
        $this->db->query('
            SELECT * FROM maintenance_records 
            WHERE vehicle_id = :vehicle_id 
            AND id != :record_id
            AND (status = "Devam Ediyor" OR status = "Planlandı")
        ');

        $this->db->bind(':vehicle_id', $vehicle_id);
        $this->db->bind(':record_id', $record_id);

        return $this->db->resultSet();
    }

    // Araç kilometresini güncelle
    public function updateVehicleCurrentKm($vehicleId, $kmReading)
    {
        $this->db->query('
            UPDATE vehicles 
            SET current_km = :km_reading 
            WHERE id = :vehicle_id AND (current_km IS NULL OR current_km < :km_reading)
        ');

        $this->db->bind(':vehicle_id', $vehicleId);
        $this->db->bind(':km_reading', $kmReading);

        return $this->db->execute();
    }

    // Bir araca ait en son bakım kaydını getir
    public function getLastMaintenanceRecordByVehicle($vehicleId)
    {
        $this->db->query('
            SELECT * FROM maintenance_records
            WHERE vehicle_id = :vehicle_id
            ORDER BY start_date DESC, id DESC
            LIMIT 1
        ');

        $this->db->bind(':vehicle_id', $vehicleId);

        return $this->db->single();
    }

    // Bakım türlerine göre maliyet analizi
    public function getMaintenanceCostAnalysis()
    {
        $this->db->query('
            SELECT 
                maintenance_type,
                COUNT(*) as record_count,
                SUM(cost) as total_cost,
                AVG(cost) as avg_cost,
                MIN(cost) as min_cost,
                MAX(cost) as max_cost
            FROM maintenance_records
            WHERE status = "Tamamlandı"
            GROUP BY maintenance_type
            ORDER BY total_cost DESC
        ');

        return $this->db->resultSet();
    }

    // Durum bazında bakım sayıları
    public function getMaintenanceCountByStatus($status)
    {
        $this->db->query('
            SELECT COUNT(*) as count
            FROM maintenance_records
            WHERE status = :status
        ');

        $this->db->bind(':status', $status);

        $row = $this->db->single();
        return $row->count;
    }

    // Toplam bakım maliyeti
    public function getTotalMaintenanceCost()
    {
        $this->db->query('
            SELECT SUM(cost) as total_cost
            FROM maintenance_records
            WHERE status = "Tamamlandı"
        ');

        $result = $this->db->single();
        return $result->total_cost;
    }

    // Duruma göre bakım kayıtlarını al
    public function getMaintenanceRecordsByStatus($status)
    {
        try {
            // SQL sorgusu
            $query = '
                SELECT m.*, v.plate_number, v.brand, v.model
                FROM maintenance_records m
                LEFT JOIN vehicles v ON m.vehicle_id = v.id
                WHERE m.status = :status
                ORDER BY 
                    CASE 
                        WHEN m.status = "Planlandı" THEN 
                            IFNULL(m.planning_date, m.start_date)
                        WHEN m.status = "Devam Ediyor" THEN 
                            m.start_date
                        WHEN m.status = "Tamamlandı" THEN 
                            IFNULL(m.end_date, m.start_date)
                        ELSE 
                            m.start_date
                    END DESC
            ';

            // Sorguyu hazırla
            $this->db->query($query);

            // Parametreleri bağla
            $this->db->bind(':status', $status);

            // Sorguyu çalıştır ve sonuçları döndür
            return $this->db->resultSet();
        } catch (PDOException $e) {
            error_log('MaintenanceModel::getMaintenanceRecordsByStatus - Hata: ' . $e->getMessage());
            return [];
        }
    }

    // Tüm durumlara göre bakım sayılarını getir
    public function getMaintenanceCountsByStatus()
    {
        $this->db->query('
            SELECT 
                status,
                COUNT(*) as count
            FROM 
                maintenance_records
            GROUP BY 
                status
        ');

        $results = $this->db->resultSet();

        // Sonuçları düzenli bir diziye çevir
        $statusCounts = [
            'Planlandı' => 0,
            'Devam Ediyor' => 0,
            'Tamamlandı' => 0,
            'İptal' => 0
        ];

        foreach ($results as $result) {
            $statusCounts[$result->status] = $result->count;
        }

        return $statusCounts;
    }

    // Servis sağlayıcıların kullanım istatistiklerini getir
    public function getServiceProviderUsage()
    {
        $this->db->query('
            SELECT 
                service_provider,
                COUNT(*) as count
            FROM 
                maintenance_records
            WHERE 
                service_provider IS NOT NULL 
                AND service_provider != ""
            GROUP BY 
                service_provider
            ORDER BY 
                count DESC
        ');

        $results = $this->db->resultSet();

        // Sonuçları düzenli bir diziye çevir
        $providers = [];
        foreach ($results as $result) {
            $providers[$result->service_provider] = $result->count;
        }

        return $providers;
    }

    // Tüm bakım tiplerine göre sayıları getir
    public function getMaintenanceCountsByType()
    {
        $this->db->query('
            SELECT 
                maintenance_type,
                COUNT(*) as count
            FROM 
                maintenance_records
            GROUP BY 
                maintenance_type
            ORDER BY 
                count DESC
        ');

        return $this->db->resultSet();
    }

    // Filtre kriterlerine göre toplam bakım kaydı sayısını al
    public function getTotalMaintenanceRecords($filterData = [])
    {
        $sql = "SELECT COUNT(*) as total FROM maintenance_records m 
                LEFT JOIN vehicles v ON m.vehicle_id = v.id
                WHERE 1=1";

        $params = [];

        // Araç ID'sine göre filtrele
        if (!empty($filterData['vehicle_id'])) {
            $sql .= " AND m.vehicle_id = :vehicle_id";
            $params[':vehicle_id'] = $filterData['vehicle_id'];
        }

        // Bakım türüne göre filtrele
        if (!empty($filterData['maintenance_type'])) {
            $sql .= " AND m.maintenance_type = :maintenance_type";
            $params[':maintenance_type'] = $filterData['maintenance_type'];
        }

        // Duruma göre filtrele
        if (!empty($filterData['status'])) {
            $sql .= " AND m.status = :status";
            $params[':status'] = $filterData['status'];
        }

        // Tarih aralığına göre filtrele
        if (!empty($filterData['start_date'])) {
            $sql .= " AND m.start_date >= :start_date";
            $params[':start_date'] = $filterData['start_date'];
        }

        if (!empty($filterData['end_date'])) {
            $sql .= " AND m.start_date <= :end_date";
            $params[':end_date'] = $filterData['end_date'];
        }

        // Arama terimini plaka, marka, model veya açıklamada ara
        if (!empty($filterData['search'])) {
            $searchTerm = '%' . $filterData['search'] . '%';
            $sql .= " AND (v.plate_number LIKE :search OR v.brand LIKE :search OR v.model LIKE :search OR m.description LIKE :search OR m.maintenance_type LIKE :search)";
            $params[':search'] = $searchTerm;
        }

        $this->db->query($sql);

        // Parametreleri bağla
        foreach ($params as $param => $value) {
            $this->db->bind($param, $value);
        }

        $row = $this->db->single();
        return $row->total;
    }

    // Filtre kriterlerine göre bakım kayıtlarını getir
    public function getFilteredMaintenanceRecords($filterData = [])
    {
        $sql = "SELECT m.*, v.plate_number, v.brand, v.model
                FROM maintenance_records m
                LEFT JOIN vehicles v ON m.vehicle_id = v.id
                WHERE 1=1";

        $params = [];

        // Araç ID'sine göre filtrele
        if (!empty($filterData['vehicle_id'])) {
            $sql .= " AND m.vehicle_id = :vehicle_id";
            $params[':vehicle_id'] = $filterData['vehicle_id'];
        }

        // Bakım türüne göre filtrele
        if (!empty($filterData['maintenance_type'])) {
            $sql .= " AND m.maintenance_type = :maintenance_type";
            $params[':maintenance_type'] = $filterData['maintenance_type'];
        }

        // Duruma göre filtrele
        if (!empty($filterData['status'])) {
            $sql .= " AND m.status = :status";
            $params[':status'] = $filterData['status'];
        }

        // Tarih aralığına göre filtrele
        if (!empty($filterData['start_date'])) {
            $sql .= " AND m.start_date >= :start_date";
            $params[':start_date'] = $filterData['start_date'];
        }

        if (!empty($filterData['end_date'])) {
            $sql .= " AND m.start_date <= :end_date";
            $params[':end_date'] = $filterData['end_date'];
        }

        // Arama terimini plaka, marka, model veya açıklamada ara
        if (!empty($filterData['search'])) {
            $searchTerm = '%' . $filterData['search'] . '%';
            $sql .= " AND (v.plate_number LIKE :search OR v.brand LIKE :search OR v.model LIKE :search OR m.description LIKE :search OR m.maintenance_type LIKE :search)";
            $params[':search'] = $searchTerm;
        }

        // Sıralama
        $sql .= " ORDER BY m.id DESC";

        // Sayfalama
        if (!empty($filterData['limit']) && isset($filterData['offset'])) {
            $sql .= " LIMIT :offset, :limit";
            $params[':limit'] = $filterData['limit'];
            $params[':offset'] = $filterData['offset'];
        }

        $this->db->query($sql);

        // Parametreleri bağla
        foreach ($params as $param => $value) {
            $this->db->bind($param, $value);
        }

        return $this->db->resultSet();
    }

    // Bakım tipleri listesini getir
    public function getMaintenanceTypes()
    {
        $this->db->query('
            SELECT DISTINCT maintenance_type 
            FROM maintenance_records 
            ORDER BY maintenance_type ASC
        ');

        return $this->db->resultSet();
    }

    // Tüm bakımları getir
    public function getMaintenances()
    {
        $this->db->query('
            SELECT m.*, v.plate_number, v.brand, v.model
            FROM maintenance_records m
            LEFT JOIN vehicles v ON m.vehicle_id = v.id
            ORDER BY m.id DESC
        ');

        return $this->db->resultSet();
    }
}
