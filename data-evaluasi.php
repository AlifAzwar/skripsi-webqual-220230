<?php
require_once 'config/database.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

// Fetch all evaluation data
try {
    $stmtData = $pdo->query("
        SELECT *
        FROM hasil_analisis
        ORDER BY id_hasil ASC
    ");
    $dataRows = $stmtData->fetchAll();
} catch (PDOException $e) {
    die("Gagal mengambil data evaluasi: " . $e->getMessage());
}
?>

<main class="main-content">
    <div class="page-header">
        <h2>Data Hasil Evaluasi</h2>
        <p>Tabel nilai rata-rata persepsi, harapan, dan skor GAP per indikator WebQual 4.0 berdasarkan data hasil penelitian.</p>
    </div>

    <!-- Table Card -->
    <div class="table-card">
        <div class="table-title">Daftar Hasil Evaluasi per Indikator</div>
        <table class="custom-table">
            <thead>
                <tr>
                    <th style="width: 70px; text-align: center;">Kode</th>
                    <th style="width: 160px;">Dimensi</th>
                    <th>Pernyataan Indikator</th>
                    <th style="width: 110px; text-align: center;">Nilai Persepsi</th>
                    <th style="width: 110px; text-align: center;">Nilai Harapan</th>
                    <th style="width: 90px; text-align: center;">Skor GAP</th>
                    <th style="width: 130px; text-align: center;">Kategori</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataRows as $row): ?>
                    <?php
                    // Menentukan badge kelas berdasarkan dimensi (diambil dari prefiks kode_indikator)
                    $kode_dimensi = substr($row['kode_indikator'], 0, 2);
                    $badgeClass = 'badge-blue';
                    if ($kode_dimensi == 'IQ') {
                        $badgeClass = 'badge-purple';
                    } elseif ($kode_dimensi == 'SI' || $kode_dimensi == 'SQ') {
                        $badgeClass = 'badge-emerald';
                    }

                    // Warna kategori kontras tinggi tanpa warna kuning mencolok (aman spasi & huruf kecil/besar)
                    $kategori = trim($row['kategori'] ?? '-');
                    $kategori_clean = strtolower($kategori);
                    if ($kategori_clean === 'perlu perbaikan') {
                        $kategoriColor = '#991b1b'; // Burgundy/Marun
                        $kategoriBg = '#fee2e2';    // Merah sangat lembut
                    } elseif ($kategori_clean === 'cukup baik') {
                        $kategoriColor = '#0369a1'; // Ocean Blue
                        $kategoriBg = '#e0f2fe';    // Biru sangat lembut
                    } else { // Sangat Memuaskan / Sangat Baik / Lainnya
                        $kategoriColor = '#15803d'; // Forest Green
                        $kategoriBg = '#dcfce7';    // Hijau sangat lembut
                    }
                    ?>
                    <tr>
                        <td style="text-align: center;">
                            <span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($row['kode_indikator']); ?></span>
                        </td>
                        <td style="font-size: 0.82rem; font-weight: 500; line-height: 1.4;">
                            <?php echo htmlspecialchars(explode(" (", $row['nama_dimensi'])[0]); ?>
                        </td>
                        <td style="color: var(--text-main); font-weight: 400; line-height: 1.5;">
                            <?php echo htmlspecialchars($row['nama_indikator']); ?>
                        </td>
                        <td style="text-align: center; font-weight: 600;">
                            <?php echo number_format($row['nilai_persepsi'], 3); ?>
                        </td>
                        <td style="text-align: center; font-weight: 600;">
                            <?php echo number_format($row['nilai_harapan'], 3); ?>
                        </td>
                        <td style="text-align: center; font-weight: 700;">
                            <?php echo ($row['nilai_gap'] > 0 ? '+' : '') . number_format($row['nilai_gap'], 3); ?>
                        </td>
                        <td style="text-align: center;">
                            <span style="
                                display: inline-block;
                                padding: 3px 10px;
                                border-radius: 20px;
                                font-size: 0.75rem;
                                font-weight: 600;
                                color: <?php echo $kategoriColor; ?>;
                                background: <?php echo $kategoriBg; ?>;
                                border: 1px solid <?php echo $kategoriColor; ?>33;
                            ">
                                <?php echo htmlspecialchars($kategori); ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php require_once 'includes/footer.php'; ?>
</main>
