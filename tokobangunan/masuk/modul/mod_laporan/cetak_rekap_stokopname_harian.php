<?php
session_start();
include "../../../configurasi/koneksi.php";
require('../../assets/pdf/fpdf.php');
include "../../../configurasi/fungsi_indotgl.php";
include "../../../configurasi/fungsi_rupiah.php";


$shift      = $_GET['shift'];
$tgl        = $_GET['tgl'];

$pdf = new FPDF("L","cm","A4");

$pdf->SetMargins(1,1,1);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(25.5,0.7,"REKAP STOKOPNAME",0,10,'L');
$pdf->SetFont('Arial','',9);
$pdf->Cell(5.5,0.5,"Tanggal Cetak : ".date('d-m-Y h:i:s'),0,0,'L');
$pdf->Cell(5,0.5,"Dicetak Oleh : ".$_SESSION['namalengkap'],0,1,'L');
// $pdf->Cell(5.5,0.5,"Periode : ".tgl_indo($tgl_awal)." - ".tgl_indo($tgl_akhir),0,0,'L');
$pdf->Line(1,2.7,28.5,2.7); //horisontal bawah
$pdf->ln(0.5);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(1, 0.7, 'NO', 1, 0, 'C');
$pdf->Cell(3.5, 0.7, 'Kode', 1, 0, 'C');
$pdf->Cell(11, 0.7, 'Nama Barang', 1, 0, 'L');
$pdf->Cell(2.5, 0.7, 'Satuan', 1, 0, 'C');
$pdf->Cell(2, 0.7, 'SS', 1, 0, 'C');
$pdf->Cell(2, 0.7, 'SF', 1, 0, 'C');
$pdf->Cell(2, 0.7, '(SF-SS)', 1, 0, 'C');
$pdf->Cell(3.5, 0.7, 'Time', 1, 1, 'C');
$pdf->SetFont('Arial','',8);
$no=1;

$query = $db->query("SELECT * FROM stok_opname a 
            JOIN barang b ON a.id_barang = b.id_barang 
            WHERE a.shift='$shift' 
            AND a.tgl_stokopname = '$tgl' 
            ORDER BY b.nm_barang DESC");

while ($r = $query->fetch_assoc()) {

    	$pdf->Cell(1, 0.6, $no , 1, 0, 'C');
    	$pdf->Cell(3.5, 0.6, $r['kd_barang'],1, 0, 'L');
    	$pdf->Cell(11, 0.6, $r['nm_barang'],1, 0, 'L');
    	$pdf->Cell(2.5, 0.6, $r['sat_barang'], 1, 0,'C');
    	$pdf->Cell(2, 0.6, $r['stok_sistem'], 1, 0,'C');
    	$pdf->Cell(2, 0.6, $r['stok_fisik'], 1, 0,'C');
    	$pdf->Cell(2, 0.6, $r['selisih'], 1, 0,'C');
    	$pdf->Cell(3.5, 0.6, date("d M Y H:i:s", strtotime($r['tgl_current'])), 1, 1,'C');
    
    	$no++;
}

$pdf->SetFont('Arial','',10);
$pdf->Cell(5.5, 1, "",0,1,'L');
$pdf->Cell(5.5, 0.5, "Keterangan :",0,1,'L');
$pdf->SetFont('Arial','',8);
$pdf->Cell(5.5, 0.5, "- SS = Stok Sistem",0,1,'L');
$pdf->SetFont('Arial','',8);
$pdf->Cell(5.5, 0.5, "- SF = Stok Fisik",0,1,'L');

$pdf->Output("rekap_stokopname_harian.pdf","I");
