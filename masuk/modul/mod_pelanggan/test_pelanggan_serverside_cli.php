<?php
// Quick CLI test for pelanggan_serverside.php
require_once __DIR__ . '/../../../configurasi/koneksi.php';
// create test pelanggan
$nm = 'Test Pel '.time();
$tlp = '081234567';
$alamat = 'Alamat Test';
$ket = 'Keterangan Test';
$res = mysqli_query($GLOBALS['___mysqli_ston'], "INSERT INTO pelanggan (nm_pelanggan, tlp_pelanggan, alamat_pelanggan, ket_pelanggan) VALUES ('".mysqli_real_escape_string($GLOBALS['___mysqli_ston'],$nm)."','".mysqli_real_escape_string($GLOBALS['___mysqli_ston'],$tlp)."','".mysqli_real_escape_string($GLOBALS['___mysqli_ston'],$alamat)."','".mysqli_real_escape_string($GLOBALS['___mysqli_ston'],$ket)."')");
$id = mysqli_insert_id($GLOBALS['___mysqli_ston']);
// insert riwayat
$follow = 'Follow up test '.date('Y-m-d H:i:s');
mysqli_query($GLOBALS['___mysqli_ston'], "INSERT INTO riwayat_pelanggan (id_pelanggan, tgl, diagnosa, tindakan, followup, created_at) VALUES ('".$id."', '".date('Y-m-d')."', 'diag', 'tindakan', '".mysqli_real_escape_string($GLOBALS['___mysqli_ston'],$follow)."', NOW())");
// set GET action and prepare POST variables
$_GET['action'] = 'table_data';
$_POST['draw'] = 1;
$_POST['length'] = 10;
$_POST['start'] = 0;
$_POST['order'] = array(array('column' => 0, 'dir' => 'asc'));
$_POST['search'] = array('value' => '');
// include the server-side script and capture output
chdir(__DIR__);
ob_start();
include 'pelanggan_serverside.php';
$output = ob_get_clean();
echo "Response:\n";
echo $output . "\n";
// cleanup
mysqli_query($GLOBALS['___mysqli_ston'], "DELETE FROM riwayat_pelanggan WHERE id_pelanggan='".$id."'");
mysqli_query($GLOBALS['___mysqli_ston'], "DELETE FROM pelanggan WHERE id_pelanggan='".$id."'");
?>