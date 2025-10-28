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

$module= "trbmasuk";
$stt_aksi=$_POST['stt_aksi'];
if($stt_aksi == "input_trbmasuk" || $stt_aksi == "ubah_trbmasuk"){
$act=$stt_aksi;
}else{
$act=$_GET['act'];
}


// Input admin
if ($module=='trbmasuk' AND $act=='input_trbmasuk'){

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
										carabayar,
										jenis)
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
										'$_POST[carabayar]',
										'nonpbf'
										)");
										
	mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO kartu_stok(kode_transaksi) VALUES('$_POST[kd_trbmasuk]')");
	
	mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE kdbm SET stt_kdbm = 'OFF' WHERE id_admin = '$_SESSION[id_admin]' AND id_resto = 'pusat' AND kd_trbmasuk = '$_POST[kd_trbmasuk]'");
										
										
	//echo "<script type='text/javascript'>alert('Transkasi berhasil ditambahkan !');window.location='../../media_admin.php?module=".$module."'</script>";
	

}
 //updata trbmasuk
 elseif ($module=='trbmasuk' AND $act=='ubah_trbmasuk'){
 

    mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE trbmasuk SET tgl_trbmasuk = '$_POST[tgl_trbmasuk]',
									id_supplier = '$_POST[id_supplier]',
									nm_supplier = '$_POST[nm_supplier]',
									tlp_supplier = '$_POST[tlp_supplier]',
									alamat_trbmasuk = '$_POST[alamat_trbmasuk]',
									ttl_trbmasuk = '$_POST[ttl_trkasir]',
									dp_bayar = '$_POST[dp_bayar]',
									sisa_bayar = '$_POST[sisa_bayar]',
									ket_trbmasuk = '$_POST[ket_trbmasuk]',
									carabayar = '$_POST[carabayar]'
									WHERE id_trbmasuk = '$_POST[id_trbmasuk]'");
										
										
	//echo "<script type='text/javascript'>alert('Transkasi berhasil Ubah !');window.location='../../media_admin.php?module=".$module."'</script>";
	
}
//Hapus Proyek
elseif ($module=='trbmasuk' AND $act=='hapus'){

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

}
?>
