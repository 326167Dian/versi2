<?php
session_start();
include "../../../configurasi/koneksi.php";

$id_dtrkasir  = $_POST['id_dtrkasir'];

//ambil data
$ambildata=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir_detail 
WHERE id_dtrkasir='$id_dtrkasir'");
$r=mysqli_fetch_array($ambildata);

$id_barang      = $r['id_barang'];
$qty_dtrkasir   = $r['qty_dtrkasir'];
$kd_trbmasuk    = $r['kd_trkasir'];
$no_batch       = $r['no_batch'];

//update stok

$cekstok=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id_barang, stok_barang FROM barang 
WHERE id_barang='$id_barang'");
$rst=mysqli_fetch_array($cekstok);

$stok_barang = $rst['stok_barang'];
$stokakhir = $stok_barang + $qty_dtrkasir;

// Update stok barang
mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET stok_barang = '$stokakhir' WHERE id_barang = '$id_barang'");
// Insert into history
mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO trkasir_detail_hist (
                                            kd_trkasir,
                                            id_barang,
                                            kd_barang,
                                            nmbrg_dtrkasir,
                                            qty_dtrkasir,
                                            sat_dtrkasir,
                                            hrgjual_dtrkasir,
                                            disc,
                                            hrgttl_dtrkasir,
                                            no_batch,
                                            exp_date,
                                            waktu,
                                            tipe,
                                            komisi,
                                            idadmin
                                            ) 
                                        VALUES (
                                            '$r[kd_trkasir]',
                                            '$r[id_barang]',
                                            '$r[kd_barang]',
                                            '$r[nmbrg_dtrkasir]',
                                            '$r[qty_dtrkasir]',
                                            '$r[sat_dtrkasir]',
                                            '$r[hrgjual_dtrkasir]',
                                            '$r[disc]',
                                            '$r[hrgttl_dtrkasir]',
                                            '$r[no_batch]',
                                            '$r[exp_date]',
                                            '$r[waktu]',
                                            '$r[tipe]',
                                            '$r[komisi]',
                                            '$r[idadmin]'
                                            )");
// Hapus detail
mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM trkasir_detail WHERE id_dtrkasir = '$id_dtrkasir'");

mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM komisi_pegawai WHERE id_dtrkasir = '$id_dtrkasir'");
mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM batch WHERE kd_transaksi = '$kd_trbmasuk' AND no_batch='$no_batch' AND status = 'keluar'");

echo $stokakhir;
?>
