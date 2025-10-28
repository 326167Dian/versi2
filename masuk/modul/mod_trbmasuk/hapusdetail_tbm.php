<?php 
include "../../../configurasi/koneksi.php";

$id_dtrbmasuk  = $_POST['id_dtrbmasuk'];

//ambil data
$ambildata=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trbmasuk_detail 
WHERE id_dtrbmasuk='$id_dtrbmasuk'");
$r=mysqli_fetch_array($ambildata);

$id_barang      = $r['id_barang'];
$qty_dtrbmasuk  = $r['qty_dtrbmasuk'];
$kd_trbmasuk    = $r['kd_trbmasuk'];
$no_batch       = $r['no_batch'];


//update stok
$cekstok=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id_barang, stok_barang FROM barang 
WHERE id_barang='$id_barang'");
$rst=mysqli_fetch_array($cekstok);

$stok_barang = $rst['stok_barang'];
$stokakhir = $stok_barang - $qty_dtrbmasuk;

// Update stok_barang
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
                                                    
// Hapus detail
mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM trbmasuk_detail WHERE id_dtrbmasuk = '$id_dtrbmasuk'");
mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM batch WHERE kd_transaksi = '$kd_trbmasuk' AND no_batch='$no_batch' AND status = 'masuk'");


?>
