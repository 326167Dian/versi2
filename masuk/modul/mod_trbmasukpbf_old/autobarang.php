<?php
include "../../../configurasi/koneksi.php";

	$kod = $_POST['kd_brg'];

	$ubah=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang WHERE kd_barang = '$kod'");
	$re=mysqli_fetch_array($ubah);
      //$json[] = $re['nm_barang'];
	  $json[] = array(
	                'id_barang'=> $re['id_barang'],
        			'nm_barang'=> $re['nm_barang'],
        			'stok_barang'=> $re['stok_barang'],
        			'sat_barang'=> $re['sat_barang'],
        			'sat_grosir'=> $re['sat_grosir'],
        // 			'indikasi'=> $re['indikasi'],
        			'konversi'=> $re['konversi'],
        			'hrgjual_barang'=> $re['hrgjual_barang'],
        			'hrgsat_barang'=> $re['hrgsat_barang'],
        			'hna'=> $re['hna'],
        			'kd_barang'=> $re['kd_barang']
        	  );
 
	echo json_encode($json);

?>
