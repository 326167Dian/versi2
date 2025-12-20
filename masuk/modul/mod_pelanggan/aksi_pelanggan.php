<?php
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

$module=$_GET['module'];
$act=$_GET['act'];

// Input admin
if ($module=='pelanggan' AND $act=='input_pelanggan'){

$cekganda=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM pelanggan WHERE nm_pelanggan='$_POST[nm_pelanggan]'AND tlp_pelanggan='$_POST[tlp_pelanggan]'");
$ada=mysqli_num_rows($cekganda);
if ($ada > 0){
echo "<script type='text/javascript'>alert('Nama Pelanggan dengan nomor telepon ini sudah ada!');history.go(-1);</script>";
}else{

    mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO pelanggan(nm_pelanggan, tlp_pelanggan, alamat_pelanggan, ket_pelanggan)
								 VALUES('$_POST[nm_pelanggan]','$_POST[tlp_pelanggan]','$_POST[alamat_pelanggan]','$_POST[ket_pelanggan]')");
										
										
	//echo "<script type='text/javascript'>alert('Data berhasil ditambahkan !');window.location='../../media_admin.php?module=".$module."'</script>";
	header('location:../../media_admin.php?module='.$module);

}
}
 //updata pelanggan
 elseif ($module=='pelanggan' AND $act=='update_pelanggan'){
 
     mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE pelanggan SET nm_pelanggan = '$_POST[nm_pelanggan]',
									tlp_pelanggan = '$_POST[tlp_pelanggan]',
									alamat_pelanggan = '$_POST[alamat_pelanggan]',
									ket_pelanggan = '$_POST[ket_pelanggan]'
									WHERE id_pelanggan = '$_POST[id]'");
									
	//echo "<script type='text/javascript'>alert('Data berhasil diubah !');window.location='../../media_admin.php?module=".$module."'</script>";
	header('location:../../media_admin.php?module='.$module);
	
}
//Hapus Proyek
elseif ($module=='pelanggan' AND $act=='hapus'){

  mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM pelanggan WHERE id_pelanggan = '$_GET[id]'");
  //echo "<script type='text/javascript'>alert('Data berhasil dihapus !');window.location='../../media_admin.php?module=".$module."'</script>";
  header('location:../../media_admin.php?module='.$module);
}

// Input Riwayat Pelanggan
elseif ($module=='pelanggan' AND $act=='input_riwayat'){
    // CSRF check
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['csrf_pelanggan']){
        $_SESSION['flash'] = "<div class='alert alert-danger'>Token tidak valid. Coba ulangi.</div>";
        header('location:../../media_admin.php?module='.$module.'&act=riwayat&id=' . intval($_POST['id_pelanggan']));
        exit;
    }
    // basic validation
    $id_p = intval($_POST['id_pelanggan']);
    $tgl = $_POST['tgl'];
    $diagnosa = trim($_POST['diagnosa']);
    $tindakan = trim($_POST['tindakan']);
    $followup = trim($_POST['followup']);

    if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl)){
        $_SESSION['flash'] = "<div class='alert alert-danger'>Format tanggal tidak valid.</div>";
        header('location:../../media_admin.php?module='.$module.'&act=riwayat&id='.$id_p);
        exit;
    }

    // ensure pelanggan exists
    $cekpel = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id_pelanggan FROM pelanggan WHERE id_pelanggan='$id_p'");
    if(mysqli_num_rows($cekpel) < 1){
        $_SESSION['flash'] = "<div class='alert alert-danger'>Pelanggan tidak ditemukan.</div>";
        header('location:../../media_admin.php?module='.$module);
        exit;
    }

    $diagnosa = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $diagnosa);
    $tindakan = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $tindakan);
    $followup = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $followup);

    mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO riwayat_pelanggan(id_pelanggan, tgl, diagnosa, tindakan, followup, created_at)
                                VALUES('$id_p', '$tgl', '$diagnosa', '$tindakan', '$followup', NOW())");

    // invalidate token so it can't be reused
    unset($_SESSION['csrf_pelanggan']);

    $_SESSION['flash'] = "<div class='alert alert-success'>Riwayat berhasil disimpan.</div>";
    header('location:../../media_admin.php?module='.$module.'&act=riwayat&id='.$id_p);
}

// Update Riwayat Pelanggan
elseif ($module=='pelanggan' AND $act=='update_riwayat'){
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['csrf_pelanggan']){
        $_SESSION['flash'] = "<div class='alert alert-danger'>Token tidak valid. Coba ulangi.</div>";
        header('location:../../media_admin.php?module='.$module.'&act=riwayat&id=' . intval($_POST['id_pelanggan']));
        exit;
    }
    $id_p = intval($_POST['id_pelanggan']);
    $id_r = intval($_POST['id_riwayat']);
    $tgl = $_POST['tgl'];
    $diagnosa = trim($_POST['diagnosa']);
    $tindakan = trim($_POST['tindakan']);
    $followup = trim($_POST['followup']);

    if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl)){
        $_SESSION['flash'] = "<div class='alert alert-danger'>Format tanggal tidak valid.</div>";
        header('location:../../media_admin.php?module='.$module.'&act=edit_riwayat&id='.$id_p.'&idr='.$id_r);
        exit;
    }

    $cek = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id FROM riwayat_pelanggan WHERE id='$id_r' AND id_pelanggan='$id_p'");
    if(mysqli_num_rows($cek) < 1){
        $_SESSION['flash'] = "<div class='alert alert-danger'>Riwayat tidak ditemukan.</div>";
        header('location:../../media_admin.php?module='.$module.'&act=riwayat&id='.$id_p);
        exit;
    }

    $diagnosa = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $diagnosa);
    $tindakan = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $tindakan);
    $followup = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $followup);

    mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE riwayat_pelanggan SET tgl='$tgl', diagnosa='$diagnosa', tindakan='$tindakan', followup='$followup' WHERE id='$id_r'");

    unset($_SESSION['csrf_pelanggan']);
    $_SESSION['flash'] = "<div class='alert alert-success'>Riwayat berhasil diperbarui.</div>";
    header('location:../../media_admin.php?module='.$module.'&act=riwayat&id='.$id_p);
}

// Hapus Riwayat Pelanggan
elseif ($module=='pelanggan' AND $act=='hapus_riwayat'){
    if (!isset($_GET['token']) || $_GET['token'] !== $_SESSION['csrf_pelanggan']){
        $_SESSION['flash'] = "<div class='alert alert-danger'>Token tidak valid. Coba ulangi.</div>";
        header('location:../../media_admin.php?module='.$module);
        exit;
    }
    $id = intval($_GET['id']);
    $q = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id_pelanggan FROM riwayat_pelanggan WHERE id='$id'");
    if (mysqli_num_rows($q) < 1){
        $_SESSION['flash'] = "<div class='alert alert-danger'>Riwayat tidak ditemukan.</div>";
        header('location:../../media_admin.php?module='.$module);
        exit;
    }
    $row = mysqli_fetch_array($q);
    $id_p = $row['id_pelanggan'];
    mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM riwayat_pelanggan WHERE id='$id'");
    unset($_SESSION['csrf_pelanggan']);
    $_SESSION['flash'] = "<div class='alert alert-success'>Riwayat berhasil dihapus.</div>";
    header('location:../../media_admin.php?module='.$module.'&act=riwayat&id='.$id_p);
}

}
?>
