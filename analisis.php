<?php
require_once 'config/database.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

// Fetch dimension averages
try {
    $stmtDimensi = $pdo->query("
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
    $dimensions = $stmtDimensi->fetchAll();

    // Arrays to store chart data
    $labels = [];
    $kinerjaData = [];
    $harapanData = [];
    $gapData = [];

    foreach ($dimensions as $dim) {
        $labels[] = explode(" (", $dim['nama_dimensi'])[0]; // Ambil nama depan saja
        $kinerjaData[] = round($dim['avg_kinerja'], 3);
        $harapanData[] = round($dim['avg_harapan'], 3);
        $gapData[] = round($dim['avg_gap'], 3);
    }
} catch (PDOException $e) {
    die("Gagal mengambil data analisis: " . $e->getMessage());
}
?>

<main class="main-content">
    <div class="page-header">
        <h2>Grafik Hasil Evaluasi</h2>
        <p>Visualisasi data hasil penelitian evaluasi kualitas layanan website InfoSumbar per dimensi WebQual 4.0 berdasarkan perbandingan nilai Persepsi dan Harapan pengguna.</p>
    </div>

    <!-- Chart Grid -->
    <div class="analysis-grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 30px;">
        <!-- Chart 1: Perbandingan Kinerja vs Harapan -->
        <div class="chart-card">
            <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 20px;">Grafik Nilai Persepsi dan Harapan per Dimensi</h3>
            <div style="height: 300px; position: relative;">
                <canvas id="comparisonChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: GAP score per dimensi -->
        <div class="chart-card">
            <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 20px;">Grafik Nilai GAP per Dimensi</h3>
            <div style="height: 300px; position: relative;">
                <canvas id="gapChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Ringkasan per Dimensi -->
    <h3 style="font-size: 1.3rem; font-weight: 600; margin-bottom: 20px;">Ringkasan Hasil per Dimensi</h3>
    <div class="card-grid" style="grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));">
        <?php foreach ($dimensions as $index => $dim): ?>
            <?php 
            $desc = '';
            $status = '';
            $color = '';
            
            if ($dim['kode_dimensi'] == 'US') {
                $color = '#475569'; // Slate Gray untuk Usability
                $desc = 'Menampilkan hasil penilaian responden terkait kemudahan penggunaan, kemudahan navigasi, dan daya tarik visual antarmuka website InfoSumbar.';
            } elseif ($dim['kode_dimensi'] == 'IQ') {
                $color = '#1e293b'; // Charcoal untuk Info Quality
                $desc = 'Menampilkan hasil penilaian responden terkait akurasi, ketepatan waktu, relevansi, dan kelayakan format informasi berita yang disajikan InfoSumbar.';
            } else {
                $color = '#991b1b'; // Deep Burgundy untuk Service Interaction
                $desc = 'Menampilkan hasil penilaian responden terkait reputasi website, keamanan data pribadi, dan kemudahan berinteraksi dengan pengelola InfoSumbar.';
            }

            $gapVal = round($dim['avg_gap'], 3);
            if ($gapVal >= 0) {
                $status = 'Sangat Memuaskan (Kinerja memenuhi/melebihi Harapan)';
                $statusColor = '#15803d'; // Forest Green
            } elseif ($gapVal > -0.5) {
                $status = 'Cukup Baik (Kesenjangan rendah)';
                $statusColor = '#0369a1'; // Ocean Blue (Ganti dari kuning)
            } else {
                $status = 'Perlu Perbaikan (Kesenjangan signifikan)';
                $statusColor = '#991b1b'; // Deep Burgundy (Ganti dari merah terang)
            }
            ?>
            <div class="stat-card" style="border-top: 4px solid <?php echo $color; ?>; justify-content: flex-start; gap: 15px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--text-dark);">
                        <?php echo htmlspecialchars($dim['kode_dimensi'] . ' - ' . explode(" (", $dim['nama_dimensi'])[0]); ?>
                    </h4>
                </div>
                <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5; min-height: 45px;">
                    <?php echo $desc; ?>
                </p>
                <div style="display: flex; gap: 15px; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); padding: 12px 0;">
                    <div>
                        <span style="display: block; font-size: 0.75rem; color: var(--text-muted);">Nilai Kinerja</span>
                        <strong style="font-size: 1.1rem; color: var(--text-dark);"><?php echo round($dim['avg_kinerja'], 3); ?></strong>
                    </div>
                    <div>
                        <span style="display: block; font-size: 0.75rem; color: var(--text-muted);">Nilai Harapan</span>
                        <strong style="font-size: 1.1rem; color: var(--text-dark);"><?php echo round($dim['avg_harapan'], 3); ?></strong>
                    </div>
                    <div style="margin-left: auto; text-align: right;">
                        <span style="display: block; font-size: 0.75rem; color: var(--text-muted);">Skor GAP</span>
                        <strong style="font-size: 1.1rem; color: <?php echo $statusColor; ?>;">
                            <?php echo ($gapVal > 0 ? '+' : '') . $gapVal; ?>
                        </strong>
                    </div>
                </div>
                <div>
                    <span style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 4px;">Status Kualitas:</span>
                    <span style="font-size: 0.85rem; font-weight: 700; color: <?php echo $statusColor; ?>;"><?php echo $status; ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Chart JS Setup Script -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Labels & Data from PHP
        const labels = <?php echo json_encode($labels); ?>;
        const kinerjaData = <?php echo json_encode($kinerjaData); ?>;
        const harapanData = <?php echo json_encode($harapanData); ?>;
        const gapData = <?php echo json_encode($gapData); ?>;

        // Chart 1: Comparison Bar Chart
        const ctxComp = document.getElementById('comparisonChart').getContext('2d');
        new Chart(ctxComp, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Nilai Persepsi',
                        data: kinerjaData,
                        backgroundColor: 'rgba(153, 27, 27, 0.8)', // Deep Burgundy (Marun)
                        borderColor: '#991b1b',
                        borderWidth: 1,
                        borderRadius: 6
                    },
                    {
                        label: 'Nilai Harapan',
                        data: harapanData,
                        backgroundColor: 'rgba(71, 85, 105, 0.8)', // Slate 600
                        borderColor: '#475569',
                        borderWidth: 1,
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5.0,
                        grid: { color: '#f1f5f9' },
                        ticks: { color: '#64748b', font: { family: 'Plus Jakarta Sans', weight: '600' } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#64748b', font: { family: 'Plus Jakarta Sans', weight: '600' } }
                    }
                },
                plugins: {
                    legend: {
                        labels: { color: '#334155', font: { family: 'Plus Jakarta Sans', weight: '700' } }
                    }
                }
            }
        });

        // Chart 2: GAP Chart
        const ctxGap = document.getElementById('gapChart').getContext('2d');
        new Chart(ctxGap, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Skor GAP',
                    data: gapData,
                    backgroundColor: gapData.map(v => v < 0 ? 'rgba(153, 27, 27, 0.8)' : 'rgba(21, 128, 61, 0.8)'), // Burgundy vs Forest Green
                    borderColor: gapData.map(v => v < 0 ? '#991b1b' : '#15803d'),
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        grid: { color: '#f1f5f9' },
                        ticks: { color: '#64748b', font: { family: 'Plus Jakarta Sans', weight: '600' } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#64748b', font: { family: 'Plus Jakarta Sans', weight: '600' } }
                    }
                },
                plugins: {
                    legend: {
                        labels: { color: '#334155', font: { family: 'Plus Jakarta Sans', weight: '700' } }
                    }
                }
            }
        });
    });
    </script>

    <?php require_once 'includes/footer.php'; ?>
</main>
