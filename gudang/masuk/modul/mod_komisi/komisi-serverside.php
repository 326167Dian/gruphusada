<?php
include_once '../../../configurasi/koneksi.php';

if ($_GET['action'] == "table_data") {

    

    $querycount = $db->query("SELECT count(id_barang) as jumlah FROM barang WHERE komisi > 0");
    $datacount = $querycount->fetch_array();

    $totalData = $datacount['jumlah'];

    $totalFiltered = $totalData;

    $limit = $_POST['length'] ?? 10;
    $start = $_POST['start'] ?? 0;
    // $order = $columns[$_POST['order']['0']['column']];
    $orderColIx = $_POST['order'][0]['column'] ?? 0;
    $orderDir   = strtolower($_POST['order'][0]['dir'] ?? 'desc');
    
    // Validasi input order
    $order = $columns[$orderColIx] ?? 'id_barang';
    $dir = in_array($orderDir, ['asc', 'desc']) ? $orderDir : 'desc';

    if (empty($_POST['search']['value'])) {
        $query = $db->query("SELECT * FROM barang 
            WHERE komisi > 0 
            ORDER BY $order $dir LIMIT $limit OFFSET $start");
    } else {
        $search = $_POST['search']['value'];
        $query = $db->query("SELECT * 
            FROM barang WHERE komisi > 0
                        AND kd_barang LIKE '%$search%' 
                        OR nm_barang LIKE '%$search%'
                        OR stok_barang LIKE '%$search%'
                        OR sat_barang LIKE '%$search%'
                        OR jenisobat LIKE '%$search%'
                        OR hrgsat_barang LIKE '%$search%'
                        OR hrgjual_barang LIKE '%$search%'
                        OR indikasi LIKE '%$search%'
            ORDER BY $order $dir LIMIT $limit OFFSET $start");

        $querycount = $db->query("SELECT count(id_barang) as jumlah 
            FROM barang WHERE komisi > 0
                        AND kd_barang LIKE '%$search%' 
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
            $nestedData['hrgjual_barang'] = $value['hrgjual_barang'];
            $nestedData['komisi'] = $value['komisi'];
            $nestedData['aksi'] = $value['id_barang'];
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
