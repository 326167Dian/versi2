<?php 
session_start();
include "../../../configurasi/koneksi.php";

$kd_trkasir = $_POST['kd_trkasir'];
$id_dtrkasir = $_POST['id_dtrkasir'];
$id_barang = $_POST['id_barang'];
$kd_barang = $_POST['kd_barang'];
$nmbrg_dtrkasir = $_POST['nmbrg_dtrkasir'];
$qty_dtrkasir = $_POST['qty_dtrkasir'];
$sat_dtrkasir = $_POST['sat_dtrkasir'];
$hrgjual_dtrkasir = $_POST['hrgjual_dtrkasir'];
$indikasi = $_POST['indikasi'];
if ($_SESSION['komisi'] == 'Y') {
    $tarik = $db->query("select komisi from barang where id_barang='$id_barang' ");
    $kom = $tarik->fetch_array();

    $komisi = $kom['komisi'] * $_POST['qty_dtrkasir'] ;
}
elseif ($_SESSION['komisi'] == 'N'){
    $komisi = 0;
}
$currentdate = date('Y-m-d',time());
$id_admin = $_POST['id_admin'];

$disc = $_POST['disc'];
$no_batch = $_POST['no_batch'];
$exp_date = $_POST['exp_date'];
$hrgdisc = $hrgjual_dtrkasir * (1-($disc/100));
$tipe = $_POST['tipe'];
$datetime = date('Y-m-d H:i:s', time());

if($qty_dtrkasir == ""){
$qty_dtrkasir = "1";
}else{

}

if($id_dtrkasir == "" || $id_dtrkasir == null){

//cek apakah barang sudah ada
$cekdetail=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * 
FROM trkasir_detail 
WHERE kd_barang='$kd_barang' AND kd_trkasir='$kd_trkasir'");

$ketemucekdetail=mysqli_num_rows($cekdetail);
$rcek=mysqli_fetch_array($cekdetail);
if ($ketemucekdetail > 0){

$id_dtrkasir = $rcek['id_dtrkasir'];
$qtylama = $rcek['qty_dtrkasir'];
$ttlqty = $qtylama + $qty_dtrkasir;
$ttlharga = $ttlqty * $hrgdisc;

mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE trkasir_detail SET qty_dtrkasir = '$ttlqty',
										hrgjual_dtrkasir = '$hrgjual_dtrkasir',
										hrgttl_dtrkasir = '$ttlharga',
                                        komisi = '$komisi'
										WHERE id_dtrkasir = '$id_dtrkasir' and kd_barang='$kd_barang'");
										
//update stok
//cek tambah stok
    $tambahstok = mysqli_query($GLOBALS["___mysqli_ston"],"select id_dtrkasir, kd_trkasir, qty_dtrkasir 
    from trkasir_detail where kd_trkasir='$kd_trkasir' and kd_barang='$kd_barang'");
    $ketemutambahstok = mysqli_fetch_array($tambahstok);
    $angka = $ketemutambahstok[$qty_dtrkasir];
    // if($angka==$ttlqty) {

        $cekstok = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang 
        WHERE id_barang='$id_barang'");
        $rst = mysqli_fetch_array($cekstok);

        $stok_barang = $rst['stok_barang'];
        $stokakhir = (($stok_barang + $qtylama) - $ttlqty);

        mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET 
            stok_barang = '$stokakhir'
            WHERE id_barang = '$id_barang'");
    //                  }
    // else{}

    if($_SESSION['komisi']=='Y'){
        if($_SESSION['penjualansebelum']=='Y'){
            $ttlkomisi = $ttlqty * $komisi;
            mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE komisi_pegawai SET ttl_komisi = '$ttlkomisi' 
            WHERE id_dtrkasir='$id_dtrkasir'");		
        } else {
            $ttlkomisi = $ttlqty * $komisi;
            mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE komisi_pegawai SET ttl_komisi = '$ttlkomisi' 
            WHERE id_dtrkasir='$id_dtrkasir' AND id_admin='$_SESSION[idadmin]'");		
        }
    }
    
    	//cek apakah barang dengan no batch yang dimaksud sudah ada
    $cekbatchdetail=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT no_batch, kd_transaksi,qty
                                                                FROM batch 
                                                                WHERE no_batch='$no_batch' AND kd_transaksi='$kd_trkasir' AND status = 'keluar'");
    $ketemucekbatchdetail=mysqli_num_rows($cekbatchdetail);
    
    if($ketemucekbatchdetail>0)
    {
        //tarikstok dari batch
        $tampung = mysqli_fetch_array($cekbatchdetail);
        $qtybatchlama = $tampung['qty'];
        $qtybatchbaru = $qtybatchlama + $qty_dtrkasir;

        mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE batch SET qty = '$qtybatchbaru' 
                    WHERE kd_transaksi = '$kd_trkasir' 
                          AND no_batch = '$no_batch'
                          AND status = 'keluar'");

    }
}else{

$ttlharga = $qty_dtrkasir * $hrgdisc;


mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO trkasir_detail(kd_trkasir,
										id_barang,
										kd_barang,
										nmbrg_dtrkasir,
										qty_dtrkasir,
										sat_dtrkasir,
										hrgjual_dtrkasir,
										disc,
										no_batch,
										exp_date,										
										hrgttl_dtrkasir,
										tipe,
                                        komisi,
                                        idadmin)
								  VALUES('$kd_trkasir',
										'$id_barang',
										'$kd_barang',
										'$nmbrg_dtrkasir',
										'$qty_dtrkasir',
										'$sat_dtrkasir',
										'$hrgjual_dtrkasir',
										'$disc',
										'$no_batch',
										'$exp_date',										
										'$ttlharga',
										'$tipe',
                                        '$komisi',
                                        '$id_admin'
										)");


$insertid_dtrkasir = mysqli_insert_id($GLOBALS["___mysqli_ston"]);

if($no_batch != ""){
    // Input batch
    mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO batch(
                                        tgl_transaksi,
                                        no_batch,
                                        exp_date,
                                        qty,
                                        satuan,
                                        kd_transaksi,										
										kd_barang,
										status	
										)
								  VALUES('$datetime',
								        '$no_batch',
										'$exp_date',
										'$qty_dtrkasir',
										'$sat_dtrkasir',
										'$kd_trkasir',
										'$kd_barang',
										'keluar'
										)");    
}

										
//cek transaksi sukses
$cekmasuk = mysqli_query($GLOBALS["___mysqli_ston"],"select * from trkasir_detail 
where kd_trkasir='$kd_trkasir'");
$ketemucekmasuk = mysqli_fetch_array($cekmasuk);
if($ketemucekmasuk > 0 ) {
//update stok
    
    $cekstok = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang 
    WHERE id_barang='$id_barang'");
    $rst = mysqli_fetch_array($cekstok);

    $stok_barang = $rst['stok_barang'];
    $stokakhir = $stok_barang - $qty_dtrkasir;
    
//update harga jual hanya sementara hanya untuk pemakai awal, setelah itu harga jual tidak bisa diupdate lewat kasir
    $lst_trx = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir 
                ORDER BY id_trkasir ASC
                LIMIT 1");
    $trx =  mysqli_fetch_array($lst_trx);
    $tgl_first = date('Y-m-d', strtotime('+1 months', strtotime($trx['tgl_trkasir'])));
    $tgl_last  = date('Y-m-d', time());
    
    if($tgl_last > $tgl_first){
        $set_query = "stok_barang = '$stokakhir'";
    } else {
        if($_POST['tipe'] == '1'){
            $set_query = "hrgjual_barang = '$hrgjual_dtrkasir',
            stok_barang = '$stokakhir'";
        } 
        elseif($_POST['tipe'] == '2'){
            $set_query = "hrgjual_barang1 = '$hrgjual_dtrkasir',
            stok_barang = '$stokakhir'";
        }
        elseif($_POST['tipe'] == '3'){
            $set_query = "hrgjual_barang2 = '$hrgjual_dtrkasir',
            stok_barang = '$stokakhir'";
        }
    }
    
    mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET 
        $set_query
        WHERE id_barang = '$id_barang'");
        
    if($_SESSION['komisi']=='Y'){
        if($_SESSION['penjualansebelum']=='Y'){
            // $cariidadmin = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM komisi_pegawai WHERE kd_trkasir = '$kd_trkasir'");
            // $getAdmin = mysqli_fetch_array($cariidadmin);
            
            $ttlkomisi = $qty_dtrkasir * $komisi;
            mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO komisi_pegawai (kd_trkasir, id_dtrkasir, id_admin, ttl_komisi, tgl_komisi, status_komisi)
            VALUES('$kd_trkasir', '$insertid_dtrkasir', '$id_admin', '$ttlkomisi', '$currentdate', 'on')");
        } else {
            $ttlkomisi = $qty_dtrkasir * $komisi;
            mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO komisi_pegawai (kd_trkasir, id_dtrkasir, id_admin, ttl_komisi, tgl_komisi, status_komisi)
            VALUES('$kd_trkasir', '$insertid_dtrkasir', '$_SESSION[idadmin]', '$ttlkomisi', '$currentdate', 'on')");
        }
    }
}
else{}
}

}else{
//
$cekdetail=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir_detail 
WHERE id_dtrkasir='$id_dtrkasir'");
$rcek=mysqli_fetch_array($cekdetail);
$id_dtrkasir = $rcek['id_dtrkasir'];
$qtylama = $rcek['qty_dtrkasir'];
$qtybaru = $qtylama + $qty_dtrkasir;
$ttlharga = $qtybaru * $hrgjual_dtrkasir;

mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE trkasir_detail SET qty_dtrkasir = '$qtybaru',
										hrgjual_dtrkasir = '$hrgjual_dtrkasir',
										hrgttl_dtrkasir = '$ttlharga'
										WHERE id_dtrkasir = '$id_dtrkasir'");
										
//update stok
    //cek untuk update
    $cekmasuk2 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir_detail 
    WHERE id_dtrkasir='$id_dtrkasir'");
    $ceklagi = $cekmasuk2[$qty_dtrkasir];
    // if($ceklagi == $qtybaru) {
        $cekstok = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang 
        WHERE id_barang='$id_barang'");
        $rst = mysqli_fetch_array($cekstok);

        $stok_barang = $rst['stok_barang'];
        $stokakhir = (($stok_barang + $qtylama) - $qty_dtrkasir);

        mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET 
            stok_barang = '$stokakhir'
            WHERE id_barang = '$id_barang'");
    // }
    // else{}
    
    if($_SESSION['komisi']=='Y'){
        if($_SESSION['penjualansebelum']=='Y'){
            $ttlkomisi = $qtybaru * $komisi;
            mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE komisi_pegawai SET ttl_komisi = '$ttlkomisi' 
            WHERE id_dtrkasir='$id_dtrkasir'");
            
        } else {
            $ttlkomisi = $qtybaru * $komisi;
            mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE komisi_pegawai SET ttl_komisi = '$ttlkomisi' 
            WHERE id_dtrkasir='$id_dtrkasir' AND id_admin='$_SESSION[idadmin]'");
        }
    }
}


?>
