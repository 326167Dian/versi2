<?php
include_once '../../../configurasi/koneksi.php';

if ($_GET['action'] == "table_data") {

    $columns = array(
        0 => 'id_pelanggan',
        1 => 'nm_pelanggan',
        2 => 'tlp_pelanggan',
        3 => 'alamat_pelanggan',
        4 => 'ket_pelanggan',
        5 => 'id_pelanggan'
    );

    $querycount = $db->query("SELECT count(id_pelanggan) as jumlah FROM pelanggan");
    $datacount = $querycount->fetch_array();

    $totalData = $datacount['jumlah'];

    $totalFiltered = $totalData;

    $limit = $_POST['length'];
    $start = $_POST['start'];
    $order = $columns[$_POST['order']['0']['column']];
    $dir = $_POST['order']['0']['dir'];

    if (empty($_POST['search']['value'])) {
        $query = $db->query("SELECT id_pelanggan, nm_pelanggan, tlp_pelanggan, alamat_pelanggan, ket_pelanggan FROM pelanggan ORDER BY $order $dir LIMIT $limit OFFSET $start");
    } else {
        $search = $db->real_escape_string($_POST['search']['value']);
        $query = $db->query("SELECT id_pelanggan, nm_pelanggan, tlp_pelanggan, alamat_pelanggan, ket_pelanggan FROM pelanggan WHERE nm_pelanggan LIKE '%$search%' OR tlp_pelanggan LIKE '%$search%' OR alamat_pelanggan LIKE '%$search%' OR ket_pelanggan LIKE '%$search%' ORDER BY $order $dir LIMIT $limit OFFSET $start");

        $querycount = $db->query("SELECT count(id_pelanggan) as jumlah FROM pelanggan WHERE nm_pelanggan LIKE '%$search%' OR tlp_pelanggan LIKE '%$search%' OR alamat_pelanggan LIKE '%$search%' OR ket_pelanggan LIKE '%$search%'");
        $datacount = $querycount->fetch_array();
        $totalFiltered = $datacount['jumlah'];
    }

    $data = array();
    if (!empty($query)) {
        $no = $start + 1;
        while ($value = $query->fetch_array()) {
            $nestedData['no'] = $no;
            $nestedData['nm_pelanggan'] = $value['nm_pelanggan'];
            $nestedData['tlp_pelanggan'] = $value['tlp_pelanggan'];
            $nestedData['alamat_pelanggan'] = $value['alamat_pelanggan'];
            $nestedData['ket_pelanggan'] = $value['ket_pelanggan'];
            $nestedData['aksi'] = $value['id_pelanggan'];

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
