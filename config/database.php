<?php
// Configuration database connection details
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Sesuaikan dengan password database local Anda (default XAMPP kosong)
define('DB_NAME', 'db_webqual_infosumbar');

try {
    // Membuat koneksi database menggunakan PDO
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    
    // Mengatur PDO error mode ke Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Mengatur default fetch mode ke Associative Array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    // Jika koneksi gagal, tampilkan pesan error
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
