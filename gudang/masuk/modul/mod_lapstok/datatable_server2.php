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

// Ambil parameter dari DataTables
$draw       = intval($_POST['draw'] ?? 0);
$start      = intval($_POST['start'] ?? 0);
$length     = intval($_POST['length'] ?? 10);
$search     = $_POST['search']['value'] ?? '';
$orderColIx = $_POST['order'][0]['column'] ?? 0;
$orderDir   = strtolower($_POST['order'][0]['dir'] ?? 'asc');

// Validasi input order
$orderCol = $columns[$orderColIx] ?? 'b.kd_barang';
$orderDir = in_array($orderDir, ['asc', 'desc']) ? $orderDir : 'asc';

// Query filter pencarian
$searchQuery = "";
if (!empty($search)) {
    $searchEscaped = $db->real_escape_string($search);
    $searchQuery = "AND b.nm_barang LIKE '%$searchEscaped%'";
}

// Hitung total data (tanpa filter)
$totalQuery = "
    SELECT 
        b.*,
        (
            CASE 
                WHEN COALESCE(t30.count_t30, 0) <= 0 THEN 'MACET'
                WHEN COALESCE(t30.count_t30, 0) <= 5 THEN 'SLOW'
                WHEN COALESCE(t30.count_t30, 0) <= 10 THEN 'LANCAR'
                WHEN COALESCE(t30.count_t30, 0) > 10 THEN 'LAKU'
            END
        ) AS status,
        COALESCE(t30.count_t30, 0) AS t30,
        COALESCE(t30.q30, 0) AS q30,
        (COALESCE(t30.count_t30, 0) - b.stok_barang) AS sfcmin,
        (COALESCE(t30.q30, 0) - b.stok_barang) AS sfcmax
    FROM barang b
    LEFT JOIN (
        SELECT td.id_barang, COUNT(*) AS count_t30, SUM(td.qty_dtrkasir) AS q30
        FROM trkasir_detail td
        JOIN trkasir t ON td.kd_trkasir = t.kd_trkasir
        WHERE t.tgl_trkasir BETWEEN '$tgl_akhir' AND '$tgl_awal'
        GROUP BY td.id_barang
    ) AS t30 ON b.id_barang = t30.id_barang
    WHERE b.kd_barang != '' 
    HAVING b.stok_barang <= (0.25*t30) AND t30 > 0
";
$totalResult = $db->query($totalQuery);
$totalData = $totalResult->num_rows;

// Query utama
$dataQuery = "
    SELECT 
        b.*,
        (
            CASE 
                WHEN COALESCE(t30.count_t30, 0) <= 0 THEN 'MACET'
                WHEN COALESCE(t30.count_t30, 0) <= 5 THEN 'SLOW'
                WHEN COALESCE(t30.count_t30, 0) <= 10 THEN 'LANCAR'
                WHEN COALESCE(t30.count_t30, 0) > 10 THEN 'LAKU'
            END
        ) AS status,
        COALESCE(t30.count_t30, 0) AS t30,
        COALESCE(t30.q30, 0) AS q30,
        (COALESCE(t30.count_t30, 0) - b.stok_barang) AS sfcmin,
        (COALESCE(t30.q30, 0) - b.stok_barang) AS sfcmax
    FROM barang b
    LEFT JOIN (
        SELECT td.id_barang, COUNT(*) AS count_t30, SUM(td.qty_dtrkasir) AS q30
        FROM trkasir_detail td
        JOIN trkasir t ON td.kd_trkasir = t.kd_trkasir
        WHERE t.tgl_trkasir BETWEEN '$tgl_akhir' AND '$tgl_awal'
        GROUP BY td.id_barang
    ) AS t30 ON b.id_barang = t30.id_barang
    WHERE b.kd_barang != '' 
    $searchQuery
    HAVING b.stok_barang <= (0.25*t30) AND t30 > 0
    ORDER BY $orderCol $orderDir
    LIMIT $start, $length
";

// $totalResult = $db->query($dataQuery);
// $totalData = $totalResult->num_rows() ?? 0;

// $filteredResult = $db->query($dataQuery);
// $totalFiltered = $filteredResult->num_rows() ?? 0;

$dataResult = $db->query($dataQuery);

if (!$dataResult) {
    echo json_encode(["error" => "SQL Error: " . $db->error]);
    exit;
}

// Siapkan data output
$data = [];
$no = $start + 1;
while ($row = $dataResult->fetch_assoc()) {
    $t30    = $row['t30'] ?? 0;
    $q30    = $row['q30'] ?? 0;
    $sfcmin = $row['sfcmin'] ?? 0;
    $sfcmax = $row['sfcmax'] ?? 0;
   
    $data[] = [
        'no'                        => $no++,
        'status'                    => $row['status'],
        'nm_barang'                 => $row['nm_barang'],
        'stok_barang'               => round($row['stok_barang']),
        'transaksi_30_hari'         => round($t30),
        'qty_terjual'               => round($q30),
        'selisih_stok_transaksi'    => round($sfcmin),
        'selisih_stok_qty'          => round($sfcmax),
        'sat_barang'                => $row['sat_barang'],
    ];
}

// Hitung total setelah filter
$filteredQuery = "
    SELECT 
        b.*,
        (
            CASE 
                WHEN COALESCE(t30.count_t30, 0) <= 0 THEN 'MACET'
                WHEN COALESCE(t30.count_t30, 0) <= 5 THEN 'SLOW'
                WHEN COALESCE(t30.count_t30, 0) <= 10 THEN 'LANCAR'
                WHEN COALESCE(t30.count_t30, 0) > 10 THEN 'LAKU'
            END
        ) AS status,
        COALESCE(t30.count_t30, 0) AS t30,
        COALESCE(t30.q30, 0) AS q30,
        (COALESCE(t30.count_t30, 0) - b.stok_barang) AS sfcmin,
        (COALESCE(t30.q30, 0) - b.stok_barang) AS sfcmax
    FROM barang b
    LEFT JOIN (
        SELECT td.id_barang, COUNT(*) AS count_t30, SUM(td.qty_dtrkasir) AS q30
        FROM trkasir_detail td
        JOIN trkasir t ON td.kd_trkasir = t.kd_trkasir
        WHERE t.tgl_trkasir BETWEEN '$tgl_akhir' AND '$tgl_awal'
        GROUP BY td.id_barang
    ) AS t30 ON b.id_barang = t30.id_barang
    WHERE b.kd_barang != '' 
    $searchQuery
    HAVING b.stok_barang <= (0.25*t30) AND t30 > 0
";
$filteredResult = $db->query($filteredQuery);
$totalFiltered = $filteredResult->num_rows;

// Return JSON
echo json_encode([
    "draw"            => $draw,
    "recordsTotal"    => $totalData,
    "recordsFiltered" => $totalFiltered,
    "data"            => $data
]);
?>
