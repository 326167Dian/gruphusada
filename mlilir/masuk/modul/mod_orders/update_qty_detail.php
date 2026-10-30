<?php
session_start();
header('Content-Type: application/json');
include "../../../configurasi/koneksi.php";

if (empty($_SESSION['username']) and empty($_SESSION['passuser'])) {
    echo json_encode(['success' => false, 'message' => 'Belum login']);
    exit;
}

$id_dtrbmasuk = intval($_POST['id_dtrbmasuk']);
$qty = is_numeric($_POST['qty_dtrbmasuk']) ? $_POST['qty_dtrbmasuk'] : 0;

if ($qty <= 0) {
    echo json_encode(['success' => false, 'message' => 'Qty harus lebih besar dari 0']);
    exit;
}

$cek = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT hrgsat_dtrbmasuk FROM ordersdetail WHERE id_dtrbmasuk='$id_dtrbmasuk'");
$row = mysqli_fetch_array($cek);

if (!$row) {
    echo json_encode(['success' => false, 'message' => 'Detail tidak ditemukan']);
    exit;
}

$hrgttl = $row['hrgsat_dtrbmasuk'] * $qty;

$success = (bool) mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE ordersdetail SET
                                    qty_dtrbmasuk = '$qty',
                                    qtygrosir_dtrbmasuk = '$qty',
                                    hrgttl_dtrbmasuk = '$hrgttl'
                                    WHERE id_dtrbmasuk = '$id_dtrbmasuk'");

echo json_encode([
    'success'  => $success,
    'hrgttl'   => $hrgttl
]);
