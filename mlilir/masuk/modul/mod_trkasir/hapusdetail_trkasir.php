<?php
session_start();
include "../../../configurasi/koneksi.php";

$conn = $GLOBALS["___mysqli_ston"];

$id_dtrkasir  = $_POST['id_dtrkasir'];

//ambil data
$ambildata=mysqli_query($conn, "SELECT id_dtrkasir, id_barang, qty_dtrkasir FROM trkasir_detail
WHERE id_dtrkasir='$id_dtrkasir'");
$r=mysqli_fetch_array($ambildata);

$id_barang = $r['id_barang'];
$qty_dtrkasir = $r['qty_dtrkasir'];

mysqli_begin_transaction($conn);

//update stok

$cekstok=mysqli_query($conn, "SELECT id_barang, stok_barang FROM barang
WHERE id_barang='$id_barang'");
$rst=mysqli_fetch_array($cekstok);

$stok_barang = $rst['stok_barang'];
$stokakhir = $stok_barang + $qty_dtrkasir;

$success = (bool) mysqli_query($conn, "UPDATE barang SET stok_barang = '$stokakhir' WHERE id_barang = '$id_barang'");

if ($success) {
	$success = (bool) mysqli_query($conn, "DELETE FROM trkasir_detail WHERE id_dtrkasir = '$id_dtrkasir'");
}

if ($success) {
	$success = (bool) mysqli_query($conn, "DELETE FROM komisi_pegawai WHERE id_dtrkasir = '$id_dtrkasir'");
}

if ($success) {
	mysqli_commit($conn);
	echo $stokakhir;
} else {
	mysqli_rollback($conn);
	echo "GAGAL";
}
?>
