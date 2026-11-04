<?php
/**
 * Class Database
 * Database Wrapper PDO dengan Prepared Statements untuk keamanan dari SQL Injection.
 * Mendukung pola instansiasi langsung maupun Singleton.
 */
class Database
{
    private static ?Database $instance = null;

    private string $host = DB_HOST;
    private string $user = DB_USER;
    private string $pass = DB_PASS;
    private string $db_name = DB_NAME;
    private string $port = DB_PORT;
    private string $charset = DB_CHARSET;

    private ?PDO $dbh = null;
    private $stmt = null;

    public function __construct()
    {
        // Data Source Name (DSN)
        $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset={$this->charset}";

        $options = [
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            // Catat log jika diperlukan dan tampilkan pesan kesalahan terkontrol
            die("Koneksi Database Gagal: " . $e->getMessage());
        }
    }

    /**
     * Pola Singleton untuk mendapatkan satu instance Database bersama
     * @return Database
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Mempersiapkan statement SQL (Prepared Statement)
     *
     * @param string $sql Query SQL dengan placeholder (?, :named)
     * @return self
     */
    public function query(string $sql): self
    {
        $this->stmt = $this->dbh->prepare($sql);
        return $this;
    }

    /**
     * Melakukan binding nilai ke parameter query secara aman
     *
     * @param string|int $param Parameter placeholder (:nama / index angka 1)
     * @param mixed $value Nilai yang ingin di-bind
     * @param int|null $type Tipe data PDO::PARAM_* (otomatis jika null)
     * @return self
     */
    public function bind($param, $value, ?int $type = null): self
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
                    break;
            }
        }

        $this->stmt->bindValue($param, $value, $type);
        return $this;
    }

    /**
     * Menjalankan statement yang sudah dipersiapkan
     *
     * @param array|null $params Array parameter opsional untuk langsung di-execute
     * @return bool
     */
    public function execute(?array $params = null): bool
    {
        return $this->stmt->execute($params);
    }

    /**
     * Mengambil seluruh hasil query sebagai array asosiatif
     *
     * @return array
     */
    public function resultSet(): array
    {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    /**
     * Mengambil satu baris data saja sebagai array asosiatif
     *
     * @return array|false
     */
    public function single()
    {
        $this->execute();
        return $this->stmt->fetch();
    }

    /**
     * Menghitung jumlah baris yang terpengaruh (INSERT/UPDATE/DELETE/SELECT)
     *
     * @return int
     */
    public function rowCount(): int
    {
        return $this->stmt->rowCount();
    }

    /**
     * Mendapatkan ID baris terakhir yang di-insert (Auto Increment)
     *
     * @return string|false
     */
    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    /**
     * Memulai transaksi database (ACID)
     *
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->dbh->beginTransaction();
    }

    /**
     * Commit transaksi jika seluruh query berhasil
     *
     * @return bool
     */
    public function commit(): bool
    {
        return $this->dbh->commit();
    }

    /**
     * Batalkan (Rollback) transaksi jika terjadi error
     *
     * @return bool
     */
    public function rollBack(): bool
    {
        return $this->dbh->rollBack();
    }

    /**
     * Mendapatkan objek native PDO instance jika dibutuhkan
     *
     * @return PDO
     */
    public function getPdo(): PDO
    {
        return $this->dbh;
    }
}
