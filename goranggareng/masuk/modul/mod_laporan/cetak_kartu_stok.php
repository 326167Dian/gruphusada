<?php
session_start();
include "../../../configurasi/koneksi.php";
require('../../assets/pdf/fpdf.php');
include "../../../configurasi/fungsi_indotgl.php";
include "../../../configurasi/fungsi_rupiah.php";


$id  = $_GET['id'];

$query1 = $db->query("SELECT * FROM barang WHERE kd_barang = '$id'");
$brg    = $query1->fetch_assoc();

$pdf = new FPDF("L","cm","A4");

$pdf->SetMargins(1,1,1);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(25.5,0.7,"KARTU STOK",0,10,'L');
$pdf->SetFont('Arial','',9);
$pdf->Cell(5.5,0.5,"Tanggal Cetak : ".date('d-m-Y h:i:s'),0,0,'L');
$pdf->Cell(5,0.5,"Dicetak Oleh : ".$_SESSION['namalengkap'],0,1,'L');

$pdf->Line(1,2.7,28.5,2.7); //horisontal bawah
$pdf->ln(1);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(2.5,0.5,"Nama Barang",0,0,'L');
$pdf->Cell(1,0.5,":",0,0,'C');
$pdf->Cell(5.5,0.5,$brg['nm_barang'],0,1,'L');

$pdf->SetFont('Arial','B',9);
$pdf->Cell(2.5,0.5,"Satuan Barang",0,0,'L');
$pdf->Cell(1,0.5,":",0,0,'C');
$pdf->Cell(5.5,0.5,$brg['sat_barang'],0,1,'L');

$pdf->ln(0.5);
$pdf->Cell(1, 0.7, 'NO', 1, 0, 'C');
$pdf->Cell(3, 0.7, 'Waktu', 1, 0, 'C');
$pdf->Cell(2, 0.7, 'Bulan', 1, 0, 'C');
$pdf->Cell(5, 0.7, 'Nomor Transaksi', 1, 0, 'C');
$pdf->Cell(3, 0.7, 'Qty Masuk (Pembelian)', 1, 0, 'C');
$pdf->Cell(3, 0.7, 'Qty Keluar (Penjualan)', 1, 0, 'C');
$pdf->Cell(3, 0.7, 'Batch', 1, 0, 'C');
$pdf->Cell(2.6, 0.7, 'ED', 1, 0, 'C');
$pdf->Cell(4, 0.7, 'Total (Qty Masuk - Qty Keluar)', 1, 1, 'C');
$pdf->SetFont('Arial','',8);
$no=1;

// Diambil langsung per baris trbmasuk_detail/trkasir_detail (bukan per kode transaksi)
// supaya transaksi dengan beberapa batch sekaligus tetap tampil sebagai baris terpisah.
$query2 = $db->query("SELECT id_dtrbmasuk AS id_detail, kd_trbmasuk AS kode_transaksi, waktu AS tgl_sekarang,
                            qty_dtrbmasuk AS msk, 0 AS klr, no_batch, exp_date
                        FROM trbmasuk_detail WHERE kd_barang = '$id'
                        UNION ALL
                        SELECT id_dtrkasir AS id_detail, kd_trkasir AS kode_transaksi, waktu AS tgl_sekarang,
                            0 AS msk, qty_dtrkasir AS klr, '' AS no_batch, NULL AS exp_date
                        FROM trkasir_detail WHERE kd_barang = '$id'
                        ORDER BY tgl_sekarang ASC, id_detail ASC");

$totalStok = 0;
while ($row = $query2->fetch_assoc()) {
        $msk = $row['msk'];
        $klr = $row['klr'];
        $totalStok += ($msk - $klr);

        $noBatch = $row['no_batch'] ?? '';
        $expDate = $row['exp_date'] ?? '';
        $expDateDisplay = (!empty($expDate) && $expDate != '0000-00-00' && $expDate != '1970-01-01') ? date('d-m-Y', strtotime($expDate)) : '';

    	$pdf->Cell(1, 0.7, $no, 1, 0, 'C');
        $pdf->Cell(3, 0.7, date('Y-m-d H:i:s', strtotime($row['tgl_sekarang'])), 1, 0, 'C');
        $pdf->Cell(2, 0.7, date('F', strtotime($row['tgl_sekarang'])), 1, 0, 'C');
        $pdf->Cell(5, 0.7, $row['kode_transaksi'], 1, 0, 'C');
        $pdf->Cell(3, 0.7, $msk, 1, 0, 'C');
        $pdf->Cell(3, 0.7, $klr, 1, 0, 'C');
        $pdf->Cell(3, 0.7, $noBatch, 1, 0, 'C');
        $pdf->Cell(2.6, 0.7, $expDateDisplay, 1, 0, 'C');
        $pdf->Cell(5, 0.7, $totalStok, 1, 1, 'C');

    	$no++;
}
$pdf->SetFont('Arial','',12);
$pdf->Cell(22.5, 0.7, 'Total Stok Barang', 1, 0, 'L');
$pdf->Cell(5.1, 0.7, $totalStok, 1, 0, 'C');

$pdf->Output("rekap_stokopname.pdf","I");
