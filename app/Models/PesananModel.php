<?php
/**
 * Class PesananModel
 * Model untuk merepresentasikan dan mengelola tabel 'pesanan' & 'pesanan_detail'
 *
 * Kolom tabel pesanan:
 * - id_pesanan (PK)
 * - id_pelanggan (FK -> users.id_user)
 * - tanggal_jadwal
 * - waktu_jadwal
 * - alamat_pembersihan
 * - patokan_lokasi
 * - total_harga
 * - metode_pembayaran ('cash', 'midtrans')
 * - status_pembayaran ('belum_bayar', 'dp_lunas', 'lunas')
 * - status_pesanan ('menunggu_konfirmasi', 'menunggu_jadwal', 'sedang_dikerjakan', 'selesai', 'dibatalkan')
 * - midtrans_snap_token
 * - created_at
 *
 * Relasi Layanan:
 * Disimpan di tabel pesanan_detail (id_pesanan, id_layanan, harga_satuan)
 */
class PesananModel
{
    private string $table = 'pesanan';
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Mengambil seluruh data pesanan lengkap dengan nama pelanggan dan nama layanan (JOIN pesanan_detail & layanan)
     */
    public function getAllWithDetails(): array
    {
        $sql = "SELECT p.*, 
                       u.nama_lengkap AS nama_pelanggan, 
                       u.no_telp AS telp_pelanggan, 
                       l.nama_layanan, 
                       pd.harga_satuan AS harga_dasar,
                       pd.id_layanan
                FROM {$this->table} p
                JOIN users u ON p.id_pelanggan = u.id_user
                LEFT JOIN pesanan_detail pd ON p.id_pesanan = pd.id_pesanan
                LEFT JOIN layanan l ON pd.id_layanan = l.id_layanan
                ORDER BY p.created_at DESC, p.id_pesanan DESC";

        $this->db->query($sql);
        return $this->db->resultSet();
    }

    /**
     * Mengambil detail satu pesanan berdasarkan id_pesanan
     */
    public function getByIdWithDetails(string $id_pesanan)
    {
        $sql = "SELECT p.*, 
                       u.nama_lengkap AS nama_pelanggan, 
                       u.no_telp AS telp_pelanggan, 
                       u.email AS email_pelanggan,
                       l.nama_layanan, 
                       pd.harga_satuan AS harga_dasar,
                       l.estimasi_waktu,
                       pd.id_layanan
                FROM {$this->table} p
                JOIN users u ON p.id_pelanggan = u.id_user
                LEFT JOIN pesanan_detail pd ON p.id_pesanan = pd.id_pesanan
                LEFT JOIN layanan l ON pd.id_layanan = l.id_layanan
                WHERE p.id_pesanan = :id_pesanan
                LIMIT 1";

        $this->db->query($sql);
        $this->db->bind(':id_pesanan', $id_pesanan);
        return $this->db->single();
    }

    /**
     * Mengambil seluruh pesanan milik pelanggan tertentu
     */
    public function getByPelangganId(int $id_pelanggan): array
    {
        $sql = "SELECT p.*, l.nama_layanan, pd.harga_satuan AS harga_dasar, pd.id_layanan
                FROM {$this->table} p
                LEFT JOIN pesanan_detail pd ON p.id_pesanan = pd.id_pesanan
                LEFT JOIN layanan l ON pd.id_layanan = l.id_layanan
                WHERE p.id_pelanggan = :id_pelanggan
                ORDER BY p.created_at DESC, p.id_pesanan DESC";

        $this->db->query($sql);
        $this->db->bind(':id_pelanggan', $id_pelanggan);
        return $this->db->resultSet();
    }

    /**
     * Mengambil pesanan berdasarkan status pesanan
     */
    public function getByStatusPesanan(string $status_pesanan): array
    {
        $sql = "SELECT p.*, u.nama_lengkap AS nama_pelanggan, l.nama_layanan, pd.id_layanan
                FROM {$this->table} p
                JOIN users u ON p.id_pelanggan = u.id_user
                LEFT JOIN pesanan_detail pd ON p.id_pesanan = pd.id_pesanan
                LEFT JOIN layanan l ON pd.id_layanan = l.id_layanan
                WHERE p.status_pesanan = :status_pesanan
                ORDER BY p.tanggal_jadwal ASC, p.waktu_jadwal ASC";

        $this->db->query($sql);
        $this->db->bind(':status_pesanan', $status_pesanan);
        return $this->db->resultSet();
    }

    /**
     * Membuat pesanan baru (Otomatis simpan ke pesanan & pesanan_detail)
     */
    public function create(array $data)
    {
        $id_pesanan = $data['id_pesanan'] ?? ('KBK-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4)));

        try {
            $this->db->beginTransaction();

            $sql = "INSERT INTO {$this->table} (
                        id_pesanan, id_pelanggan, tanggal_jadwal, waktu_jadwal, 
                        alamat_pembersihan, patokan_lokasi, total_harga, 
                        metode_pembayaran, status_pembayaran, status_pesanan, 
                        midtrans_snap_token
                    ) VALUES (
                        :id_pesanan, :id_pelanggan, :tanggal_jadwal, :waktu_jadwal, 
                        :alamat_pembersihan, :patokan_lokasi, :total_harga, 
                        :metode_pembayaran, :status_pembayaran, :status_pesanan, 
                        :midtrans_snap_token
                    )";

            $this->db->query($sql);
            $this->db->bind(':id_pesanan', $id_pesanan);
            $this->db->bind(':id_pelanggan', $data['id_pelanggan']);
            $this->db->bind(':tanggal_jadwal', $data['tanggal_jadwal']);
            $this->db->bind(':waktu_jadwal', $data['waktu_jadwal']);
            $this->db->bind(':alamat_pembersihan', $data['alamat_pembersihan']);
            $this->db->bind(':patokan_lokasi', $data['patokan_lokasi'] ?? null);
            $this->db->bind(':total_harga', $data['total_harga']);
            $this->db->bind(':metode_pembayaran', $data['metode_pembayaran'] ?? 'cash');
            $this->db->bind(':status_pembayaran', $data['status_pembayaran'] ?? 'belum_bayar');
            $this->db->bind(':status_pesanan', $data['status_pesanan'] ?? 'menunggu_konfirmasi');
            $this->db->bind(':midtrans_snap_token', $data['midtrans_snap_token'] ?? null);
            $this->db->execute();

            // Masukkan ke tabel pesanan_detail
            if (!empty($data['id_layanan'])) {
                $sqlDetail = "INSERT INTO pesanan_detail (id_pesanan, id_layanan, harga_satuan)
                              VALUES (:id_pesanan, :id_layanan, :harga_satuan)";
                $this->db->query($sqlDetail);
                $this->db->bind(':id_pesanan', $id_pesanan);
                $this->db->bind(':id_layanan', $data['id_layanan']);
                $this->db->bind(':harga_satuan', $data['total_harga']);
                $this->db->execute();
            }

            $this->db->commit();
            return $id_pesanan;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Memperbarui informasi jadwal & lokasi pesanan
     */
    public function update(string $id_pesanan, array $data): bool
    {
        try {
            $this->db->beginTransaction();

            $sql = "UPDATE {$this->table} 
                    SET tanggal_jadwal = :tanggal_jadwal,
                        waktu_jadwal = :waktu_jadwal,
                        alamat_pembersihan = :alamat_pembersihan,
                        patokan_lokasi = :patokan_lokasi,
                        total_harga = :total_harga
                    WHERE id_pesanan = :id_pesanan";

            $this->db->query($sql);
            $this->db->bind(':id_pesanan', $id_pesanan);
            $this->db->bind(':tanggal_jadwal', $data['tanggal_jadwal']);
            $this->db->bind(':waktu_jadwal', $data['waktu_jadwal']);
            $this->db->bind(':alamat_pembersihan', $data['alamat_pembersihan']);
            $this->db->bind(':patokan_lokasi', $data['patokan_lokasi'] ?? null);
            $this->db->bind(':total_harga', $data['total_harga']);
            $this->db->execute();

            if (!empty($data['id_layanan'])) {
                $sqlDetail = "UPDATE pesanan_detail SET id_layanan = :id_layanan, harga_satuan = :harga_satuan WHERE id_pesanan = :id_pesanan";
                $this->db->query($sqlDetail);
                $this->db->bind(':id_layanan', $data['id_layanan']);
                $this->db->bind(':harga_satuan', $data['total_harga']);
                $this->db->bind(':id_pesanan', $id_pesanan);
                $this->db->execute();
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Memperbarui status pengerjaan pesanan
     */
    public function updateStatusPesanan(string $id_pesanan, string $status_pesanan): bool
    {
        $sql = "UPDATE {$this->table} SET status_pesanan = :status_pesanan WHERE id_pesanan = :id_pesanan";
        $this->db->query($sql);
        $this->db->bind(':id_pesanan', $id_pesanan);
        $this->db->bind(':status_pesanan', $status_pesanan);
        return $this->db->execute();
    }

    /**
     * Memperbarui status pembayaran & token Midtrans
     */
    public function updateStatusPembayaran(string $id_pesanan, string $status_pembayaran, ?string $snap_token = null): bool
    {
        if ($snap_token !== null) {
            $sql = "UPDATE {$this->table} 
                    SET status_pembayaran = :status_pembayaran, midtrans_snap_token = :midtrans_snap_token 
                    WHERE id_pesanan = :id_pesanan";
            $this->db->query($sql);
            $this->db->bind(':midtrans_snap_token', $snap_token);
        } else {
            $sql = "UPDATE {$this->table} 
                    SET status_pembayaran = :status_pembayaran 
                    WHERE id_pesanan = :id_pesanan";
            $this->db->query($sql);
        }

        $this->db->bind(':id_pesanan', $id_pesanan);
        $this->db->bind(':status_pembayaran', $status_pembayaran);

        return $this->db->execute();
    }

    /**
     * Memperbarui status pembayaran sekaligus status pengerjaan pesanan
     */
    public function updateStatusPembayaranDanPesanan(string $id_pesanan, string $status_pembayaran, string $status_pesanan): bool
    {
        $sql = "UPDATE {$this->table} 
                SET status_pembayaran = :status_pembayaran, 
                    status_pesanan = :status_pesanan 
                WHERE id_pesanan = :id_pesanan";
        $this->db->query($sql);
        $this->db->bind(':status_pembayaran', $status_pembayaran);
        $this->db->bind(':status_pesanan', $status_pesanan);
        $this->db->bind(':id_pesanan', $id_pesanan);
        return $this->db->execute();
    }

    /**
     * Menyimpan Snap Token dari Midtrans
     */
    public function setSnapToken(string $id_pesanan, string $snapToken): bool
    {
        $sql = "UPDATE {$this->table} SET midtrans_snap_token = :snap_token WHERE id_pesanan = :id_pesanan";
        $this->db->query($sql);
        $this->db->bind(':id_pesanan', $id_pesanan);
        $this->db->bind(':snap_token', $snapToken);
        return $this->db->execute();
    }

    /**
     * Menghapus pesanan berdasarkan id_pesanan
     */
    public function delete(string $id_pesanan): bool
    {
        $this->db->query("DELETE FROM {$this->table} WHERE id_pesanan = :id_pesanan");
        $this->db->bind(':id_pesanan', $id_pesanan);
        return $this->db->execute();
    }
}
