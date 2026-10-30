<?php
include_once '../../../configurasi/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

$shift  = $_REQUEST['shift'];
$tgl    = $_REQUEST['tgl_awal'];

$where = "";
$query = $db->query("
    SELECT 
    	b.id_barang,
        b.kd_barang,
        b.nm_barang,
        b.sat_barang,
        b.jenisobat,
        b.hrgsat_barang,
        COALESCE(beli.totalbeli, 0) AS totalbeli,
        COALESCE(jual.totaljual, 0) AS totaljual,
        COALESCE(st.id_barang, 0) AS idbrg_stok,
        (COALESCE(beli.totalbeli, 0) - COALESCE(jual.totaljual, 0)) AS selisih,
        COALESCE(kasir.terjual, 0) AS terjual,
        kasir.shift
    FROM barang AS b
    LEFT JOIN (
        SELECT * 
        FROM stok_opname 
        WHERE tgl_stokopname = '$tgl'
    ) AS st ON st.id_barang = b.id_barang
    LEFT JOIN (
        SELECT 
            trbmasuk_detail.id_barang, 
            SUM(trbmasuk_detail.qty_dtrbmasuk) AS totalbeli 
        FROM trbmasuk_detail
        INNER JOIN trbmasuk 
            ON trbmasuk_detail.kd_trbmasuk = trbmasuk.kd_trbmasuk
        GROUP BY trbmasuk_detail.id_barang
    ) AS beli ON beli.id_barang = b.id_barang
    LEFT JOIN (
    	SELECT
        	trkasir_detail.id_barang,
        	SUM(trkasir_detail.qty_dtrkasir) AS totaljual
        FROM trkasir_detail
        INNER JOIN trkasir
        	ON trkasir_detail.kd_trkasir = trkasir.kd_trkasir
        GROUP BY trkasir_detail.id_barang
    ) AS jual ON jual.id_barang = b.id_barang
    INNER JOIN (
    	SELECT 
        	trkasir_detail.id_barang,
        	trkasir.shift,
        	SUM(trkasir_detail.qty_dtrkasir) AS terjual
        FROM trkasir_detail
        INNER JOIN trkasir
        	ON trkasir_detail.kd_trkasir = trkasir.kd_trkasir
        WHERE trkasir.tgl_trkasir = '$tgl'
        AND trkasir.shift = '$shift'
        GROUP BY trkasir_detail.id_barang
    ) AS kasir ON kasir.id_barang = b.id_barang
    HAVING idbrg_stok = 0 
    AND selisih > 0
    ORDER BY b.nm_barang ASC;
");

$data = array();
$no = 1;

while ($r = $query->fetch_assoc()) {
    $id_barang      = $r['id_barang'];
    $kd_barang      = $r['kd_barang'];
    $nm_barang      = $r['nm_barang'];
    $sat_barang     = $r['sat_barang'];
    $jenisobat      = $r['jenisobat'];
    $terjual        = $r['terjual'];
    $selisih        = $r['selisih'];
    $hrgsat_barang  = $r['hrgsat_barang'];
    $dtshift          = $r['shift'];
    
    $nama = strtolower($nm_barang);
    $data[] = [
        'no'            => $id_barang,
        'kd_barang'     => $kd_barang,
        'nm_barang'     => ucfirst($nama),
        'sat_barang'    => $sat_barang,
        'jenisobat'     => $jenisobat,
        'terjual'       => $terjual,
        'stok_sistem'   => ($selisih),
        'stok_fisik'    => $id_barang,
        'aksi'          => $id_barang,
        'hrgsat_barang' => $hrgsat_barang,
        'shift'         => $dtshift
    ];
    
}

// Ambil parameter dari DataTables
$start  = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? 5;
$search = $_POST['search']['value'] ?? '';
$orderCol = $_POST['order'][0]['column'] ?? 2;
$orderDir = $_POST['order'][0]['dir'] ?? 'asc';
$columns = ['no','kd_barang', 'nm_barang', 'sat_barang', 'jenisobat', 'terjual', 'stok_sistem', 'stok_fisik', 'aksi', 'hrgsat_barang', 'shift'];

// Filtering (searching)
if (!empty($search)) {
    $data = array_filter($data, function($item) use ($search) {
        return  stripos($item['no'], $search) !== false ||  
                stripos($item['kd_barang'], $search) !== false ||  
                stripos($item['nm_barang'], $search) !== false || 
                stripos($item['sat_barang'], $search) !== false || 
                stripos($item['jenisobat'], $search) !== false || 
                stripos($item['terjual'], $search) !== false || 
                stripos($item['stok_sistem'], $search) !== false || 
                stripos($item['stok_fisik'], $search) !== false || 
                stripos($item['aksi'], $search) !== false || 
                stripos($item['hrgsat_barang'], $search) !== false || 
                stripos($item['shift'], $search) !== false;
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
