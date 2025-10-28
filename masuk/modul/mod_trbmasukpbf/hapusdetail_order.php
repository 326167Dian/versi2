<?php 
include "../../../configurasi/koneksi.php";

$id_dtrbmasuk  = $_POST['id_dtrbmasuk'];

// //ambil data
// $dataitem = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM ordersdetail 
//     WHERE id_dtrbmasuk='$id_dtrbmasuk'");
// $r = mysqli_fetch_array($dataitem);
// $kd_trbmasuk = $r['kd_trbmasuk'];

// $dataheader = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM orders 
//     WHERE kd_trbmasuk = '$kd_trbmasuk'");
// $r1 = mysqli_fetch_array($dataheader);
// $ttl_trbmasuk = $r1['ttl_trbmasuk'] - $r['hrgttl_dtrbmasuk'];
// $sisa = $ttl_trbmasuk - $r1['dp_bayar'];


// mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE orders SET ttl_trbmasuk = '$ttl_trbmasuk',
//                                                             sisa_bayar   = '$sisa'
//                                                             WHERE kd_trbmasuk = '$kd_trbmasuk'");
                                                            
// $trbmasuk = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trbmasuk_detail WHERE kd_orders='$r[kd_trbmasuk]' AND id_barang='$r[id_barang]'");
$trbmasuk = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trbmasuk_detail WHERE id_dtrbmasuk = '$id_dtrbmasuk'");
$r1 = mysqli_fetch_array($trbmasuk);
$r1_num = mysqli_num_rows($trbmasuk);

if ($r1_num > 0) {
    //update stok
    $cekstok=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id_barang, stok_barang FROM barang 
    WHERE id_barang='$r1[id_barang]'");
    $rst=mysqli_fetch_array($cekstok);
    
    $stok_barang = $rst['stok_barang'];
    $stokakhir = $stok_barang - $r1['qty_dtrbmasuk'];
    
    mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET stok_barang = '$stokakhir' WHERE id_barang = '$r1[id_barang]'");
    mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE ordersdetail SET masuk = '0' WHERE id_barang = '$r1[id_barang]' AND kd_trbmasuk = '$r1[kd_orders]'");
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
                                                    '$r1[kd_trbmasuk]',
                                                    '$r1[kd_orders]',
                                                    '$r1[id_barang]',
                                                    '$r1[kd_barang]',
                                                    '$r1[nmbrg_dtrbmasuk]',
                                                    '$r1[qty_dtrbmasuk]',
                                                    '$r1[sat_dtrbmasuk]',
                                                    '$r1[qty_grosir]',
                                                    '$r1[satgrosir_dtrbmasuk]',
                                                    '$r1[hnasat_dtrbmasuk]',
                                                    '$r1[diskon]',
                                                    '$r1[konversi]',
                                                    '$r1[hrgsat_dtrbmasuk]',
                                                    '$r1[hrgjual_dtrbmasuk]',
                                                    '$r1[hrgttl_dtrbmasuk]',
                                                    '$r1[no_batch]',
                                                    '$r1[exp_date]',
                                                    '$r1[waktu]',
                                                    '$r1[tipe]'
                                                    )");
    
    mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM trbmasuk_detail WHERE id_dtrbmasuk = '$r1[id_dtrbmasuk]'");
} else {
    mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE ordersdetail SET masuk = '0' WHERE id_dtrbmasuk = '$id_dtrbmasuk'");
}
    
// mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM ordersdetail WHERE id_dtrbmasuk = '$id_dtrbmasuk'");



?>
