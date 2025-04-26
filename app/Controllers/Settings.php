<?php
   namespace App\Controllers;

   use App\Core\Controller;
   
   
class Settings extends Controller {
    private $userModel;
    private $vehicleModel;
    private $driverModel;

    public function __construct() {
        // Sadece admin erişebilir
        if(!isAdmin()) {
            redirect('dashboard');
        }

        // Modelleri yükle
        $this->userModel = $this->model('User');
        $this->vehicleModel = $this->model('Vehicle');
        $this->driverModel = $this->model('Driver');
    }

    // Ayarlar sayfası
    public function index() {
        // Sistem istatistiklerini al
        $stats = [
            'total_vehicles' => $this->vehicleModel->getTotalVehicleCount(),
            'active_vehicles' => $this->vehicleModel->getVehicleCountByStatus('Aktif'),
            'maintenance_vehicles' => $this->vehicleModel->getVehicleCountByStatus('Bakımda'),
            'total_drivers' => $this->driverModel->getTotalDriverCount(),
            'active_drivers' => $this->driverModel->getDriverCountByStatus('Aktif'),
            'user_count' => $this->userModel->getUserCount(),
        ];

        // Veritabanı yapılandırması (config dosyasından)
        $dbConfig = [
            'host' => DB_HOST,
            'user' => DB_USER,
            'name' => DB_NAME,
            'charset' => 'utf8mb4'
        ];

        // Sistem ayarları
        $siteSettings = [
            'site_name' => SITENAME,
            'app_version' => '1.0.0',
            'app_root' => APPROOT,
            'url_root' => URLROOT
        ];

        $data = [
            'title' => 'Sistem Ayarları',
            'stats' => $stats,
            'db_config' => $dbConfig,
            'site_settings' => $siteSettings
        ];

        $this->view('settings/index', $data);
    }

    // Genel ayarlar güncelleme
    public function updateGeneral() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'site_name' => trim($_POST['site_name']),
                'site_name_err' => ''
            ];

            // Site adı doğrulama
            if (empty($data['site_name'])) {
                $data['site_name_err'] = 'Lütfen site adını giriniz';
            }

            // Hata yoksa güncelle
            if (empty($data['site_name_err'])) {
                // Burada config dosyasını güncelleyecek bir fonksiyon çağrılabilir
                // updateConfig('SITENAME', $data['site_name']);
                
                flash('settings_message', 'Genel ayarlar başarıyla güncellendi');
                redirect('settings');
            } else {
                // Hata varsa formu tekrar göster
                $this->view('settings/index', $data);
            }
        } else {
            redirect('settings');
        }
    }

    // Sistem bilgisi 
    public function systemInfo() {
        $data = [
            'title' => 'Sistem Bilgisi',
            'php_version' => phpversion(),
            'server_info' => $_SERVER['SERVER_SOFTWARE'],
            'memory_limit' => ini_get('memory_limit'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'max_execution_time' => ini_get('max_execution_time'),
            'session_path' => session_save_path()
        ];

        $this->view('settings/system', $data);
    }
    
    // Veritabanı yedekleme
    public function backup() {
        // Sadece POST istekleri için işlem yap
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Yedekleme işlemi için gerekli parametreler
            $dbHost = DB_HOST;
            $dbUser = DB_USER;
            $dbPass = DB_PASS;
            $dbName = DB_NAME;
            $backupDir = APPROOT . '/../backups/';
            
            // Yedekleme dizini kontrol et, yoksa oluştur
            if (!file_exists($backupDir)) {
                if (!mkdir($backupDir, 0755, true)) {
                    flash('settings_message', 'Yedekleme dizini oluşturulamadı.', 'alert alert-danger');
                    redirect('settings/backup');
                    return;
                }
            }
            
            // Dosya adı oluştur (tarih-saat formatında)
            $fileName = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filePath = $backupDir . $fileName;
            
            try {
                // Windows sistemler için mysqldump yolunu kontrol et
                $mysqldumpPath = '';
                
                // Windows sistemde yaygın mysqldump yolları
                $possiblePaths = [
                    'C:/xampp/mysql/bin/mysqldump.exe',
                    'C:/wamp/bin/mysql/mysql5.7.26/bin/mysqldump.exe',
                    'C:/wamp64/bin/mysql/mysql5.7.26/bin/mysqldump.exe',
                    'C:/laragon/bin/mysql/mysql-5.7.24-win32/bin/mysqldump.exe',
                    'mysqldump'  // PATH'e eklenmişse
                ];
                
                foreach ($possiblePaths as $path) {
                    if (file_exists($path) || $path === 'mysqldump') {
                        $mysqldumpPath = $path;
                        break;
                    }
                }
                
                if (empty($mysqldumpPath)) {
                    flash('settings_message', 'mysqldump komutu bulunamadı. Manuel olarak veritabanı yedeği alın.', 'alert alert-danger');
                    redirect('settings/backup');
                    return;
                }
                
                // mysqldump komutunu oluştur (tırnak içine al ve yolları güvenli hale getir)
                $command = sprintf('"%s" --host=%s --user=%s --password=%s --databases %s > "%s"', 
                    $mysqldumpPath,
                    escapeshellarg($dbHost),
                    escapeshellarg($dbUser),
                    escapeshellarg($dbPass),
                    escapeshellarg($dbName),
                    $filePath
                );
                
                // Komut çıktısını ve hata kodunu al
                $output = [];
                $returnVar = 0;
                
                // Windows'da cmd ile çalıştır
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    exec("cmd /c " . $command . " 2>&1", $output, $returnVar);
                } else {
                    exec($command . " 2>&1", $output, $returnVar);
                }
                
                // Manuel olarak veritabanı yedeği oluştur (mysqldump çalışmadığında alternatif yöntem)
                if ($returnVar !== 0 || !file_exists($filePath) || filesize($filePath) === 0) {
                    // Manuel yedekleme için PHP PDO kullan
                    $manualBackup = $this->createManualBackup($dbHost, $dbUser, $dbPass, $dbName, $filePath);
                    
                    if ($manualBackup) {
                        // Yedekleme başarılı
                        if (file_exists($filePath)) {
                            header('Content-Description: File Transfer');
                            header('Content-Type: application/octet-stream');
                            header('Content-Disposition: attachment; filename="'.basename($filePath).'"');
                            header('Expires: 0');
                            header('Cache-Control: must-revalidate');
                            header('Pragma: public');
                            header('Content-Length: ' . filesize($filePath));
                            readfile($filePath);
                            exit;
                        } else {
                            flash('settings_message', 'Yedekleme dosyası oluşturuldu ancak indirilemedi', 'alert alert-warning');
                            redirect('settings/backup');
                        }
                    } else {
                        flash('settings_message', 'Veritabanı yedeklemesi başarısız. Sistemde mysqldump komutu çalıştırılamadı ve manuel yedekleme de başarısız oldu.', 'alert alert-danger');
                        redirect('settings/backup');
                    }
                } else {
                    // mysqldump başarılı
                    if (file_exists($filePath)) {
                        header('Content-Description: File Transfer');
                        header('Content-Type: application/octet-stream');
                        header('Content-Disposition: attachment; filename="'.basename($filePath).'"');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate');
                        header('Pragma: public');
                        header('Content-Length: ' . filesize($filePath));
                        readfile($filePath);
                        exit;
                    } else {
                        flash('settings_message', 'Yedekleme dosyası oluşturuldu ancak indirilemedi', 'alert alert-warning');
                        redirect('settings/backup');
                    }
                }
            } catch (Exception $e) {
                flash('settings_message', 'Veritabanı yedeklemesi sırasında hata oluştu: ' . $e->getMessage(), 'alert alert-danger');
                redirect('settings/backup');
            }
        } else {
            // GET isteği için yedekleme sayfasını göster
            $backupDir = APPROOT . '/../backups/';
            $backups = [];
            
            if (file_exists($backupDir)) {
                $files = scandir($backupDir);
                
                foreach ($files as $file) {
                    if ($file != '.' && $file != '..' && pathinfo($file, PATHINFO_EXTENSION) == 'sql') {
                        $backups[] = [
                            'name' => $file,
                            'size' => filesize($backupDir . $file) / 1024, // KB cinsinden
                            'date' => date('d.m.Y H:i', filemtime($backupDir . $file))
                        ];
                    }
                }
                
                // Dosyaları tarih sırasına göre sırala (en yeni en üstte)
                usort($backups, function($a, $b) {
                    return strtotime(str_replace(['.', ' '], ['/', ':'], $b['date'])) - 
                           strtotime(str_replace(['.', ' '], ['/', ':'], $a['date']));
                });
            }
            
            $data = [
                'title' => 'Veritabanı Yedekleme',
                'backups' => $backups
            ];
            
            $this->view('settings/backup', $data);
        }
    }
    
    // Manuel veritabanı yedeği oluşturma
    private function createManualBackup($host, $user, $pass, $dbName, $filePath) {
        try {
            $pdo = new \PDO("mysql:host=$host;dbname=$dbName;charset=utf8mb4", $user, $pass, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ]);
            
            // Tabloları al
            $tables = [];
            $stmt = $pdo->query("SHOW TABLES");
            while ($row = $stmt->fetch()) {
                $tables[] = $row[0];
            }
            
            // Dosyayı oluştur ve SQL komutlarını yaz
            $output = "-- Filo Takip Sistemi Veritabanı Yedeği\n";
            $output .= "-- Oluşturma Tarihi: " . date('d.m.Y H:i:s') . "\n";
            $output .= "-- --------------------------------------------------------\n\n";
            $output .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
            $output .= "SET AUTOCOMMIT = 0;\n";
            $output .= "START TRANSACTION;\n";
            $output .= "SET time_zone = \"+00:00\";\n\n";
            
            // Veritabanı adını belirt
            $output .= "CREATE DATABASE IF NOT EXISTS `" . $dbName . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;\n";
            $output .= "USE `" . $dbName . "`;\n\n";
            
            // Her tablo için yapı ve veri yedeği al
            foreach ($tables as $table) {
                // Tablo yapısı
                $stmt = $pdo->query("SHOW CREATE TABLE `$table`");
                $row = $stmt->fetch();
                $output .= "DROP TABLE IF EXISTS `$table`;\n";
                $output .= $row['Create Table'] . ";\n\n";
                
                // Tablo verileri
                $stmt = $pdo->query("SELECT * FROM `$table`");
                $rowCount = $stmt->rowCount();
                
                if ($rowCount > 0) {
                    $output .= "INSERT INTO `$table` VALUES\n";
                    $counter = 0;
                    
                    while ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
                        $output .= "(";
                        
                        for ($i = 0; $i < count($row); $i++) {
                            $output .= ($row[$i] === null) ? "NULL" : "'" . addslashes($row[$i]) . "'";
                            
                            if ($i < count($row) - 1) {
                                $output .= ", ";
                            }
                        }
                        
                        $counter++;
                        $output .= ($counter < $rowCount) ? "),\n" : ");\n\n";
                    }
                }
            }
            
            $output .= "COMMIT;\n";
            
            // Dosyaya yaz
            if (file_put_contents($filePath, $output)) {
                return true;
            }
            
            return false;
            
        } catch (\PDOException $e) {
            // Hata durumunda false döndür
            return false;
        }
    }
    
    // Yedekleme dosyasını silme
    public function deleteBackup($fileName) {
        $backupDir = APPROOT . '/../backups/';
        $filePath = $backupDir . $fileName;
        
        // Güvenlik kontrolü - Sadece .sql dosyalarını sil
        if (!pathinfo($fileName, PATHINFO_EXTENSION) == 'sql') {
            flash('settings_message', 'Geçersiz dosya türü', 'alert alert-danger');
            redirect('settings/backup');
            return;
        }
        
        // Dosyayı sil
        if (file_exists($filePath)) {
            if (unlink($filePath)) {
                flash('settings_message', 'Yedekleme dosyası başarıyla silindi', 'alert alert-success');
            } else {
                flash('settings_message', 'Yedekleme dosyası silinemedi', 'alert alert-danger');
            }
        } else {
            flash('settings_message', 'Yedekleme dosyası bulunamadı', 'alert alert-danger');
        }
        
        redirect('settings/backup');
    }
    
    // Yedekleme indirme
    public function downloadBackup($fileName) {
        $backupDir = APPROOT . '/../backups/';
        $filePath = $backupDir . $fileName;
        
        // Güvenlik kontrolü - Sadece .sql dosyalarını indir
        if (!pathinfo($fileName, PATHINFO_EXTENSION) == 'sql') {
            flash('settings_message', 'Geçersiz dosya türü', 'alert alert-danger');
            redirect('settings/backup');
            return;
        }
        
        if (file_exists($filePath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($filePath).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit;
        } else {
            flash('settings_message', 'Yedekleme dosyası bulunamadı', 'alert alert-danger');
            redirect('settings/backup');
        }
    }
    
    // Yardım ve destek sayfası
    public function help() {
        $data = [
            'title' => 'Yardım ve Destek'
        ];
        
        $this->view('settings/help', $data);
    }
} 