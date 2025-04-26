<?php
// Hata raporlamasını aktifleştir
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Composer autoloader'ı yükle
if (file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    require_once dirname(__DIR__) . '/vendor/autoload.php';
} else {
    die("Composer autoloader bulunamadı. Lütfen 'composer install' komutunu çalıştırın.");
}

// Config dosyasını yükle (Composer autoload files içinde zaten tanımlı)
if (!defined('APPROOT')) {
    include_once 'config/config.php';
}

// Oturum başlat
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Eski yükleme yönteminde problem olursa kullanılacak yedek mekanizma
if (!function_exists('redirect')) {
    include_once 'helpers/url_helper.php';
}

if (!function_exists('flash')) {
    include_once 'helpers/session_helper.php';
}

if (!function_exists('logCreate')) {
    include_once 'helpers/log_helper.php';
}

// Alternatif otoloader (eski koruması)
spl_autoload_register(function($className) {
    if (file_exists('libraries/' . $className . '.php')) {
        require_once 'libraries/' . $className . '.php';
    }
}); 