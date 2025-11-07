<?php
session_start();
include "../../../configurasi/koneksi.php";
require('../../assets/pdf/fpdf.php');
include "../../../configurasi/fungsi_indotgl.php";
include "../../../configurasi/fungsi_rupiah.php";


$tgl_awal = date('Y-m-d');
$tgl_akhir = date('Y-m-d', strtotime('-30 days', strtotime($tgl_awal)));

$pdf = new FPDF("L","cm","A4");

$pdf->SetMargins(1,1,1);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(25.5,0.7,"STOK KRITIS",0,10,'L');
$pdf->SetFont('Arial','',9);
$pdf->Cell(5.5,0.5,"Tanggal Cetak : ".date('d-m-Y h:i:s'),0,0,'L');
$pdf->Cell(5,0.5,"Dicetak Oleh : ".$_SESSION['namalengkap'],0,1,'L');
// $pdf->Cell(5.5,0.5,"Periode : ".tgl_indo($tgl_awal)." - ".tgl_indo($tgl_akhir),0,0,'L');
$pdf->Line(1,2.7,28.5,2.7); //horisontal bawah
$pdf->ln(0.5);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(1, 0.7, 'NO', 1, 0, 'C');
$pdf->Cell(2.5, 0.7, 'Kategori', 1, 0, 'L');
$pdf->Cell(11, 0.7, 'Nama Barang', 1, 0, 'L');
$pdf->Cell(2, 0.7, 'Qty/Stok', 1, 0, 'C');
$pdf->Cell(2, 0.7, 'T30', 1, 0, 'C');
$pdf->Cell(2, 0.7, 'Q30', 1, 0, 'C');
$pdf->Cell(2, 0.7, 'SFCMin', 1, 0, 'C');
$pdf->Cell(2, 0.7, 'SFCMax', 1, 0, 'C');
$pdf->Cell(3, 0.7, 'Satuan', 1, 1, 'C');
$pdf->SetFont('Arial','',8);
$no=1;


$query = $db->query("SELECT barang.*, COUNT(trkasir_detail.id_barang) AS total, SUM(trkasir_detail.qty_dtrkasir) AS qty_terjual
    FROM barang
    LEFT JOIN trkasir_detail ON trkasir_detail.id_barang = barang.id_barang
    LEFT JOIN trkasir ON trkasir.kd_trkasir = trkasir_detail.kd_trkasir
    WHERE trkasir.tgl_trkasir BETWEEN '$tgl_akhir' AND '$tgl_awal'
    GROUP BY barang.id_barang
    ORDER BY barang.nm_barang ASC");

$no = 1;
while ($r = $query->fetch_assoc()) {

    $id_barang      = $r['id_barang'];
    $pass1          = $r['total'];
    $stok           = $r['stok_barang'];
    $qty_terjual    = $r['qty_terjual'];
    $sfc            = $pass1 - $stok;
    $qfc            = $qty_terjual - $stok;

    // Status warna
    if ($pass1 <= 0) {
        $status = "MACET";
    } elseif ($pass1 <= 5) {
        $status = "SLOW";
    } elseif ($pass1 <= 10) {
        $status = "LANCAR";
    } else {
        $status = "LAKU";
    }

    if ($stok <= (0.25 * $pass1) && $pass1 > 0) {
    	$pdf->Cell(1, 0.6, $no , 1, 0, 'C');
    	$pdf->Cell(2.5, 0.6, $status,1, 0, 'L');
    	$pdf->Cell(11, 0.6, $r['nm_barang'],1, 0, 'L');
    	$pdf->Cell(2, 0.6, round($stok), 1, 0,'C');
    	$pdf->Cell(2, 0.6, round($pass1), 1, 0,'C');
    	$pdf->Cell(2, 0.6, round($qty_terjual), 1, 0,'C');
    	$pdf->Cell(2, 0.6, round($sfc), 1, 0,'C');
    	$pdf->Cell(2, 0.6, round($qfc), 1, 0,'C');
    	$pdf->Cell(3, 0.6, $r['sat_barang'], 1, 1,'C');
    
    	$no++;
    }
}


$pdf->Output("Laporan_stok_kritis.pdf","I");
