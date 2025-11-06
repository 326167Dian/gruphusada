<?php
include_once '../../../configurasi/koneksi.php';

if ($_GET['action'] == "table_data") {

    $columns = array(
        0 => 'no',
        1 => 'status',
        2 => 'nm_barang',
        3 => 'stok_barang',
        4 => 'transaksi_30_hari',
        5 => 'qty_terjual',
        6 => 'selisih_stok_transaksi',
        7 => 'selisih_stok_qty',
        8 => 'sat_barang'
    );
    
    $tgl_awal = date('Y-m-d');
    $tgl_akhir = date('Y-m-d', strtotime('-30 days', strtotime($tgl_awal)));
    
    $querycount = $db->query("
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
    ");
    // $datacount = $querycount->fetch_array();

    $totalData = $querycount->num_rows;

    $totalFiltered = $totalData;

    $limit = $_POST['length'];
    $start = $_POST['start'];
    $order = $columns[$_POST['order']['0']['column']];
    $dir = $_POST['order']['0']['dir'];

    if (empty($_POST['search']['value'])) {
        $query = $db->query("
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
            ORDER BY $order $dir
            LIMIT $limit OFFSET $start");
            
    } else {
        $search = $_POST['search']['value'];
        $query = $db->query("SELECT id_barang,
                                    kd_barang,
                                    nm_barang,
                                    stok_barang,
                                    sat_barang,
                                    jenisobat,
                                    hrgsat_barang,
                                    hrgjual_barang,
                                    indikasi 
            FROM barang WHERE kd_barang LIKE '%$search%' 
                        OR nm_barang LIKE '%$search%'
                        OR stok_barang LIKE '%$search%'
                        OR sat_barang LIKE '%$search%'
                        OR jenisobat LIKE '%$search%'
                        OR hrgsat_barang LIKE '%$search%'
                        OR hrgjual_barang LIKE '%$search%'
                        OR indikasi LIKE '%$search%' 
            ORDER BY $order $dir LIMIT $limit OFFSET $start");

        $querycount = $db->query("SELECT count(id_barang) as jumlah 
            FROM barang WHERE kd_barang LIKE '%$search%' 
                        OR nm_barang LIKE '%$search%'
                        OR stok_barang LIKE '%$search%'
                        OR sat_barang LIKE '%$search%'
                        OR jenisobat LIKE '%$search%'
                        OR hrgsat_barang LIKE '%$search%'
                        OR hrgjual_barang LIKE '%$search%'
                        OR indikasi LIKE '%$search%'");

        $datacount = $querycount->fetch_array();
        $totalFiltered = $datacount['jumlah'];
    }

    $data = array();
    if (!empty($query)) {
        $no = $start + 1;
        while ($value = $query->fetch_array()) {
            $nestedData['no'] = $no;
            $nestedData['kd_barang'] = $value['kd_barang'];
            $nestedData['nm_barang'] = $value['nm_barang'];
            $nestedData['stok_barang'] = $value['stok_barang'];
            $nestedData['sat_barang'] = $value['sat_barang'];
            $nestedData['jenisobat'] = $value['jenisobat'];
            $nestedData['hrgsat_barang'] = $value['hrgsat_barang'];
            $nestedData['hrgjual_barang'] = $value['hrgjual_barang'];
            $nestedData['indikasi'] = $value['indikasi'];
            $nestedData['aksi'] = $value['id_barang'];;
            $data[] = $nestedData;
            $no++;
        }
    }

    $json_data = [
        "draw"            => intval($_POST['draw']),
        "recordsTotal"    => intval($totalData),
        "recordsFiltered" => intval($totalFiltered),
        "data"            => $data
    ];

    echo json_encode($json_data);
}
