<?php
/**
 * Class Home
 * Controller Landing Page & Informasi Publik Jasa Bersih Kos
 */
class Home extends Controller
{
    private LayananModel $layananModel;
    private TestimoniModel $testimoniModel;

    public function __construct()
    {
        $this->layananModel = $this->model('LayananModel');
        $this->testimoniModel = $this->model('TestimoniModel');
    }

    /**
     * Halaman Utama (Landing Page)
     */
    public function index()
    {
        $layanan = $this->layananModel->getActive();
        $testimoni = $this->testimoniModel->getPublished(6);

        $data = [
            'title'     => 'Jasa Bersih Kos Semarang - Datang ke Lokasi, Hasil Bersih Rapi.',
            'layanan'   => $layanan,
            'testimoni' => $testimoni
        ];

        $this->view('home/index', $data);
    }

    /**
     * Halaman Syarat & Ketentuan (Terms)
     */
    public function terms()
    {
        $data = [
            'title' => 'Syarat & Ketentuan Layanan - KosClean'
        ];

        $this->view('home/terms', $data);
    }
}
