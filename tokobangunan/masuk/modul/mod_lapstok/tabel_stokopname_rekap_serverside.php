<?php
include "../../../configurasi/koneksi.php";
date_default_timezone_set('Asia/Jakarta');

// Kolom yang dapat diurutkan
$columns = [
    0   => 'a.id_stok_opname',
    1   => 'b.kd_barang',
    2   => 'b.nm_barang',
    3   => 'b.sat_barang',
    4   => 'a.stok_sistem',
    5   => 'a.stok_fisik',
    6   => 'a.exp_date',
    7   => 'a.jml',
    8   => 'a.selisih',
    9   => 'a.tgl_current',
    10  => 'a.id_stok_opname'
];

// var_dump($_POST);
$draw       = intval($_POST['draw'] ?? 0);
$start      = intval($_POST['start'] ?? 0);
$length     = intval($_POST['length'] ?? 5);
$search     = $_POST['search']['value'] ?? '';
$orderColIx = $_POST['order'][0]['column'] ?? 0;
$orderDir   = strtolower($_POST['order'][0]['dir'] ?? 'desc');

// Validasi input order
$order_column = $columns[$orderColIx] ?? 'trkasir.id_trkasir';
$order_dir = in_array($orderDir, ['asc', 'desc']) ? $orderDir : 'desc';

$jenisobat  = $_REQUEST['jenisobat'];
$tgl        = $_REQUEST['tgl_awal'];


$where = "";
if (!empty($search)) {
    $where = "AND (a.id_stok_opname LIKE '%$search%'
                OR b.kd_barang LIKE '%$search%' 
                OR b.nm_barang LIKE '%$search%' 
                OR b.sat_barang LIKE '%$search%'
                OR a.stok_sistem LIKE '%$search%'
                OR a.stok_fisik LIKE '%$search%'
                OR a.exp_date LIKE '%$search%'
                OR a.jml LIKE '%$search%'
                OR a.selisih LIKE '%$search%'
                OR a.tgl_current LIKE '%$search%')";
}

$sqlTotal = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT COUNT(*) AS total FROM stok_opname a 
                            JOIN barang b ON a.id_barang = b.id_barang 
                            WHERE b.jenisobat='$jenisobat' AND a.tgl_stokopname = '$tgl'");
$totalData = mysqli_fetch_assoc($sqlTotal)['total'];

$query = mysqli_query($GLOBALS["___mysqli_ston"], "
    SELECT  a.id_stok_opname , b.kd_barang, b.nm_barang, b.sat_barang,
            a.stok_sistem, a.stok_fisik, a.exp_date, a.jml, a.selisih, a.tgl_current
    FROM stok_opname a 
    JOIN barang b ON a.id_barang = b.id_barang WHERE b.jenisobat='$jenisobat' AND a.tgl_stokopname = '$tgl'
    $where 
    ORDER BY $order_column $order_dir 
    LIMIT $start, $length
");

$data = [];
$no = $start + 1;
while ($row = mysqli_fetch_assoc($query)) {
    
    $data[] = [
        "no"            => $no++,
        "kd_barang"     => $row['kd_barang'],
        "nm_barang"     => $row['nm_barang'],
        "sat_barang"    => $row['sat_barang'],
        "stok_sistem"   => $row['stok_sistem'],
        "stok_fisik"    => $row['stok_fisik'],
        "exp_date"      => $row['exp_date'],
        "jml"           => $row['jml'],
        "selisih"       => $row['selisih'],
        "tgl_current"   => $row['tgl_current'],
        "aksi"          => $row['id_stok_opname']
    ];
}

$response = [
    "draw" => intval($draw),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalData),
    "data" => $data
];

// header('Content-Type: application/json');
echo json_encode($response);
?>
