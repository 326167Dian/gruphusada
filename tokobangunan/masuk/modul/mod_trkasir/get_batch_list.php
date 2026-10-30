<?php
session_start();
header('Content-Type: application/json');
include "../../../configurasi/koneksi.php";

if (empty($_SESSION['username']) and empty($_SESSION['passuser'])) {
    echo json_encode([]);
    exit;
}

$conn = $GLOBALS["___mysqli_ston"];

$kd_barang = mysqli_real_escape_string($conn, $_POST['kd_barang'] ?? '');

if ($kd_barang === '') {
    echo json_encode([]);
    exit;
}

// Sisa per batch = total masuk (trbmasuk_detail) - total keluar (trkasir_detail) untuk batch yang sama.
$q = mysqli_query($conn, "SELECT tb.no_batch, tb.exp_date,
        SUM(tb.qty_dtrbmasuk) - COALESCE(jual.terjual, 0) AS sisa
    FROM trbmasuk_detail tb
    LEFT JOIN (
        SELECT no_batch, SUM(qty_dtrkasir) AS terjual
        FROM trkasir_detail
        WHERE kd_barang = '$kd_barang' AND no_batch IS NOT NULL AND no_batch != ''
        GROUP BY no_batch
    ) jual ON jual.no_batch = tb.no_batch
    WHERE tb.kd_barang = '$kd_barang' AND tb.no_batch IS NOT NULL AND tb.no_batch != ''
    GROUP BY tb.no_batch, tb.exp_date
    HAVING sisa > 0
    ORDER BY (tb.exp_date IS NULL OR tb.exp_date IN ('0000-00-00','1970-01-01')) ASC, tb.exp_date ASC");

$batches = [];
while ($row = mysqli_fetch_array($q)) {
    $expDate = $row['exp_date'];
    $expValid = (!empty($expDate) && $expDate != '0000-00-00' && $expDate != '1970-01-01');
    $batches[] = [
        'no_batch' => $row['no_batch'],
        'exp_date' => $expValid ? $expDate : '',
        'exp_date_display' => $expValid ? date('d-m-Y', strtotime($expDate)) : '-',
        'sisa' => $row['sisa'] + 0,
    ];
}

echo json_encode($batches);
