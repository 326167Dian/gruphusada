<?php
header('Content-Type: application/json');
date_default_timezone_set('Asia/Jakarta');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../../../configurasi/koneksi.php';

// Setup tanggal
$tgl_awal = date('Y-m-d');
$tgl_akhir = date('Y-m-d', strtotime('-30 days', strtotime($tgl_awal)));
$tgl_60 = date('Y-m-d', strtotime('-60 days', strtotime($tgl_awal)));

// Kolom yang dapat diurutkan
$columns = [
    0 =>    'b.kd_barang',
    1 =>    'b.kd_barang',
    2 =>    'b.nm_barang',
    3 =>    'b.stok_barang',
    4 =>    't30.count_t30',
    5 =>    't60.count_t60',
    6 =>    'b.kd_barang',
    7 =>    't30.q30',
    8 =>    'b.sat_barang',
    9 =>    'b.hrgsat_barang',
    10 =>   'b.kd_barang',
    11 =>   'b.kd_barang',
];

// Ambil parameter dari DataTables
$draw       = intval($_POST['draw'] ?? 0);
$start      = intval($_POST['start'] ?? 0);
$length     = intval($_POST['length'] ?? 10);
$search     = $_POST['search']['value'] ?? '';
$orderColIx = $_POST['order'][0]['column'] ?? 0;
$orderDir   = strtolower($_POST['order'][0]['dir'] ?? 'desc');

// Validasi input order
$orderCol = $columns[$orderColIx] ?? 'b.kd_barang';
$orderDir = in_array($orderDir, ['asc', 'desc']) ? $orderDir : 'desc';

// Hitung total data (tanpa filter)
$totalQuery = "SELECT COUNT(*) AS total FROM barang WHERE kd_barang != ''";
$totalResult = $db->query($totalQuery);
$totalData = $totalResult->fetch_assoc()['total'] ?? 0;

// Query filter pencarian
$searchQuery = "";
if (!empty($search)) {
    $searchEscaped = $db->real_escape_string($search);
    $searchQuery = "AND b.kd_barang LIKE '%$searchEscaped%' OR b.nm_barang LIKE '%$searchEscaped%'";
}

// Query utama
$dataQuery = "
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
    $searchQuery
    ORDER BY $orderCol $orderDir
    LIMIT $start, $length
";

$dataResult = $db->query($dataQuery);
if (!$dataResult) {
    echo json_encode(["error" => "SQL Error: " . $db->error]);
    exit;
}

// Siapkan data output
$data = [];
$no = $start + 1;
while ($row = $dataResult->fetch_assoc()) {
    $t30 = $row['t30'] ?? 0;
    $t60 = $row['t60'] ?? 0;
    $selisih = $t60 - $t30;
    // $gr = ($selisih === 0) ? 0 : intval(round(($t30 / $selisih * 100) - 100));
    $gr = (is_null($row['gr']))? 0:$row['gr'];
    $q30 = $row['q30'] ?? 0;
    $nilai_barang = $row['hrgsat_barang'] * $row['stok_barang'];

    $data[] = [
        "no"             => $no++,
        "kd_barang"      => $row['kd_barang'],
        "nm_barang"      => $row['nm_barang'],
        "stok_barang"    => round($row['stok_barang']),
        "t30"            => $t30,
        "t60"            => $selisih,
        "gr"             => $gr,
        "q30"            => $q30,
        "sat_barang"     => $row['sat_barang'],
        "hrgsat_barang"  => $row['hrgsat_barang'],
        "nilai_barang"   => $nilai_barang,
        "kartu_stok"     => "<button type='button' id='btn_kartustok' data-id='{$row['kd_barang']}' class='btn btn-warning btn-xs'>Kartu Stok</button>"
    ];
}

// Hitung total setelah filter
$filteredQuery = "
    SELECT COUNT(*) AS total
    FROM barang b
    LEFT JOIN (
        SELECT td.id_barang
        FROM trkasir_detail td
        JOIN trkasir t ON td.kd_trkasir = t.kd_trkasir
        WHERE t.tgl_trkasir BETWEEN '$tgl_akhir' AND '$tgl_awal'
        GROUP BY td.id_barang
    ) AS t30 ON b.id_barang = t30.id_barang
    LEFT JOIN (
        SELECT td.id_barang
        FROM trkasir_detail td
        JOIN trkasir t ON td.kd_trkasir = t.kd_trkasir
        WHERE t.tgl_trkasir BETWEEN '$tgl_60' AND '$tgl_awal'
        GROUP BY td.id_barang
    ) AS t60 ON b.id_barang = t60.id_barang
    WHERE b.kd_barang != '' 
    $searchQuery
";
$filteredResult = $db->query($filteredQuery);
$totalFiltered = $filteredResult->fetch_assoc()['total'] ?? 0;

// Return JSON
echo json_encode([
    "draw"            => $draw,
    "recordsTotal"    => $totalData,
    "recordsFiltered" => $totalFiltered,
    "data"            => $data
]);
?>
