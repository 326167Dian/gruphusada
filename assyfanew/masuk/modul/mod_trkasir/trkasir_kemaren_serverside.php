<?php
session_start();
include "../../../configurasi/koneksi.php";
date_default_timezone_set('Asia/Jakarta');

// Kolom yang dapat diurutkan
$columns = [
    0 => 'trkasir.id_trkasir',
    1 => 'trkasir.kd_trkasir',
    2 => 'trkasir.tgl_trkasir',
    3 => 'trkasir.nm_pelanggan',
    4 => 'trkasir.petugas',
    5 => 'carabayar.nm_carabayar',
    6 => 'trkasir.ttl_trkasir',
    7 => 'trkasir.id_trkasir'
];

// var_dump($_POST);
$draw       = intval($_POST['draw'] ?? 0);
$start      = intval($_POST['start'] ?? 0);
$length     = intval($_POST['length'] ?? 10);
$search     = $_POST['search']['value'] ?? '';
$orderColIx = $_POST['order'][0]['column'] ?? 0;
$orderDir   = strtolower($_POST['order'][0]['dir'] ?? 'desc');

// Validasi input order
$order_column = $columns[$orderColIx] ?? 'trkasir.id_trkasir';
$order_dir = in_array($orderDir, ['asc', 'desc']) ? $orderDir : 'desc';

$tgl_awal = date('Y-m-d');
$tgl_kemarin = date('Y-m-d', strtotime('-1 days', strtotime( $tgl_awal)));
$tgl_akhir = date('Y-m-d', strtotime('-60 days', strtotime( $tgl_awal)));
// $tampil_trkasir = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir  
//                 where tgl_trkasir between '$tgl_akhir' and '$tgl_kemarin'ORDER BY id_trkasir desc ") ;

$where = "";
if (!empty($search)) {
    $where = "AND trkasir.kd_trkasir LIKE '%$search%' OR trkasir.nm_pelanggan LIKE '%$search%' OR trkasir.tgl_trkasir LIKE '%$search%'";
}

$sqlTotal = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT COUNT(*) AS total FROM trkasir 
    JOIN carabayar ON trkasir.id_carabayar = carabayar.id_carabayar
    WHERE trkasir.tgl_trkasir BETWEEN '$tgl_akhir' AND '$tgl_kemarin'");
$totalData = mysqli_fetch_assoc($sqlTotal)['total'];

$query = mysqli_query($GLOBALS["___mysqli_ston"], "
    SELECT trkasir.*, carabayar.nm_carabayar 
    FROM trkasir 
    JOIN carabayar ON trkasir.id_carabayar = carabayar.id_carabayar 
    WHERE trkasir.tgl_trkasir BETWEEN '$tgl_akhir' AND '$tgl_kemarin'
    $where 
    ORDER BY $order_column $order_dir 
    LIMIT $start, $length
");

$data = [];
$no = $start + 1;
$aksi = "modul/mod_trkasir/aksi_trkasir.php";
while ($row = mysqli_fetch_assoc($query)) {
    // $ttl_trkasir_format = number_format($row['ttl_trkasir'], 0, ',', '.');
    
    $lupa = $_SESSION['level'];
    if ($lupa == 'pemilik') {
        // $aksi = "
        //     <a href='?module=trkasir&act=ubah&id={$row['id_trkasir']}' title='EDIT' class='glyphicon glyphicon-pencil'>&nbsp;</a>
        //     <a class='glyphicon glyphicon-print' onclick=\"window.open('modul/mod_laporan/struk.php?kd_trkasir={$row['kd_trkasir']}','popup','width=500,height=600')\">&nbsp;</a>
        //     <a href=javascript:confirmdelete('modul/mod_trkasir/aksi_trkasir.php?module=trkasir&act=hapus&id={$row['id_trkasir']}') title='HAPUS' class='glyphicon glyphicon-remove'>&nbsp;</a>
        //     <a href='modul/mod_laporan/faktur.php?kd_trkasir={$row['kd_trkasir']}' target='_blank' title='FAKTUR' class='btn btn-primary btn-xs'>FAKTUR</a>
        // ";
        
        $aksi = "
                <a href='#' id='btn_edit' data-id='$row[id_trkasir]' class='glyphicon glyphicon-pencil'>&nbsp;</a>
                <a href='#' id='btn_print' data-id='$row[id_trkasir]' data-kd_trkasir='$row[kd_trkasir]' class='glyphicon glyphicon-print' >&nbsp;</a>
                <a href='#' id='btn_hapus' data-id='$row[id_trkasir]' class='glyphicon glyphicon-remove'>&nbsp;</a>
                <a href='#' id='btn_faktur' data-id='$row[id_trkasir]' data-kd_trkasir='$row[kd_trkasir]' class='btn btn-primary btn-xs'>FAKTUR</a>
                ";
    } else {
        $aksi = "
                <a href='#' id='btn_print' data-id='$row[id_trkasir]' data-kd_trkasir='$row[kd_trkasir]' class='glyphicon glyphicon-print' >&nbsp;</a>
                <a href='#' id='btn_faktur' data-id='$row[id_trkasir]' data-kd_trkasir='$row[kd_trkasir]' class='btn btn-primary btn-xs'>FAKTUR</a>
                ";
    }
    
    $data[] = [
        "no"            => $no++,
        "kd_trkasir"    => $row['kd_trkasir'],
        "tgl_trkasir"   => $row['tgl_trkasir'],
        "nm_pelanggan"  => $row['nm_pelanggan'],
        "petugas"       => $row['petugas'],
        "nm_carabayar"  => $row['nm_carabayar'],
        "ttl_trkasir"   => $row['ttl_trkasir'],
        "aksi"          => $aksi,
        "carabayar"     => $row['id_carabayar']
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
