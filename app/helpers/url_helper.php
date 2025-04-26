<?php

/**
 * URL Yardımcı Fonksiyonları
 */

// URL'ye yönlendirme
function redirect($page)
{
    // Debug bilgisi
    error_log("URL Yönlendirme: " . URLROOT . '/' . $page);
    
    if (headers_sent($file, $line)) {
        error_log("Header gönderilmiş, yönlendirme yapılamadı. Dosya: $file, Satır: $line");
        echo '<script>window.location.href="' . URLROOT . '/' . $page . '";</script>';
        echo '<noscript><meta http-equiv="refresh" content="0;url=' . URLROOT . '/' . $page . '" /></noscript>';
        exit;
    }
    
    header('Location: ' . URLROOT . '/' . $page);
    exit;
}

// Tam URL döndürür
function getUrl($path = '')
{
    return URLROOT . '/' . $path;
}

// Public klasörüne tam yol döndürür
function getPublicUrl($path = '')
{
    return URLROOT . '/public/' . $path;
}

// Mevcut URL'nin debug dostu bir versiyonunu verir
function getCurrentUrl()
{
    $currentURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    return $currentURL;
}

// URL parametrelerini güvenli bir şekilde alır
function getUrlSegment($index = 0)
{
    if (isset($_GET['url'])) {
        $url = rtrim($_GET['url'], '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);
        return $index < count($url) ? $url[$index] : null;
    }
    return null;
}
