<?php
session_start();
include "../../../configurasi/koneksi.php";
date_default_timezone_set('Asia/Jakarta');

// Kolom yang dapat diurutkan
$columns = [
    0   => 'id_shift',
    1   => 'petugasbuka',
    2   => 'petugastutup',
    3   => 'shift',
    4   => 'tanggal',
    5   => 'waktubuka',
    6   => 'waktututup',
    7   => 'saldoawal',
    8   => 'saldoakhir',
    9   => 'status',
    10  => 'id_shift'
];

// var_dump($_POST);
$draw       = intval($_POST['draw'] ?? 0);
$start      = intval($_POST['start'] ?? 0);
$length     = intval($_POST['length'] ?? 10);
$search     = $_POST['search']['value'] ?? '';
$orderColIx = $_POST['order'][0]['column'] ?? 0;
$orderDir   = strtolower($_POST['order'][0]['dir'] ?? 'desc');

// Validasi input order
$order_column = $columns[$orderColIx] ?? 'id_shift';
$order_dir = in_array($orderDir, ['asc', 'desc']) ? $orderDir : 'desc';


$where = "";
if (!empty($search)) {
    $where = "WHERE id_shift LIKE '%$search%' 
                    OR petugasbuka LIKE '%$search%'
                    OR petugastutup LIKE '%$search%'
                    OR shift LIKE '%$search%'
                    OR tanggal LIKE '%$search%'
                    OR waktubuka LIKE '%$search%'
                    OR waktututup LIKE '%$search%'
                    OR saldoawal LIKE '%$search%'
                    OR saldoakhir LIKE '%$search%'
                    OR status LIKE '%$search%'";
}

$sqlTotal = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT COUNT(*) AS total FROM waktukerja");
$totalData = mysqli_fetch_assoc($sqlTotal)['total'];

$query = mysqli_query($GLOBALS["___mysqli_ston"], "
    SELECT * 
    FROM waktukerja 
    $where 
    ORDER BY $order_column $order_dir 
    LIMIT $start, $length
");

$data = [];
$no = $start + 1;
$aksi = "modul/mod_shiftkerja/aksi_shiftkerja.php";
$koreksi = "";
while ($row = mysqli_fetch_assoc($query)) {
    $lupa = $_SESSION['level'];
	if ($lupa == 'pemilik') {
		$koreksi = "<a href='?module=shiftkerja&act=editkoreksi&id=$row[id_shift]' title='EDIT' class='glyphicon glyphicon-pencil'>&nbsp</a> 
						<a href='#' id='btn_hapus' data-id='$row[id_shift]' title='HAPUS' class='glyphicon glyphicon-remove'>&nbsp</a>
						<a class='glyphicon glyphicon-print' onclick=\"javascript:window.open('modul/mod_shiftkerja/laporanshiftday.php?idshift=$row[id_shift]','nama window','width=500,height=600,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no')\">&nbsp</a>";
		
				
	} else {
		$koreksi = "<a class='glyphicon glyphicon-print' onclick=\"javascript:window.open('modul/mod_shiftkerja/laporanshiftday.php?idshift=$row[id_shift]','nama window','width=500,height=600,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no')\">&nbsp</a>";
	}

    $data[] = [
        "no"            => $no++,
        "petugasbuka"   => $row['petugasbuka'],
        "petugastutup"  => $row['petugastutup'],
        "shift"         => $row['shift'],
        "tanggal"       => $row['tanggal'],
        "waktubuka"     => $row['waktubuka'],
        "waktututup"    => $row['waktututup'],
        "saldoawal"     => $row['saldoawal'],
        "saldoakhir"    => $row['saldoakhir'],
        "status"        => $row['status'],
        "koreksi"       => $koreksi
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
