<?php
/**
 * Fungsi bantu interpretasi hasil WebQual 4.0 untuk laporan.
 *
 * WebQual Index (WQI) dihitung sama seperti Tabel 4.19 pada skripsi:
 *   WQI = Σ(nilai_persepsi × nilai_harapan) / Σ(5 × nilai_harapan)
 * (weight score dibagi maximum score), lalu dikategorikan berdasarkan
 * Tabel 4.7 Interpretasi WebQual Index.
 */

// Potongan SQL untuk menghitung WQI keseluruhan dari tabel hasil_analisis
const SQL_WQI_KESELURUHAN = "(SELECT SUM(nilai_persepsi * nilai_harapan) / SUM(5 * nilai_harapan) FROM hasil_analisis)";

/**
 * Kategori WQI sesuai Tabel 4.7:
 * 0,00 - 0,19 Sangat Kurang Baik | 0,20 - 0,39 Kurang Baik | 0,40 - 0,59 Cukup Baik
 * 0,60 - 0,79 Baik | 0,80 - 1,00 Sangat Baik
 */
function interpretasiWQI($wqi): string
{
    if ($wqi === null) {
        return '-';
    }
    $w = round((float) $wqi, 2); // dibulatkan 2 desimal seperti pada skripsi
    if ($w >= 0.80) return 'Sangat Baik';
    if ($w >= 0.60) return 'Baik';
    if ($w >= 0.40) return 'Cukup Baik';
    if ($w >= 0.20) return 'Kurang Baik';
    return 'Sangat Kurang Baik';
}
