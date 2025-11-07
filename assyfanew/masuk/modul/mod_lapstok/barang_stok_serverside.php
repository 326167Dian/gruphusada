<?php
include_once '../../../configurasi/koneksi.php';

$start  = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? 10;
$search = $_POST['search']['value'] ?? '';

$whereSearch = $search ? "WHERE kd_barang LIKE '%$search%' OR nm_barang LIKE '%$search%'" : "";

$queryTotal = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT COUNT(*) as total FROM barang");
$totalData = mysqli_fetch_assoc($queryTotal)['total'];

$queryData = mysqli_query($GLOBALS["___mysqli_ston"], "
    SELECT * FROM barang $whereSearch
    ORDER BY id_barang DESC
    LIMIT $start, $length
");

$data = [];
$no = $start + 1;

while ($r = mysqli_fetch_assoc($queryData)) {
    $kd_barang = $r['kd_barang'];

    // Stok masuk & waktu masuk awal
    $qMasuk = mysqli_query($GLOBALS["___mysqli_ston"], "
        SELECT SUM(qty_dtrbmasuk) AS subtotal, MIN(waktu) AS masukawal
        FROM trbmasuk_detail
        WHERE kd_barang = '$kd_barang'
    ");
    $masuk = mysqli_fetch_assoc($qMasuk);
    $stokMasuk = $masuk['subtotal'] ?? 0;
    $masukAwal = $masuk['masukawal'];

    // Waktu jual awal & akhir
    $qKeluar = mysqli_query($GLOBALS["___mysqli_ston"], "
        SELECT MIN(waktu) AS keluarawal, MAX(waktu) AS keluarakhir
        FROM trkasir_detail
        WHERE kd_barang = '$kd_barang'
    ");
    $keluar = mysqli_fetch_assoc($qKeluar);
    $keluarAwal = $keluar['keluarawal'];
    $keluarAkhir = $keluar['keluarakhir'];

    $patokan = ($keluarAwal < $masukAwal) ? $masukAwal : $keluarAwal;

    // Penjualan lebih cepat
    $qCepat = mysqli_query($GLOBALS["___mysqli_ston"], "
        SELECT SUM(qty_dtrkasir) AS qty_atas
        FROM trkasir_detail
        WHERE kd_barang = '$kd_barang' AND waktu BETWEEN '$keluarAwal' AND '$masukAwal'
    ");
    $qtyCepat = mysqli_fetch_assoc($qCepat)['qty_atas'] ?? 0;

    // Penjualan setelah stok masuk
    $qSetelah = mysqli_query($GLOBALS["___mysqli_ston"], "
        SELECT SUM(qty_dtrkasir) AS qty_bawah
        FROM trkasir_detail
        WHERE kd_barang = '$kd_barang' AND waktu BETWEEN '$masukAwal' AND '$keluarAkhir'
    ");
    $qtySetelah = mysqli_fetch_assoc($qSetelah)['qty_bawah'] ?? 0;

    // Stok real
    $stokReal = $qtyCepat + $stokMasuk - ($qtyCepat + $qtySetelah);

    // Tambahkan ke array
    $data[] = [
        'no' => $no++,
        'kd_barang' => $kd_barang,
        'nm_barang' => $r['nm_barang'],
        'penjualan_cepat' => $qtyCepat,
        'stok_masuk' => $stokMasuk,
        'penjualan_setelah' => $qtySetelah,
        'stok_real' => $stokReal,
        'koreksi' => "<a href='?module=koreksistok&act=edit&id={$r['id_barang']}' class='btn btn-primary btn-xs'>KOREKSI</a>"
    ];
}

echo json_encode([
    'draw' => intval($_POST['draw'] ?? 0),
    'recordsTotal' => $totalData,
    'recordsFiltered' => $totalData, // Gunakan COUNT dengan filter jika diperlukan
    'data' => $data
]);
