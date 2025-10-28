<?php

session_start();
include "../../../configurasi/koneksi.php";

$module         = $_GET['module'];
$act            = $_GET['act'];
$count          = $_POST['check'];
$id_supplier    = $_POST['id_supplier'];

//cek apakah ada kode transaksi ON berdasarkan user
$cekkd = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM kdbm WHERE id_admin='$_SESSION[id_admin]' AND id_resto='pesan' AND stt_kdbm='ON'");
$ketemucekkd = mysqli_num_rows($cekkd);
$hcekkd = mysqli_fetch_array($cekkd);

if ($ketemucekkd > 0) {
    $kdtransaksi = $hcekkd['kd_trbmasuk'];
} else {
    $kdunik = date('dmyhis')+1;
	$kdtransaksi = "ORD-" . $kdunik;
	mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO kdbm(kd_trbmasuk,id_resto,id_admin) VALUES('$kdtransaksi','pesan','$_SESSION[id_admin]')");
}

$tglharini = date('Y-m-d');
$ttl_trkasir = 0;

for ($i = 0; $i < count($count); $i++) {
    // echo $count[$i] . '<br>';
    
    $getbarang  = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang WHERE id_barang='$count[$i]'");
    $brg        = mysqli_fetch_array($getbarang);
    
    $id_barang      = $brg['id_barang'];
    $kd_barang      = $brg['kd_barang'];
    $nm_barang      = $brg['nm_barang'];
    $qty_retail     = $brg['t30'] - $brg['stok_barang'];
    $sat_barang     = $brg['sat_barang'];
    $konversi       = $brg['konversi'];
    $qty_grosir     = $qty_retail / $konversi;
    $sat_grosir     = $brg['sat_grosir'];
    $hna            = $brg['hna'];
    $hrgsat_barang  = $brg['hrgsat_barang'];
    $hrgjual_barang = $brg['hrgjual_barang'];
    $ttl_harga      = $hrgsat_barang * $qty_retail;
    $ttl_trkasir    = $ttl_trkasir + $ttl_harga;
    
    
    mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO ordersdetail(kd_trbmasuk,
										id_barang,
										kd_barang,
										nmbrg_dtrbmasuk,
										qty_dtrbmasuk,
										sat_dtrbmasuk,
										hrgsat_dtrbmasuk,
										hrgjual_dtrbmasuk,
										hnasat_dtrbmasuk,
										hrgttl_dtrbmasuk,
										konversi,
										satgrosir_dtrbmasuk,
										qtygrosir_dtrbmasuk)
								  VALUES('$kdtransaksi',
										'$id_barang',
										'$kd_barang',
										'$nm_barang',
										'$qty_retail',
										'$sat_barang',
										'$hrgsat_barang',
										'$hrgjual_barang',
										'$hna',
										'$ttl_harga',
										'$konversi',
										'$sat_grosir',
										'$qty_grosir')");
    
}

$getsupplier    = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM supplier WHERE id_supplier='$id_supplier'");
$supplier       = mysqli_fetch_array($getsupplier);
    
// mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO 
//         										orders(id_resto,
//         										petugas,
//         										kd_trbmasuk,
//         										tgl_trbmasuk,
//         										id_supplier,
//         										nm_supplier,
//         										tlp_supplier,
//         										alamat_trbmasuk,
//         										ttl_trbmasuk,
//         										dp_bayar,
//         										sisa_bayar,
//         										ket_trbmasuk)
//         								 VALUES('pesan',
//         								        '$_SESSION[namalengkap]',
//         										'$kdtransaksi',
//         										'$tglharini',
//         										'$id_supplier',
//         										'$supplier[nm_supplier]',
//         										'$supplier[tlp_supplier]',
//         										'$supplier[alamat_supplier]',
//         										'$ttl_trkasir',
//         										'0',
//         										'$ttl_trkasir',
//         										'')");

// mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE kdbm SET stt_kdbm = 'OFF' WHERE id_admin = '$_SESSION[idadmin]' AND id_resto = 'pesan' AND kd_trbmasuk = '$kdtransaksi'");
	
// echo $kdtransaksi;
header('location:../../media_admin.php?module=stok_kritis&act=orders&id='.$kdtransaksi);
