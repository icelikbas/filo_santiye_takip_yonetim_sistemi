<?php

namespace App\Models;

use App\Core\Database;
use \PDO;
use \PDOException;
use \Exception;

class FinePayment
{
    private $db;

    public function __construct()
    {
        try {
            $this->db = new Database;
            
            // Bağlantıyı test etmek için basit bir sorgu
            $this->db->query('SELECT 1');
            $this->db->execute();
        } catch (\Exception $e) {
            error_log("FinePayment::__construct - Database hatası: " . $e->getMessage());
            die("Veritabanı bağlantı hatası: " . $e->getMessage());
        }
    }

    // Tüm ödemeleri getir
    public function getAllPayments()
    {
        $this->db->query('
            SELECT fp.*, tf.fine_number, v.plate_number, 
                   ft.name as fine_type_name
            FROM fine_payments fp
            JOIN traffic_fines tf ON fp.fine_id = tf.id
            JOIN vehicles v ON tf.vehicle_id = v.id
            LEFT JOIN fine_types ft ON tf.fine_type_id = ft.id
            ORDER BY fp.payment_date DESC
        ');

        return $this->db->resultSet();
    }

    // ID'ye göre ödeme getir
    public function getPaymentById($id)
    {
        try {
            if (!is_numeric($id)) {
                error_log("FinePayment::getPaymentById - Geçersiz ID formatı: " . print_r($id, true));
                return false;
            }
            
            $query = '
                SELECT fp.*, tf.fine_number, v.plate_number,
                       tf.fine_type_id, ft.name as fine_type_name 
                FROM fine_payments fp
                JOIN traffic_fines tf ON fp.fine_id = tf.id
                JOIN vehicles v ON tf.vehicle_id = v.id
                LEFT JOIN fine_types ft ON tf.fine_type_id = ft.id
                WHERE fp.id = :id
            ';
            
            $this->db->query($query);
            $this->db->bind(':id', $id);
            
            $row = $this->db->single();
            
            if ($row === false) {
                error_log("FinePayment::getPaymentById - Ödeme bulunamadı. ID: " . $id);
                return null;
            }
            
            return $row;
        } catch (\Exception $e) {
            error_log("FinePayment::getPaymentById - Hata: " . $e->getMessage());
            error_log("FinePayment::getPaymentById - Hata detayı: " . $e->getTraceAsString());
            return null;
        }
    }

    // Ceza ID'sine göre ödemeleri getir
    public function getPaymentsByFineId($fine_id)
    {
        $this->db->query('
            SELECT fp.*, u.name as created_by_name, u.surname as created_by_surname 
            FROM fine_payments fp
            LEFT JOIN users u ON fp.created_by = u.id
            WHERE fp.fine_id = :fine_id
            ORDER BY fp.payment_date DESC
        ');
        
        $this->db->bind(':fine_id', $fine_id);
        
        return $this->db->resultSet();
    }

    // Yeni ödeme ekle
    public function addPayment($data)
    {
        $this->db->query('INSERT INTO fine_payments (fine_id, payment_date, amount, payment_method, receipt_number, notes, created_by) 
                          VALUES (:fine_id, :payment_date, :amount, :payment_method, :receipt_number, :notes, :created_by)');

        // Parametreleri bağla
        $this->db->bind(':fine_id', $data['fine_id']);
        $this->db->bind(':payment_date', $data['payment_date']);
        $this->db->bind(':amount', str_replace(',', '.', $data['amount']));
        $this->db->bind(':payment_method', $data['payment_method']);
        $this->db->bind(':receipt_number', $data['receipt_number']);
        $this->db->bind(':notes', $data['notes']);
        $this->db->bind(':created_by', $data['created_by']);

        // Çalıştır
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Ödeme güncelle
    public function updatePayment($data)
    {
        $this->db->query('UPDATE fine_payments 
                          SET payment_date = :payment_date, 
                              amount = :amount, 
                              payment_method = :payment_method, 
                              receipt_number = :receipt_number, 
                              notes = :notes
                          WHERE id = :id');

        // Parametreleri bağla
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':payment_date', $data['payment_date']);
        $this->db->bind(':amount', str_replace(',', '.', $data['amount']));
        $this->db->bind(':payment_method', $data['payment_method']);
        $this->db->bind(':receipt_number', $data['receipt_number']);
        $this->db->bind(':notes', $data['notes']);

        // Çalıştır
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Ödeme sil
    public function deletePayment($id)
    {
        $this->db->query('DELETE FROM fine_payments WHERE id = :id');
        $this->db->bind(':id', $id);

        // Çalıştır
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Cezaya ait tüm ödemeleri sil
    public function deletePaymentsByFineId($fine_id)
    {
        $this->db->query('DELETE FROM fine_payments WHERE fine_id = :fine_id');
        $this->db->bind(':fine_id', $fine_id);

        // Çalıştır
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Toplam ödeme tutarını hesapla
    public function getTotalPaymentAmount()
    {
        $this->db->query('SELECT SUM(amount) as total FROM fine_payments');
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    // Belirli tarih aralığındaki ödemeleri getir
    public function getPaymentsByDateRange($startDate, $endDate)
    {
        $this->db->query('
            SELECT fp.*, tf.fine_number, v.plate_number 
            FROM fine_payments fp
            JOIN traffic_fines tf ON fp.fine_id = tf.id
            JOIN vehicles v ON tf.vehicle_id = v.id
            WHERE fp.payment_date BETWEEN :start_date AND :end_date
            ORDER BY fp.payment_date DESC
        ');
        
        $this->db->bind(':start_date', $startDate);
        $this->db->bind(':end_date', $endDate);
        
        return $this->db->resultSet();
    }

    // Ödeme yöntemine göre ödemeleri getir
    public function getPaymentsByMethod($method)
    {
        $this->db->query('
            SELECT fp.*, tf.fine_number, v.plate_number 
            FROM fine_payments fp
            JOIN traffic_fines tf ON fp.fine_id = tf.id
            JOIN vehicles v ON tf.vehicle_id = v.id
            WHERE fp.payment_method = :method
            ORDER BY fp.payment_date DESC
        ');
        
        $this->db->bind(':method', $method);
        
        return $this->db->resultSet();
    }

    // Son ödemeleri getir
    public function getRecentPayments($limit = 5)
    {
        $this->db->query('
            SELECT fp.*, tf.fine_number, v.plate_number 
            FROM fine_payments fp
            JOIN traffic_fines tf ON fp.fine_id = tf.id
            JOIN vehicles v ON tf.vehicle_id = v.id
            ORDER BY fp.created_at DESC
            LIMIT :limit
        ');
        
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }

    // Ödeme yöntemi dağılımını getir
    public function getPaymentMethodDistribution()
    {
        $this->db->query('
            SELECT payment_method, COUNT(*) as count, SUM(amount) as total_amount
            FROM fine_payments
            GROUP BY payment_method
            ORDER BY count DESC
        ');
        
        return $this->db->resultSet();
    }
} 