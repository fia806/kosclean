<?php
/**
 * Class App
 * Core Router untuk memparsing URL dan menjalankan Controller, Method, serta Parameter.
 * Pola URL: /Controller/Method/Param1/Param2/...
 */
class App
{
    /** @var mixed Controller default atau yang sedang aktif */
    protected $controller = 'Home';

    /** @var string Method default */
    protected $method = 'index';

    /** @var array Parameter yang diteruskan ke method */
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseURL();

        // 1. Evaluasi Controller
        if (!empty($url[0])) {
            $controllerName = ucfirst($url[0]);
            $controllerFile = __DIR__ . '/../Controllers/' . $controllerName . '.php';

            if (file_exists($controllerFile)) {
                $this->controller = $controllerName;
                unset($url[0]);
            } elseif (file_exists(__DIR__ . '/../Controllers/' . $controllerName . 'Controller.php')) {
                $this->controller = $controllerName . 'Controller';
                unset($url[0]);
            } else {
                // Controller tidak ditemukan -> Tampilkan 404
                http_response_code(404);
                echo "<h1>404 Not Found</h1><p>Controller <strong>" . htmlspecialchars($controllerName) . "</strong> tidak ditemukan.</p>";
                exit;
            }
        }

        // Muat file controller dan instansiasi
        require_once __DIR__ . '/../Controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // 2. Evaluasi Method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            } else {
                // Method tidak ditemukan -> Tampilkan 404
                http_response_code(404);
                echo "<h1>404 Not Found</h1><p>Method <strong>" . htmlspecialchars($url[1]) . "</strong> tidak ditemukan pada controller ini.</p>";
                exit;
            }
        }

        // 3. Evaluasi Parameter
        $this->params = !empty($url) ? array_values($url) : [];

        // 4. Eksekusi Controller, Method, dan kirimkan parameter
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * Memecah dan membersihkan URL dari query string $_GET['url']
     * @return array
     */
    private function parseURL(): array
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }

        return [];
    }
}
