<?php
session_start();
require_once 'config/database.php';

// Proteksi akses
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: auth/login.php");
    exit;
}

try {
    // 1. Ringkasan
    $stmtSummary = $pdo->query("
        SELECT 
            (SELECT COUNT(*) FROM hasil_analisis) as total_indikator,
            (SELECT AVG(nilai_persepsi) FROM hasil_analisis) as avg_kinerja,
            (SELECT AVG(nilai_harapan) FROM hasil_analisis) as avg_harapan,
            (SELECT AVG(nilai_gap) FROM hasil_analisis) as avg_gap
    ");
    $summary = $stmtSummary->fetch();

    // 2. Rekap per Dimensi
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

    // 3. Seluruh Indikator
    $stmtInd = $pdo->query("
        SELECT *,
               CASE 
                   WHEN nama_dimensi LIKE 'Usability%' THEN 'US'
                   WHEN nama_dimensi LIKE 'Information Quality%' THEN 'IQ'
                   ELSE 'SI'
               END as kode_dimensi
        FROM hasil_analisis
        ORDER BY id_hasil ASC
    ");
    $indRows = $stmtInd->fetchAll();

} catch (PDOException $e) {
    die("Gagal mengambil data cetak: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan - WebQual 4.0 InfoSumbar</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', Arial, sans-serif;
        }

        body {
            background: #f8fafc;
            color: #000000;
            padding: 40px 20px;
            font-size: 10pt;
            line-height: 1.5;
        }

        .document-wrapper {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .header {
            text-align: center;
            border-bottom: 3px double #000000;
            padding-bottom: 12px;
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 1.35rem;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 4px;
            color: #000000;
            letter-spacing: 0.5px;
        }

        .header h2 {
            font-size: 1.05rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 6px;
            color: #111827;
        }

        .header p {
            font-size: 0.78rem;
            color: #374151;
        }

        h3 {
            font-size: 0.95rem;
            font-weight: 700;
            text-transform: uppercase;
            margin-top: 22px;
            margin-bottom: 10px;
            border-bottom: 1px solid #000000;
            padding-bottom: 4px;
            color: #000000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9pt;
            color: #000000;
            border: 1px solid #000000;
        }

        th, td {
            border: 1px solid #000000;
            padding: 7px 10px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background-color: #ffffff;
            color: #000000;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 8.5pt;
            letter-spacing: 0.3px;
        }

        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: 700;
        }

        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
            page-break-inside: avoid;
        }

        .signature-box {
            width: 250px;
            text-align: center;
            font-size: 0.85rem;
        }

        .signature-space {
            height: 65px;
        }

        .no-print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 22px;
            background: #111827;
            color: white;
            border: 1px solid #000000;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: all 0.2s;
            z-index: 9999;
        }

        .no-print-btn:hover {
            background: #1f2937;
            transform: translateY(-1px);
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .document-wrapper {
                border: none;
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
            .no-print-btn {
                display: none !important;
            }
            th {
                background-color: #ffffff !important;
                color: #000000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            tbody tr:nth-child(even) {
                background-color: #f9fafb !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

<button onclick="window.print()" class="no-print-btn">🖨️ Cetak Dokumen / Simpan PDF</button>

<div class="document-wrapper">
    <!-- Kop Surat Laporan -->
    <div class="header">
        <h1>LAPORAN HASIL EVALUASI KUALITAS LAYANAN</h1>
        <h2>WEBSITE INFOSUMBAR BERDASARKAN METODE WEBQUAL 4.0</h2>
        <p>Tanggal Cetak: <?php echo date('d-m-Y H:i'); ?> WIB &nbsp;|&nbsp; Dicetak Oleh: <?php echo htmlspecialchars($_SESSION['admin_name']); ?></p>
    </div>

    <!-- Ringkasan Metrik -->
    <h3>1. Ringkasan Metrik Hasil Evaluasi</h3>
    <table>
        <thead>
            <tr>
                <th>Metrik Pengukuran</th>
                <th class="text-center" style="width: 160px;">Hasil Rata-Rata</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Jumlah Butir Indikator WebQual 4.0</td>
                <td class="text-center text-bold"><?php echo $summary['total_indikator']; ?> Indikator</td>
            </tr>
            <tr>
                <td>Rata-rata Skor Kinerja Aktual (Perceived Quality)</td>
                <td class="text-center text-bold"><?php echo number_format($summary['avg_kinerja'], 3); ?></td>
            </tr>
            <tr>
                <td>Rata-rata Skor Harapan Ideal (Expected Quality)</td>
                <td class="text-center text-bold"><?php echo number_format($summary['avg_harapan'], 3); ?></td>
            </tr>
            <tr>
                <td>Skor Kesenjangan Rata-rata (GAP)</td>
                <td class="text-center text-bold"><?php echo number_format($summary['avg_gap'], 3); ?></td>
            </tr>
        </tbody>
    </table>

    <!-- Hasil Per Dimensi -->
    <h3>2. Rekapitulasi Nilai Per Dimensi</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 60px;" class="text-center">Kode</th>
                <th>Dimensi WebQual 4.0</th>
                <th class="text-center" style="width: 110px;">Rerata Kinerja</th>
                <th class="text-center" style="width: 110px;">Rerata Harapan</th>
                <th class="text-center" style="width: 100px;">Skor GAP</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dimRows as $row): ?>
                <tr>
                    <td class="text-center text-bold"><?php echo htmlspecialchars($row['kode_dimensi']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama_dimensi']); ?></td>
                    <td class="text-center text-bold"><?php echo number_format($row['avg_kinerja'], 3); ?></td>
                    <td class="text-center text-bold"><?php echo number_format($row['avg_harapan'], 3); ?></td>
                    <td class="text-center text-bold"><?php echo number_format($row['avg_gap'], 3); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Rincian Seluruh Indikator -->
    <div style="page-break-before: always; padding-top: 10px;"></div>
    <div class="header" style="margin-bottom: 20px;">
        <h1>RINCIAN INDIKATOR EVALUASI</h1>
        <h2>WEBSITE INFOSUMBAR (WEBQUAL 4.0)</h2>
    </div>

    <h3>3. Rincian Skor per Butir Indikator WebQual 4.0</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 60px;" class="text-center">Kode</th>
                <th>Pernyataan Indikator Kualitas</th>
                <th style="width: 80px;" class="text-center">Kinerja</th>
                <th style="width: 80px;" class="text-center">Harapan</th>
                <th style="width: 80px;" class="text-center">GAP</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($indRows as $row): ?>
                <tr>
                    <td class="text-center text-bold"><?php echo htmlspecialchars($row['kode_indikator']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama_indikator']); ?></td>
                    <td class="text-center"><?php echo number_format($row['nilai_persepsi'], 3); ?></td>
                    <td class="text-center"><?php echo number_format($row['nilai_harapan'], 3); ?></td>
                    <td class="text-center text-bold"><?php echo number_format($row['nilai_gap'], 3); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
