<?php
/**
 * Class LaporanController
 * Controller untuk semua Laporan Keuangan SAK EMKM:
 *  - Mutasi Kas & Bank
 *  - Neraca Saldo (Trial Balance)
 *  - Laporan Laba / Rugi (Income Statement)
 *  - Neraca Keuangan (Balance Sheet)
 *  - Laporan Arus Kas (Cash Flow)
 */
class LaporanController extends Controller
{
    private AccountingModel $accountingModel;

    public function __construct()
    {
        require_role(['admin'], 'auth/login');
        $this->accountingModel = $this->model('AccountingModel');
    }

    /**
     * Menu / Dashboard Pusat Laporan
     */
    public function index()
    {
        $data = [
            'title' => 'Pusat Laporan Keuangan SAK - KosClean'
        ];
        $this->view('laporan/index', $data);
    }

    /**
     * Laporan Mutasi Kas & Bank
     * Saldo Awal + Kas Masuk - Kas Keluar = Saldo Akhir
     */
    public function mutasiKeuangan()
    {
        $startDate = $_GET['start'] ?? date('Y-m-01');
        $endDate   = $_GET['end']   ?? date('Y-m-d');

        $report = $this->accountingModel->getMutasiKeuangan($startDate, $endDate);

        $data = [
            'title'  => 'Mutasi Kas & Bank - KosClean',
            'report' => $report
        ];

        $this->view('laporan/mutasi_keuangan', $data);
    }

    /**
     * Neraca Saldo (Trial Balance)
     * SUM Debet & Kredit per akun COA — Grand Total harus seimbang
     */
    public function neracaSaldo()
    {
        $startDate = $_GET['start'] ?? date('Y-m-01');
        $endDate   = $_GET['end']   ?? date('Y-m-d');

        $report = $this->accountingModel->getNeracaSaldo($startDate, $endDate);

        $data = [
            'title'  => 'Neraca Saldo (Trial Balance) - KosClean',
            'report' => $report
        ];

        $this->view('laporan/neraca_saldo', $data);
    }

    /**
     * Laporan Laba / Rugi (Income Statement)
     * Total Pendapatan (kat. Pendapatan) - Total Beban (kat. Beban)
     */
    public function labaRugi()
    {
        $startDate = $_GET['start'] ?? date('Y-m-01');
        $endDate   = $_GET['end']   ?? date('Y-m-t');

        $report = $this->accountingModel->getLabaRugiReport($startDate, $endDate);

        $data = [
            'title'  => 'Laporan Laba / Rugi - KosClean',
            'report' => $report
        ];

        $this->view('laporan/laba_rugi', $data);
    }

    /**
     * Neraca Keuangan (Balance Sheet)
     * Harta = Kewajiban + Modal (+Laba Berjalan)
     */
    public function neraca()
    {
        $endDate = $_GET['end'] ?? date('Y-m-d');

        $report = $this->accountingModel->getNeracaKeuangan($endDate);

        $data = [
            'title'  => 'Neraca Keuangan (Balance Sheet) - KosClean',
            'report' => $report
        ];

        $this->view('laporan/neraca', $data);
    }

    /**
     * Laporan Arus Kas (Cash Flow Statement)
     * Pergerakan akun Kas Tunai (111) & Kas Bank (112)
     */
    public function arusKas()
    {
        $startDate = $_GET['start'] ?? date('Y-m-01');
        $endDate   = $_GET['end']   ?? date('Y-m-d');

        $report = $this->accountingModel->getCashFlowReport($startDate, $endDate);

        $data = [
            'title'  => 'Laporan Arus Kas - KosClean',
            'report' => $report
        ];

        $this->view('laporan/arus_kas', $data);
    }
}

