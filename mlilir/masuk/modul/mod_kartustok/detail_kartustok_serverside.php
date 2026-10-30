<?php
include_once '../../../configurasi/koneksi.php';

// Paging
$draw   = $_POST['draw'] ?? 0;
$start  = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? 10;
$search = $_POST['search']['value'] ?? '';

// $kdbarang = $_POST['kdbarang'] ?? '';
$kdbarang = $_GET['id'] ?? '';

$totalQuery = "SELECT COUNT(*) as total FROM kartu_stok
    WHERE kartu_stok.kode_transaksi IN (SELECT trbmasuk_detail.kd_trbmasuk FROM trbmasuk_detail WHERE trbmasuk_detail.kd_barang = '$kdbarang')
    OR kartu_stok.kode_transaksi IN (SELECT trkasir_detail.kd_trkasir FROM trkasir_detail WHERE trkasir_detail.kd_barang = '$kdbarang')
    ORDER BY kartu_stok.tgl_sekarang ASC";
$totalResult = $db->query($totalQuery);
$totalData = $totalResult->fetch_assoc()['total'];

// Query data dengan pencarian
$searchQuery = "";
if (!empty($search)) {
    $searchQuery = "AND (kartu_stok.kode_transaksi LIKE '%$search%' OR kartu_stok.tgl_sekarang LIKE '%$search%')";
}

$dataQuery = "SELECT * FROM kartu_stok
    WHERE kartu_stok.kode_transaksi IN (SELECT trbmasuk_detail.kd_trbmasuk FROM trbmasuk_detail WHERE trbmasuk_detail.kd_barang = '$kdbarang')
    OR kartu_stok.kode_transaksi IN (SELECT trkasir_detail.kd_trkasir FROM trkasir_detail WHERE trkasir_detail.kd_barang = '$kdbarang')
    $searchQuery
    ORDER BY kartu_stok.tgl_sekarang ASC 
    LIMIT $start, $length";
$dataResult = $db->query($dataQuery);

$data = array();
$no = 1;
$totalStok = 0;
while ($row = $dataResult->fetch_assoc()) {
    $brgMasuk   = $db->query("SELECT qty_dtrbmasuk FROM trbmasuk_detail WHERE kd_trbmasuk = '$row[kode_transaksi]' AND kd_barang = '$kdbarang'");
    $masuk      = $brgMasuk->fetch_assoc();
    
    $brgKeluar  = $db->query("SELECT qty_dtrkasir FROM trkasir_detail WHERE kd_trkasir = '$row[kode_transaksi]' AND kd_barang = '$kdbarang'");
    $keluar     = $brgKeluar->fetch_assoc();
    
    $msk = (int) ($masuk['qty_dtrbmasuk'] ?? 0);
    $klr = (int) ($keluar['qty_dtrkasir'] ?? 0);
    $totalStok += ($msk - $klr);
    
    $data[] = [
        'no' => $no++,
        'current_time' => date('Y-m-d H:i:s', strtotime($row['tgl_sekarang'])),
        'bulan' => date('F', strtotime($row['tgl_sekarang'])),
        'kode_transaksi' => $row['kode_transaksi'],
        'qty_masuk' => $msk,
        'qty_keluar' => $klr,
        'total' => $totalStok
    ];
}

// Hitung total data setelah filter
$filteredQuery = "SELECT COUNT(*) as total FROM kartu_stok
    WHERE kartu_stok.kode_transaksi IN (SELECT trbmasuk_detail.kd_trbmasuk FROM trbmasuk_detail WHERE trbmasuk_detail.kd_barang = '$kdbarang')
    OR kartu_stok.kode_transaksi IN (SELECT trkasir_detail.kd_trkasir FROM trkasir_detail WHERE trkasir_detail.kd_barang = '$kdbarang')
    $searchQuery";
$filteredResult = $db->query($filteredQuery);
$totalFiltered = $filteredResult->fetch_assoc()['total'];

// Kembalikan data dalam format JSON
$response = [
    "draw"              => intval($draw),
    "recordsTotal"      => intval($totalData),
    "recordsFiltered"   => intval($totalFiltered),
    "data"              => $data,
    "total_stok"        => $totalStok
];

echo json_encode($response);




//==================================
// include_once '../../../configurasi/koneksi.php';

// $kdbarang = $_POST['kdbarang'] ?? '';
// $conn = $GLOBALS["___mysqli_ston"];

// // $request = $_POST;
// $columns = [
//     0 => 'a.id_kartu',
//     1 => 'a.tgl_sekarang',
//     2 => 'a.tgl_sekarang',
//     3 => 'a.kode_transaksi'
// ];

// $draw = $_POST['draw']['value'] ?? '';
// $search = $_POST['search']['value'] ?? '';
// $limit = $_POST['length'] ?? 10;
// $start = $_POST['start'] ?? 0;
// $order_column = $columns[$_POST['order'][0]['column']];
// $order_dir = $_POST['order'][0]['dir'];

// $base_query = "
//     FROM kartu_stok a 
//     LEFT JOIN trbmasuk_detail b ON a.kode_transaksi = b.kd_trbmasuk
//     LEFT JOIN trkasir_detail c ON a.kode_transaksi = c.kd_trkasir
//     WHERE b.kd_barang = '$kdbarang' OR c.kd_barang = '$kdbarang'
// ";

// $query_total = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT COUNT(*) AS total $base_query");
// $totalData = mysqli_fetch_assoc($query_total)['total'];
// $totalFiltered = $totalData;

// if (!empty($search)) {
//     $base_query .= " AND (a.kode_transaksi LIKE '%$search%' OR a.tgl_sekarang LIKE '%$search%')";
//     $query_filtered = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT COUNT(*) AS total $base_query");
//     $totalFiltered = mysqli_fetch_assoc($query_filtered)['total'];
// }

// $query = "
//     SELECT a.tgl_sekarang, a.kode_transaksi, b.qty_dtrbmasuk, c.qty_dtrkasir
//     $base_query
//     ORDER BY $order_column $order_dir
//     LIMIT $start, $limit
// ";

// $data = [];
// // $no = $start + 1;
// $no = 1;
// $totalStok = 0;
// $res = mysqli_query($GLOBALS["___mysqli_ston"], $query);
// while ($row = mysqli_fetch_assoc($res)) {
//     $masuk = (int) ($row['qty_dtrbmasuk'] ?? 0);
//     $keluar = (int) ($row['qty_dtrkasir'] ?? 0);
//     $totalStok += ($masuk - $keluar);

//     $data[] = [
//         'no' => $no++,
//         'current_time' => date('Y-m-d H:i:s', strtotime($row['tgl_sekarang'])),
//         'bulan' => date('F', strtotime($row['tgl_sekarang'])),
//         'kode_transaksi' => $row['kode_transaksi'],
//         'qty_masuk' => $masuk,
//         'qty_keluar' => $keluar,
//         'total' => $totalStok
//     ];
// }

// echo json_encode([
//     'draw' => intval($draw),
//     'recordsTotal' => intval($totalData),
//     'recordsFiltered' => intval($totalFiltered),
//     'data' => $data,
//     'total_stok' => $totalStok
// ]);
