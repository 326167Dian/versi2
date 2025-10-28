<?php
include "../../../configurasi/koneksi.php";

$act = $_GET['act'];

if($act == 'update_order'){
    $id_dtrbmasuk       = $_POST['id_dtrbmasuk'];
    // $qty_dtrbmasuk      = ceil($_POST['qty_dtrbmasuk']/$_POST['konversi']);
    $qty_dtrbmasuk          = $_POST['qty_dtrbmasuk'];
    $qtygrosir_dtrbmasuk    = $_POST['qtygrosir_dtrbmasuk'];
    $konversi               = $_POST['konversi'];
    
    $cekdetail = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * 
                                FROM ordersdetail 
                                WHERE id_dtrbmasuk='$id_dtrbmasuk'");
    $ketemucekdetail = mysqli_num_rows($cekdetail);
    $rcek = mysqli_fetch_array($cekdetail);
    
    if ($ketemucekdetail > 0) {
        // $qtygrosir_dtrbmasuk    = $qty_dtrbmasuk / $rcek['konversi'];
        $hrgttl_dtrbmasuk       = $qty_dtrbmasuk * $rcek['hrgsat_dtrbmasuk'];
        $id_barang              = $rcek['id_barang'];
        
        mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE ordersdetail SET
                                                    qty_dtrbmasuk       = '$qty_dtrbmasuk',
                                                    konversi            = '$konversi',
                                                    qtygrosir_dtrbmasuk = '$qtygrosir_dtrbmasuk',
                                                    hrgttl_dtrbmasuk    = '$hrgttl_dtrbmasuk'
                                                WHERE id_dtrbmasuk  = '$id_dtrbmasuk'");
                                                
        mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET
                                                    konversi    = '$konversi'
                                                WHERE id_barang = '$id_barang'");                                        
    }
    
}
elseif($act == 'satgrosir'){
    $id_dtrbmasuk           = $_POST['id_dtrbmasuk'];
    $satgrosir_dtrbmasuk    = $_POST['satgrosir_dtrbmasuk'];
    $id_barang              = $_POST['id_barang'];
    
    mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE ordersdetail SET
                                                satgrosir_dtrbmasuk = '$satgrosir_dtrbmasuk'
                                                WHERE id_dtrbmasuk  = '$id_dtrbmasuk'");
                                                
    mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET
                                                    sat_grosir  = '$satgrosir_dtrbmasuk'
                                                WHERE id_barang = '$id_barang'");                                               
}

?>