<?php
require_once 'config/database.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

// Fetch summary metrics
try {
    $stmtSummary = $pdo->query("
        SELECT 
            (SELECT COUNT(*) FROM hasil_analisis) as total_indikator,
            (SELECT AVG(nilai_persepsi) FROM hasil_analisis) as avg_kinerja,
            (SELECT AVG(nilai_harapan) FROM hasil_analisis) as avg_harapan,
            (SELECT AVG(nilai_gap) FROM hasil_analisis) as avg_gap
    ");
    $summary = $stmtSummary->fetch();

    // Fetch dimension-level results
    $stmtDim = $pdo->query("
        SELECT nama_dimensi,
               CASE 
                   WHEN nama_dimensi LIKE 'Usability%' THEN 'US'
                   WHEN nama_dimensi LIKE 'Information Quality%' THEN 'IQ'
                   ELSE 'SI'
               END as kode_dimensi,
               AVG(nilai_persepsi) as avg_kinerja, 
               AVG(nilai_harapan) as avg_harapan, 
               AVG(nilai_gap) as avg_gap
        FROM hasil_analisis
        GROUP BY nama_dimensi
        ORDER BY FIELD(kode_dimensi, 'US', 'IQ', 'SI')
    ");
    $dimRows = $stmtDim->fetchAll();

    // Fetch latest report
    $stmtReport = $pdo->query("
        SELECT l.*, a.nama_admin 
        FROM laporan l
        JOIN admin a ON l.id_admin = a.id_admin
        ORDER BY l.id_laporan DESC
        LIMIT 1
    ");
    $reportData = $stmtReport->fetch();

} catch (PDOException $e) {
    die("Gagal mengambil data laporan: " . $e->getMessage());
}
?>

<main class="main-content">
    <div class="page-header">
        <h2>Laporan Hasil Evaluasi</h2>
        <p>Ringkasan data hasil evaluasi kualitas layanan website InfoSumbar berdasarkan WebQual 4.0 yang tersimpan di database, disajikan dalam format laporan terstruktur.</p>
    </div>

    <!-- Print Action Card -->
    <div class="table-card" style="display: flex; justify-content: space-between; align-items: center; gap: 20px;">
        <div>
            <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 5px;">Cetak Laporan</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted);">Sajikan data hasil evaluasi yang tersimpan di database dalam format laporan PDF yang rapi dan siap cetak.</p>
        </div>
        <a href="cetak-laporan.php" target="_blank" class="btn-action">
            <i class="fa-solid fa-print"></i>
            Cetak Laporan (PDF)
        </a>
    </div>

    <!-- Document structure preview -->
    <div class="table-card" style="background: rgba(255, 255, 255, 0.02); border-style: dashed; padding: 30px;">
        <div style="max-width: 800px; margin: 0 auto; background: #ffffff; color: #000000; border-radius: 4px; padding: 45px; box-shadow: 0 4px 20px rgba(0,0,0,0.2); font-family: 'Inter', Arial, sans-serif;">
            <!-- Mock Document Header -->
            <?php 
            $judul = $reportData ? $reportData['judul_laporan'] : 'LAPORAN HASIL EVALUASI KUALITAS LAYANAN';
            $dibuat = $reportData ? $reportData['nama_admin'] : 'Administrator';
            $periode = $reportData ? $reportData['periode'] : 'Juli 2026';
            $tanggal = $reportData ? date('d-m-Y', strtotime($reportData['tanggal_laporan'])) : date('d-m-Y');
            ?>
            <div style="text-align: center; border-bottom: 3px double #000000; padding-bottom: 15px; margin-bottom: 25px;">
                <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0; color: #000000; text-transform: uppercase; letter-spacing: 0.5px;"><?php echo htmlspecialchars($judul); ?></h2>
                <h3 style="font-size: 1rem; font-weight: 600; margin: 6px 0 0 0; color: #111827;">WEBSITE INFOSUMBAR (WEBQUAL 4.0)</h3>
                <p style="font-size: 0.8rem; margin: 8px 0 0 0; color: #374151;">Periode: <?php echo htmlspecialchars($periode); ?> &nbsp;|&nbsp; Tanggal: <?php echo $tanggal; ?> &nbsp;|&nbsp; Dibuat Oleh: <?php echo htmlspecialchars($dibuat); ?></p>
            </div>

            <!-- Summary Table Section -->
            <div style="margin-bottom: 25px;">
                <h4 style="font-size: 0.9rem; font-weight: 700; color: #000000; text-transform: uppercase; border-bottom: 1px solid #000000; padding-bottom: 5px; margin-bottom: 12px;">1. RINGKASAN METRIK EVALUASI</h4>
                <table style="width: 100%; border-collapse: collapse; margin-top: 5px; font-size: 0.85rem; color: #000000; border: 1px solid #000000;">
                    <thead>
                        <tr style="background: #ffffff; color: #000000; text-align: left;">
                            <th style="padding: 9px 12px; border: 1px solid #000000; font-weight: 700;">Metrik Pengukuran</th>
                            <th style="padding: 9px 12px; border: 1px solid #000000; text-align: center; font-weight: 700; width: 160px;">Hasil Rata-Rata</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="background: #ffffff;">
                            <td style="padding: 8px 12px; border: 1px solid #000000;">Jumlah Indikator WebQual 4.0</td>
                            <td style="padding: 8px 12px; border: 1px solid #000000; text-align: center; font-weight: bold;"><?php echo $summary['total_indikator']; ?> Butir</td>
                        </tr>
                        <tr style="background: #f9fafb;">
                            <td style="padding: 8px 12px; border: 1px solid #000000;">Skor Kinerja Aktual (Perceived Quality)</td>
                            <td style="padding: 8px 12px; border: 1px solid #000000; text-align: center; font-weight: bold;"><?php echo number_format($summary['avg_kinerja'], 3); ?></td>
                        </tr>
                        <tr style="background: #ffffff;">
                            <td style="padding: 8px 12px; border: 1px solid #000000;">Skor Harapan Pengguna (Expected Quality)</td>
                            <td style="padding: 8px 12px; border: 1px solid #000000; text-align: center; font-weight: bold;"><?php echo number_format($summary['avg_harapan'], 3); ?></td>
                        </tr>
                        <tr style="background: #f9fafb;">
                            <td style="padding: 8px 12px; border: 1px solid #000000;">Skor Kesenjangan (GAP)</td>
                            <td style="padding: 8px 12px; border: 1px solid #000000; text-align: center; font-weight: bold;"><?php echo number_format($summary['avg_gap'], 3); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Dimension Table Section -->
            <div style="margin-bottom: 25px;">
                <h4 style="font-size: 0.9rem; font-weight: 700; color: #000000; text-transform: uppercase; border-bottom: 1px solid #000000; padding-bottom: 5px; margin-bottom: 12px;">2. REKAPITULASI PER DIMENSI</h4>
                <table style="width: 100%; border-collapse: collapse; margin-top: 5px; font-size: 0.85rem; color: #000000; border: 1px solid #000000;">
                    <thead>
                        <tr style="background: #ffffff; color: #000000; text-align: left;">
                            <th style="padding: 9px 12px; border: 1px solid #000000; font-weight: 700;">Dimensi WebQual 4.0</th>
                            <th style="padding: 9px 12px; border: 1px solid #000000; text-align: center; font-weight: 700; width: 110px;">Rerata Kinerja</th>
                            <th style="padding: 9px 12px; border: 1px solid #000000; text-align: center; font-weight: 700; width: 110px;">Rerata Harapan</th>
                            <th style="padding: 9px 12px; border: 1px solid #000000; text-align: center; font-weight: 700; width: 100px;">Skor GAP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dimRows as $idx => $row): ?>
                            <tr style="background: <?php echo ($idx % 2 == 0) ? '#ffffff' : '#f9fafb'; ?>;">
                                <td style="padding: 8px 12px; border: 1px solid #000000;"><?php echo htmlspecialchars(explode(" (", $row['nama_dimensi'])[0]); ?></td>
                                <td style="padding: 8px 12px; border: 1px solid #000000; text-align: center; font-weight: bold;"><?php echo number_format($row['avg_kinerja'], 3); ?></td>
                                <td style="padding: 8px 12px; border: 1px solid #000000; text-align: center; font-weight: bold;"><?php echo number_format($row['avg_harapan'], 3); ?></td>
                                <td style="padding: 8px 12px; border: 1px solid #000000; text-align: center; font-weight: bold;"><?php echo number_format($row['avg_gap'], 3); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php require_once 'includes/footer.php'; ?>
</main>
