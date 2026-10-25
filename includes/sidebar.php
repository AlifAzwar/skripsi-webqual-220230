<?php
// Mengambil inisial nama admin untuk avatar
$admin_initial = '';
if (isset($_SESSION['admin_name'])) {
    $words = explode(" ", $_SESSION['admin_name']);
    foreach ($words as $w) {
        $admin_initial .= strtoupper(substr($w, 0, 1));
    }
    $admin_initial = substr($admin_initial, 0, 2);
} else {
    $admin_initial = 'AD';
}
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <h1>InfoSumbar</h1>
        <p>Evaluasi WebQual 4.0</p>
    </div>
    
    <ul class="sidebar-menu">
        <li class="menu-item <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
            <a href="index.php">
                <i class="fa-solid fa-chart-pie"></i>
                Dashboard Overview
            </a>
        </li>
        <li class="menu-item <?php echo ($current_page == 'data-evaluasi.php') ? 'active' : ''; ?>">
            <a href="data-evaluasi.php">
                <i class="fa-solid fa-table"></i>
                Data Hasil Evaluasi
            </a>
        </li>
        <li class="menu-item <?php echo ($current_page == 'analisis.php') ? 'active' : ''; ?>">
            <a href="analisis.php">
                <i class="fa-solid fa-chart-line"></i>
                Analisis & Grafik
            </a>
        </li>
        <li class="menu-item <?php echo ($current_page == 'laporan.php') ? 'active' : ''; ?>">
            <a href="laporan.php">
                <i class="fa-solid fa-file-invoice"></i>
                Laporan & Cetak
            </a>
        </li>
    </ul>
    
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">
                <?php echo htmlspecialchars($admin_initial); ?>
            </div>
            <div class="user-details">
                <h4><?php echo htmlspecialchars($_SESSION['admin_name']); ?></h4>
                <p>Role: Admin</p>
            </div>
        </div>
        <button onclick="window.location.href='auth/logout.php'" class="btn-logout">
            <i class="fa-solid fa-right-from-bracket"></i>
            Log Out
        </button>
    </div>
</aside>
