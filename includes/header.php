<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah admin sudah login, jika belum arahkan ke login.php
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: auth/login.php");
    exit;
}

// Menentukan halaman aktif untuk highlight menu
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Evaluasi WebQual 4.0 InfoSumbar</title>
    <!-- Google Fonts Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom Style Sheet with Cache Buster -->
    <link rel="stylesheet" href="assets/css/style.css?v=1.3">
    <!-- ChartJS for Graphic visualization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="app-container">
