<?php
$_GET['action']='table_data';
$_GET['start']=date('Y-m-d',strtotime('-30 days'));
$_GET['finish']=date('Y-m-d');
$_POST['draw']=1;
$_POST['start']=0;
$_POST['length']=10;
$_POST['order']=array(0=>array('column'=>1,'dir'=>'asc'));
$_POST['search']=array('value'=>'');
include 'd:/xampp/htdocs/mysifabest/masuk/modul/mod_kartustok/kartu_stok_serverside.php';
