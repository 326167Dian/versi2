<?php
error_reporting(0);
session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
include "../../../configurasi/koneksi.php";
include "../../../configurasi/fungsi_thumb.php";
include "../../../configurasi/library.php";

$module= "trbmasukpbf";
$stt_aksi=$_POST['stt_aksi'];
if($stt_aksi == "input_trbmasuk" || $stt_aksi == "ubah_trbmasuk" || $stt_aksi == "input_order_trbmasuk"){
$act=$stt_aksi;
}else{
$act=$_GET['act'];
}

$timestamp = date('Y-m-d H:i:s', time());

// Input admin
if ($module=='trbmasukpbf' AND $act=='input_trbmasuk'){

    if($_POST['carabayar'] == 'LUNAS'){
        $tgl_lunas      = date('Y-m-d', time());
        $petugas_lunas  = $_POST['petugas'];
    } else {
        $tgl_lunas      = '0000-00-00';
        $petugas_lunas  = '';
    }
    
    
    mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO 
										trbmasuk(id_resto,
										kd_trbmasuk,
										tgl_trbmasuk,
										id_supplier,
										petugas,
										nm_supplier,
										tlp_supplier,
										alamat_trbmasuk,
										ttl_trbmasuk,
										dp_bayar,
										sisa_bayar,
										ket_trbmasuk,
										jatuhtempo,
										carabayar,
										jenis,
										tgl_lunas,
										petugas_lunas)
								 VALUES('pusat',
										'$_POST[kd_trbmasuk]',
										'$_POST[tgl_trbmasuk]',
										'$_POST[id_supplier]',
										'$_POST[petugas]',
										'$_POST[nm_supplier]',
										'$_POST[tlp_supplier]',
										'$_POST[alamat_trbmasuk]',
										'$_POST[ttl_trkasir]',
										'$_POST[dp_bayar]',
										'$_POST[sisa_bayar]',
										'$_POST[ket_trbmasuk]',
										'$_POST[jatuhtempo]',
										'$_POST[carabayar]',
										'pbf',
										'$tgl_lunas',
										'$petugas_lunas'
										)");
										
	mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO kartu_stok(kode_transaksi) VALUES('$_POST[kd_trbmasuk]')");
	
	mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE kdbm SET stt_kdbm = 'OFF' WHERE id_admin = '$_SESSION[id_admin]' AND id_resto = 'pusat' AND kd_trbmasuk = '$_POST[kd_trbmasuk]'");
										
										
	//echo "<script type='text/javascript'>alert('Transkasi berhasil ditambahkan !');window.location='../../media_admin.php?module=".$module."'</script>";
	

}
 //updata trbmasukpbf
 elseif ($module=='trbmasukpbf' AND $act=='ubah_trbmasuk'){
 
    if($_POST['carabayar'] == 'LUNAS'){
        $tgl_lunas      = date('Y-m-d', time());
        $petugas_lunas  = $_POST['petugas'];
    } else {
        $tgl_lunas      = '0000-00-00';
        $petugas_lunas  = '';
    }
    
    
    mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE trbmasuk SET tgl_trbmasuk = '$_POST[tgl_trbmasuk]',
									id_supplier     = '$_POST[id_supplier]',
									nm_supplier     = '$_POST[nm_supplier]',
									tlp_supplier    = '$_POST[tlp_supplier]',
									alamat_trbmasuk = '$_POST[alamat_trbmasuk]',
									ttl_trbmasuk    = '$_POST[ttl_trkasir]',
									dp_bayar        = '$_POST[dp_bayar]',
									sisa_bayar      = '$_POST[sisa_bayar]',
									ket_trbmasuk    = '$_POST[ket_trbmasuk]',
									jatuhtempo      = '$_POST[jatuhtempo]',
									carabayar       = '$_POST[carabayar]',
									tgl_lunas       = '$tgl_lunas',
									petugas_lunas   = '$petugas_lunas'
									WHERE id_trbmasuk = '$_POST[id_trbmasuk]'");
										
										
	//echo "<script type='text/javascript'>alert('Transkasi berhasil Ubah !');window.location='../../media_admin.php?module=".$module."'</script>";
	
}
//Hapus Proyek
elseif ($module=='trbmasukpbf' AND $act=='hapus'){

  //update bagian stok dulu
  //ambil data induk
	$ambildatainduk=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id_trbmasuk, kd_trbmasuk FROM trbmasuk 
	WHERE id_trbmasuk='$_GET[id]'");
	$r1=mysqli_fetch_array($ambildatainduk);
	$kd_trbmasuk = $r1['kd_trbmasuk'];
	
	//loop data detail
	//ambil data induk
	$ambildatadetail=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trbmasuk_detail WHERE kd_trbmasuk='$kd_trbmasuk'");
	while ($r=mysqli_fetch_array($ambildatadetail)){
	
	$id_dtrbmasuk = $r['id_dtrbmasuk'];
	$id_barang = $r['id_barang'];
	$qty_dtrbmasuk = $r['qty_dtrbmasuk'];

	//update stok
	$cekstok=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id_barang, stok_barang FROM barang 
	WHERE id_barang='$id_barang'");
	$rst=mysqli_fetch_array($cekstok);

	$stok_barang = $rst['stok_barang'];
	$stokakhir = $stok_barang - $qty_dtrbmasuk;

	mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET stok_barang = '$stokakhir' WHERE id_barang = '$id_barang'");
	// Insert History Deleted
    mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO trbmasuk_detail_hist (
                                                    kd_trbmasuk,
                                                    kd_orders,
                                                    id_barang,
                                                    kd_barang,
                                                    nmbrg_dtrbmasuk,
                                                    qty_dtrbmasuk,
                                                    sat_dtrbmasuk,
                                                    qty_grosir,
                                                    satgrosir_dtrbmasuk,
                                                    hnasat_dtrbmasuk,
                                                    diskon,
                                                    konversi,
                                                    hrgsat_dtrbmasuk,
                                                    hrgjual_dtrbmasuk,
                                                    hrgttl_dtrbmasuk,
                                                    no_batch,
                                                    exp_date,
                                                    waktu,
                                                    tipe
                                                    ) 
                                                VALUES (
                                                    '$r[kd_trbmasuk]',
                                                    '$r[kd_orders]',
                                                    '$r[id_barang]',
                                                    '$r[kd_barang]',
                                                    '$r[nmbrg_dtrbmasuk]',
                                                    '$r[qty_dtrbmasuk]',
                                                    '$r[sat_dtrbmasuk]',
                                                    '$r[qty_grosir]',
                                                    '$r[satgrosir_dtrbmasuk]',
                                                    '$r[hnasat_dtrbmasuk]',
                                                    '$r[diskon]',
                                                    '$r[konversi]',
                                                    '$r[hrgsat_dtrbmasuk]',
                                                    '$r[hrgjual_dtrbmasuk]',
                                                    '$r[hrgttl_dtrbmasuk]',
                                                    '$r[no_batch]',
                                                    '$r[exp_date]',
                                                    '$r[waktu]',
                                                    '$r[tipe]'
                                                    )");
	mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM trbmasuk_detail WHERE id_dtrbmasuk = '$id_dtrbmasuk'");
	mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM batch WHERE kd_transaksi = '$r[kd_trbmasuk]' AND no_batch='$r[no_batch]' AND status = 'masuk'");
	
	}

  mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM trbmasuk WHERE id_trbmasuk = '$_GET[id]'");
  mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM kartu_stok WHERE kode_transaksi = '$kd_trbmasuk'");

    $module2 = $_GET['module2'];
  echo "<script type='text/javascript'>alert('Data berhasil dihapus !');window.location='../../media_admin.php?module=".$module2."'</script>";
}
//Input order trbmasuk
elseif ($module=='trbmasukpbf' AND $act=='input_order_trbmasuk'){
    
    if($_POST['carabayar'] == 'LUNAS'){
        $tgl_lunas      = date('Y-m-d', time());
        $petugas_lunas  = $_POST['petugas'];
    } else {
        $tgl_lunas      = '0000-00-00';
        $petugas_lunas  = '';
    }
    
    mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO 
										trbmasuk(id_resto,
										kd_trbmasuk,
										kd_orders,
										tgl_trbmasuk,
										id_supplier,
										petugas,
										nm_supplier,
										tlp_supplier,
										alamat_trbmasuk,
										ttl_trbmasuk,
										dp_bayar,
										sisa_bayar,
										ket_trbmasuk,
										jatuhtempo,
										carabayar,
										jenis,
										tgl_lunas,
										petugas_lunas)
								 VALUES('pusat',
										'$_POST[kd_trbmasuk1]',
										'$_POST[kd_trbmasuk]',
										'$_POST[tgl_trbmasuk]',
										'$_POST[id_supplier]',
										'$_POST[petugas]',
										'$_POST[nm_supplier]',
										'$_POST[tlp_supplier]',
										'$_POST[alamat_trbmasuk]',
										'$_POST[ttl_trkasir]',
										'$_POST[dp_bayar]',
										'$_POST[sisa_bayar]',
										'$_POST[ket_trbmasuk]',
										'$_POST[jatuhtempo]',
										'$_POST[carabayar]',
										'pbf',
										'$tgl_lunas',
										'$petugas_lunas'
										)");
	
	
// 	$ambildatadetail=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM ordersdetail WHERE kd_trbmasuk='$_POST[kd_trbmasuk]'");
// 	while ($r=mysqli_fetch_array($ambildatadetail)){
	    
	   // $cekdetail=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang 
    //         WHERE kd_barang='$r[kd_barang]' AND id_barang='$r[id_barang]'");
    //     $rcek=mysqli_fetch_array($cekdetail);
	    
// 	    mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO trbmasuk_detail(
//                                         kd_trbmasuk,
// 										id_barang,
// 										kd_barang,
// 										nmbrg_dtrbmasuk,
// 										qty_dtrbmasuk,
// 										qty_grosir,
// 										sat_dtrbmasuk,
// 										satgrosir_dtrbmasuk,
// 										konversi,
// 										hnasat_dtrbmasuk,
// 										diskon,
// 										hrgsat_dtrbmasuk,										
// 										hrgjual_dtrbmasuk,										
// 										hrgttl_dtrbmasuk,
// 										no_batch,
// 										exp_date,
// 										waktu)
// 								  VALUES('$_POST[kd_trbmasuk1]',
// 										'$r[id_barang]',
// 										'$r[kd_barang]',
// 										'$r[nmbrg_dtrbmasuk]',
// 										'$r[qty_dtrbmasuk]',
// 										'$r[qtygrosir_dtrbmasuk]',
// 										'$r[sat_dtrbmasuk]',
// 										'$r[satgrosir_dtrbmasuk]',
// 										'$r[konversi]',
// 										'$r[hnasat_dtrbmasuk]',
// 										'$r[diskon]',
// 										'$r[hrgsat_dtrbmasuk]',
// 										'$r[hrgjual_dtrbmasuk]',
// 										'$r[hrgttl_dtrbmasuk]',
// 										'$r[no_batch]',
// 										'$r[exp_date]',
// 										'$timestamp'										
// 										)");
										
		
		//update stok
        // $cekstok=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang 
        //     WHERE id_barang='$r[id_barang]'");
        // $rst=mysqli_fetch_array($cekstok);
    
        // $stok_barang = $rcek['stok_barang'];
        // $stokakhir = $stok_barang + $r['qty_dtrbmasuk'];
    
        // $hrgjual_barang=round($r['hrgjual_dtrbmasuk']) ;
        // $hrgjual_barang1=round($r['hrgjual_dtrbmasuk']*1,0) ;
        // $hrgjual_barang3=round($r['hrgjual_dtrbmasuk']*1.22,0) ;
        
    
        // mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET 
        //                                       stok_barang = '$stokakhir', 
        //                                       hna = '$rcek[hna]',
        //                                       hrgsat_barang = '$r[sat_dtrbmasuk]',
        //                                       hrgjual_barang='$hrgjual_barang',
        //                                       hrgjual_barang1='$hrgjual_barang',
        //                                       hrgjual_barang3='$hrgjual_barang3'
        //                                       WHERE id_barang = '$r[id_barang]'");
								
// 	}	
	
	$caridetail = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trbmasuk_detail WHERE kd_trbmasuk = '$_POST[kd_trbmasuk1]'");
	$datetime = date('Y-m-d H:i:s', time());
	
	while($row = mysqli_fetch_array($caridetail)){
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
								        '$row[no_batch]',
										'$row[exp_date]',
										'$row[qty_dtrbmasuk]',
										'$row[sat_dtrbmasuk]',
										'$row[kd_trbmasuk]',
										'$row[kd_barang]',
										'masuk'
										)");    
	}
	
	mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO trx_orders(kd_trbmasuk, kd_orders) VALUES('$_POST[kd_trbmasuk1]','$_POST[kd_trbmasuk]')");
	mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO kartu_stok(kode_transaksi) VALUES('$_POST[kd_trbmasuk1]')");
	
	
	mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE kdbm SET stt_kdbm = 'OFF' WHERE id_admin = '$_SESSION[id_admin]' AND id_resto = 'pusat' AND kd_trbmasuk = '$_POST[kd_trbmasuk1]'");
										
	
}
}
?>
