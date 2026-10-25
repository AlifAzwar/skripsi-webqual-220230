<?php
require_once 'config/database.php';
try {
    $stmt = $pdo->prepare("UPDATE admin SET nama_admin = 'Administrator' WHERE id_admin = 1");
    $stmt->execute();
    echo "SUCCESS: Updated admin name to Administrator. Rows affected: " . $stmt->rowCount() . "\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
