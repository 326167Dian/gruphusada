<?php
include_once '../../../configurasi/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

$tgl_awal = date('Y-m-d');
$tgl_akhir = date('Y-m-d', strtotime('-30 days', strtotime($tgl_awal)));

$where = "WHERE trkasir.tgl_trkasir BETWEEN '$tgl_akhir' AND '$tgl_awal'";
$query = $db->query("SELECT barang.*, COUNT(trkasir_detail.id_barang) AS total, SUM(trkasir_detail.qty_dtrkasir) AS qty_terjual
    FROM barang
    LEFT JOIN trkasir_detail ON trkasir_detail.id_barang = barang.id_barang
    LEFT JOIN trkasir ON trkasir.kd_trkasir = trkasir_detail.kd_trkasir
    $where
    GROUP BY barang.id_barang
    ORDER BY barang.nm_barang ASC");

$data = array();
$no = 1;

while ($r = $query->fetch_assoc()) {
    $id_barang = $r['id_barang'];

    $pass1 = $r['total'];

    $stok = $r['stok_barang'];
    $sfc = $r['total'] - $stok;
    $qfc = $r['qty_terjual'] - $stok;

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
        $nama = strtolower($r['nm_barang']);
        $data[] = [
            // 'no' => $no++,
            'status' => $status,
            'nm_barang' => ucfirst($nama),
            'stok_barang' => round($stok),
            'transaksi_30_hari' => round($pass1),
            'qty_terjual' => round($r['qty_terjual']),
            'selisih_stok_transaksi' => round($sfc),
            'selisih_stok_qty' => round($qfc),
            'sat_barang' => $r['sat_barang'],
        ];
    }
}

// Ambil parameter dari DataTables
$start  = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? 10;
$search = $_POST['search']['value'] ?? '';
$orderCol = $_POST['order'][0]['column'] ?? 0;
$orderDir = $_POST['order'][0]['dir'] ?? 'asc';
$columns = ['nm_barang','status', 'nm_barang', 'stok_barang', 'transaksi_30_hari', 'qty_terjual', 'selisih_stok_transaksi', 'selisih_stok_qty', 'sat_barang'];

// Filtering (searching)
if (!empty($search)) {
    $data = array_filter($data, function($item) use ($search) {
        return  stripos($item['nm_barang'], $search) !== false ||  
                stripos($item['status'], $search) !== false ||  
                stripos($item['nm_barang'], $search) !== false || 
                stripos($item['stok_barang'], $search) !== false || 
                stripos($item['transaksi_30_hari'], $search) !== false || 
                stripos($item['qty_terjual'], $search) !== false || 
                stripos($item['selisih_stok_transaksi'], $search) !== false || 
                stripos($item['selisih_stok_qty'], $search) !== false || 
                stripos($item['sat_barang'], $search) !== false;
    });
}

// Sorting
usort($data, function ($a, $b) use ($columns, $orderCol, $orderDir) {
    $col = $columns[$orderCol];
    return $orderDir === 'asc'
        ? strcmp($a[$col], $b[$col])
        : strcmp($b[$col], $a[$col]);
});

// Total filtered
$totalFiltered = count($data);

// Pagination
$data = array_slice($data, $start, $length);

echo json_encode([
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalFiltered, // jumlah total sebelum filter
    "recordsFiltered" => $totalFiltered,
    "data" => array_values($data)
]);

?>
