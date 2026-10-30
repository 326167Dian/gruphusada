<?php
session_start();
header('Content-Type: application/json');
include "../../../configurasi/koneksi.php";

if (empty($_SESSION['username']) and empty($_SESSION['passuser'])) {
    echo json_encode(['success' => false, 'message' => 'Belum login']);
    exit;
}

$conn = $GLOBALS["___mysqli_ston"];

$id_dtrbmasuk = intval($_POST['id_dtrbmasuk']);
$qty = is_numeric($_POST['qty_dtrbmasuk']) ? $_POST['qty_dtrbmasuk'] : 0;

if ($qty <= 0) {
    echo json_encode(['success' => false, 'message' => 'Qty harus lebih besar dari 0']);
    exit;
}

$cek = mysqli_query($conn, "SELECT id_barang, qty_dtrbmasuk, hnasat_dtrbmasuk, diskon FROM trbmasuk_detail WHERE id_dtrbmasuk='$id_dtrbmasuk'");
$row = mysqli_fetch_array($cek);

if (!$row) {
    echo json_encode(['success' => false, 'message' => 'Detail tidak ditemukan']);
    exit;
}

$qtyLama = $row['qty_dtrbmasuk'];
$deltaQty = $qty - $qtyLama;
$faktordiskon = (1 - ($row['diskon'] / 100));
$hrgttl = $row['hnasat_dtrbmasuk'] * $qty * $faktordiskon;

mysqli_begin_transaction($conn);

$success = (bool) mysqli_query($conn, "UPDATE trbmasuk_detail SET
                                    qty_dtrbmasuk = '$qty',
                                    hrgttl_dtrbmasuk = '$hrgttl'
                                    WHERE id_dtrbmasuk = '$id_dtrbmasuk'");

if ($success) {
    $success = (bool) mysqli_query($conn, "UPDATE barang SET
                                        stok_barang = stok_barang + ($deltaQty)
                                        WHERE id_barang = '{$row['id_barang']}'");
}

if ($success) {
    mysqli_commit($conn);
} else {
    mysqli_rollback($conn);
}

echo json_encode([
    'success' => $success,
    'hrgttl'  => $hrgttl
]);
