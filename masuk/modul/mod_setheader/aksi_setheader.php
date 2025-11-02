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
if ($module=='setheader' AND $act=='update_setheader'){
 
    $fupload_name =  $_FILES["fupload1"]["name"];
    UploadLogo_Struk($fupload_name);
    
    $data = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT logo FROM setheader WHERE id_setheader = '$_POST[id]'");
    $row = mysqli_fetch_array($data);
    
    if($row['logo'] != 'mysifalogo.png' and $fupload_name !=''){
        unlink('../../images/'.$row['logo']);
    }
    if($fupload_name == ''){
        $fupload_name = $row['logo'];
    }
    
    mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE setheader SET satu = '$_POST[satu]',
        										dua         = '$_POST[dua]',
        										tiga        = '$_POST[tiga]',
        										empat       = '$_POST[empat]',
        										lima        = '$_POST[lima]',
        										enam        = '$_POST[enam]',
        										tujuh       = '$_POST[tujuh]',
        										delapan     = '$_POST[delapan]',
        										sembilan    = '$_POST[sembilan]',
        										sepuluh     = '$_POST[sepuluh]',
        										sebelas     = '$_POST[sebelas]',
        										duabelas    = '$_POST[duabelas]',
        										tigabelas   = '$_POST[tigabelas]',
        										logo        = '$fupload_name'
        									WHERE id_setheader = '$_POST[id]'");
									
	echo "<script type='text/javascript'>alert('Data berhasil diubah !');window.location='../../media_admin.php?module=".$module."'</script>";
	//header('location:../../media_admin.php?module='.$module);
	
}

}
?>
