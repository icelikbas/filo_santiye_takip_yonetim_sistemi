<?php
namespace App\Core;

class Controller {
    public function model(string $model): object {
        try {
            $modelClass = 'App\\Models\\' . $model;
            if (class_exists($modelClass)) {
                return new $modelClass();
            }
            throw new \Exception("Model bulunamadı: $model");
        } catch (\Exception $e) {
            error_log("Model yükleme hatası: " . $e->getMessage());
            throw $e;
        }
    }

    public function view(string $view, array $data = []): void {
        error_log("Controller::view - Görünüm yükleniyor: " . $view);
        
        $viewPath = 'app/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            // Değişkenleri al
            extract($data);
            
            // Görünümü yükle
            require_once $viewPath;
        } else {
            error_log("Controller::view - Görünüm bulunamadı: " . $viewPath);
            echo "<div style='color:red'>Görünüm dosyası bulunamadı: " . htmlspecialchars($view) . "</div>";
        }
    }
    
    // Güvenli bir şekilde JSON yanıt döndür
    public function jsonResponse($data, $status = 200): void {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
    
    // Hata yanıtı döndür
    public function errorResponse($message, $status = 500): void {
        $this->jsonResponse(['error' => $message], $status);
    }
}