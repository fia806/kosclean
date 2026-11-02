<?php
/**
 * Class Controller
 * Base Controller yang mewarisi fungsi pemanggilan View dan Model
 */
class Controller
{
    /**
     * Memuat file View dan mengirimkan data ke dalamnya
     *
     * @param string $view Nama file view (contoh: 'home/index' atau 'pesanan/detail')
     * @param array $data Data asosiatif yang akan diekstrak menjadi variabel di dalam view
     * @return void
     */
    public function view(string $view, array $data = []): void
    {
        $viewFile = __DIR__ . '/../Views/' . $view . '.php';

        if (file_exists($viewFile)) {
            // Ekstrak data agar elemen array bisa diakses sebagai variabel langsung di view
            // Misal: $data['pesanan'] menjadi $pesanan
            extract($data);
            require_once $viewFile;
        } else {
            die("View <strong>" . htmlspecialchars($view) . "</strong> tidak ditemukan di: " . htmlspecialchars($viewFile));
        }
    }

    /**
     * Memuat dan menginstansiasi Model
     *
     * @param string $model Nama class model (contoh: 'UserModel', 'PesananModel')
     * @return object Instance dari model yang diminta
     */
    public function model(string $model): object
    {
        $modelFile = __DIR__ . '/../Models/' . $model . '.php';

        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        } else {
            die("Model <strong>" . htmlspecialchars($model) . "</strong> tidak ditemukan di: " . htmlspecialchars($modelFile));
        }
    }

    /**
     * Helper untuk melakukan redirect URL
     *
     * @param string $path Path tujuan relatif setelah BASE_URL
     * @return void
     */
    public function redirect(string $path): void
    {
        header('Location: ' . BASE_URL . '/' . ltrim($path, '/'));
        exit;
    }

    /**
     * Helper untuk mengembalikan respons JSON (berguna untuk API / Midtrans webhook)
     *
     * @param mixed $data Data yang akan di-encode ke JSON
     * @param int $statusCode HTTP Status Code
     * @return void
     */
    public function json($data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }
}
