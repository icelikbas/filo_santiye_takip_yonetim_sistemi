<?php

namespace App\Models;

use App\Core\Database;
use \PDO;
use \PDOException;
use \Exception;
use \DateTime;

class TrafficFine
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // Tüm trafik cezalarını getir
    public function getTrafficFines()
    {
        $this->db->query('
            SELECT tf.*, v.plate_number, d.name as driver_name, d.surname as driver_surname,
                   ft.name as fine_type_name
            FROM traffic_fines tf
            LEFT JOIN vehicles v ON tf.vehicle_id = v.id
            LEFT JOIN drivers d ON tf.driver_id = d.id
            LEFT JOIN fine_types ft ON tf.fine_type_id = ft.id
            ORDER BY tf.fine_date DESC
        ');

        return $this->db->resultSet();
    }

    // ID'ye göre trafik cezası getir
    public function getTrafficFineById($id)
    {
        try {
            if (!is_numeric($id)) {
                error_log("TrafficFine::getTrafficFineById - Geçersiz ID formatı: " . print_r($id, true));
                return false;
            }
            
            $query = '
                SELECT tf.*, v.plate_number, v.brand, v.model, 
                       d.name as driver_name, d.surname as driver_surname,
                       ft.name as fine_type_name
                FROM traffic_fines tf
                LEFT JOIN vehicles v ON tf.vehicle_id = v.id
                LEFT JOIN drivers d ON tf.driver_id = d.id
                LEFT JOIN fine_types ft ON tf.fine_type_id = ft.id
                WHERE tf.id = :id
            ';
            
            $this->db->query($query);
            $this->db->bind(':id', $id);
            
            $row = $this->db->single();
            
            if ($row === false) {
                error_log("TrafficFine::getTrafficFineById - Trafik cezası bulunamadı. ID: " . $id);
                return null;
            }
            
            return $row;
        } catch (\Exception $e) {
            error_log("TrafficFine::getTrafficFineById - Hata: " . $e->getMessage());
            error_log("TrafficFine::getTrafficFineById - Hata detayı: " . $e->getTraceAsString());
            return null;
        }
    }

    // Ceza numarasına göre trafik cezası bul
    public function findTrafficFineByNumber($fine_number)
    {
        $this->db->query('SELECT * FROM traffic_fines WHERE fine_number = :fine_number');
        $this->db->bind(':fine_number', $fine_number);

        $row = $this->db->single();

        // Ceza bulundu mu kontrolü
        return ($this->db->rowCount() > 0) ? $row : false;
    }

    // Araca göre trafik cezalarını getir
    public function getTrafficFinesByVehicle($vehicle_id)
    {
        $this->db->query('
            SELECT tf.*, d.name as driver_name, d.surname as driver_surname 
            FROM traffic_fines tf
            LEFT JOIN drivers d ON tf.driver_id = d.id
            WHERE tf.vehicle_id = :vehicle_id
            ORDER BY tf.fine_date DESC
        ');
        
        $this->db->bind(':vehicle_id', $vehicle_id);
        
        return $this->db->resultSet();
    }

    // Sürücüye göre trafik cezalarını getir
    public function getTrafficFinesByDriver($driver_id)
    {
        $this->db->query('
            SELECT tf.*, v.plate_number, v.brand, v.model 
            FROM traffic_fines tf
            LEFT JOIN vehicles v ON tf.vehicle_id = v.id
            WHERE tf.driver_id = :driver_id
            ORDER BY tf.fine_date DESC
        ');
        
        $this->db->bind(':driver_id', $driver_id);
        
        return $this->db->resultSet();
    }

    // Yeni trafik cezası ekle
    public function addTrafficFine($data)
    {
        $this->db->query('INSERT INTO traffic_fines (fine_number, vehicle_id, driver_id, fine_type_id, fine_date, fine_time, 
                           fine_amount, fine_location, description, payment_status, payment_date, 
                           payment_amount, document_url, created_by) 
                          VALUES (:fine_number, :vehicle_id, :driver_id, :fine_type_id, :fine_date, :fine_time, 
                           :fine_amount, :fine_location, :description, :payment_status, :payment_date,
                           :payment_amount, :document_url, :created_by)');

        // Parametreleri bağla
        $this->db->bind(':fine_number', $data['fine_number']);
        $this->db->bind(':vehicle_id', $data['vehicle_id']);
        $this->db->bind(':driver_id', $data['driver_id']);
        $this->db->bind(':fine_date', $data['fine_date']);
        $this->db->bind(':fine_time', $data['fine_time']);
        $this->db->bind(':fine_amount', str_replace(',', '.', $data['fine_amount']));
        $this->db->bind(':fine_type_id', $data['fine_type_id']);
        $this->db->bind(':fine_location', $data['fine_location']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':payment_status', $data['payment_status']);
        $this->db->bind(':payment_date', $data['payment_date']);
        $this->db->bind(':payment_amount', $data['payment_amount'] ? str_replace(',', '.', $data['payment_amount']) : null);
        $this->db->bind(':document_url', $data['document_url']);
        $this->db->bind(':created_by', $_SESSION['user_id']);

        // Çalıştır
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    // Trafik cezası güncelle
    public function updateTrafficFine($data)
    {
        $this->db->query('UPDATE traffic_fines 
                          SET fine_number = :fine_number, 
                              vehicle_id = :vehicle_id, 
                              driver_id = :driver_id, 
                              fine_type_id = :fine_type_id,
                              fine_date = :fine_date, 
                              fine_time = :fine_time, 
                              fine_amount = :fine_amount,
                              fine_location = :fine_location,
                              description = :description,
                              payment_status = :payment_status,
                              payment_date = :payment_date,
                              payment_amount = :payment_amount,
                              document_url = :document_url,
                              updated_at = CURRENT_TIMESTAMP
                          WHERE id = :id');

        // Parametreleri bağla
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':fine_number', $data['fine_number']);
        $this->db->bind(':vehicle_id', $data['vehicle_id']);
        $this->db->bind(':driver_id', $data['driver_id']);
        $this->db->bind(':fine_date', $data['fine_date']);
        $this->db->bind(':fine_time', $data['fine_time']);
        $this->db->bind(':fine_amount', str_replace(',', '.', $data['fine_amount']));
        $this->db->bind(':fine_type_id', $data['fine_type_id']);
        $this->db->bind(':fine_location', $data['fine_location']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':payment_status', $data['payment_status']);
        $this->db->bind(':payment_date', $data['payment_date']);
        $this->db->bind(':payment_amount', $data['payment_amount'] ? str_replace(',', '.', $data['payment_amount']) : null);
        $this->db->bind(':document_url', $data['document_url']);

        // Çalıştır
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Ödeme durumunu güncelle
    public function updatePaymentStatus($data)
    {
        $this->db->query('UPDATE traffic_fines 
                          SET payment_status = :payment_status,
                              payment_amount = :payment_amount,
                              updated_at = CURRENT_TIMESTAMP
                          WHERE id = :id');

        // Parametreleri bağla
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':payment_status', $data['payment_status']);
        $this->db->bind(':payment_amount', $data['payment_amount']);

        // Çalıştır
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Trafik cezası sil
    public function deleteTrafficFine($id)
    {
        $this->db->query('DELETE FROM traffic_fines WHERE id = :id');
        $this->db->bind(':id', $id);

        // Çalıştır
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Ödenmemiş cezaları getir
    public function getUnpaidFines()
    {
        $this->db->query('
            SELECT tf.*, v.plate_number, d.name as driver_name, d.surname as driver_surname 
            FROM traffic_fines tf
            LEFT JOIN vehicles v ON tf.vehicle_id = v.id
            LEFT JOIN drivers d ON tf.driver_id = d.id
            WHERE tf.payment_status IN ("Ödenmedi", "İtiraz Edildi", "Taksitli Ödeme")
            ORDER BY tf.fine_date DESC
        ');

        return $this->db->resultSet();
    }

    // Ödenmiş cezaları getir
    public function getPaidFines()
    {
        $this->db->query('
            SELECT tf.*, v.plate_number, d.name as driver_name, d.surname as driver_surname 
            FROM traffic_fines tf
            LEFT JOIN vehicles v ON tf.vehicle_id = v.id
            LEFT JOIN drivers d ON tf.driver_id = d.id
            WHERE tf.payment_status = "Ödendi"
            ORDER BY tf.fine_date DESC
        ');

        return $this->db->resultSet();
    }

    // Ceza tipine göre cezaları getir
    public function getFinesByType($type_id)
    {
        $this->db->query('
            SELECT tf.*, v.plate_number, d.name as driver_name, d.surname as driver_surname 
            FROM traffic_fines tf
            LEFT JOIN vehicles v ON tf.vehicle_id = v.id
            LEFT JOIN drivers d ON tf.driver_id = d.id
            WHERE tf.fine_type_id = :fine_type_id
            ORDER BY tf.fine_date DESC
        ');
        
        $this->db->bind(':fine_type_id', $type_id);
        
        return $this->db->resultSet();
    }

    // Toplam ceza tutarını hesapla
    public function getTotalFineAmount()
    {
        $this->db->query('SELECT SUM(fine_amount) as total FROM traffic_fines');
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    // Toplam ödenmemiş ceza tutarını hesapla
    public function getTotalUnpaidAmount()
    {
        $this->db->query('
            SELECT SUM(fine_amount - COALESCE(payment_amount, 0)) as total 
            FROM traffic_fines 
            WHERE payment_status IN ("Ödenmedi", "Taksitli Ödeme")
        ');
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    // Son cezaları getir
    public function getRecentFines($limit = 5)
    {
        $this->db->query('
            SELECT tf.*, v.plate_number, d.name as driver_name, d.surname as driver_surname 
            FROM traffic_fines tf
            LEFT JOIN vehicles v ON tf.vehicle_id = v.id
            LEFT JOIN drivers d ON tf.driver_id = d.id
            ORDER BY tf.created_at DESC
            LIMIT :limit
        ');
        
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }

    // Ceza tipi dağılımını getir
    public function getFineTypeDistribution()
    {
        $this->db->query('
            SELECT fine_type_id, COUNT(*) as count, SUM(fine_amount) as total_amount
            FROM traffic_fines
            GROUP BY fine_type_id
            ORDER BY count DESC
        ');
        
        return $this->db->resultSet();
    }

    // Ödeme durumu dağılımını getir
    public function getPaymentStatusDistribution()
    {
        $this->db->query('
            SELECT payment_status, COUNT(*) as count, SUM(fine_amount) as total_amount
            FROM traffic_fines
            GROUP BY payment_status
            ORDER BY count DESC
        ');
        
        return $this->db->resultSet();
    }
} 