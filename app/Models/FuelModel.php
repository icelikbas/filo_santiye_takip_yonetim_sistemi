<?php

namespace App\Models;

use App\Core\Database;
use \PDO;
use \PDOException;
use \Exception;
use \DateTime;

class FuelModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Get fuel records with pagination
     * 
     * @param int $page Current page number
     * @param int $limit Records per page
     * @return array Fuel records with pagination data
     */
    public function getFuelRecords($page = 1, $limit = 20)
    {
        try {
            error_log("FuelModel::getFuelRecords - Sorgu başlıyor - Sayfa: " . $page . ", Limit: " . $limit);
            
            // Toplam kayıt sayısını hesapla
            $this->db->query("SELECT COUNT(*) as total FROM fuel_records");
            $countResult = $this->db->single();
            $totalRecords = $countResult->total;
            
            // Offset hesapla
            $offset = ($page - 1) * $limit;
            
            // Kayıtları getir
            $this->db->query("SELECT f.*, v.plate_number, v.brand, v.model, 
                             CONCAT(d.name, ' ', IFNULL(d.surname, '')) as driver_name, 
                             t.name as tank_name, t.fuel_type as tank_fuel_type 
                             FROM fuel_records f 
                             LEFT JOIN vehicles v ON f.vehicle_id = v.id 
                             LEFT JOIN drivers d ON f.driver_id = d.id
                             LEFT JOIN fuel_tanks t ON f.tank_id = t.id
                             ORDER BY f.date DESC, f.id DESC
                             LIMIT :limit OFFSET :offset");
            
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
            $this->db->bind(':offset', $offset, PDO::PARAM_INT);
            
            $results = $this->db->resultSet();
            
            error_log("FuelModel::getFuelRecords - Sorgu tamamlandı. Kayıt sayısı: " . count($results));
            
            // Sayfalama verilerini içeren bir dizi döndür
            return [
                'records' => $results,
                'total_records' => $totalRecords,
                'current_page' => $page,
                'total_pages' => ceil($totalRecords / $limit),
                'limit' => $limit
            ];
        } catch (PDOException $e) {
            error_log('FuelModel::getFuelRecords - Hata: ' . $e->getMessage());
            return [
                'records' => [],
                'total_records' => 0,
                'current_page' => $page,
                'total_pages' => 0,
                'limit' => $limit
            ];
        }
    }

    /**
     * Get total count of fuel records
     * 
     * @return int Total count of fuel records
     */
    public function getFuelRecordsCount()
    {
        try {
            $this->db->query("SELECT COUNT(*) as total FROM fuel_records");
            $row = $this->db->single();
            return $row->total;
        } catch (PDOException $e) {
            error_log('FuelModel::getFuelRecordsCount - Hata: ' . $e->getMessage());
            return 0;
        }
    }

    // ID'ye göre yakıt kaydını al
    public function getFuelRecordById($id) {
        if (!$id || !is_numeric($id)) {
            error_log("FuelModel::getFuelRecordById - Geçersiz ID formatı: " . print_r($id, true));
            return false;
        }

        try {
            error_log("FuelModel::getFuelRecordById - Sorgu başlıyor, ID: " . $id);
            
            $sql = '
                SELECT f.*, 
                       v.plate_number, v.brand, v.model, 
                       d.name as driver_name, d.surname as driver_surname,
                       t.name as tank_name,
                       t.current_amount as tank_current_amount, 
                       t.capacity as tank_capacity, 
                       t.type as tank_type,
                       t.fuel_type as tank_fuel_type,
                       u.name as dispenser_name, u.surname as dispenser_surname,
                       creator.name as user_name, creator.surname as user_surname,
                       f.created_at
                FROM fuel_records f 
                LEFT JOIN vehicles v ON f.vehicle_id = v.id
                LEFT JOIN drivers d ON f.driver_id = d.id
                LEFT JOIN fuel_tanks t ON f.tank_id = t.id
                LEFT JOIN users u ON f.dispenser_id = u.id
                LEFT JOIN users creator ON f.created_by = creator.id
                WHERE f.id = :id
            ';
            
            error_log("FuelModel::getFuelRecordById - SQL hazırlandı");
            
            if (!$this->db->query($sql)) {
                error_log("FuelModel::getFuelRecordById - SQL sorgusu hazırlanırken hata oluştu");
                return false;
            }

            if (!$this->db->bind(':id', $id)) {
                error_log("FuelModel::getFuelRecordById - Parametre bağlanırken hata oluştu");
                return false;
            }
            
            $result = $this->db->single();
            
            if ($result) {
                error_log("FuelModel::getFuelRecordById - Kayıt başarıyla bulundu. ID: " . $id);
                error_log("FuelModel::getFuelRecordById - Veri detayları: " . json_encode($result, JSON_UNESCAPED_UNICODE));
            } else {
                error_log("FuelModel::getFuelRecordById - ID için kayıt bulunamadı: " . $id);
            }
            
            return $result;

        } catch (PDOException $e) {
            error_log("FuelModel::getFuelRecordById - PDO Hatası: " . $e->getMessage());
            error_log("FuelModel::getFuelRecordById - Hata Kodu: " . $e->getCode());
            return false;
        } catch (Exception $e) {
            error_log("FuelModel::getFuelRecordById - Genel Hata: " . $e->getMessage());
            error_log("FuelModel::getFuelRecordById - Hata Detayı: " . $e->getTraceAsString());
            return false;
        }
    }

    // Araca göre yakıt kayıtlarını al
    public function getFuelRecordsByVehicle($vehicleId) {
        $this->db->query('
            SELECT f.*, v.plate_number, v.brand, v.model, CONCAT(d.name, " ", IFNULL(d.surname, "")) as driver_name, t.name as tank_name,
                   t.current_amount as tank_current_amount, t.capacity as tank_capacity, t.type as tank_type,
                   t.fuel_type as tank_fuel_type, CONCAT(u.name, " ", IFNULL(u.surname, "")) as dispenser_name,
                   CONCAT(creator.name, " ", IFNULL(creator.surname, "")) as user_name
            FROM fuel_records f
            JOIN vehicles v ON f.vehicle_id = v.id
            LEFT JOIN drivers d ON f.driver_id = d.id
            JOIN fuel_tanks t ON f.tank_id = t.id
            LEFT JOIN users u ON f.dispenser_id = u.id
            LEFT JOIN users creator ON f.created_by = creator.id
            WHERE f.vehicle_id = :vehicle_id
            ORDER BY f.date DESC
        ');
        
        $this->db->bind(':vehicle_id', $vehicleId);
        
        return $this->db->resultSet();
    }

    // Sürücüye göre yakıt kayıtlarını al
    public function getFuelRecordsByDriver($driverId) {
        $this->db->query('
            SELECT f.*, v.plate_number, v.brand, v.model, CONCAT(d.name, " ", IFNULL(d.surname, "")) as driver_name, t.name as tank_name,
                   t.current_amount as tank_current_amount, t.capacity as tank_capacity, t.type as tank_type,
                   t.fuel_type as tank_fuel_type, CONCAT(u.name, " ", IFNULL(u.surname, "")) as dispenser_name,
                   CONCAT(creator.name, " ", IFNULL(creator.surname, "")) as user_name
            FROM fuel_records f
            JOIN vehicles v ON f.vehicle_id = v.id
            LEFT JOIN drivers d ON f.driver_id = d.id
            JOIN fuel_tanks t ON f.tank_id = t.id
            LEFT JOIN users u ON f.dispenser_id = u.id
            LEFT JOIN users creator ON f.created_by = creator.id
            WHERE f.driver_id = :driver_id
            ORDER BY f.date DESC
        ');
        
        $this->db->bind(':driver_id', $driverId);
        
        return $this->db->resultSet();
    }

    // Tanka göre yakıt kayıtlarını al
    public function getFuelRecordsByTank($tankId) {
        $this->db->query('
            SELECT f.*, v.plate_number, v.brand, v.model, CONCAT(d.name, " ", IFNULL(d.surname, "")) as driver_name, t.name as tank_name,
                   t.current_amount as tank_current_amount, t.capacity as tank_capacity, t.type as tank_type,
                   t.fuel_type as tank_fuel_type, CONCAT(u.name, " ", IFNULL(u.surname, "")) as dispenser_name,
                   CONCAT(creator.name, " ", IFNULL(creator.surname, "")) as user_name
            FROM fuel_records f
            JOIN vehicles v ON f.vehicle_id = v.id
            LEFT JOIN drivers d ON f.driver_id = d.id
            JOIN fuel_tanks t ON f.tank_id = t.id
            LEFT JOIN users u ON f.dispenser_id = u.id
            LEFT JOIN users creator ON f.created_by = creator.id
            WHERE f.tank_id = :tank_id
            ORDER BY f.date DESC
        ');
        
        $this->db->bind(':tank_id', $tankId);
        
        return $this->db->resultSet();
    }

    // Tarihe göre yakıt kayıtlarını al (belirli tarih aralığında)
    public function getFuelRecordsByDateRange($startDate, $endDate) {
        $this->db->query('
            SELECT f.*, v.plate_number, v.brand, v.model, CONCAT(d.name, " ", IFNULL(d.surname, "")) as driver_name, t.name as tank_name,
                   t.current_amount as tank_current_amount, t.capacity as tank_capacity, t.type as tank_type,
                   t.fuel_type as tank_fuel_type, CONCAT(u.name, " ", IFNULL(u.surname, "")) as dispenser_name,
                   CONCAT(creator.name, " ", IFNULL(creator.surname, "")) as user_name
            FROM fuel_records f
            JOIN vehicles v ON f.vehicle_id = v.id
            LEFT JOIN drivers d ON f.driver_id = d.id
            JOIN fuel_tanks t ON f.tank_id = t.id
            LEFT JOIN users u ON f.dispenser_id = u.id
            LEFT JOIN users creator ON f.created_by = creator.id
            WHERE f.date BETWEEN :start_date AND :end_date
            ORDER BY f.date DESC
        ');
        
        $this->db->bind(':start_date', $startDate);
        $this->db->bind(':end_date', $endDate);
        
        return $this->db->resultSet();
    }

    // Yakıt kaydı ekle
    public function addFuelRecord($data) {
        try {
            // Transaction başlat
            $this->db->beginTransaction();
            
            // Önce tank miktarını kontrol et
            $tankModel = new FuelTank();
            $tank = $tankModel->getTankById($data['tank_id']);
            
            // Tank yeterli miktarda yakıt içeriyor mu?
            if(!$tank) {
                $this->db->rollback();
                error_log('FuelModel::addFuelRecord - Tank bulunamadı. Tank ID: ' . $data['tank_id']);
                return false;
            }
            
            if($tank->current_amount < $data['amount']) {
                $this->db->rollback();
                error_log('FuelModel::addFuelRecord - Tank miktarı yetersiz. Tank ID: ' . $data['tank_id'] . 
                         ', Tank adı: ' . $tank->name . 
                         ', İstenen miktar: ' . $data['amount'] . 
                         ', Mevcut miktar: ' . $tank->current_amount);
                return false;
            }
            
            // Tarih formatını düzelt
            $date = $data['date'];
            
            // Saati kontrol et ve ekle (varsa)
            if(isset($data['time'])) {
                $date .= ' ' . $data['time'];
            }
            
            // SQL sorgusu
            $this->db->query('INSERT INTO fuel_records (vehicle_id, driver_id, tank_id, dispenser_id, fuel_type, amount, km_reading, hour_reading, date, notes, created_at, created_by) 
                            VALUES(:vehicle_id, :driver_id, :tank_id, :dispenser_id, :fuel_type, :amount, :km_reading, :hour_reading, :date, :notes, NOW(), :created_by)');
            
            // Bind values
            $this->db->bind(':vehicle_id', $data['vehicle_id']);
            $this->db->bind(':driver_id', !empty($data['driver_id']) ? $data['driver_id'] : null);
            $this->db->bind(':tank_id', $data['tank_id']);
            $this->db->bind(':dispenser_id', !empty($data['dispenser_id']) ? $data['dispenser_id'] : null);
            $this->db->bind(':fuel_type', $data['fuel_type']);
            $this->db->bind(':amount', $data['amount']);
            $this->db->bind(':km_reading', $data['km_reading']);
            $this->db->bind(':hour_reading', $data['hour_reading']);
            $this->db->bind(':date', $date);
            $this->db->bind(':notes', $data['notes']);
            $this->db->bind(':created_by', $_SESSION['user_id']);
            
            // Sorguyu çalıştır
            $result = $this->db->execute();
            
            if ($result) {
                // Tank kayıdını güncelle
                $tankResult = $tankModel->updateTankAmount($data['tank_id'], $data['amount'], false);
                
                if ($tankResult) {
                    // Aracın mevcut kilometresini güncelle
                    if (isset($data['km_reading']) && !empty($data['km_reading'])) {
                        // Direk SQL sorgusu ile aracın km bilgisini güncelle
                        $this->db->query('UPDATE vehicles SET current_km = :km_reading WHERE id = :vehicle_id');
                        $this->db->bind(':vehicle_id', $data['vehicle_id']);
                        $this->db->bind(':km_reading', $data['km_reading']);
                        $this->db->execute();
                    }
                    
                    // Aracın mevcut çalışma saatini güncelle
                    if (isset($data['hour_reading']) && !empty($data['hour_reading'])) {
                        // Direk SQL sorgusu ile aracın saat bilgisini güncelle
                        $this->db->query('UPDATE vehicles SET current_hours = :hour_reading WHERE id = :vehicle_id');
                        $this->db->bind(':vehicle_id', $data['vehicle_id']);
                        $this->db->bind(':hour_reading', $data['hour_reading']);
                        $this->db->execute();
                    }
                    
                    // Transaction başarılı, değişiklikleri kaydet
                    $this->db->commit();
                    return true;
                }
            }
            
            // Herhangi bir sorun olursa rollback yap
            $this->db->rollback();
            return false;
            
        } catch (Exception $e) {
            // Hata durumunda rollback yap ve false döndür
            $this->db->rollback();
            error_log('FuelModel::addFuelRecord - Hata: ' . $e->getMessage());
            return false;
        }
    }

    // Yakıt kaydını güncelle
    public function updateFuelRecord($data) {
        try {
            // Geliştirme için tüm gelen veriyi logla
            error_log('FuelModel::updateFuelRecord - DEBUG - Gelen veri: ' . print_r($data, true));
            
            // Gerekli alanları kontrol et
            if (empty($data['id']) || !is_numeric($data['id'])) {
                error_log('FuelModel::updateFuelRecord - Geçersiz ID: ' . print_r($data['id'], true));
                return false;
            }
            if (empty($data['vehicle_id']) || !is_numeric($data['vehicle_id'])) {
                error_log('FuelModel::updateFuelRecord - Geçersiz araç ID: ' . print_r($data['vehicle_id'], true));
                return false;
            }
            if (empty($data['amount']) || !is_numeric($data['amount']) || $data['amount'] <= 0) {
                error_log('FuelModel::updateFuelRecord - Geçersiz miktar: ' . print_r($data['amount'], true));
                return false;
            }
            if (empty($data['date']) || !strtotime($data['date'])) {
                error_log('FuelModel::updateFuelRecord - Geçersiz tarih: ' . print_r($data['date'], true));
                return false;
            }
            
            // Tarih formatını kontrol et ve düzelt
            try {
                // Gelen tarihi bir DateTime nesnesine dönüştür
                error_log('FuelModel::updateFuelRecord - DEBUG - Tarih dönüştürme öncesi: ' . $data['date']);
                $dateObj = new DateTime($data['date']);
                // Veritabanı formatına dönüştür (YYYY-MM-DD)
                $data['date'] = $dateObj->format('Y-m-d');
                error_log('FuelModel::updateFuelRecord - DEBUG - Tarih dönüştürme sonrası: ' . $data['date']);
            } catch (Exception $e) {
                error_log('FuelModel::updateFuelRecord - Tarih formatı düzeltme hatası: ' . $e->getMessage());
                return false;
            }
            
            error_log('FuelModel::updateFuelRecord - Güncelleme başlıyor. Kayıt ID: ' . $data['id'] . ', Yeni miktar: ' . $data['amount']);
            
            // Önceki kayıt bilgilerini al
            $oldRecord = $this->getFuelRecordById($data['id']);
            
            if (!$oldRecord) {
                error_log('FuelModel::updateFuelRecord - Kayıt bulunamadı. Kayıt ID: ' . $data['id']);
                return false;
            }
            
            // Eski kaydı logla
            error_log('FuelModel::updateFuelRecord - DEBUG - Eski kayıt: ' . print_r($oldRecord, true));
            
            // Tank değişikliğine izin verme - data'daki tank_id'yi eski kayıttaki ile değiştir
            $data['tank_id'] = $oldRecord->tank_id;
            
            error_log('FuelModel::updateFuelRecord - Eski kayıt bilgileri: Tank ID: ' . $oldRecord->tank_id . ', Miktar: ' . $oldRecord->amount);
            
            // Transaction başlat
            $this->db->beginTransaction();
            error_log('FuelModel::updateFuelRecord - DEBUG - Transaction başlatıldı');
            
            // Tank kontrolü
            $tankModel = new FuelTank();
            $tank = $tankModel->getTankById($oldRecord->tank_id);
            
            if (!$tank) {
                $this->db->rollback();
                error_log('FuelModel::updateFuelRecord - Tank bulunamadı. Tank ID: ' . $oldRecord->tank_id);
                return false;
            }
            
            // Tank bilgilerini logla
            error_log('FuelModel::updateFuelRecord - DEBUG - Tank bilgileri: ' . print_r($tank, true));

            // Miktar değişikliği kontrolü
            if (floatval($data['amount']) != floatval($oldRecord->amount)) {
                error_log('FuelModel::updateFuelRecord - Miktar değişikliği tespit edildi. ' . 
                         'Eski miktar: ' . $oldRecord->amount . ', Yeni miktar: ' . $data['amount']);

                // Fuel records tablosunu önce güncelle (tank güncellemelerinden önce)
                $sql = 'UPDATE fuel_records 
                        SET vehicle_id = :vehicle_id, 
                            driver_id = :driver_id, 
                            tank_id = :tank_id,
                            dispenser_id = :dispenser_id,
                            fuel_type = :fuel_type, 
                            amount = :amount, 
                            km_reading = :km_reading, 
                            hour_reading = :hour_reading,
                            date = :date, 
                            notes = :notes
                        WHERE id = :id';
                
                if (!$this->db->query($sql)) {
                    $this->db->rollback();
                    error_log('FuelModel::updateFuelRecord - SQL sorgusu hazırlanamadı.');
                    return false;
                }
                error_log('FuelModel::updateFuelRecord - DEBUG - SQL query hazır: ' . $sql);
                
                $this->db->bind(':id', $data['id']);
                $this->db->bind(':vehicle_id', $data['vehicle_id']);
                $this->db->bind(':driver_id', !empty($data['driver_id']) ? $data['driver_id'] : null);
                $this->db->bind(':tank_id', $oldRecord->tank_id); // Orijinal tank ID'sini kullan
                $this->db->bind(':dispenser_id', !empty($data['dispenser_id']) ? $data['dispenser_id'] : null);
                $this->db->bind(':fuel_type', $data['fuel_type']);
                $this->db->bind(':amount', $data['amount']);
                $this->db->bind(':km_reading', !empty($data['km_reading']) ? $data['km_reading'] : null);
                $this->db->bind(':hour_reading', !empty($data['hour_reading']) ? $data['hour_reading'] : null);
                $this->db->bind(':date', $data['date']);
                $this->db->bind(':notes', !empty($data['notes']) ? $data['notes'] : null);
                error_log('FuelModel::updateFuelRecord - DEBUG - Parametreler bağlandı');
                
                if (!$this->db->execute()) {
                    $this->db->rollback();
                    error_log('FuelModel::updateFuelRecord - Kayıt güncellenemedi.');
                    return false;
                }
                error_log('FuelModel::updateFuelRecord - DEBUG - SQL execute edildi');
                
                // Güncellenen kayıt sayısını kontrol et
                if ($this->db->rowCount() == 0) {
                    $this->db->rollback();
                    error_log('FuelModel::updateFuelRecord - Kayıt güncellendi ancak hiçbir satır etkilenmedi. ID: ' . $data['id']);
                    return false;
                }
                
                error_log('FuelModel::updateFuelRecord - Kayıt başarıyla güncellendi. Şimdi tank miktarları güncelleniyor...');
                
                // Miktar farkını hesapla (pozitif: çıkarılacak, negatif: eklenecek)
                $amountDifference = (float)$data['amount'] - (float)$oldRecord->amount;
                error_log('FuelModel::updateFuelRecord - DEBUG - Miktar farkı: ' . $amountDifference);
                
                // Tank miktarını manuel olarak güncelle
                if ($amountDifference > 0) {
                    // Yeni miktar daha fazla, tanktan daha fazla yakıt çek
                    // Önce tankta yeterli yakıt olup olmadığını kontrol et
                    if ($tank->current_amount < $amountDifference) {
                        $this->db->rollback();
                        error_log('FuelModel::updateFuelRecord - Tank miktarı yetersiz. Tank ID: ' . $oldRecord->tank_id . 
                                ', Mevcut miktar: ' . $tank->current_amount . 
                                ', Gerekli ek miktar: ' . $amountDifference);
                        return false;
                    }
                    
                    $updateTankSql = 'UPDATE fuel_tanks SET current_amount = current_amount - :amount WHERE id = :tank_id';
                    error_log('FuelModel::updateFuelRecord - DEBUG - Tank güncelleme SQL: ' . $updateTankSql . 
                              ' (Amount: ' . abs($amountDifference) . ', Tank ID: ' . $oldRecord->tank_id . ')');
                    
                    $this->db->query($updateTankSql);
                    $this->db->bind(':amount', abs($amountDifference));
                    $this->db->bind(':tank_id', $oldRecord->tank_id);
                    
                    error_log('FuelModel::updateFuelRecord - Tanktan ek yakıt çekiliyor. Tank ID: ' . $oldRecord->tank_id . 
                             ', Miktar: ' . abs($amountDifference));
                } else {
                    // Yeni miktar daha az, tanka yakıt geri ekle
                    $updateTankSql = 'UPDATE fuel_tanks SET current_amount = current_amount + :amount WHERE id = :tank_id';
                    error_log('FuelModel::updateFuelRecord - DEBUG - Tank güncelleme SQL: ' . $updateTankSql . 
                              ' (Amount: ' . abs($amountDifference) . ', Tank ID: ' . $oldRecord->tank_id . ')');
                    
                    $this->db->query($updateTankSql);
                    $this->db->bind(':amount', abs($amountDifference));
                    $this->db->bind(':tank_id', $oldRecord->tank_id);
                    
                    error_log('FuelModel::updateFuelRecord - Tanka yakıt ekleniyor. Tank ID: ' . $oldRecord->tank_id . 
                             ', Miktar: ' . abs($amountDifference));
                }
                
                $tankUpdateResult = $this->db->execute();
                if (!$tankUpdateResult) {
                    $this->db->rollback();
                    error_log('FuelModel::updateFuelRecord - Tank miktarı güncellenemedi.');
                    return false;
                } else {
                    error_log('FuelModel::updateFuelRecord - DEBUG - Tank güncelleme başarılı');
                }
            } else {
                // Miktar değişikliği yok, sadece kayıt güncelle
                $sql = 'UPDATE fuel_records 
                        SET vehicle_id = :vehicle_id, 
                            driver_id = :driver_id, 
                            tank_id = :tank_id,
                            dispenser_id = :dispenser_id,
                            fuel_type = :fuel_type, 
                            amount = :amount, 
                            km_reading = :km_reading, 
                            hour_reading = :hour_reading,
                            date = :date, 
                            notes = :notes
                        WHERE id = :id';
                
                if (!$this->db->query($sql)) {
                    $this->db->rollback();
                    error_log('FuelModel::updateFuelRecord - SQL sorgusu hazırlanamadı.');
                    return false;
                }
                error_log('FuelModel::updateFuelRecord - DEBUG - SQL query hazır (miktar değişikliği yok): ' . $sql);
                
                $this->db->bind(':id', $data['id']);
                $this->db->bind(':vehicle_id', $data['vehicle_id']);
                $this->db->bind(':driver_id', !empty($data['driver_id']) ? $data['driver_id'] : null);
                $this->db->bind(':tank_id', $oldRecord->tank_id); // Orijinal tank ID'sini kullan
                $this->db->bind(':dispenser_id', !empty($data['dispenser_id']) ? $data['dispenser_id'] : null);
                $this->db->bind(':fuel_type', $data['fuel_type']);
                $this->db->bind(':amount', $data['amount']);
                $this->db->bind(':km_reading', !empty($data['km_reading']) ? $data['km_reading'] : null);
                $this->db->bind(':hour_reading', !empty($data['hour_reading']) ? $data['hour_reading'] : null);
                $this->db->bind(':date', $data['date']);
                $this->db->bind(':notes', !empty($data['notes']) ? $data['notes'] : null);
                error_log('FuelModel::updateFuelRecord - DEBUG - Parametreler bağlandı (miktar değişikliği yok)');
                
                $recordUpdateResult = $this->db->execute();
                if (!$recordUpdateResult) {
                    $this->db->rollback();
                    error_log('FuelModel::updateFuelRecord - Kayıt güncellenemedi (miktar değişikliği yok).');
                    return false;
                } else {
                    error_log('FuelModel::updateFuelRecord - DEBUG - Kayıt güncelleme başarılı (miktar değişikliği yok)');
                }
            }
            
            // Aracın mevcut kilometresini güncelle
            if (isset($data['km_reading']) && !empty($data['km_reading'])) {
                $kmSql = 'UPDATE vehicles SET current_km = :km_reading WHERE id = :vehicle_id';
                $this->db->query($kmSql);
                $this->db->bind(':vehicle_id', $data['vehicle_id']);
                $this->db->bind(':km_reading', $data['km_reading']);
                
                error_log('FuelModel::updateFuelRecord - DEBUG - Araç KM güncelleme SQL: ' . $kmSql . 
                         ' (Vehicle ID: ' . $data['vehicle_id'] . ', KM: ' . $data['km_reading'] . ')');
                
                $kmResult = $this->db->execute();
                if (!$kmResult) {
                    error_log('FuelModel::updateFuelRecord - UYARI - Araç kilometresi güncellenemedi. İşlem devam edecek.');
                } else {
                    error_log('FuelModel::updateFuelRecord - DEBUG - Araç kilometresi başarıyla güncellendi.');
                }
            }
            
            // Aracın mevcut çalışma saatini güncelle
            if (isset($data['hour_reading']) && !empty($data['hour_reading'])) {
                $hourSql = 'UPDATE vehicles SET current_hours = :hour_reading WHERE id = :vehicle_id';
                $this->db->query($hourSql);
                $this->db->bind(':vehicle_id', $data['vehicle_id']);
                $this->db->bind(':hour_reading', $data['hour_reading']);
                
                error_log('FuelModel::updateFuelRecord - DEBUG - Araç saat güncelleme SQL: ' . $hourSql . 
                         ' (Vehicle ID: ' . $data['vehicle_id'] . ', Hour: ' . $data['hour_reading'] . ')');
                
                $hourResult = $this->db->execute();
                if (!$hourResult) {
                    error_log('FuelModel::updateFuelRecord - UYARI - Araç çalışma saati güncellenemedi. İşlem devam edecek.');
                } else {
                    error_log('FuelModel::updateFuelRecord - DEBUG - Araç çalışma saati başarıyla güncellendi.');
                }
            }
            
            error_log('FuelModel::updateFuelRecord - Tüm işlemler başarılı, commit yapılıyor...');
            
            // Transaction başarılı
            $this->db->commit();
            error_log('FuelModel::updateFuelRecord - Transaction başarıyla tamamlandı. Kayıt ID: ' . $data['id']);
            return true;
            
        } catch (Exception $e) {
            $this->db->rollback();
            error_log('FuelModel::updateFuelRecord - Hata: ' . $e->getMessage());
            error_log('FuelModel::updateFuelRecord - Hata detayı: ' . $e->getTraceAsString());
            return false;
        }
    }

    // Yakıt kaydını sil
    public function deleteFuelRecord($id) {
        // Silinecek kayıt bilgilerini al
        $record = $this->getFuelRecordById($id);
        
        if (!$record) {
            return false;
        }
        
        // Transaction başlat
        $this->db->beginTransaction();
        
        try {
            $this->db->query('DELETE FROM fuel_records WHERE id = :id');
            $this->db->bind(':id', $id);
            $deleteResult = $this->db->execute();
            
            if($deleteResult) {
                // Tanka yakıtı geri ekle
                $tankModel = new FuelTank();
                $tankUpdateResult = $tankModel->updateTankAmount($record->tank_id, $record->amount, true);
                
                if ($tankUpdateResult) {
                    // Transaction başarılı
                    $this->db->commit();
                    return true;
                }
            }
            
            // Herhangi bir sorun olursa rollback yap
            $this->db->rollback();
            return false;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    // Araç yakıt tüketim özeti
    public function getVehicleFuelConsumption($vehicleId) {
        $this->db->query('
            SELECT 
                SUM(amount) as total_fuel,
                MIN(date) as first_record,
                MAX(date) as last_record,
                COUNT(*) as record_count
            FROM fuel_records
            WHERE vehicle_id = :vehicle_id
        ');
        
        $this->db->bind(':vehicle_id', $vehicleId);
        
        return $this->db->single();
    }

    // Toplam yakıt istatistikleri
    public function getTotalFuelStats() {
        $this->db->query('
            SELECT 
                COUNT(*) as total_records,
                SUM(amount) as total_amount,
                AVG(amount) as avg_amount_per_record
            FROM fuel_records
        ');
        
        return $this->db->single();
    }

    // Belirli tarihler arasındaki yakıt istatistikleri
    public function getFuelStatsByDateRange($startDate, $endDate) {
        $this->db->query('
            SELECT 
                COUNT(*) as total_records,
                SUM(amount) as total_amount,
                AVG(amount) as avg_amount
            FROM fuel_records
            WHERE date BETWEEN :start_date AND :end_date
        ');
        
        $this->db->bind(':start_date', $startDate);
        $this->db->bind(':end_date', $endDate);
        
        return $this->db->single();
    }

    // Yakıt tiplerine göre istatistikler
    public function getFuelStatsByType() {
        $this->db->query('
            SELECT 
                fuel_type,
                COUNT(*) as record_count,
                SUM(amount) as total_amount,
                AVG(amount) as avg_amount
            FROM fuel_records
            GROUP BY fuel_type
            ORDER BY total_amount DESC
        ');
        
        return $this->db->resultSet();
    }

    // Toplam yakıt kaydı sayısı
    public function getFuelRecordCount() {
        $this->db->query('SELECT COUNT(*) as count FROM fuel_records');
        $row = $this->db->single();
        return $row->count;
    }

    // Araçları listeleme için (aktif araçlar)
    public function getActiveVehiclesForSelect($includeVehicleId = null) {
        if ($includeVehicleId) {
            // Eğer belirli bir araç ID'si dahil edilecekse, bu aracı durumundan bağımsız olarak ekle
            $this->db->query('
                SELECT id, CONCAT(brand, " ", model, " (", plate_number, ")") as vehicle_name
                FROM vehicles
                WHERE status = "Aktif" OR id = :vehicle_id
                ORDER BY brand, model
            ');
            $this->db->bind(':vehicle_id', $includeVehicleId);
        } else {
            // Sadece aktif araçları getir
            $this->db->query('
                SELECT id, CONCAT(brand, " ", model, " (", plate_number, ")") as vehicle_name
                FROM vehicles
                WHERE status = "Aktif"
                ORDER BY brand, model
            ');
        }
        
        return $this->db->resultSet();
    }

    // Seçim kutuları için aktif sürücüler
    public function getActiveDriversForSelect() {
        $this->db->query('
            SELECT id, CONCAT(name, " ", surname) as full_name
            FROM drivers
            WHERE status = "Aktif"
            ORDER BY name, surname
        ');
        
        return $this->db->resultSet();
    }

    // Son yakıt kaydını getirir
    public function getLastFuelRecordByVehicle($vehicleId) {
        $this->db->query('
            SELECT id, vehicle_id, driver_id, tank_id, fuel_type, amount, km_reading, hour_reading, date, notes
            FROM fuel_records 
            WHERE vehicle_id = :vehicle_id
            ORDER BY date DESC 
            LIMIT 1
        ');
        
        $this->db->bind(':vehicle_id', $vehicleId);
        
        return $this->db->single();
    }

    // Aylık yakıt tüketimini getirir (son x ay için)
    public function getMonthlyFuelConsumption($months = 6) {
        $this->db->query("
            SELECT 
                DATE_FORMAT(date, '%Y-%m') as month,
                DATE_FORMAT(date, '%M %Y') as month_name,
                SUM(amount) as total_amount,
                COUNT(*) as record_count
            FROM fuel_records
            WHERE date >= DATE_SUB(CURRENT_DATE(), INTERVAL :months MONTH)
            GROUP BY DATE_FORMAT(date, '%Y-%m'), DATE_FORMAT(date, '%M %Y')
            ORDER BY DATE_FORMAT(date, '%Y-%m')
        ");
        
        $this->db->bind(':months', $months);
        
        return $this->db->resultSet();
    }

    // Haftalık yakıt tüketimini getirir (son x hafta için)
    public function getWeeklyFuelConsumption($weeks = 6) {
        $this->db->query("
            SELECT 
                YEARWEEK(date, 1) as week_number,
                CONCAT('Hafta ', WEEK(date, 1), ' (', DATE_FORMAT(date, '%Y'), ')') as week_name, 
                MIN(DATE(date)) as week_start,
                MAX(DATE(date)) as week_end,
                SUM(amount) as total_amount,
                COUNT(*) as record_count
            FROM fuel_records
            WHERE date >= DATE_SUB(CURRENT_DATE(), INTERVAL :weeks WEEK)
            GROUP BY YEARWEEK(date, 1), CONCAT('Hafta ', WEEK(date, 1), ' (', DATE_FORMAT(date, '%Y'), ')')
            ORDER BY YEARWEEK(date, 1)
        ");
        
        $this->db->bind(':weeks', $weeks);
        
        return $this->db->resultSet();
    }

    // Yakıt türüne göre tüketimi getirir (son x ay için)
    public function getFuelConsumptionByType($months = 6) {
        $this->db->query("
            SELECT 
                fuel_type,
                SUM(amount) as total_amount
            FROM fuel_records
            WHERE date >= DATE_SUB(CURRENT_DATE(), INTERVAL :months MONTH)
            GROUP BY fuel_type
            ORDER BY total_amount DESC
        ");
        
        $this->db->bind(':months', $months);
        
        return $this->db->resultSet();
    }

    // Yakıt türüne göre kayıtları getir
    public function getFuelRecordsByType($fuelType) {
        $this->db->query('
            SELECT f.*, v.plate_number, v.brand, v.model, CONCAT(d.name, " ", IFNULL(d.surname, "")) as driver_name, t.name as tank_name,
                   t.current_amount as tank_current_amount, t.capacity as tank_capacity, t.type as tank_type,
                   t.fuel_type as tank_fuel_type, CONCAT(u.name, " ", IFNULL(u.surname, "")) as dispenser_name,
                   CONCAT(creator.name, " ", IFNULL(creator.surname, "")) as user_name
            FROM fuel_records f
            JOIN vehicles v ON f.vehicle_id = v.id
            LEFT JOIN drivers d ON f.driver_id = d.id
            JOIN fuel_tanks t ON f.tank_id = t.id
            LEFT JOIN users u ON f.dispenser_id = u.id
            LEFT JOIN users creator ON f.created_by = creator.id
            WHERE f.fuel_type = :fuel_type
            ORDER BY f.date DESC
        ');
        
        $this->db->bind(':fuel_type', $fuelType);
        
        return $this->db->resultSet();
    }

    // Tarih aralığı ve yakıt türüne göre kayıtları getir
    public function getFuelRecordsByDateRangeAndType($startDate, $endDate, $fuelType) {
        $this->db->query('
            SELECT f.*, v.plate_number, v.brand, v.model, CONCAT(d.name, " ", IFNULL(d.surname, "")) as driver_name, t.name as tank_name,
                   t.current_amount as tank_current_amount, t.capacity as tank_capacity, t.type as tank_type,
                   t.fuel_type as tank_fuel_type, CONCAT(u.name, " ", IFNULL(u.surname, "")) as dispenser_name
            FROM fuel_records f
            JOIN vehicles v ON f.vehicle_id = v.id
            LEFT JOIN drivers d ON f.driver_id = d.id
            JOIN fuel_tanks t ON f.tank_id = t.id
            LEFT JOIN users u ON f.dispenser_id = u.id
            WHERE f.date BETWEEN :start_date AND :end_date
            AND f.fuel_type = :fuel_type
            ORDER BY f.date DESC
        ');
        
        $this->db->bind(':start_date', $startDate);
        $this->db->bind(':end_date', $endDate);
        $this->db->bind(':fuel_type', $fuelType);
        
        return $this->db->resultSet();
    }

    // Filtrelere göre yakıt kayıtlarını getir
    public function getFilteredFuelRecords($filters, $page = 1, $limit = 20) {
        try {
            // SQL sorgusu - önce toplam kayıt sayısını hesapla
            $countSql = '
                SELECT COUNT(*) as total 
                FROM fuel_records f
                JOIN vehicles v ON f.vehicle_id = v.id
                LEFT JOIN drivers d ON f.driver_id = d.id
                JOIN fuel_tanks t ON f.tank_id = t.id
                LEFT JOIN users u ON f.dispenser_id = u.id
                WHERE 1=1';
            
            // Veri sorgusu
            $dataSql = '
                SELECT f.*, v.plate_number, v.brand, v.model, 
                       d.name, d.surname,
                       CONCAT(IFNULL(d.name, ""), " ", IFNULL(d.surname, "")) as driver_name, 
                       t.name as tank_name,
                       t.current_amount as tank_current_amount, 
                       t.capacity as tank_capacity, 
                       t.type as tank_type,
                       t.fuel_type as tank_fuel_type,
                       CONCAT(IFNULL(u.name, ""), " ", IFNULL(u.surname, "")) as dispenser_name
                FROM fuel_records f
                JOIN vehicles v ON f.vehicle_id = v.id
                LEFT JOIN drivers d ON f.driver_id = d.id
                JOIN fuel_tanks t ON f.tank_id = t.id
                LEFT JOIN users u ON f.dispenser_id = u.id
                WHERE 1=1';
            
            // Parametreleri bind etmek için dizi
            $params = [];
            
            // Filtreleri SQL sorgusuna ekle
            if (!empty($filters['vehicle_id'])) {
                $countSql .= ' AND f.vehicle_id = :vehicle_id';
                $dataSql .= ' AND f.vehicle_id = :vehicle_id';
                $params[':vehicle_id'] = $filters['vehicle_id'];
            }
            
            if (!empty($filters['driver_id'])) {
                $countSql .= ' AND f.driver_id = :driver_id';
                $dataSql .= ' AND f.driver_id = :driver_id';
                $params[':driver_id'] = $filters['driver_id'];
            }
            
            if (!empty($filters['dispenser_id'])) {
                $countSql .= ' AND f.dispenser_id = :dispenser_id';
                $dataSql .= ' AND f.dispenser_id = :dispenser_id';
                $params[':dispenser_id'] = $filters['dispenser_id'];
            }
            
            if (!empty($filters['tank_id'])) {
                $countSql .= ' AND f.tank_id = :tank_id';
                $dataSql .= ' AND f.tank_id = :tank_id';
                $params[':tank_id'] = $filters['tank_id'];
            }
            
            if (!empty($filters['fuel_type'])) {
                $countSql .= ' AND f.fuel_type = :fuel_type';
                $dataSql .= ' AND f.fuel_type = :fuel_type';
                $params[':fuel_type'] = $filters['fuel_type'];
            }
            
            if (!empty($filters['start_date'])) {
                $countSql .= ' AND f.date >= :start_date';
                $dataSql .= ' AND f.date >= :start_date';
                $params[':start_date'] = $filters['start_date'];
            }
            
            if (!empty($filters['end_date'])) {
                $countSql .= ' AND f.date <= :end_date';
                $dataSql .= ' AND f.date <= :end_date';
                $params[':end_date'] = $filters['end_date'];
            }
            
            // Önce toplam kayıt sayısını al
            $this->db->query($countSql);
            
            // Parametreleri bind et
            foreach ($params as $param => $value) {
                $this->db->bind($param, $value);
            }
            
            $countResult = $this->db->single();
            $totalRecords = $countResult->total;
            
            // Offset hesapla
            $offset = ($page - 1) * $limit;
            
            // Sıralama ve limitleme ekle
            $dataSql .= ' ORDER BY f.date DESC, f.id DESC LIMIT :limit OFFSET :offset';
            
            // Sorguyu hazırla
            $this->db->query($dataSql);
            
            // Parametreleri bind et
            foreach ($params as $param => $value) {
                $this->db->bind($param, $value);
            }
            
            // Limit ve offset için parametreleri bind et
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
            $this->db->bind(':offset', $offset, PDO::PARAM_INT);
            
            // Sorguyu çalıştır ve sonuçları döndür
            $records = $this->db->resultSet();
            
            return [
                'records' => $records,
                'total_records' => $totalRecords,
                'current_page' => $page,
                'total_pages' => ceil($totalRecords / $limit),
                'limit' => $limit
            ];
            
        } catch (Exception $e) {
            error_log('FuelModel::getFilteredFuelRecords - Hata: ' . $e->getMessage());
            return [
                'records' => [],
                'total_records' => 0,
                'current_page' => $page,
                'total_pages' => 0,
                'limit' => $limit
            ];
        }
    }

    // Yakıt türlerini getir
    public function getFuelTypes() {
        return ['Dizel', 'Benzin']; // Desteklenen yakıt türleri
    }

    // Araçların yakıt tüketimi
    public function getVehicleFuelConsumptionSummary() {
        $this->db->query('
            SELECT 
                v.id as vehicle_id,
                v.plate_number,
                v.brand,
                v.model,
                COUNT(f.id) as record_count,
                SUM(f.amount) as total_amount,
                SUM(CASE WHEN MONTH(f.date) = MONTH(CURRENT_DATE()) AND YEAR(f.date) = YEAR(CURRENT_DATE()) THEN f.amount ELSE 0 END) as current_month_amount,
                AVG(f.amount) as avg_amount,
                MAX(f.date) as last_refuel_date
            FROM 
                vehicles v
            LEFT JOIN 
                fuel_records f ON v.id = f.vehicle_id
            WHERE 
                v.status = 1
            GROUP BY 
                v.id, v.plate_number, v.brand, v.model
            ORDER BY 
                total_amount DESC
        ');
        
        return $this->db->resultSet();
    }

    // Bir araç için son kullanılan sürücüyü bul
    public function getLastDriverForVehicle($vehicleId) {
        $this->db->query("SELECT driver_id FROM fuel_records 
                          WHERE vehicle_id = :vehicle_id AND driver_id IS NOT NULL 
                          ORDER BY date DESC LIMIT 1");
        $this->db->bind(':vehicle_id', $vehicleId);
        
        $row = $this->db->single();
        
        return $row;
    }

    // Araca göre toplam yakıt maliyeti
    public function getTotalFuelCostByVehicle($vehicleId) {
        try {
            $this->db->query('
                SELECT SUM(amount) as total_cost
                FROM fuel_records
                WHERE vehicle_id = :vehicle_id
            ');
            
            $this->db->bind(':vehicle_id', $vehicleId);
            
            $result = $this->db->single();
            
            if ($result) {
                return $result->total_cost ?? 0;
            }
            
            return 0;
        } catch (Exception $e) {
            error_log('FuelModel::getTotalFuelCostByVehicle - Hata: ' . $e->getMessage());
            return 0;
        }
    }
}