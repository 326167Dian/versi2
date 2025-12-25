<?php
include "../../../configurasi/koneksi.php";

$hnasat_dtrbmasuk       = str_replace(".","",$_POST['hnasat_dtrbmasuk']);
$kd_barang              = $_POST['kd_barang'];
$kd_trbmasuk            = $_POST['kd_trbmasuk'];
$kd_orders              = $_POST['kd_orders'];

$trbmasuk = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trbmasuk_detail 
                            WHERE kd_barang='$kd_barang' AND kd_trbmasuk='$kd_trbmasuk' AND id_dtrbmasuk='$_POST[id_dtrbmasuk]'");
$detail = mysqli_fetch_array($trbmasuk);
$cari   = mysqli_num_rows($trbmasuk);

if ($cari > 0) {
    // code...
    $id_dtrbmasuk   = $detail['id_dtrbmasuk'];
    // $harga_satuan   = round((($hnasat_dtrbmasuk * 1.11) * (1 - ($detail['diskon']/100))) / $detail['konversi']);
    // $harga_satuan   = round($hnasat_dtrbmasuk / $detail['konversi']);
    // // $harga_grosir   = round(($hnasat_dtrbmasuk * 1.11) * (1 - ($detail['diskon']/100)));
    // $harga_grosir   = round($hnasat_dtrbmasuk );
    // // $total_harga    = $harga_satuan * $detail['qty_dtrbmasuk'];
    // $total_harga    = round(($hnasat_dtrbmasuk * 1.11) * $_POST['qtygrosir_dtrbmasuk']) * (1 - ($detail['diskon']/100));
    
    // mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE trbmasuk_detail SET 
    //                                     hnasat_dtrbmasuk    = '$hnasat_dtrbmasuk',
				// 						hrgsat_dtrbmasuk    = '$harga_satuan',
				// 						hrgttl_dtrbmasuk    = '$total_harga'
				// 						WHERE id_dtrbmasuk  = '$id_dtrbmasuk'");
				
	$ceksql = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trbmasuk_detail 
                            WHERE kd_barang='$kd_barang' AND kd_trbmasuk='$kd_trbmasuk'");
    
    while($sq = mysqli_fetch_array($ceksql)){
        // $harga_satuan   = round($hnasat_dtrbmasuk / $sq['konversi']);
        $harga_satuan   = round(($hnasat_dtrbmasuk / $sq['konversi']) * (1-($sq['diskon']/100)) * 1.11);
        $harga_grosir   = round($hnasat_dtrbmasuk );
        $total_harga    = round(($hnasat_dtrbmasuk * 1.11) * $sq['qty_grosir']) * (1 - ($sq['diskon']/100));
        
        mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE trbmasuk_detail SET 
                                        hnasat_dtrbmasuk    = '$hnasat_dtrbmasuk',
										hrgsat_dtrbmasuk    = '$harga_satuan',
										hrgttl_dtrbmasuk    = '$total_harga'
										WHERE id_dtrbmasuk  = '$sq[id_dtrbmasuk]'");
		
		mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET 
                                                hrgsat_barang   = '$harga_satuan',
                                                hna             = '$hnasat_dtrbmasuk',
                                                hrgsat_grosir   = '$harga_grosir'
                                                WHERE id_barang = '$detail[id_barang]'");
    }                        
    
										
// 	mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET 
//                                                 hrgsat_barang   = '$harga_satuan',
//                                                 hna             = '$hnasat_dtrbmasuk',
//                                                 hrgsat_grosir   = '$harga_grosir'
//                                                 WHERE id_barang = '$detail[id_barang]'");
									
}
else {
    $order  = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM ordersdetail 
                            WHERE kd_barang='$kd_barang' AND kd_trbmasuk='$kd_orders'");
    $odt    = mysqli_fetch_array($order);
    
    $qty_dtrbmasuk  = $_POST['qtygrosir_dtrbmasuk'] * $odt['konversi'];
    // $harga_satuan   = round((($hnasat_dtrbmasuk * 1.11) * (1-($odt['diskon']/100))) / $odt['konversi']);
    // $total_harga    = (($hnasat_dtrbmasuk * 1.11) * $odt['qtygrosir_dtrbmasuk']) * (1 - ($odt['diskon']/100));
    // $harga_satuan   = round($hnasat_dtrbmasuk / $odt['konversi']);
    $harga_satuan   = round(($hnasat_dtrbmasuk / $odt['konversi']) * (1-($odt['diskon']/100)) * 1.11);
    $total_harga    = round(($hnasat_dtrbmasuk * 1.11) * $_POST['qtygrosir_dtrbmasuk']) * (1 - ($odt['diskon']/100));
    $harga_grosir   = round($hnasat_dtrbmasuk );
    
    $waktu          = date('Y-m-d H:i:s', time());
    
    // Update stok
    $cekstok        = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang 
                        WHERE id_barang = '$odt[id_barang]'");
    $rst            = mysqli_fetch_array($cekstok);
    $stok_barang    = $rst['stok_barang'];
    $stokakhir      = $stok_barang + ($qty_dtrbmasuk);
    
    $hrgjual_barang     = round($odt['hrgjual_dtrbmasuk']);
    $hrgjual_barang1    = round($odt['hrgjual_dtrbmasuk']*1.05);
    $hrgjual_barang3    = round($odt['hrgjual_dtrbmasuk']*1.22);
    
    // mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET 
    //                                             stok_barang     = '$stokakhir',
    //                                             hna             = '$hnasat_dtrbmasuk',
    //                                             hrgsat_barang   = '$harga_satuan',
    //                                             hrgsat_grosir   = '$harga_grosir',
    //                                             hrgjual_barang  = '$hrgjual_barang',
    //                                             hrgjual_barang1 = '$hrgjual_barang1',
    //                                             hrgjual_barang3 = '$hrgjual_barang3'
    //                                             WHERE id_barang = '$odt[id_barang]'");
    mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET 
                                                stok_barang     = '$stokakhir',
                                                hna             = '$hnasat_dtrbmasuk',
                                                hrgsat_barang   = '$harga_satuan',
                                                hrgsat_grosir   = '$harga_grosir',
                                                hrgjual_barang  = '$hrgjual_barang'
                                                WHERE id_barang = '$odt[id_barang]'");

    // Update order karena barang sudah masuk
    mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE ordersdetail SET 
                                                masuk     = '0'
                                                WHERE id_dtrbmasuk = '$odt[id_dtrbmasuk]'");
                                                
    // Insert trbmasuk detail
    mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO trbmasuk_detail(
                                        kd_trbmasuk,
                                        kd_orders,
										id_barang,
										kd_barang,
										nmbrg_dtrbmasuk,
										qty_dtrbmasuk,
										qty_grosir,
										sat_dtrbmasuk,
										satgrosir_dtrbmasuk,
										konversi,
										hnasat_dtrbmasuk,
										diskon,
										hrgsat_dtrbmasuk,										
										hrgjual_dtrbmasuk,										
										hrgttl_dtrbmasuk,
										no_batch,
										exp_date,
										waktu)
								  VALUES('$kd_trbmasuk',
								        '$kd_orders',
										'$odt[id_barang]',
										'$odt[kd_barang]',
										'$odt[nmbrg_dtrbmasuk]',
										'$qty_dtrbmasuk',
										'$_POST[qtygrosir_dtrbmasuk]',
										'$odt[sat_dtrbmasuk]',
										'$odt[satgrosir_dtrbmasuk]',
										'$odt[konversi]',
										'$hnasat_dtrbmasuk',
										'$odt[diskon]',
										'$harga_satuan',
										'$hrgjual_barang',
										'$total_harga',
										'$odt[no_batch]',
										'$odt[exp_date]',
										'$waktu'
										)");
										
	
}
?>