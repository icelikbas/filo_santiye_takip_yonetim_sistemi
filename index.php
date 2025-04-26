<?php
// Ana uygulama dosyası - Tüm istekler buradan yönlendirilir
define('ROOT', dirname(__FILE__));

// Hata raporlamasını etkinleştir (Debug için)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Oturum başlatma
session_start();

// Composer autoloader'ı dahil et
require_once ROOT . '/vendor/autoload.php';

// Yardımcı fonksiyonları dahil et
require_once ROOT . '/app/config/config.php';
require_once ROOT . '/app/helpers/url_helper.php';
require_once ROOT . '/app/helpers/session_helper.php';
require_once ROOT . '/app/helpers/log_helper.php';

// Uygulamayı başlat
use App\Core\App;

$app = new App();



