<?php
include "../configurasi/koneksi.php";

$act = $_GET['act'];
if ($act == 'icon') {
    // code...
    // $cek = mysqli_query(
    //     $GLOBALS["___mysqli_ston"],
    //     "SELECT * FROM order_online WHERE kode_pesanan IS NOT NULL AND (status != 'selesai' OR status != 'SELESAI' OR status != 'Selesai')"
    //   );
    $cek = mysqli_query(
        $GLOBALS["___mysqli_ston"],
        "SELECT * FROM order_online WHERE kode_pesanan IS NOT NULL AND status != 'Selesai' AND status != 'Dibatalkan'"
      );
    
    $count = mysqli_num_rows($cek);
    echo $count;

    
} elseif ($act == 'data') {
    // code..
    // $data = mysqli_query(
    //     $GLOBALS["___mysqli_ston"],
    //     "SELECT * FROM order_online WHERE kode_pesanan IS NOT NULL AND (status != 'selesai' OR status != 'SELESAI')"
    //   );
    $data = mysqli_query(
        $GLOBALS["___mysqli_ston"],
        "SELECT * FROM order_online WHERE kode_pesanan IS NOT NULL AND status != 'Selesai' AND status != 'Dibatalkan'"
      );
    
    $json = [];
    while($re = mysqli_fetch_array($data)){
        // $json[] = $re['kode_pesanan'];
        $json[] = array(
                'kode'=> $re['kode_pesanan'],
			    'date'=> $re['created_at']
			);
    }
    echo json_encode($json);
    
}

?>