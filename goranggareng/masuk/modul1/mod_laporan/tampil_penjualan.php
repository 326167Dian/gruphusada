<?php
session_start();
include "../../../configurasi/koneksi.php";
require('../../assets/pdf/fpdf.php');
include "../../../configurasi/fungsi_indotgl.php";
include "../../../configurasi/fungsi_rupiah.php";


$tgl_awal = $_POST['tgl_awal'];
$tgl_akhir = $_POST['tgl_akhir'];
if ($_POST['shift']<4){
$shift = $_POST['shift'];}
else {
	$shift=("1,2");
}

$pdf = new FPDF("L","cm","A4");

$pdf->SetMargins(1,1,1);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(25.5,0.7,"LAPORAN PENJUALAN",0,10,'L');
$pdf->SetFont('Arial','',9);
$pdf->Cell(5.5,0.5,"Tanggal Cetak : ".date('d-m-Y h:i:s'),0,0,'L');
$pdf->Cell(5,0.5,"Dicetak Oleh : ".$_SESSION['namalengkap'],0,1,'L');
$pdf->Cell(5.5,0.5,"Periode : ".tgl_indo($tgl_awal)." - ".tgl_indo($tgl_akhir),0,0,'L');
$pdf->Cell(5.5,0.5,"Shift : ".$shift,0,0,'R');

$pdf->Line(1,2.7,28.5,2.7); //horisontal bawah
$pdf->ln(0.5);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(1, 0.7, 'NO', 1, 0, 'C');
$pdf->Cell(2.5, 0.7, 'Kode Barang', 1, 0, 'L');
$pdf->Cell(11, 0.7, 'Nama Barang', 1, 0, 'L');
$pdf->Cell(1.5, 0.7, 'Qty', 1, 0, 'R');
$pdf->Cell(2, 0.7, 'Satuan', 1, 0, 'C');
$pdf->Cell(3, 0.7, 'Total', 1, 1, 'R');
$pdf->SetFont('Arial','',8);
$no=1;


$query=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT 
trkasir_detail.kd_barang,
trkasir_detail.id_dtrkasir,
trkasir_detail.kd_trkasir,
trkasir.kd_trkasir,
trkasir.tgl_trkasir
FROM trkasir_detail 
JOIN trkasir ON trkasir_detail.kd_trkasir = trkasir.kd_trkasir
WHERE shift in ($shift) and trkasir.tgl_trkasir BETWEEN '$tgl_awal' AND '$tgl_akhir'
GROUP BY trkasir_detail.kd_barang");



while($lihat=mysqli_fetch_array($query)){

$query2=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT 
trkasir_detail.kd_barang,
trkasir_detail.id_dtrkasir,
trkasir_detail.kd_trkasir,
trkasir_detail.kd_barang,
SUM(trkasir_detail.qty_dtrkasir) as ttlqty,
SUM(trkasir_detail.hrgttl_dtrkasir) as ttlhrg,
trkasir.kd_trkasir,
trkasir.tgl_trkasir
FROM trkasir_detail 
JOIN trkasir ON trkasir_detail.kd_trkasir = trkasir.kd_trkasir
WHERE shift in ($shift) and trkasir_detail.kd_barang='$lihat[kd_barang]'
AND trkasir.tgl_trkasir BETWEEN '$tgl_awal' AND '$tgl_akhir'
ORDER BY trkasir_detail.id_dtrkasir ASC");

$r2=mysqli_fetch_array($query2);
$ttlqty = $r2['ttlqty'];
$ttlhrg = $r2['ttlhrg'];


$query3=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir_detail 
WHERE id_dtrkasir='$lihat[id_dtrkasir]'");
$r3=mysqli_fetch_array($query3);
$kd_barang = $r3['kd_barang'];
$nmbrg_dtrkasir = $r3['nmbrg_dtrkasir'];
$sat_dtrkasir = $r3['sat_dtrkasir'];

	$pdf->Cell(1, 0.6, $no , 1, 0, 'C');
	$pdf->Cell(2.5, 0.6, $kd_barang,1, 0, 'L');
	$pdf->Cell(11, 0.6, $nmbrg_dtrkasir, 1, 0,'L');
	$pdf->Cell(1.5, 0.6, $ttlqty, 1, 0,'R');
	$pdf->Cell(2, 0.6, $sat_dtrkasir, 1, 0,'C');
	$pdf->Cell(3, 0.6, format_rupiah($ttlhrg), 1, 1,'R');
	$no++;
}



/*
$koneksi = $GLOBALS["___mysqli_ston"];
$query = "SELECT
SUM(a.hrgttl_dtrkasir) as gttl
FROM trkasir_detail AS a
left join trkasir as b
on a.kd_trkasir = b.kd_trkasir
where b.tgl_trkasir BETWEEN  '$tgl_awal' AND '$tgl_akhir'";
$result = $koneksi -> query($query);
$row = $result -> fetch_array();
/*$query4=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT SUM(hrgttl_dtrkasir) AS gttl FROM trkasir_detail WHERE tgl_trkasir BETWEEN '$tgl_awal' AND '$tgl_akhir'");
$query5=mysqli_fetch_assoc($query4);
var_dump($query5); die();*/
$tamtot = mysqli_query($GLOBALS["___mysqli_ston"],"select * from carabayar order by urutan");
while ($tt=mysqli_fetch_array($tamtot)){
	$tcb= $db->query( "SELECT id_trkasir, kd_trkasir, SUM(ttl_trkasir) as ttlskrg1
                                        FROM trkasir WHERE tgl_trkasir='$tgl_awal' AND id_carabayar='$tt[id_carabayar]'");
	$tamtcb = $tcb->fetch_array();
	$dtamtcb = format_rupiah($tamtcb['ttlskrg1']);

	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(18, 0.7, $tt['nm_carabayar'], 1, 0, 'C');
	$pdf->Cell(3, 0.7,$dtamtcb , 1, 1, 'R');

}

$tothari = $db->query("SELECT id_trkasir, kd_trkasir, SUM(ttl_trkasir) as ttlskrg4                                                              
                                            FROM trkasir WHERE tgl_trkasir='$tgl_awal' and '$tgl_akhir'");
$arth = $tothari->fetch_array();
$akhir = format_rupiah($arth['ttlskrg4']);

$pdf->SetFont('Arial','B',9);
$pdf->Cell(18, 0.7, 'TOTAL', 1, 0, 'C');
$pdf->Cell(3, 0.7,$akhir , 1, 1, 'R');





$pdf->Output("Laporan_data_barang.pdf","I");

?>

