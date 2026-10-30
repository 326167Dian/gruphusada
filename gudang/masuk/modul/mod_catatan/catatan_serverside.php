<?php
include_once '../../../configurasi/koneksi.php';

$request = $_POST;

$columns = [
    0 => 'tgl',
    1 => 'shift',
    2 => 'petugas',
    3 => 'deskripsi'
];

$search = $request['search']['value'] ?? '';
$limit = $request['length'] ?? 10;
$start = $request['start'] ?? 0;
$order_column = $columns[$request['order'][0]['column']] ?? 'tgl';
$order_dir = $request['order'][0]['dir'] ?? 'asc';

$sql_total = "SELECT COUNT(*) AS total FROM catatan";
$res_total = mysqli_query($GLOBALS["___mysqli_ston"], $sql_total);
$totalData = mysqli_fetch_assoc($res_total)['total'];

$sql = "SELECT * FROM catatan WHERE 1=1";

if (!empty($search)) {
    $sql .= " AND (tgl LIKE '%$search%' OR shift LIKE '%$search%' OR petugas LIKE '%$search%' OR deskripsi LIKE '%$search%')";
}

$res_filtered = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
$totalFiltered = mysqli_num_rows($res_filtered);

$sql .= " ORDER BY $order_column $order_dir LIMIT $start, $limit";
$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);

$data = [];
$no = $start + 1;
while ($r = mysqli_fetch_assoc($query)) {
    // $aksi = "
    //     <a href='?module=catatan&act=edit&id={$r['id_catatan']}' title='EDIT' class='btn btn-warning btn-xs'>EDIT</a> 
    //     <a href='?module=catatan&act=tampil&id={$r['id_catatan']}' title='TAMPIL' class='btn btn-primary btn-xs'>TAMPIL</a> 
    //     <a href=javascript:confirmdelete('modul/mod_catatan/aksi_catatan.php?module=catatan&act=hapus&id={$r['id_catatan']}') title='HAPUS' class='btn btn-danger btn-xs'>HAPUS</a>
    // ";
    $aksi = "
        <button type='button' id='btn_edit' data-id='{$r['id_catatan']}' title='EDIT' class='btn btn-warning btn-xs'>EDIT</button> 
        <button type='button' id='btn_tampil' data-id='{$r['id_catatan']}' title='TAMPIL' class='btn btn-primary btn-xs'>TAMPIL</button> 
        <button type='button' id='btn_hapus' data-id='{$r['id_catatan']}' title='HAPUS' class='btn btn-danger btn-xs'>HAPUS</button>
    ";

    $data[] = [
        'no' => $no++,
        'tgl' => $r['tgl'],
        'shift' => $r['shift'],
        'petugas' => $r['petugas'],
        'deskripsi' => $r['deskripsi'],
        'aksi' => $aksi
    ];
}

echo json_encode([
    'draw' => intval($request['draw']),
    'recordsTotal' => intval($totalData),
    'recordsFiltered' => intval($totalFiltered),
    'data' => $data
]);
