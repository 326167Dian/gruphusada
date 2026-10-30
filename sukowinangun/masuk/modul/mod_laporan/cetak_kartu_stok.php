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

// Lebar kolom tabel (total = 27.6 cm, sesuai lebar halaman A4 landscape - margin)
$wNo = 1.0;
$wWaktu = 3.2;
$wBulan = 2.2;
$wNoTrans = 4.5;
$wMasuk = 2.6;
$wKeluar = 2.6;
$wBatch = 3.0;
$wEd = 2.0;
$wTotal = 6.5;

$pdf->ln(0.5);
$pdf->SetFont('Arial','B',8);
$headerLineH = 0.4;
$headerH = $headerLineH * 2;

$startX = $pdf->GetX();
$startY = $pdf->GetY();

$pdf->Cell($wNo, $headerH, 'NO', 1, 0, 'C');
$pdf->Cell($wWaktu, $headerH, 'Waktu', 1, 0, 'C');
$pdf->Cell($wBulan, $headerH, 'Bulan', 1, 0, 'C');
$pdf->Cell($wNoTrans, $headerH, 'Nomor Transaksi', 1, 0, 'C');

$x = $pdf->GetX();
$pdf->MultiCell($wMasuk, $headerLineH, "Qty Masuk\n(Pembelian)", 1, 'C');
$pdf->SetXY($x + $wMasuk, $startY);

$x = $pdf->GetX();
$pdf->MultiCell($wKeluar, $headerLineH, "Qty Keluar\n(Penjualan)", 1, 'C');
$pdf->SetXY($x + $wKeluar, $startY);

$pdf->Cell($wBatch, $headerH, 'Batch', 1, 0, 'C');
$pdf->Cell($wEd, $headerH, 'ED', 1, 0, 'C');

$x = $pdf->GetX();
$pdf->MultiCell($wTotal, $headerLineH, "Total\n(Qty Masuk - Qty Keluar)", 1, 'C');

$pdf->SetXY($startX, $startY + $headerH);
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

    	$pdf->Cell($wNo, 0.7, $no, 1, 0, 'C');
        $pdf->Cell($wWaktu, 0.7, date('Y-m-d H:i:s', strtotime($row['tgl_sekarang'])), 1, 0, 'C');
        $pdf->Cell($wBulan, 0.7, date('F', strtotime($row['tgl_sekarang'])), 1, 0, 'C');
        $pdf->Cell($wNoTrans, 0.7, $row['kode_transaksi'], 1, 0, 'C');
        $pdf->Cell($wMasuk, 0.7, $msk, 1, 0, 'C');
        $pdf->Cell($wKeluar, 0.7, $klr, 1, 0, 'C');
        $pdf->Cell($wBatch, 0.7, $noBatch, 1, 0, 'C');
        $pdf->Cell($wEd, 0.7, $expDateDisplay, 1, 0, 'C');
        $pdf->Cell($wTotal, 0.7, $totalStok, 1, 1, 'C');

    	$no++;
}
$pdf->SetFont('Arial','',12);
$pdf->Cell($wNo + $wWaktu + $wBulan + $wNoTrans + $wMasuk + $wKeluar + $wBatch + $wEd, 0.7, 'Total Stok Barang', 1, 0, 'L');
$pdf->Cell($wTotal, 0.7, $totalStok, 1, 0, 'C');

$pdf->Output("rekap_stokopname.pdf","I");
