<?php

namespace App\Core;

class App 
{
    protected $controller = "HomeController";
    protected $method = "index";
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseURL();

        // 1. Cek ketersediaan Controller dari URL
        if (isset($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            $controllerPath = __DIR__ . '/../Controllers/' . $controllerName . '.php';

            if (file_exists($controllerPath)) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }

        // 2. Tentukan Path File Controller
        $fileController = __DIR__ . '/../Controllers/' . $this->controller . '.php';

        // 3. Validasi keberadaan file controller ada atau tidak
        if (!file_exists($fileController)) 
        {
            die("<b>Error MVC:</b> File controller <code>" . $this->controller . ".php</code> tidak ditemukan di folder <code>app/Controllers/</code>.");
        }

        // 4. Muat file controller
        require_once $fileController;

        // 5. Validasi Keberadaan Class
        $controllerClass = 'App\\Controllers\\' . $this->controller;
        if (!class_exists($controllerClass)) 
        {
            die("<b>Error MVC:</b> Class <code>" . $this->controller . "</code> tidak ditemukan di dalam file <code>" . $this->controller . ".php</code>.");
        }

        // 6. Deteksi method dari URL
        if (isset($url[1])) {
            if (method_exists($controllerClass, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 7. Ambil Params/ID jika ada
        if (!empty($url)) {
            $this->params = array_values($url);
        }

        // 8. Instansiasi Controller & Jalankan Method
        $controllerInstance = new $controllerClass;
        call_user_func_array([$controllerInstance, $this->method], $this->params);
    }

    public function parseURL() {
        // Mengecek apakah ada url?=....
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/'); // Menghilangkan garis miring di akhir bila setelah tidak ada
            $url = filter_var($url, FILTER_SANITIZE_URL); // Mengfilter karakter aneh ke dalam uri web
            return explode('/', $url); //mengembalikan array dengan pemisah slash
        }
        return [];
    }
}
