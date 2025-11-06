<?php
session_start();
include "../../../configurasi/koneksi.php";
require('../../assets/pdf/fpdf.php');
include "../../../configurasi/fungsi_indotgl.php";
include "../../../configurasi/fungsi_rupiah.php";

$kdorders = $_GET['id'];




$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM orders WHERE kd_trbmasuk = '$kdorders' ");
$res = mysqli_fetch_array($query);
$alamat = $db->query("select * from supplier where id_supplier='$res[id_supplier]' ");
$alt = $alamat->fetch_array();
//ambil header
$ah = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM setheader ");
$rh = mysqli_fetch_array($ah);

$pdf = new FPDF("L", "cm", "A4");

$pdf->SetMargins(1, 0, 1);
$pdf->AliasNbPages();
$pdf->AddPage();

$text1 = substr($rh['satu'], 7,34);



$pdf->Image('../../images/logo.png',0.4,0.7,3,2.5,'');
$pdf->ln(0.5);
$pdf->SetFont('helvetica', 'B', 18);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(3, 0.6,'' , 0, 0, 'C');
$pdf->Cell(10, 0.6, 'APOTEK', 0, 1, 'C');
$pdf->Cell(15.5, 0.6, $text1, 0, 1, 'C');

$pdf->ln(0.1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(3, 0.4,'' , 0, 0, 'C');
$pdf->Cell(10, 0.5,$rh['enam'], 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(3, 0.4,'' , 0, 0, 'C');
$pdf->Cell(10, 0.5,$rh['dua'], 0, 1, 'C');
$pdf->Cell(3, 0.4,'' , 0, 0, 'C');
$pdf->Cell(10, 0.5,$rh['tiga'], 0, 1, 'C');

$pdf->SetLineWidth(0.15);
$pdf->Line(0.5, 3.5, 14.3, 3.5); //horisontal bawah

$pdf->ln(0.7);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(14, 0, 'SURAT PESANAN OBAT', 0, 0, 'C');

$pdf->ln(1);
$pdf->SetX(1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(2.5, 0, 'Nomor SP', 0, 0, 'L');
$pdf->Cell(0.5, 0, ':', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, 0, $kdorders, 0, 0, 'L');

$pdf->ln(0.5);
$pdf->SetX(1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(2.5, 0, 'Tanggal', 0, 0, 'L');
$pdf->Cell(0.5, 0, ':', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, 0, tgl_indo($res['tgl_trbmasuk']), 0, 0, 'L');

$pdf->ln(0.5);
$pdf->SetX(1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(2.5, 0, 'Kepada', 0, 0, 'L');
$pdf->Cell(0.5, 0, ':', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, 0, $res['nm_supplier'], 0, 0, 'L');

$pdf->ln(0.5);
$pdf->SetX(1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(2.5, 0, 'Alamat', 0, 0, 'L');
$pdf->Cell(0.5, 0, ':', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);

$text2 = substr($alt['alamat_supplier'], 0,45);
$text3 = substr($alt['alamat_supplier'], 45,90);


$pdf->Cell(10, 0, $text2, 0, 1, 'L');
$pdf->Cell(3, 0.7,'', 0, 0, 'L');
$pdf->Cell(10, 0.7, $text3, 0, 0, 'L');

$pdf->SetLineWidth(0);
$pdf->ln(0.8);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(1, 0.7, 'No.', 1, 0, 'C');
$pdf->Cell(6.5, 0.7, 'Nama Obat', 1, 0, 'C');
$pdf->Cell(1.5, 0.7, 'Satuan', 1, 0, 'C');
$pdf->Cell(1.5, 0.7, 'Jumlah', 1, 0, 'C');
$pdf->Cell(2.5, 0.7, 'Ket', 1, 0, 'C');
// $pdf->ln(0.7);
// $pdf->SetFont('Arial', '', 10);

$no = 1;
$query1 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT *
FROM ordersdetail
WHERE kd_trbmasuk = '$kdorders' ");

$pdf->ln(0.2);
$pdf->SetFont('Arial', '', 10);


$no = 1;
$query1 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT *
FROM ordersdetail
WHERE kd_trbmasuk = '$kdorders'");

while ($lihat = mysqli_fetch_array($query1)) {
    $qty = ($lihat['qtygrosir_dtrbmasuk'] == "") ? $lihat['qty_dtrbmasuk'] : $lihat['qtygrosir_dtrbmasuk'];
    $satuan = ($lihat['satgrosir_dtrbmasuk'] == "") ? $lihat['sat_dtrbmasuk'] : $lihat['satgrosir_dtrbmasuk'];
    
    $text1 = substr($lihat['nmbrg_dtrbmasuk'], 0,35);
    $text2 = substr($lihat['nmbrg_dtrbmasuk'], 35,70);
    $text3 = strlen($lihat['nmbrg_dtrbmasuk']);
    
    if ($text3 >35){
    $pdf->ln(0.5);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(1, 0.5, $no,'LTR', 0, 'C');
    $pdf->Cell(6.5, 0.5, $text1,'LTR', 0, 'L');
    $pdf->Cell(1.5, 0.5, $satuan,'LTR', 0, 'C');
    $pdf->Cell(1.5, 0.5, $qty,'LTR', 0, 'C');
    $pdf->Cell(2.5, 0.5, terbilang($qty),'LTR', 0, 'C');
    
    $pdf->ln(0.5);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(1, 0.5,'','LBR', 0, 'C');
    $pdf->Cell(6.5, 0.5,$text2,'LBR', 0, 'L');
    $pdf->Cell(1.5, 0.5,'','LBR', 0, 'C');
    $pdf->Cell(1.5, 0.5,'','LBR', 0, 'C');
    $pdf->Cell(2.5, 0.5,'','LBR', 0, 'C');
    
    }
    
    else{
    $pdf->ln(0.5);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(1, 0.5, $no, 1, 0, 'C');
    $pdf->Cell(6.5, 0.5, $text1, 1, 0, 'L');
    $pdf->Cell(1.5, 0.5, $satuan, 1, 0, 'C');
    $pdf->Cell(1.5, 0.5, $qty, 1, 0, 'C');
    $pdf->Cell(2.5, 0.5, terbilang($qty), 1, 0, 'C');}
    
    $no++;
}

$pdf->ln(1);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(5, 0, '', 0, 0, 'R');
$pdf->Cell(9, 0, 'Madiun, ' . tgl_indo(date("Y-m-d")), 0, 1, 'C');

$pdf->ln(0.4);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(5, 0, '', 0, 0, 'R');
$pdf->Cell(9, 0, 'Apoteker Pemesan,', 0, 0, 'C');

$pdf->ln(2.5);
$pdf->SetFont('Arial', 'BU', 10);
$pdf->Cell(5, 0, '', 0, 0, 'R');
$pdf->Cell(9, 0,$rh['lima'],0, 0, 'C');

$pdf->ln(0.4);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(5, 0, '', 0, 0, 'R');
$pdf->Cell(9, 0,$rh['tujuh'], 0, 0, 'C');

$pdf->Output("order".$res['tgl_trbmasuk'], "I");
