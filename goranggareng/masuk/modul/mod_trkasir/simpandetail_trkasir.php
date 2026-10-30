<?php
session_start();
include "../../../configurasi/koneksi.php";
include "../../../configurasi/fungsi_sinkronisasi.php";

$conn = $GLOBALS["___mysqli_ston"];

$kd_trkasir = $_POST['kd_trkasir'];
$id_dtrkasir = $_POST['id_dtrkasir'];
$id_barang = $_POST['id_barang'];
$beli = $db->query("SELECT * FROM barang WHERE id_barang='$id_barang'");
$rbeli = mysqli_fetch_array($beli);
$hrgbeli = $rbeli['hrgsat_barang'];

$kd_barang = $_POST['kd_barang'];
$nmbrg_dtrkasir = $_POST['nmbrg_dtrkasir'];
$qty_dtrkasir = $_POST['qty_dtrkasir'];
$sat_dtrkasir = $_POST['sat_dtrkasir'];
$hrgjual_dtrkasir = $_POST['hrgjual_dtrkasir'];
$indikasi = $_POST['indikasi'];
$komisi = $_POST['komisi_dtrkasir'];
$currentdate = date('Y-m-d',time());
$id_admin = $_POST['id_admin'];

if($qty_dtrkasir == ""){
$qty_dtrkasir = "1";
}else{

}

mysqli_begin_transaction($conn);
$success = true;

if($id_dtrkasir == "" || $id_dtrkasir == null){

//cek apakah barang sudah ada
$cekdetail=mysqli_query($conn, "SELECT id_barang, kd_barang, kd_trkasir, id_dtrkasir, qty_dtrkasir
FROM trkasir_detail
WHERE kd_barang='$kd_barang' AND kd_trkasir='$kd_trkasir'");

$ketemucekdetail=mysqli_num_rows($cekdetail);
$rcek=mysqli_fetch_array($cekdetail);
if ($ketemucekdetail > 0){

$id_dtrkasir = $rcek['id_dtrkasir'];
$qtylama = $rcek['qty_dtrkasir'];
$ttlqty = $qtylama + $qty_dtrkasir;
$ttlharga = $ttlqty * $hrgjual_dtrkasir;

$success = (bool) mysqli_query($conn, "UPDATE trkasir_detail SET qty_dtrkasir = '$ttlqty',
										hrgjual_dtrkasir = '$hrgjual_dtrkasir',
										hrgttl_dtrkasir = '$ttlharga'
										WHERE id_dtrkasir = '$id_dtrkasir' and kd_barang='$kd_barang'");

//update stok
if ($success) {
    $cekstok = mysqli_query($conn, "SELECT * FROM barang
    WHERE id_barang='$id_barang'");
    $rst = mysqli_fetch_array($cekstok);

    $stok_barang = $rst['stok_barang'];
    $stokakhir = (($stok_barang + $qtylama) - $ttlqty);

    $success = (bool) mysqli_query($conn, "UPDATE barang SET
        stok_barang = '$stokakhir'
        WHERE id_barang = '$id_barang'");
}

if ($success && $_SESSION['komisi']=='Y'){
    if($_SESSION['penjualansebelum']=='Y'){
        $ttlkomisi = $ttlqty * $komisi;
        $success = (bool) mysqli_query($conn, "UPDATE komisi_pegawai SET ttl_komisi = '$ttlkomisi'
        WHERE id_dtrkasir='$id_dtrkasir'");
    } else {
        $ttlkomisi = $ttlqty * $komisi;
        $success = (bool) mysqli_query($conn, "UPDATE komisi_pegawai SET ttl_komisi = '$ttlkomisi'
        WHERE id_dtrkasir='$id_dtrkasir' AND id_admin='$_SESSION[idadmin]'");
    }
}
}else{

$ttlharga = $qty_dtrkasir * $hrgjual_dtrkasir;
$profit = $hrgjual_dtrkasir - $hrgbeli;

$success = (bool) mysqli_query($conn, "INSERT INTO trkasir_detail(kd_trkasir,
										id_barang,
										kd_barang,
										nmbrg_dtrkasir,
										qty_dtrkasir,
										sat_dtrkasir,
                                        profit,
										hrgjual_dtrkasir,
										hrgttl_dtrkasir,
                                        idadmin)
								  VALUES('$kd_trkasir',
										'$id_barang',
										'$kd_barang',
										'$nmbrg_dtrkasir',
										'$qty_dtrkasir',
										'$sat_dtrkasir',
										'$profit',
										'$hrgjual_dtrkasir',
										'$ttlharga',
                                        '$id_admin')");

if ($success) {
	$insertid_dtrkasir = mysqli_insert_id($conn);

	//update stok
	$cekstok = mysqli_query($conn, "SELECT * FROM barang
	WHERE id_barang='$id_barang'");
	$rst = mysqli_fetch_array($cekstok);

	$stok_barang = $rst['stok_barang'];
	$stokakhir = $stok_barang - $qty_dtrkasir;

	$success = (bool) mysqli_query($conn, "UPDATE barang SET
		stok_barang = '$stokakhir'
		WHERE id_barang = '$id_barang'");
}

if ($success && $_SESSION['komisi']=='Y'){
    if($_SESSION['penjualansebelum']=='Y'){
        $ttlkomisi = $qty_dtrkasir * $komisi;
        $success = (bool) mysqli_query($conn, "INSERT INTO komisi_pegawai (kd_trkasir, id_dtrkasir, id_admin, ttl_komisi, tgl_komisi, status_komisi)
        VALUES('$kd_trkasir', '$insertid_dtrkasir', '$id_admin', '$ttlkomisi', '$currentdate', 'on')");
    } else {
        $ttlkomisi = $qty_dtrkasir * $komisi;
        $success = (bool) mysqli_query($conn, "INSERT INTO komisi_pegawai (kd_trkasir, id_dtrkasir, id_admin, ttl_komisi, tgl_komisi, status_komisi)
        VALUES('$kd_trkasir', '$insertid_dtrkasir', '$_SESSION[idadmin]', '$ttlkomisi', '$currentdate', 'on')");
    }
}
}

}else{
//
$cekdetail=mysqli_query($conn, "SELECT * FROM trkasir_detail
WHERE id_dtrkasir='$id_dtrkasir'");
$rcek=mysqli_fetch_array($cekdetail);
$id_dtrkasir = $rcek['id_dtrkasir'];
$qtylama = $rcek['qty_dtrkasir'];
$qtybaru = $qtylama + $qty_dtrkasir;
$ttlharga = $qtybaru * $hrgjual_dtrkasir;

$success = (bool) mysqli_query($conn, "UPDATE trkasir_detail SET qty_dtrkasir = '$qtybaru',
										hrgjual_dtrkasir = '$hrgjual_dtrkasir',
										hrgttl_dtrkasir = '$ttlharga'
										WHERE id_dtrkasir = '$id_dtrkasir'");

//update stok
if ($success) {
    $cekstok = mysqli_query($conn, "SELECT * FROM barang
    WHERE id_barang='$id_barang'");
    $rst = mysqli_fetch_array($cekstok);

    $stok_barang = $rst['stok_barang'];
    $stokakhir = (($stok_barang + $qtylama) - $qty_dtrkasir);

    $success = (bool) mysqli_query($conn, "UPDATE barang SET
        stok_barang = '$stokakhir'
        WHERE id_barang = '$id_barang'");
}

if ($success && $_SESSION['komisi']=='Y'){
    if($_SESSION['penjualansebelum']=='Y'){
        $ttlkomisi = $qtybaru * $komisi;
        $success = (bool) mysqli_query($conn, "UPDATE komisi_pegawai SET ttl_komisi = '$ttlkomisi'
        WHERE id_dtrkasir='$id_dtrkasir'");

    } else {
        $ttlkomisi = $qtybaru * $komisi;
        $success = (bool) mysqli_query($conn, "UPDATE komisi_pegawai SET ttl_komisi = '$ttlkomisi'
        WHERE id_dtrkasir='$id_dtrkasir' AND id_admin='$_SESSION[idadmin]'");
    }
}
}

if ($success) {
	mysqli_commit($conn);
	sinkronisasi_stok($kd_barang);
} else {
	mysqli_rollback($conn);
}
?>
