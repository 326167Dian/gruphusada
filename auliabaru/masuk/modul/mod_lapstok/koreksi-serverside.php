<?php
include_once '../../../configurasi/koneksi.php';

// Paging
$start  = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? 10;
$search = $_POST['search']['value'] ?? '';

// Query total records
$totalQuery = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT COUNT(*) as total FROM barang");
$totalData = mysqli_fetch_assoc($totalQuery);
$totalFiltered = $totalData['total'];

// Query filtered data
$searchSQL = $search ? "WHERE nm_barang LIKE '%$search%' OR kd_barang LIKE '%$search%'" : "";

$dataQuery = mysqli_query($GLOBALS["___mysqli_ston"], "
    SELECT * FROM barang $searchSQL 
    ORDER BY id_barang DESC 
    LIMIT $start, $length
");

$data = array();
// $no = $start + 1;
$no = 1;
while ($r = mysqli_fetch_assoc($dataQuery)) {
    // Total beli
    $beli = mysqli_query($GLOBALS["___mysqli_ston"], "
        SELECT SUM(trbmasuk_detail.qty_dtrbmasuk) AS totalbeli 
        FROM trbmasuk_detail 
        JOIN trbmasuk ON trbmasuk_detail.kd_trbmasuk = trbmasuk.kd_trbmasuk 
        WHERE kd_barang = '{$r['kd_barang']}'
    ");
    $buy = mysqli_fetch_assoc($beli);
    $totalbeli = $buy['totalbeli'] ?? 0;

    // Total jual
    $jual = mysqli_query($GLOBALS["___mysqli_ston"], "
        SELECT SUM(trkasir_detail.qty_dtrkasir) AS totaljual 
        FROM trkasir_detail 
        JOIN trkasir ON trkasir_detail.kd_trkasir = trkasir.kd_trkasir 
        WHERE kd_barang = '{$r['kd_barang']}'
    ");
    $sell = mysqli_fetch_assoc($jual);
    $totaljual = $sell['totaljual'] ?? 0;

    $selisih = $totalbeli - $totaljual;

    // Highlight jika selisih tidak sesuai stok
    $stokHTML = ($selisih == $r['stok_barang']) ?
        "<span class='text-right'>{$r['stok_barang']}</span>" :
        "<span style='background-color:#ffbf00; display:block; text-align:right;'>{$r['stok_barang']}</span>";

    $data[] = [
        "no" => $no++,
        "kd_barang" => $r['kd_barang'],
        "nm_barang" => $r['nm_barang'],
        "totalbeli" => max(0, $totalbeli),
        "totaljual" => max(0, $totaljual),
        "selisih" => $selisih,
        "stok_barang_html" => $stokHTML,
        // "koreksi" => "<a href='?module=koreksistok&act=edit&id={$r['id_barang']}' title='EDIT' class='btn btn-primary btn-xs'>KOREKSI</a>"
        "koreksi" => "<button type='button' id='btn_koreksi' data-id='{$r['id_barang']}' title='EDIT' class='btn btn-primary btn-xs'>KOREKSI</button>"
    ];
}

// Response JSON
echo json_encode([
    "draw" => intval($_POST['draw'] ?? 0),
    "recordsTotal" => $totalData['total'],
    "recordsFiltered" => $totalData['total'],
    // "recordsTotal" => count($data),
    // "recordsFiltered" => count($data),
    "data" => $data
]);
?>
