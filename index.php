<?php
require_once 'config/database.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

// Fetch overview statistics
try {
    // 1. Total Indikator
    $stmtIndikator = $pdo->query("SELECT COUNT(*) as total FROM hasil_analisis");
    $totalIndikator = $stmtIndikator->fetch()['total'];

    // 2. Rata-rata Kinerja
    $stmtKinerja = $pdo->query("SELECT AVG(nilai_persepsi) as avg_kinerja FROM hasil_analisis");
    $avgKinerja = round($stmtKinerja->fetch()['avg_kinerja'], 3);

    // 3. Rata-rata Harapan
    $stmtHarapan = $pdo->query("SELECT AVG(nilai_harapan) as avg_harapan FROM hasil_analisis");
    $avgHarapan = round($stmtHarapan->fetch()['avg_harapan'], 3);

    // 4. Rata-rata GAP
    $stmtGap = $pdo->query("SELECT AVG(nilai_gap) as avg_gap FROM hasil_analisis");
    $avgGap = round($stmtGap->fetch()['avg_gap'], 3);

    // 5. 5 Indikator dengan GAP Terbesar (Prioritas Perbaikan Utama)
    $stmtPrioritas = $pdo->query("
        SELECT kode_indikator, nama_indikator, nilai_gap, nama_dimensi
        FROM hasil_analisis
        ORDER BY nilai_gap ASC
        LIMIT 5
    ");
    $prioritasList = $stmtPrioritas->fetchAll();

} catch (PDOException $e) {
    die("Gagal mengambil data statistik: " . $e->getMessage());
}
?>

<main class="main-content">
    <div class="page-header">
        <h2>Dashboard</h2>
        <p>Ringkasan hasil evaluasi kualitas layanan website InfoSumbar berdasarkan metode WebQual 4.0 dan Analisis GAP.</p>
    </div>

    <!-- Stat Cards Grid -->
    <div class="card-grid">
        <!-- Card 1: Total Indikator -->
        <div class="stat-card">
            <div class="stat-header">
                <span>TOTAL INDIKATOR</span>
                <div class="stat-icon icon-blue">
                    <i class="fa-solid fa-list-check"></i>
                </div>
            </div>
            <div class="stat-value"><?php echo $totalIndikator; ?></div>
            <div class="stat-footer">Pernyataan WebQual 4.0</div>
        </div>

        <!-- Card 2: Rerata Kinerja -->
        <div class="stat-card">
            <div class="stat-header">
                <span>RATA-RATA KINERJA</span>
                <div class="stat-icon icon-emerald">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <div class="stat-value"><?php echo number_format($avgKinerja, 3); ?> <span style="font-size: 1rem; color: var(--text-muted);">/ 5.00</span></div>
            <div class="stat-footer">Nilai Persepsi Rata-rata</div>
        </div>

        <!-- Card 3: Rerata Harapan -->
        <div class="stat-card">
            <div class="stat-header">
                <span>RATA-RATA HARAPAN</span>
                <div class="stat-icon icon-purple">
                    <i class="fa-solid fa-star"></i>
                </div>
            </div>
            <div class="stat-value"><?php echo number_format($avgHarapan, 3); ?> <span style="font-size: 1rem; color: var(--text-muted);">/ 5.00</span></div>
            <div class="stat-footer">Nilai Harapan Rata-rata</div>
        </div>

        <!-- Card 4: Rata-rata GAP -->
        <div class="stat-card">
            <div class="stat-header">
                <span>RATA-RATA GAP</span>
                <div class="stat-icon" style="background: rgba(153, 27, 27, 0.1); color: var(--primary);">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
            </div>
            <div class="stat-value" style="color: var(--primary);"><?php echo number_format($avgGap, 3); ?></div>
            <div class="stat-footer">Nilai Kesenjangan (GAP) Rata-rata</div>
        </div>
    </div>

    <div class="analysis-grid">
        <!-- Left: Summary explanation -->
        <div class="chart-card" style="display: flex; flex-direction: column; gap: 20px;">
            <h3 style="font-size: 1.2rem; font-weight: 600; background: linear-gradient(to right, #60a5fa, #a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                Ringkasan Hasil Evaluasi
            </h3>
            <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-muted);">
                Berdasarkan data hasil penelitian yang tersimpan di database, rata-rata nilai persepsi kualitas layanan website InfoSumbar tercatat sebesar <strong><?php echo number_format($avgKinerja, 3); ?></strong> dari skala 5.00, sementara rata-rata nilai harapan pengguna berada pada angka <strong><?php echo number_format($avgHarapan, 3); ?></strong>.
            </p>
            <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-muted);">
                Dari selisih kedua nilai tersebut, diperoleh rata-rata nilai GAP sebesar <strong style="color: var(--primary);"><?php echo number_format($avgGap, 3); ?></strong>. Nilai GAP yang <strong>negatif</strong> menunjukkan bahwa kualitas layanan website InfoSumbar secara umum <strong>belum sepenuhnya memenuhi harapan pengguna</strong>, sehingga terdapat ruang perbaikan yang perlu diperhatikan.
            </p>
            <div style="margin-top: 10px;">
                <a href="analisis.php" class="btn-action">
                    Lihat Grafik per Dimensi
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Right: Top 5 Improvement Priorities -->
        <div class="chart-card">
            <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 15px;">5 Indikator dengan GAP Terbesar</h3>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <?php foreach ($prioritasList as $row): ?>
                    <?php
                    // Menentukan warna badge berdasarkan prefix kode dimensi (selaras dengan data-evaluasi.php)
                    $kode_dimensi = substr($row['kode_indikator'], 0, 2);
                    $badgeClass = 'badge-blue';    // US → Biru
                    if ($kode_dimensi == 'IQ') {
                        $badgeClass = 'badge-purple';  // IQ → Ungu
                    } elseif ($kode_dimensi == 'SI' || $kode_dimensi == 'SQ') {
                        $badgeClass = 'badge-emerald'; // SI/SQ → Hijau
                    }
                    ?>
                    <div style="background: rgba(255, 255, 255, 0.02); border: 1px solid var(--border); border-radius: 8px; padding: 12px; display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <span class="badge <?php echo $badgeClass; ?>" style="align-self: flex-start;"><?php echo htmlspecialchars($row['kode_indikator']); ?></span>
                            <span style="font-size: 0.8rem; color: var(--text-muted); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.3;">
                                <?php echo htmlspecialchars($row['nama_indikator']); ?>
                            </span>
                        </div>
                        <div style="font-weight: 700; color: var(--primary); font-size: 1.05rem;">
                            <?php echo number_format($row['nilai_gap'], 3); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php require_once 'includes/footer.php'; ?>
</main>
