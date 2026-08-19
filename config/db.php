<?php
// Bertindak sebagai pengelola koneksi ke database MySQL menggunakan PDO.
class Database{
private $host = "localhost";
    private $db_name = "db_absensi";
    private $username = "root";
    private $password = ""; 
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,$this->username,$this->password
            );
            // Mengatur mode error ke Exception agar penanganan error query lebih akurat
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Koneksi Error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>