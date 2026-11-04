<?php
/**
 * Class UserModel
 * Model untuk merepresentasikan dan mengelola tabel 'users'
 *
 * Kolom tabel:
 * - id_user (PK)
 * - nama_lengkap
 * - no_telp
 * - email
 * - password
 * - alamat
 * - role ('admin', 'karyawan', 'pelanggan')
 * - created_at
 */
class UserModel
{
    private string $table = 'users';
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Mengambil semua user
     * @return array
     */
    public function getAll(): array
    {
        $this->db->query("SELECT id_user, nama_lengkap, no_telp, email, alamat, role, created_at FROM {$this->table} ORDER BY id_user DESC");
        return $this->db->resultSet();
    }

    /**
     * Mengambil user berdasarkan id_user
     * @param int $id_user
     * @return array|false
     */
    public function getById(int $id_user)
    {
        $this->db->query("SELECT id_user, nama_lengkap, no_telp, email, alamat, role, created_at FROM {$this->table} WHERE id_user = :id_user");
        $this->db->bind(':id_user', $id_user);
        return $this->db->single();
    }

    /**
     * Mengambil user lengkap beserta password hash berdasarkan email (untuk login)
     * @param string $email
     * @return array|false
     */
    public function getByEmail(string $email)
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    /**
     * Mengambil daftar user berdasarkan role ('admin', 'karyawan', 'pelanggan')
     * @param string $role
     * @return array
     */
    public function getByRole(string $role): array
    {
        $this->db->query("SELECT id_user, nama_lengkap, no_telp, email, alamat, role, created_at FROM {$this->table} WHERE role = :role ORDER BY nama_lengkap ASC");
        $this->db->bind(':role', $role);
        return $this->db->resultSet();
    }

    /**
     * Menambahkan user baru dengan password yang sudah di-hash
     * @param array $data Data user [nama_lengkap, no_telp, email, password, alamat, role]
     * @return int|false ID user baru atau false jika gagal
     */
    public function create(array $data)
    {
        $sql = "INSERT INTO {$this->table} (nama_lengkap, no_telp, email, password, alamat, role) 
                VALUES (:nama_lengkap, :no_telp, :email, :password, :alamat, :role)";

        $this->db->query($sql);
        $this->db->bind(':nama_lengkap', $data['nama_lengkap']);
        $this->db->bind(':no_telp', $data['no_telp']);
        $this->db->bind(':email', $data['email']);
        // Pastikan password di-hash dengan algoritma BCRYPT / Argon2ID jika belum di-hash
        $hashedPassword = password_get_info($data['password'])['algo'] !== 0 
            ? $data['password'] 
            : password_hash($data['password'], PASSWORD_DEFAULT);
        $this->db->bind(':password', $hashedPassword);
        $this->db->bind(':alamat', $data['alamat'] ?? null);
        $this->db->bind(':role', $data['role'] ?? 'pelanggan');

        if ($this->db->execute()) {
            return (int) $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Memperbarui profil data user (tanpa password)
     * @param int $id_user
     * @param array $data Data user baru
     * @return bool
     */
    public function update(int $id_user, array $data): bool
    {
        $sql = "UPDATE {$this->table} 
                SET nama_lengkap = :nama_lengkap, 
                    no_telp = :no_telp, 
                    email = :email, 
                    alamat = :alamat, 
                    role = :role 
                WHERE id_user = :id_user";

        $this->db->query($sql);
        $this->db->bind(':id_user', $id_user);
        $this->db->bind(':nama_lengkap', $data['nama_lengkap']);
        $this->db->bind(':no_telp', $data['no_telp']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':alamat', $data['alamat'] ?? null);
        $this->db->bind(':role', $data['role']);

        return $this->db->execute();
    }

    /**
     * Memperbarui password user
     * @param int $id_user
     * @param string $newPassword Plain text password baru
     * @return bool
     */
    public function updatePassword(int $id_user, string $newPassword): bool
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE {$this->table} SET password = :password WHERE id_user = :id_user";

        $this->db->query($sql);
        $this->db->bind(':id_user', $id_user);
        $this->db->bind(':password', $hashedPassword);

        return $this->db->execute();
    }

    /**
     * Menghapus user berdasarkan id_user
     * @param int $id_user
     * @return bool
     */
    public function delete(int $id_user): bool
    {
        $this->db->query("DELETE FROM {$this->table} WHERE id_user = :id_user");
        $this->db->bind(':id_user', $id_user);
        return $this->db->execute();
    }
}
