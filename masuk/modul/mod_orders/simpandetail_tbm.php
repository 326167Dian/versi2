<?php
include "../../../configurasi/koneksi.php";

$kd_trbmasuk            = $_POST['kd_trbmasuk'];
$id_barang              = $_POST['id_barang'];
$kd_barang              = $_POST['kd_barang'];
$nmbrg_dtrbmasuk        = $_POST['nmbrg_dtrbmasuk'];
$qty_dtrbmasuk          = $_POST['qty_dtrbmasuk'];
$sat_dtrbmasuk          = $_POST['sat_dtrbmasuk'];
$hrgsat_dtrbmasuk       = $_POST['hrgsat_dtrbmasuk'];
$satgrosir_dtrbmasuk    = $_POST['satgrosir_dtrbmasuk'];
$qtygrosir_dtrbmasuk    = $_POST['qtygrosir_dtrbmasuk'];
$konversi               = $_POST['konversi'];

if ($qty_dtrbmasuk == "") {
	$qty_dtrbmasuk = "1";
} else {
}

//cek apakah barang sudah ada
$cekdetail = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * 
FROM ordersdetail 
WHERE kd_barang='$kd_barang' AND kd_trbmasuk='$kd_trbmasuk'");

$ketemucekdetail = mysqli_num_rows($cekdetail);
$rcek = mysqli_fetch_array($cekdetail);
if ($ketemucekdetail > 0) {

	$id_dtrbmasuk = $rcek['id_dtrbmasuk'];
	$qtylama = $rcek['qty_dtrbmasuk'];
	$ttlqty = $qtylama + $qty_dtrbmasuk;
	$ttlharga = $ttlqty * $hrgsat_dtrbmasuk;
	$qtygrosirlama =  $rcek['qtygrosir_dtrbmasuk'];
	$qtygrosirbaru = $qtygrosirlama + $qtygrosir_dtrbmasuk ;

	mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE ordersdetail SET qty_dtrbmasuk = '$ttlqty',
										hrgsat_dtrbmasuk = '$hrgsat_dtrbmasuk',
										hrgttl_dtrbmasuk = '$ttlharga',
										satgrosir_dtrbmasuk = '$satgrosir_dtrbmasuk',
										qtygrosir_dtrbmasuk = '$qtygrosirbaru'
										WHERE id_dtrbmasuk = '$id_dtrbmasuk'");

	//update stok
	$cekstok = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id_barang, stok_barang FROM barang 
        WHERE id_barang='$id_barang'");
	$rst = mysqli_fetch_array($cekstok);

	$stok_barang = $rst['stok_barang'];
	$stokakhir = $stok_barang;

	mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET stok_barang = '$stokakhir' WHERE id_barang = '$id_barang'");
} else {

    $cekstok = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang 
        WHERE id_barang='$id_barang'");
	$rst = mysqli_fetch_array($cekstok);
	
// 	$ttlharga = $qty_dtrbmasuk * $hrgsat_dtrbmasuk;
	$ttlharga = $rst['hna'] * $qtygrosir_dtrbmasuk;

	mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO ordersdetail(kd_trbmasuk,
										id_barang,
										kd_barang,
										nmbrg_dtrbmasuk,
										qty_dtrbmasuk,
										sat_dtrbmasuk,
										konversi,
										hrgsat_dtrbmasuk,
										hrgttl_dtrbmasuk,
										hrgjual_dtrbmasuk,
										satgrosir_dtrbmasuk,
										qtygrosir_dtrbmasuk,
										hnasat_dtrbmasuk)
								  VALUES('$kd_trbmasuk',
										'$id_barang',
										'$kd_barang',
										'$nmbrg_dtrbmasuk',
										'$qty_dtrbmasuk',
										'$sat_dtrbmasuk',
										'$konversi',
										'$hrgsat_dtrbmasuk',
										'$ttlharga',
										'$rst[hrgjual_barang]',
										'$satgrosir_dtrbmasuk',
										'$qtygrosir_dtrbmasuk',
										'$rst[hna]')");

	//update stok
	$stok_barang = $rst['stok_barang'];
	$stokakhir = $stok_barang;

	mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang 
	                                          SET stok_barang = '$stokakhir',
	                                                 konversi = '$konversi',
	                                                 sat_grosir = '$satgrosir_dtrbmasuk'
	                                          WHERE id_barang = '$id_barang'");
}
