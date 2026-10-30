<?php
error_reporting(0);
session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
include "../../../configurasi/koneksi.php";
include "../../../configurasi/fungsi_thumb.php";
include "../../../configurasi/library.php";

$module= "trbmasuk";
$stt_aksi=$_POST['stt_aksi'];
if($stt_aksi == "input_trbmasuk" || $stt_aksi == "ubah_trbmasuk"){
$act=$stt_aksi;
}else{
$act=$_GET['act'];
}

$conn = $GLOBALS["___mysqli_ston"];

// Input admin
if ($module=='trbmasuk' AND $act=='input_trbmasuk'){

	mysqli_begin_transaction($conn);

    $success = (bool) mysqli_query($conn, "INSERT INTO
										trbmasuk(id_resto,
										kd_trbmasuk,
										tgl_trbmasuk,
										id_supplier,
										petugas,
										nm_supplier,
										tlp_supplier,
										alamat_trbmasuk,
										ttl_trbmasuk,
										dp_bayar,
										sisa_bayar,
										ket_trbmasuk,
										carabayar,
										jenis)
								 VALUES('pusat',
										'$_POST[kd_trbmasuk]',
										'$_POST[tgl_trbmasuk]',
										'$_POST[id_supplier]',
										'$_POST[petugas]',
										'$_POST[nm_supplier]',
										'$_POST[tlp_supplier]',
										'$_POST[alamat_trbmasuk]',
										'$_POST[ttl_trkasir]',
										'$_POST[dp_bayar]',
										'$_POST[sisa_bayar]',
										'$_POST[ket_trbmasuk]',
										'$_POST[carabayar]',
										'nonpbf'
										)");

	if ($success) {
		$tgl_sekarang = date('Y-m-d H:i:s', time());
		$success = (bool) mysqli_query($conn, "INSERT IGNORE INTO kartu_stok(kode_transaksi, tgl_sekarang) VALUES('$_POST[kd_trbmasuk]','$tgl_sekarang')");
	}

	if ($success) {
		$success = (bool) mysqli_query($conn, "UPDATE kdbm SET stt_kdbm = 'OFF' WHERE id_admin = '$_SESSION[idadmin]' AND id_resto = 'pusat' AND kd_trbmasuk = '$_POST[kd_trbmasuk]'");
	}

	if ($success) {
		mysqli_commit($conn);
	} else {
		mysqli_rollback($conn);
	}

	//echo "<script type='text/javascript'>alert('Transkasi berhasil ditambahkan !');window.location='../../media_admin.php?module=".$module."'</script>";


}
 //updata trbmasuk
 elseif ($module=='trbmasuk' AND $act=='ubah_trbmasuk'){


    mysqli_query($conn, "UPDATE trbmasuk SET tgl_trbmasuk = '$_POST[tgl_trbmasuk]',
									id_supplier = '$_POST[id_supplier]',
									nm_supplier = '$_POST[nm_supplier]',
									tlp_supplier = '$_POST[tlp_supplier]',
									alamat_trbmasuk = '$_POST[alamat_trbmasuk]',
									ttl_trbmasuk = '$_POST[ttl_trkasir]',
									dp_bayar = '$_POST[dp_bayar]',
									sisa_bayar = '$_POST[sisa_bayar]',
									ket_trbmasuk = '$_POST[ket_trbmasuk]',
									carabayar = '$_POST[carabayar]'
									WHERE id_trbmasuk = '$_POST[id_trbmasuk]'");
										
										
	//echo "<script type='text/javascript'>alert('Transkasi berhasil Ubah !');window.location='../../media_admin.php?module=".$module."'</script>";
	
}
//Hapus Proyek
elseif ($module=='trbmasuk' AND $act=='hapus'){

  mysqli_begin_transaction($conn);
  $success = true;

  //update bagian stok dulu
  //ambil data induk
	$ambildatainduk=mysqli_query($conn, "SELECT id_trbmasuk, kd_trbmasuk FROM trbmasuk
	WHERE id_trbmasuk='$_GET[id]'");
	$r1=mysqli_fetch_array($ambildatainduk);
	$kd_trbmasuk = $r1['kd_trbmasuk'];

	//loop data detail
	//ambil data induk
	$ambildatadetail=mysqli_query($conn, "SELECT id_dtrbmasuk, kd_trbmasuk, id_barang, qty_dtrbmasuk FROM trbmasuk_detail WHERE kd_trbmasuk='$kd_trbmasuk'");
	while ($success && ($r=mysqli_fetch_array($ambildatadetail))){

	$id_dtrbmasuk = $r['id_dtrbmasuk'];
	$id_barang = $r['id_barang'];
	$qty_dtrbmasuk = $r['qty_dtrbmasuk'];

	//update stok
	$cekstok=mysqli_query($conn, "SELECT id_barang, stok_barang FROM barang
	WHERE id_barang='$id_barang'");
	$rst=mysqli_fetch_array($cekstok);

	$stok_barang = $rst['stok_barang'];
	$stokakhir = $stok_barang - $qty_dtrbmasuk;

	$success = (bool) mysqli_query($conn, "UPDATE barang SET stok_barang = '$stokakhir' WHERE id_barang = '$id_barang'");

	if ($success) {
		$success = (bool) mysqli_query($conn, "DELETE FROM trbmasuk_detail WHERE id_dtrbmasuk = '$id_dtrbmasuk'");
	}

	}

  if ($success) {
	  $success = (bool) mysqli_query($conn, "DELETE FROM trbmasuk WHERE id_trbmasuk = '$_GET[id]'");
  }
  if ($success) {
	  $success = (bool) mysqli_query($conn, "DELETE FROM kartu_stok WHERE kode_transaksi = '$kd_trbmasuk'");
  }

  if ($success) {
	  mysqli_commit($conn);
	  echo "<script type='text/javascript'>alert('Data berhasil dihapus !');window.location='../../media_admin.php?module=".$module."'</script>";
  } else {
	  mysqli_rollback($conn);
	  echo "<script type='text/javascript'>alert('Data gagal dihapus, transaksi dibatalkan !');window.location='../../media_admin.php?module=".$module."'</script>";
  }
}
elseif ($module=='trbmasuk' AND $act=='dataawal'){
	mysqli_begin_transaction($conn);
	$success = true;

    $kdunik = date('dmyHis');
	$kdtransaksi = "BMP-" . $kdunik;
	$cekkd2 = mysqli_query($conn, "SELECT * FROM kdbm WHERE kd_trbmasuk='$kdtransaksi'");
	$ketemucekkd2 = mysqli_num_rows($cekkd2);
	if($ketemucekkd2 > 0){
	    $kdunik2 = date('dmyHis') + 1;
	    $kdtransaksi = "BMP-" . $kdunik2;
	}
	$success = (bool) mysqli_query($conn, "INSERT INTO kdbm(kd_trbmasuk,id_resto,id_admin) VALUES('$kdtransaksi','pusat','$_SESSION[idadmin]')");
    $tgl_sekarang = date('Y-m-d',time());
    $tgl_datetime = date('Y-m-d H:i:s', time());

    $petugas = $_SESSION['namalengkap'];

    $grandtotal = 0;
    if ($success) {
	    $getbarang = mysqli_query($conn, "SELECT * FROM barang ORDER BY nm_barang ASC");
	    while ($success && ($brg = mysqli_fetch_array($getbarang))){
	        $ttlharga   = $brg['hrgsat_barang'] * $brg['stok_barang'];
	        $grandtotal = $grandtotal + $ttlharga;

	        $success = (bool) mysqli_query($conn, "INSERT INTO trbmasuk_detail(kd_trbmasuk,
										id_barang,
										kd_barang,
										nmbrg_dtrbmasuk,
										qty_dtrbmasuk,
										sat_dtrbmasuk,
										hrgsat_dtrbmasuk,
										hrgjual_dtrbmasuk,
										hrgttl_dtrbmasuk,
										no_batch,
										exp_date)
								  VALUES('$kdtransaksi',
										'$brg[id_barang]',
										'$brg[kd_barang]',
										'$brg[nm_barang]',
										'$brg[stok_barang]',
										'$brg[sat_barang]',
										'$brg[hrgsat_barang]',
										'$brg[hrgjual_barang]',
										'$ttlharga',
										'',
										'$exp_date')");
	    }
    }

    if ($success) {
	    $success = (bool) mysqli_query($conn, "INSERT INTO
										trbmasuk(id_resto,
										kd_trbmasuk,
										tgl_trbmasuk,
										id_supplier,
										petugas,
										nm_supplier,
										tlp_supplier,
										alamat_trbmasuk,
										ttl_trbmasuk,
										dp_bayar,
										sisa_bayar,
										ket_trbmasuk,
										carabayar,
										jenis)
								 VALUES('pusat',
										'$kdtransaksi',
										'$tgl_sekarang',
										'1',
										'$petugas',
										'stok awal',
										'',
										'',
										'$grandtotal',
										'$grandtotal',
										'',
										'Data Awal pindah dari Aulia Baru',
										'LUNAS',
										'nonpbf'
										)");
    }

	if ($success) {
	    $tgl_sekarang = date('Y-m-d H:i:s', time());
	    $success = (bool) mysqli_query($conn, "INSERT IGNORE INTO kartu_stok(kode_transaksi, tgl_sekarang) VALUES('$kdtransaksi','$tgl_datetime')");
	}

	if ($success) {
		$success = (bool) mysqli_query($conn, "UPDATE kdbm SET stt_kdbm = 'OFF' WHERE id_admin = '$_SESSION[idadmin]' AND id_resto = 'pusat' AND kd_trbmasuk = '$kdtransaksi'");
	}

	if ($success) {
		mysqli_commit($conn);
		echo "<script type='text/javascript'>alert('Data berhasil masuk !');window.location='../../media_admin.php?module=".$module."'</script>";
	} else {
		mysqli_rollback($conn);
		echo "<script type='text/javascript'>alert('Data gagal masuk, transaksi dibatalkan !');window.location='../../media_admin.php?module=".$module."'</script>";
	}
}
}
?>
