<?php
include_once '../../../configurasi/koneksi.php';

if ($_GET['action'] == "table_data") {

    $columns = array(
        0 => 'id_barang',
        1 => 'kd_barang',
        2 => 'nm_barang',
        3 => 'sat_barang',
        4 => 'hrgsat_barang',
        5 => 'hrgjual_barang',
        6 => 'komisi',
        7 => 'id_barang'
    );

    $querycount = $db->query("SELECT count(id_barang) as jumlah FROM barang WHERE stok_barang > 0");
    $datacount = $querycount->fetch_array();

    $totalData = $datacount['jumlah'];

    $totalFiltered = $totalData;

    $limit = intval($_POST['length']);
    $start = intval($_POST['start']);
    $order = $columns[$_POST['order']['0']['column']] ?? 'nm_barang';
    $dir = ($_POST['order']['0']['dir'] == 'desc') ? 'desc' : 'asc';

    if (empty($_POST['search']['value'])) {
        $query = $db->query("SELECT id_barang,
                                    kd_barang,
                                    nm_barang,
                                    sat_barang,
                                    hrgsat_barang,
                                    hrgjual_barang,
                                    komisi
            FROM barang
            WHERE stok_barang > 0
            ORDER BY $order $dir LIMIT $limit OFFSET $start");
    } else {
        $search = $db->real_escape_string($_POST['search']['value']);
        $query = $db->query("SELECT id_barang,
                                    kd_barang,
                                    nm_barang,
                                    sat_barang,
                                    hrgsat_barang,
                                    hrgjual_barang,
                                    komisi
            FROM barang
            WHERE stok_barang > 0
                AND (kd_barang LIKE '%$search%'
                     OR nm_barang LIKE '%$search%')
            ORDER BY $order $dir LIMIT $limit OFFSET $start");

        $querycount = $db->query("SELECT count(id_barang) as jumlah
            FROM barang
            WHERE stok_barang > 0
                AND (kd_barang LIKE '%$search%'
                     OR nm_barang LIKE '%$search%')");

        $datacount = $querycount->fetch_array();
        $totalFiltered = $datacount['jumlah'];
    }

    $data = array();
    if (!empty($query)) {
        $no = $start + 1;
        while ($value = $query->fetch_array()) {
            $nestedData['no'] = $no;
            $nestedData['id_barang'] = $value['id_barang'];
            $nestedData['kd_barang'] = $value['kd_barang'];
            $nestedData['nm_barang'] = $value['nm_barang'];
            $nestedData['sat_barang'] = $value['sat_barang'];
            $nestedData['hrgsat_barang'] = $value['hrgsat_barang'];
            $nestedData['hrgjual_barang'] = $value['hrgjual_barang'];
            $nestedData['komisi'] = $value['komisi'];
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
