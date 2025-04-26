<?php
namespace App\Core;

class App {
    /** @var object Controller nesnesi */
    protected $currentController;
    
    /** @var string Controller metod adı */
    protected $currentMethod = 'index';
    
    /** @var array Controller metodu parametreleri */
    protected $params = [];

    /** @var string Varsayılan controller adı */
    protected $defaultController = 'Pages';

    /**
     * Uygulama başlatıcı
     */
    public function __construct() {
        // URL bilgisini al
        $url = $this->getUrl();
        
        // URL bilgisini logla
        $urlStr = empty($url) ? 'Ana Sayfa' : implode('/', $url);
        error_log('İstek: ' . $urlStr);
        
        // Controller'ı ara
        if (!empty($url) && isset($url[0])) {
            $controllerName = ucwords($url[0]);
            $controllerClass = 'App\\Controllers\\' . $controllerName;
            
            if (class_exists($controllerClass)) {
                $this->defaultController = $controllerName;
                unset($url[0]);
                error_log('Controller bulundu: ' . $controllerName);
            } else {
                error_log('Hata: Controller bulunamadı: ' . $controllerClass);
                $this->errorPage('Controller bulunamadı: ' . $controllerName);
                return;
            }
        }

        // Controller örneği oluştur
        $controllerClass = 'App\\Controllers\\' . $this->defaultController;
        try {
            $this->currentController = new $controllerClass();
        } catch (\Throwable $e) {
            error_log('Hata: Controller oluşturulamadı: ' . $e->getMessage());
            $this->errorPage('Controller oluşturulamadı: ' . $e->getMessage());
            return;
        }

        // Method kontrolü
        if (isset($url[1])) {
            $methodName = $url[1];
            if (method_exists($this->currentController, $methodName)) {
                $this->currentMethod = $methodName;
                unset($url[1]);
                error_log('Metod bulundu: ' . $methodName);
            } else {
                error_log('Hata: Metod bulunamadı: ' . $methodName);
                $this->errorPage('Metod bulunamadı: ' . $methodName);
                return;
            }
        }

        // Parametreleri al
        $this->params = $url ? array_values($url) : [];

        // Controller metodu çağır
        try {
            $controllerClassName = get_class($this->currentController);
            error_log('Çağrılıyor: ' . $controllerClassName . '::' . $this->currentMethod . '(' . implode(', ', $this->params) . ')');
            call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
        } catch (\ArgumentCountError $e) {
            error_log('Hata: Parametre sayısı hatası: ' . $e->getMessage());
            $this->errorPage('Yanlış sayıda parametre: ' . $e->getMessage());
        } catch (\Throwable $e) {
            error_log('Hata: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            $this->errorPage($e->getMessage());
        }
    }

    /**
     * Hata sayfası gösterir
     * 
     * @param string $message Hata mesajı
     * @return void
     */
    private function errorPage($message) {
        echo '<div style="background: #f8d7da; color: #721c24; padding: 20px; margin: 20px; border-radius: 5px; font-family: Arial, sans-serif;">';
        echo '<h2>Sayfa Yüklenirken Hata Oluştu</h2>';
        echo '<p>' . htmlspecialchars($message) . '</p>';
        echo '<p><a href="' . URLROOT . '" style="color: #721c24;">Ana Sayfaya Dön</a></p>';
        echo '</div>';
    }

    /**
     * URL'den bölümleri alır
     * 
     * @return array URL bölümleri
     */
    public function getUrl(): array {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }
}