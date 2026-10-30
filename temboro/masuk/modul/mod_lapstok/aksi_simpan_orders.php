<?php

session_start();
include "../../../configurasi/koneksi.php";

$module      = $_GET['module'];
$act         = $_GET['act'];
$count       = $_POST['check'];
$id_supplier = $_POST['id_supplier'];

// cek apakah ada kode transaksi pesanan ON berdasarkan user
$cekkd = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM kdbm WHERE id_admin='$_SESSION[idadmin]' AND id_resto='pesan' AND stt_kdbm='ON'");
$ketemucekkd = mysqli_num_rows($cekkd);
$hcekkd = mysqli_fetch_array($cekkd);

if ($ketemucekkd > 0) {
    $kdtransaksi = $hcekkd['kd_trbmasuk'];
} else {
    $kdunik = date('dmyhis');
    $kdtransaksi = "ORD-" . $kdunik;
    mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO kdbm(kd_trbmasuk,id_resto,id_admin) VALUES('$kdtransaksi','pesan','$_SESSION[idadmin]')");
}

$tglharini = date('Y-m-d');
$ttl_trkasir = 0;

for ($i = 0; $i < count($count); $i++) {

    $getbarang = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang WHERE id_barang='$count[$i]'");
    $brg = mysqli_fetch_array($getbarang);

    $id_barang      = $brg['id_barang'];
    $kd_barang      = $brg['kd_barang'];
    $nm_barang      = $brg['nm_barang'];
    $qty_retail     = $brg['t30'] - $brg['stok_barang'];
    $sat_barang     = $brg['sat_barang'];
    $hrgsat_barang  = $brg['hrgsat_barang'];
    $ttl_harga      = $hrgsat_barang * $qty_retail;
    $ttl_trkasir    = $ttl_trkasir + $ttl_harga;

    mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO ordersdetail(kd_trbmasuk,
                                        id_barang,
                                        kd_barang,
                                        nmbrg_dtrbmasuk,
                                        qty_dtrbmasuk,
                                        sat_dtrbmasuk,
                                        hrgsat_dtrbmasuk,
                                        hrgttl_dtrbmasuk,
                                        satgrosir_dtrbmasuk,
                                        qtygrosir_dtrbmasuk)
                                  VALUES('$kdtransaksi',
                                        '$id_barang',
                                        '$kd_barang',
                                        '$nm_barang',
                                        '$qty_retail',
                                        '$sat_barang',
                                        '$hrgsat_barang',
                                        '$ttl_harga',
                                        '$sat_barang',
                                        '$qty_retail')");
}

$getsupplier = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM supplier WHERE id_supplier='$id_supplier'");
$supplier = mysqli_fetch_array($getsupplier);

mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO
                                                orders(id_resto,
                                                kd_trbmasuk,
                                                tgl_trbmasuk,
                                                id_supplier,
                                                nm_supplier,
                                                tlp_supplier,
                                                alamat_trbmasuk,
                                                ttl_trbmasuk,
                                                dp_bayar,
                                                sisa_bayar,
                                                ket_trbmasuk)
                                         VALUES('pesan',
                                                '$kdtransaksi',
                                                '$tglharini',
                                                '$id_supplier',
                                                '$supplier[nm_supplier]',
                                                '$supplier[tlp_supplier]',
                                                '$supplier[alamat_supplier]',
                                                '$ttl_trkasir',
                                                '0',
                                                '$ttl_trkasir',
                                                '')");

$id_trbmasuk = mysqli_insert_id($GLOBALS["___mysqli_ston"]);

if (!$id_trbmasuk) {
    $cekorders = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id_trbmasuk FROM orders WHERE kd_trbmasuk='$kdtransaksi'");
    $rorders = mysqli_fetch_array($cekorders);
    $id_trbmasuk = $rorders['id_trbmasuk'];
}

header('location:../../media_admin.php?module=orders&act=ubah&id=' . $id_trbmasuk);
