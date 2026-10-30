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

// Resolusi No. Batch: kalau dipilih manual pakai batch itu (gagal kalau sisanya kurang),
// kalau dikosongkan pakai FEFO (batch dengan ED paling dekat yang sisanya cukup).
$no_batch = isset($_POST['no_batch']) ? trim($_POST['no_batch']) : '';
$resolvedExpDate = null;

if ($id_dtrkasir == "" || $id_dtrkasir == null) {
    $kd_barang_esc = mysqli_real_escape_string($conn, $kd_barang);

    if ($no_batch !== '') {
        $no_batch_esc = mysqli_real_escape_string($conn, $no_batch);
        $qBatch = mysqli_query($conn, "SELECT tb.exp_date,
                SUM(tb.qty_dtrbmasuk) - COALESCE((SELECT SUM(td.qty_dtrkasir) FROM trkasir_detail td WHERE td.kd_barang='$kd_barang_esc' AND td.no_batch='$no_batch_esc'), 0) AS sisa
            FROM trbmasuk_detail tb
            WHERE tb.kd_barang='$kd_barang_esc' AND tb.no_batch='$no_batch_esc'
            GROUP BY tb.exp_date");
        $rBatch = mysqli_fetch_array($qBatch);

        if (!$rBatch || $rBatch['sisa'] < $qty_dtrkasir) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => "Stok batch $no_batch tidak mencukupi"]);
            exit;
        }

        $expRaw = $rBatch['exp_date'];
        $resolvedExpDate = (!empty($expRaw) && $expRaw != '0000-00-00' && $expRaw != '1970-01-01') ? $expRaw : null;
    } else {
        $qList = mysqli_query($conn, "SELECT tb.no_batch, tb.exp_date,
                SUM(tb.qty_dtrbmasuk) - COALESCE(jual.terjual, 0) AS sisa
            FROM trbmasuk_detail tb
            LEFT JOIN (
                SELECT no_batch, SUM(qty_dtrkasir) AS terjual
                FROM trkasir_detail
                WHERE kd_barang='$kd_barang_esc' AND no_batch IS NOT NULL AND no_batch != ''
                GROUP BY no_batch
            ) jual ON jual.no_batch = tb.no_batch
            WHERE tb.kd_barang='$kd_barang_esc' AND tb.no_batch IS NOT NULL AND tb.no_batch != ''
            GROUP BY tb.no_batch, tb.exp_date
            HAVING sisa > 0
            ORDER BY (tb.exp_date IS NULL OR tb.exp_date IN ('0000-00-00','1970-01-01')) ASC, tb.exp_date ASC");

        while ($rB = mysqli_fetch_array($qList)) {
            if ($rB['sisa'] >= $qty_dtrkasir) {
                $no_batch = $rB['no_batch'];
                $expRaw = $rB['exp_date'];
                $resolvedExpDate = (!empty($expRaw) && $expRaw != '0000-00-00' && $expRaw != '1970-01-01') ? $expRaw : null;
                break;
            }
        }
        // Kalau tidak ada 1 batch pun yang sisanya cukup untuk seluruh qty,
        // no_batch dibiarkan kosong (tidak diatribusikan ke batch manapun).
    }
}

$no_batch_sql = ($no_batch !== '') ? "'" . mysqli_real_escape_string($conn, $no_batch) . "'" : "NULL";
$exp_date_sql = $resolvedExpDate ? "'" . mysqli_real_escape_string($conn, $resolvedExpDate) . "'" : "NULL";

mysqli_begin_transaction($conn);
$success = true;

if($id_dtrkasir == "" || $id_dtrkasir == null){

//cek apakah barang dengan batch yang sama sudah ada (batch beda = baris baru)
$cekdetail=mysqli_query($conn, "SELECT id_barang, kd_barang, kd_trkasir, id_dtrkasir, qty_dtrkasir
FROM trkasir_detail
WHERE kd_barang='$kd_barang' AND kd_trkasir='$kd_trkasir' AND IFNULL(no_batch,'') = IFNULL($no_batch_sql,'')");

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
										no_batch,
										exp_date,
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
										$no_batch_sql,
										$exp_date_sql,
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

header('Content-Type: application/json');
echo json_encode(['success' => $success, 'message' => $success ? '' : 'Gagal menyimpan detail', 'no_batch' => $no_batch]);
