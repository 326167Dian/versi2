<?php
// Date_Default_timezone_set('Asia/jakarta');
// $server = "localhost";
//  $user = "u725913413_iwan";
//  $password = "7390091979Dian&&";
//  $database = "u725913413_tenjo";
//  set_time_limit(1800);

// ($GLOBALS["___mysqli_ston"] = mysqli_connect($server, $user, $password)) or die("Koneksi gagal");
// mysqli_select_db($GLOBALS["___mysqli_ston"], $database) or die("Database tidak ditemukan");
// // $koneksi = new mysqli($server, $user, $password) or die("Koneksi gagal");
// $db = mysqli_connect('localhost', 'u725913413_iwan', '7390091979Dian&&', 'u725913413_tenjo');

Date_Default_timezone_set('Asia/jakarta');
$server = "localhost";
$user = "root";
$password = "";
$database = "mysifabest";
set_time_limit(1800);

($GLOBALS["___mysqli_ston"] = mysqli_connect($server, $user, $password)) or die("Koneksi gagal");
mysqli_select_db($GLOBALS["___mysqli_ston"], $database) or die("Database tidak ditemukan");
// $koneksi = new mysqli($server, $user, $password) or die("Koneksi gagal");
$db = mysqli_connect('localhost', 'root', '', 'mysifabest'); 
?>