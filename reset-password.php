<?php
// File sementara untuk reset password admin
// HAPUS file ini setelah password berhasil direset!

require_once 'config/database.php';

$username   = 'admin';
$password   = 'admin123';
$newHash    = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("UPDATE admin SET password = :password WHERE username = :username");
    $stmt->execute([
        'password' => $newHash,
        'username' => $username,
    ]);

    if ($stmt->rowCount() > 0) {
        echo "<h2 style='color:green; font-family:sans-serif;'>✅ Password berhasil direset!</h2>";
        echo "<p style='font-family:sans-serif;'>Username: <strong>admin</strong><br>Password baru: <strong>admin123</strong></p>";
        echo "<p style='font-family:sans-serif;'>Silakan <a href='auth/login.php'>login di sini</a>.</p>";
        echo "<hr><p style='color:red; font-family:sans-serif;'>⚠️ Segera hapus file <strong>reset-password.php</strong> ini dari folder PROGRAM!</p>";
    } else {
        echo "<h2 style='color:orange; font-family:sans-serif;'>⚠️ User 'admin' tidak ditemukan di database.</h2>";
        echo "<p style='font-family:sans-serif;'>Pastikan database sudah ter-import dengan benar.</p>";
    }
} catch (PDOException $e) {
    echo "<h2 style='color:red; font-family:sans-serif;'>❌ Error: " . $e->getMessage() . "</h2>";
}
?>
