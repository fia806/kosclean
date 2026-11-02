<?php
/**
 * Class AsetModel
 * Model untuk mengelola tabel 'aset_persediaan' & Perhitungan Penyusutan Garis Lurus (Depresiasi SAK)
 */
class AsetModel
{
    private string $table = 'aset_persediaan';
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Mengambil seluruh data aset & persediaan
     */
    public function getAll(): array
    {
        $this->db->query("SELECT * FROM {$this->table} ORDER BY kategori ASC, nama_barang ASC");
        return $this->db->resultSet();
    }

    /**
     * Mengambil satu data aset
     */
    public function getById(int $id_aset)
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE id_aset = :id_aset LIMIT 1");
        $this->db->bind(':id_aset', $id_aset);
        return $this->db->single();
    }

    /**
     * Menghitung dan Membukukan Penyusutan Garis Lurus Bulanan Seluruh Aset Peralatan (TRIGGER AKUNTANSI 4)
     * Rumus: Penyusutan Bulanan = (Harga Beli - Nilai Sisa) / Umur Ekonomis (Bulan)
     * Jurnal: Debet: Beban Penyusutan Peralatan (513) | Kredit: Akumulasi Penyusutan (122)
     *
     * @param string $periodeBulan Format 'YYYY-MM'
     * @param int|null $adminId
     * @return array Ringkasan pemrosesan ['total_aset' => int, 'total_nominal' => float, 'jurnal_ids' => array]
     */
    public function processMonthlyDepreciation(string $periodeBulan, ?int $adminId = null): array
    {
        // Ambil hanya aset yang memiliki harga beli dan umur ekonomis (kategori alat_kebersihan)
        $sql = "SELECT * FROM {$this->table} 
                WHERE harga_beli > 0 AND umur_ekonomis > 0 AND kategori = 'alat_kebersihan'";
        $this->db->query($sql);
        $asets = $this->db->resultSet();

        $accountingModel = new AccountingModel();
        $processedCount = 0;
        $totalNominal = 0;
        $jurnalIds = [];

        foreach ($asets as $aset) {
            $hargaBeli     = (float)$aset['harga_beli'];
            $nilaiSisa     = (float)($aset['nilai_sisa'] ?? 0);
            $umurEkonomis  = (int)$aset['umur_ekonomis']; // dalam bulan

            if ($umurEkonomis <= 0 || $hargaBeli <= $nilaiSisa) {
                continue;
            }

            // Rumus Garis Lurus Bulanan
            $depresiasiBulanan = round(($hargaBeli - $nilaiSisa) / $umurEkonomis, 2);

            $referensi = "DEP-{$periodeBulan}-ASET-{$aset['id_aset']}";

            // Cek idempotensi: Jangan jurnal dua kali untuk aset yang sama di bulan yang sama
            $this->db->query("SELECT id_jurnal FROM jurnal_umum WHERE referensi = :ref LIMIT 1");
            $this->db->bind(':ref', $referensi);
            if ($this->db->single()) {
                continue; // Sudah pernah disusutkan untuk bulan ini
            }

            $details = [
                [
                    'kode_akun' => '513', // Beban Penyusutan Peralatan
                    'posisi'    => 'Debet',
                    'nominal'   => $depresiasiBulanan
                ],
                [
                    'kode_akun' => '122', // Akumulasi Penyusutan Peralatan
                    'posisi'    => 'Kredit',
                    'nominal'   => $depresiasiBulanan
                ]
            ];

            $deskripsi = "Penyusutan Bulanan " . $aset['nama_barang'] . " (" . $aset['kondisi'] . ") Periode " . $periodeBulan;

            $id_jurnal = $accountingModel->createJurnalEntry(date('Y-m-d'), $deskripsi, $referensi, $adminId, $details);

            if ($id_jurnal) {
                $processedCount++;
                $totalNominal += $depresiasiBulanan;
                $jurnalIds[] = $id_jurnal;
            }
        }

        return [
            'total_aset'    => $processedCount,
            'total_nominal' => $totalNominal,
            'jurnal_ids'    => $jurnalIds
        ];
    }
}

