<?php
include "../../../configurasi/koneksi.php";

$kd_trbmasuk = $_POST['kd_trbmasuk'];
$id_barang = $_POST['id_barang'];
$kd_barang = $_POST['kd_barang'];
$nmbrg_dtrbmasuk = $_POST['nmbrg_dtrbmasuk'];
$qty_dtrbmasuk = $_POST['qty_dtrbmasuk'];
$sat_dtrbmasuk = $_POST['sat_dtrbmasuk'];
$hrgsat_dtrbmasuk = round($_POST['hrgsat_dtrbmasuk'],0);
$hrgjual_dtrbmasuk = round($_POST['hrgjual_dtrbmasuk'],0);

$no_batch = $_POST['no_batch'];
$exp_date = date('Y-m-d', strtotime($_POST['exp_date']));

$datetime = date('Y-m-d H:i:s', time());

if ($_POST['exp_date']=='')
{ $tgl_awal = date('Y-m-d');
    $exp_date=date('Y-m-d', strtotime('+720 days', strtotime( $tgl_awal)));
}
else {
    $exp_date = $_POST['exp_date'];
}

if ($qty_dtrbmasuk == "") {
	$qty_dtrbmasuk = "1";
} else {
}

//cek apakah barang sudah ada
$cekdetail = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trbmasuk_detail 
                WHERE kd_barang='$kd_barang' 
                AND kd_trbmasuk='$kd_trbmasuk'
                AND no_batch='$no_batch'");

$ketemucekdetail = mysqli_num_rows($cekdetail);
$rcek = mysqli_fetch_array($cekdetail);
if ($ketemucekdetail > 0) {

	$id_dtrbmasuk = $rcek['id_dtrbmasuk'];
	$qtylama = $rcek['qty_dtrbmasuk'];
	$ttlqty = $qtylama + $qty_dtrbmasuk;
	$ttlharga = $ttlqty * $hrgsat_dtrbmasuk;

	mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE trbmasuk_detail SET qty_dtrbmasuk = '$ttlqty',
										hrgsat_dtrbmasuk = '$hrgsat_dtrbmasuk',
										hrgjual_dtrbmasuk = '$hrgjual_dtrbmasuk',
										hrgttl_dtrbmasuk = '$ttlharga',
										no_batch = '$no_batch',
										exp_date = '$exp_date'
										WHERE id_dtrbmasuk = '$id_dtrbmasuk'");

	//update stok
	$cekstok = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang WHERE id_barang='$id_barang'");
	$rst = mysqli_fetch_array($cekstok);

	$stok_barang = $rst['stok_barang'];
	$stokakhir = (($stok_barang - $qtylama) + $ttlqty);

	mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET 
		stok_barang = '$stokakhir',
		sat_barang = '$sat_dtrbmasuk',
        hrgsat_barang = '$hrgsat_dtrbmasuk'
		WHERE id_barang = '$id_barang'");
		
	//cek apakah barang dengan no batch yang dimaksud sudah ada
    $cekbatchdetail=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT no_batch, kd_transaksi,qty
                                                                FROM batch 
                                                                WHERE no_batch = '$no_batch' 
                                                                AND kd_transaksi = '$kd_trbmasuk' 
                                                                AND kd_barang = '$kd_barang'
                                                                AND status = 'masuk'");
    $ketemucekbatchdetail=mysqli_num_rows($cekbatchdetail);
    
    if($ketemucekbatchdetail>0)
    {
        //tarikstok dari batch
        $tampung = mysqli_fetch_array($cekbatchdetail);
        $qtybatchlama = $tampung['qty'];
        $qtybatchbaru = $qtybatchlama + $qty_dtrbmasuk;

        mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE batch SET qty = '$qtybatchbaru' 
                    WHERE kd_transaksi = '$kd_trbmasuk' 
                          AND no_batch = '$no_batch'
                          AND kd_barang = '$kd_barang'
                          AND status = 'masuk'");

    }
} else {

    //Query barang
    $cekstok = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang WHERE id_barang='$id_barang'");
    $rst = mysqli_fetch_array($cekstok);
    
	$ttlharga = $qty_dtrbmasuk * $hrgsat_dtrbmasuk;
	
	mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO trbmasuk_detail(kd_trbmasuk,
										id_barang,
										kd_barang,
										nmbrg_dtrbmasuk,
										qty_dtrbmasuk,
										sat_dtrbmasuk,
										hrgsat_dtrbmasuk,
										hrgjual_dtrbmasuk,
										hrgttl_dtrbmasuk,
										hnasat_dtrbmasuk,
										no_batch,
										exp_date)
								  VALUES('$kd_trbmasuk',
										'$id_barang',
										'$kd_barang',
										'$nmbrg_dtrbmasuk',
										'$qty_dtrbmasuk',
										'$sat_dtrbmasuk',
										'$hrgsat_dtrbmasuk',
										'$hrgjual_dtrbmasuk',
										'$ttlharga',
										'$rst[hna]',
										'$no_batch',
										'$exp_date')");

	//update stok
	$stok_barang = $rst['stok_barang'];
	$stokakhir = $stok_barang + $qty_dtrbmasuk;
	
    $hrgjual_barang=round($hrgjual_dtrbmasuk) ;
    

	mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET 
		stok_barang = '$stokakhir',
		sat_barang = '$sat_dtrbmasuk',
        hrgsat_barang = '$hrgsat_dtrbmasuk',
        hrgjual_barang='$hrgjual_barang'
		WHERE id_barang = '$id_barang'");
		
	mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO batch(
	                                    tgl_transaksi,
                                        no_batch,
                                        exp_date,
                                        qty,
                                        satuan,
                                        kd_transaksi,										
										kd_barang,
										status
										)
								  VALUES('$datetime',
								        '$no_batch',
										'$exp_date',
										'$qty_dtrbmasuk',
										'$sat_dtrbmasuk',
										'$kd_trbmasuk',
										'$kd_barang',
										'masuk'
										)");	
}
