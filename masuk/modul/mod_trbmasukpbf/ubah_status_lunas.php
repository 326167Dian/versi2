<?php
session_start();
include "../../../configurasi/koneksi.php";

$module     = $_GET['module'];
$act        = $_GET['act'];
$count      = $_POST['check'];
$tgl_lunas  = date('Y-m-d', time());
$petugas    = $_SESSION['namalengkap'];

for ($i = 0; $i < count($count); $i++) {
    echo $count[$i] . '<br>';

    mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE trbmasuk SET 
                                                carabayar       = 'LUNAS',
                                                tgl_lunas       = '$tgl_lunas',
                                                petugas_lunas   = '$petugas'
                                                WHERE kd_trbmasuk = '$count[$i]'");
}

header('location:../../media_admin.php?module=trbmasukpbf');
