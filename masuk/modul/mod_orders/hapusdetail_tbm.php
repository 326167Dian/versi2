<?php 
include "../../../configurasi/koneksi.php";

$id_dtrbmasuk  = $_POST['id_dtrbmasuk'];

//ambil data
$ambildata=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM ordersdetail 
WHERE id_dtrbmasuk='$id_dtrbmasuk'");
$r=mysqli_fetch_array($ambildata);

$id_barang = $r['id_barang'];
$qty_dtrbmasuk = $r['qty_dtrbmasuk'];

//update stok
$cekstok=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id_barang, stok_barang FROM barang 
WHERE id_barang='$id_barang'");
$rst=mysqli_fetch_array($cekstok);

$stok_barang = $rst['stok_barang'];
$stokakhir = $stok_barang ;

// Update stok
mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET stok_barang = '$stokakhir' WHERE id_barang = '$id_barang'");

// Insert history
mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO ordersdetail_hist(
                                            kd_trbmasuk,
                                            id_barang,
                                            kd_barang,
                                            nmbrg_dtrbmasuk,
                                            qty_dtrbmasuk,
                                            sat_dtrbmasuk,
                                            hnasat_dtrbmasuk,
                                            diskon,
                                            konversi,
                                            hrgsat_dtrbmasuk,
                                            hrgjual_dtrbmasuk,
                                            hrgttl_dtrbmasuk,
                                            qtygrosir_dtrbmasuk,
                                            satgrosir_dtrbmasuk,
                                            no_batch,
                                            exp_date,
                                            masuk
                                            )
                                        VALUES (
                                            '$r[kd_trbmasuk]',
                                            '$r[id_barang]',
                                            '$r[kd_barang]',
                                            '$r[nmbrg_dtrbmasuk]',
                                            '$r[qty_dtrbmasuk]',
                                            '$r[sat_dtrbmasuk]',
                                            '$r[hnasat_dtrbmasuk]',
                                            '$r[diskon]',
                                            '$r[konversi]',
                                            '$r[hrgsat_dtrbmasuk]',
                                            '$r[hrgjual_dtrbmasuk]',
                                            '$r[hrgttl_dtrbmasuk]',
                                            '$r[qtygrosir_dtrbmasuk]',
                                            '$r[satgrosir_dtrbmasuk]',
                                            '$r[no_batch]',
                                            '$r[exp_date]',
                                            '$r[masuk]'
                                            )");

// Hapus
mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM ordersdetail WHERE id_dtrbmasuk = '$id_dtrbmasuk'");

?>
