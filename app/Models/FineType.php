<?php

namespace App\Models;

use App\Core\Database;
use \PDO;
use \PDOException;
use \Exception;

class FineType
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // Tüm ceza tiplerini getir
    public function getFineTypes()
    {
        $this->db->query('SELECT * FROM fine_types ORDER BY name ASC');

        return $this->db->resultSet();
    }

    // Aktif ceza tiplerini getir
    public function getActiveFineTypes()
    {
        $this->db->query('SELECT * FROM fine_types WHERE active = 1 ORDER BY name ASC');

        return $this->db->resultSet();
    }

    // ID'ye göre ceza tipini getir
    public function getFineTypeById($id)
    {
        try {
            if (!is_numeric($id)) {
                error_log("FineType::getFineTypeById - Geçersiz ID formatı: " . print_r($id, true));
                return false;
            }
            
            $query = 'SELECT * FROM fine_types WHERE id = :id';
            
            $this->db->query($query);
            $this->db->bind(':id', $id);
            
            $row = $this->db->single();
            
            if ($row === false) {
                error_log("FineType::getFineTypeById - Ceza tipi bulunamadı. ID: " . $id);
                return null;
            }
            
            return $row;
        } catch (\Exception $e) {
            error_log("FineType::getFineTypeById - Hata: " . $e->getMessage());
            error_log("FineType::getFineTypeById - Hata detayı: " . $e->getTraceAsString());
            return null;
        }
    }

    // Ceza koduna göre ceza tipini bul
    public function findFineTypeByCode($code)
    {
        $this->db->query('SELECT * FROM fine_types WHERE code = :code');
        $this->db->bind(':code', $code);

        $row = $this->db->single();

        // Ceza tipi bulundu mu kontrolü
        return ($this->db->rowCount() > 0) ? $row : false;
    }

    // Bu ceza tipine ait cezaları getir
    public function getFinesByTypeId($id)
    {
        $this->db->query('
            SELECT tf.id, tf.fine_date, tf.fine_amount as amount, tf.payment_status, 
                  v.plate_number, d.name as driver_name, d.surname as driver_surname 
            FROM traffic_fines tf
            LEFT JOIN vehicles v ON tf.vehicle_id = v.id
            LEFT JOIN drivers d ON tf.driver_id = d.id
            WHERE tf.fine_type_id = :id
            ORDER BY tf.fine_date DESC
        ');
        
        $this->db->bind(':id', $id);
        
        return $this->db->resultSet();
    }

    // Yeni ceza tipi ekle
    public function addFineType($data)
    {
        $this->db->query('INSERT INTO fine_types (code, name, legal_article, description, default_amount, points, active) 
                          VALUES (:code, :name, :legal_article, :description, :default_amount, :points, :active)');

        // Parametreleri bağla
        $this->db->bind(':code', $data['code']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':legal_article', $data['legal_article']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':default_amount', str_replace(',', '.', $data['default_amount']));
        $this->db->bind(':points', $data['points']);
        $this->db->bind(':active', $data['active']);

        // Çalıştır
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Ceza tipini güncelle
    public function updateFineType($data)
    {
        $this->db->query('UPDATE fine_types 
                          SET code = :code, 
                              name = :name, 
                              legal_article = :legal_article,
                              description = :description, 
                              default_amount = :default_amount,
                              points = :points, 
                              active = :active
                          WHERE id = :id');

        // Parametreleri bağla
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':code', $data['code']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':legal_article', $data['legal_article']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':default_amount', str_replace(',', '.', $data['default_amount']));
        $this->db->bind(':points', $data['points']);
        $this->db->bind(':active', $data['active']);

        // Çalıştır
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Ceza tipi durumunu güncelle
    public function updateStatus($id, $status)
    {
        $this->db->query('UPDATE fine_types SET active = :active WHERE id = :id');
        
        // Parametreleri bağla
        $this->db->bind(':id', $id);
        $this->db->bind(':active', $status);

        // Çalıştır
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Ceza tipi sil
    public function deleteFineType($id)
    {
        $this->db->query('DELETE FROM fine_types WHERE id = :id');
        $this->db->bind(':id', $id);

        // Çalıştır
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Ceza tipi kullanılıyor mu kontrol et
    public function isTypeInUse($id)
    {
        // Bu ceza tipine ait ceza var mı kontrol et
        $this->db->query('SELECT COUNT(*) as count FROM traffic_fines WHERE fine_type_id = :id');
        $this->db->bind(':id', $id);
        
        $result = $this->db->single();
        
        return ($result->count > 0);
    }

    // Ceza tiplerini select için getir
    public function getFineTypesForSelect()
    {
        $this->db->query('SELECT id, name FROM fine_types WHERE active = 1 ORDER BY name ASC');

        return $this->db->resultSet();
    }

    // Aktif ceza tipi sayısını getir
    public function getActiveFineTypeCount()
    {
        $this->db->query('SELECT COUNT(*) as count FROM fine_types WHERE active = 1');
        $result = $this->db->single();
        return $result->count ?? 0;
    }

    // Pasif ceza tipi sayısını getir
    public function getInactiveFineTypeCount()
    {
        $this->db->query('SELECT COUNT(*) as count FROM fine_types WHERE active = 0');
        $result = $this->db->single();
        return $result->count ?? 0;
    }
} 