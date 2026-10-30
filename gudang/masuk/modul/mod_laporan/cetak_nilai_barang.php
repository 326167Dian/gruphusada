<?php
session_start();
include "../../../configurasi/koneksi.php";
require('../../assets/pdf/fpdf.php');
include "../../../configurasi/fungsi_indotgl.php";
include "../../../configurasi/fungsi_rupiah.php";

$pdf = new FPDF("L","cm","A4");

$pdf->SetMargins(1,1,1);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(25.5,0.7,"Nilai Barang",0,10,'L');
$pdf->SetFont('Arial','',9);
$pdf->Cell(5.5,0.5,"Tanggal Cetak : ".date('d-m-Y h:i:s'),0,0,'L');
$pdf->Cell(5,0.5,"Dicetak Oleh : ".$_SESSION['namalengkap'],0,1,'L');

$pdf->Line(1,2.7,28.5,2.7); //horisontal bawah

$pdf->ln(0.7);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(1, 0.7, 'NO', 1, 0, 'C');
$pdf->Cell(3.5, 0.7, 'Kode', 1, 0, 'C');
$pdf->Cell(9.5, 0.7, 'Nama Barang', 1, 0, 'C');
$pdf->Cell(1.5, 0.7, 'Qty/Stok', 1, 0, 'C');
$pdf->Cell(1.2, 0.7, 'T30', 1, 0, 'C');
$pdf->Cell(1.2, 0.7, 'T60', 1, 0, 'C');
$pdf->Cell(1.2, 0.7, 'Gr(%)', 1, 0, 'C');
$pdf->Cell(1.2, 0.7, 'Q30', 1, 0, 'C');
$pdf->Cell(2, 0.7, 'Satuan', 1, 0, 'C');
$pdf->Cell(2.6, 0.7, 'Harga Beli', 1, 0, 'C');
$pdf->Cell(2.6, 0.7, 'Nilai Barang', 1, 1, 'C');
$pdf->SetFont('Arial','',8);
$no=1;

$query2 = $db->query("
    SELECT 
        b.*,
        COALESCE(t30.count_t30, 0) AS t30,
        COALESCE(t30.q30, 0) AS q30,
        COALESCE(t60.count_t60, 0) AS t60,
        ROUND((t30.count_t30/(t60.count_t60-t30.count_t30)*100)-100) AS gr
    FROM barang b
    LEFT JOIN (
        SELECT td.id_barang, COUNT(*) AS count_t30, SUM(td.qty_dtrkasir) AS q30
        FROM trkasir_detail td
        JOIN trkasir t ON td.kd_trkasir = t.kd_trkasir
        WHERE t.tgl_trkasir BETWEEN '$tgl_akhir' AND '$tgl_awal'
        GROUP BY td.id_barang
    ) AS t30 ON b.id_barang = t30.id_barang
    LEFT JOIN (
        SELECT td.id_barang, COUNT(*) AS count_t60
        FROM trkasir_detail td
        JOIN trkasir t ON td.kd_trkasir = t.kd_trkasir
        WHERE t.tgl_trkasir BETWEEN '$tgl_60' AND '$tgl_awal'
        GROUP BY td.id_barang
    ) AS t60 ON b.id_barang = t60.id_barang
    WHERE b.kd_barang != '' 
");

while ($row = $query2->fetch_assoc()) {
    $t30 = $row['t30'] ?? 0;
    $t60 = $row['t60'] ?? 0;
    $selisih = $t60 - $t30;
    $gr = (is_null($row['gr']))? 0:$row['gr'];
    $q30 = $row['q30'] ?? 0;
    $nilai_barang = $row['hrgsat_barang'] * $row['stok_barang'];

    $pdf->Cell(1, 0.7, $no, 1, 0, 'C');
    $pdf->Cell(3.5, 0.7, $row['kd_barang'], 1, 0, 'L');
    $pdf->Cell(9.5, 0.7, $row['nm_barang'], 1, 0, 'L');
    $pdf->Cell(1.5, 0.7, round($row['stok_barang']), 1, 0, 'C');
    $pdf->Cell(1.2, 0.7, $t30, 1, 0, 'C');
    $pdf->Cell(1.2, 0.7, $t60, 1, 0, 'C');
    $pdf->Cell(1.2, 0.7, $gr, 1, 0, 'C');
    $pdf->Cell(1.2, 0.7, $q30, 1, 0, 'C');
    $pdf->Cell(2, 0.7, $row['sat_barang'], 1, 0, 'C');
    $pdf->Cell(2.6, 0.7, format_rupiah($row['hrgsat_barang']), 1, 0, 'R');
    $pdf->Cell(2.6, 0.7, format_rupiah($nilai_barang), 1, 1, 'R');
    
    $no++;
}

$pdf->Output("nilai_barang.pdf","I");
