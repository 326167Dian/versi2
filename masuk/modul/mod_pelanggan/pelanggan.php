<?php
session_start();
if (empty($_SESSION['username']) and empty($_SESSION['passuser'])) {
	echo "<link href=../css/style.css rel=stylesheet type=text/css>";
	echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
} else {

	$aksi = "modul/mod_pelanggan/aksi_pelanggan.php";
	$aksi_pelanggan = "masuk/modul/mod_pelanggan/aksi_pelanggan.php";
	switch ($_GET['act']) {
			// Tampil Siswa
		default:

			$tampil_pelanggan = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM pelanggan ORDER BY id_pelanggan ASC");

?>


<div class='box box-primary box-solid'>
    <div class='box-header with-border'>
        <h3 class='box-title'>DATA PELANGGAN</h3>
        <div class='box-tools pull-right'>
            <button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
        </div><!-- /.box-tools -->
    </div>
    <div class='box-body table-responsive'>
        <table id='tampil' class='table table-bordered table-striped'>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pelanggan</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                    <th>Keterangan</th>
                    <th width='120'>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>


<?php

			break;

            $pel = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM pelanggan WHERE id_pelanggan='$_GET[id]'");
            $pp = mysqli_fetch_array($pel);

            echo "
          <div class='box box-primary box-solid'>
                <div class='box-header with-border'>
                    <h3 class='box-title'>TAMBAH RIWAYAT - $pp[nm_pelanggan]</h3>
                    <div class='box-tools pull-right'>
                        <button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
                </div>
                <div class='box-body table-responsive'>

                        <form method=POST action='$aksi?module=pelanggan&act=input_riwayat' class='form-horizontal'>

                              <input type=hidden name='id_pelanggan' value='$pp[id_pelanggan]'>

                              <div class='form-group'>
                                    <label class='col-sm-2 control-label'>Tgl</label>                
                                     <div class='col-sm-3'>
                                        <input type=date name='tgl' class='form-control' value='".date('Y-m-d')."' required>
                                     </div>
                              </div>

                              <div class='form-group'>
                                    <label class='col-sm-2 control-label'>Diagnosa</label>                
                                     <div class='col-sm-6'>
                                        <textarea name='diagnosa' class='form-control' rows='3' required></textarea>
                                     </div>
                              </div>

                              <div class='form-group'>
                                    <label class='col-sm-2 control-label'>Tindakan</label>                
                                     <div class='col-sm-6'>
                                        <textarea name='tindakan' class='form-control' rows='3' required></textarea>
                                     </div>
                              </div>

                              <div class='form-group'>
                                    <label class='col-sm-2 control-label'>Follow Up</label>                
                                     <div class='col-sm-6'>
                                        <textarea name='followup' class='form-control' rows='2'></textarea>
                                     </div>
                              </div>

                              <div class='form-group'>
                                    <label class='col-sm-2 control-label'></label>       
                                        <div class='col-sm-5'>
                                            <input class='btn btn-info' type=submit value=SIMPAN>
                                            <input class='btn btn-primary' type=button value=BATAL onclick=self.history.back()>
                                        </div>
                                </div>
                                
                              </form>
                              
                </div> 
                
            </div>";


            break;
        case "riwayat":

			echo "
		  <div class='box box-primary box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>TAMBAH</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body table-responsive'>
				
						<form method=POST action='$aksi?module=pelanggan&act=input_pelanggan' enctype='multipart/form-data' class='form-horizontal'>
						
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Nama Pelanggan</label>        		
									 <div class='col-sm-4'>
										<input type=text name='nm_pelanggan' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Telepon</label>        		
									 <div class='col-sm-4'>
										<input type=text name='tlp_pelanggan' class='form-control' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Alamat</label>        		
									 <div class='col-sm-4'>
										<textarea name='alamat_pelanggan' class='form-control' rows='3'></textarea>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Keterangan</label>        		
									 <div class='col-sm-4'>
										<textarea name='ket_pelanggan' class='form-control' rows='3'></textarea>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'></label>       
										<div class='col-sm-5'>
											<input class='btn btn-info' type=submit value=SIMPAN>
											<input class='btn btn-primary' type=button value=BATAL onclick=self.history.back()>
										</div>
								</div>
								
							  </form>
							  
				</div> 
				</div>
			</div>";



		case "edit":
			$edit = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM pelanggan WHERE id_pelanggan='$_GET[id]'");
			$r = mysqli_fetch_array($edit);

			echo "
		  <div class='box box-primary box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>UBAH</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body table-responsive'>
						<form method=POST method=POST action=$aksi?module=pelanggan&act=update_pelanggan  enctype='multipart/form-data' class='form-horizontal'>
							  <input type=hidden name=id value='$r[id_pelanggan]'>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Nama Pelanggan</label>        		
									 <div class='col-sm-4'>
										<input type=text name='nm_pelanggan' class='form-control' value='$r[nm_pelanggan]' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Telepon</label>        		
									 <div class='col-sm-4'>
										<input type=text name='tlp_pelanggan' class='form-control' value='$r[tlp_pelanggan]' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Alamat</label>        		
									 <div class='col-sm-4'>
										<textarea name='alamat_pelanggan' class='form-control' rows='3'>$r[alamat_pelanggan]</textarea>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Keterangan</label>        		
									 <div class='col-sm-4'>
										<textarea name='ket_pelanggan' class='form-control' rows='3'>$r[ket_pelanggan]</textarea>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'></label>       
										<div class='col-sm-5'>
											<input class='btn btn-primary' type=submit value=SIMPAN>
											<input class='btn btn-primary' type=button value=BATAL onclick=self.history.back()>
										</div>
								</div>
								
							  </form>
							  
				</div> 
				
			</div>";




			break;
	}
}
?>

<script>
	$(document).ready(function() {
		$('#tampil').DataTable({
			processing: true,
			serverSide: true,
			ajax: {
				"url": "modul/mod_pelanggan/pelanggan_serverside.php?action=table_data",
				"dataType": "JSON",
				"type": "POST"
			},
			columns: [
				{ "data": "no", "className": 'text-center' },
				{ "data": "nm_pelanggan" },
				{ "data": "tlp_pelanggan" },
				{ "data": "alamat_pelanggan" },
				{ "data": "ket_pelanggan" },
				{ "data": "aksi", "visible": <?= ($_SESSION['level'] == 'pemilik') ? 'true' : 'false'; ?>, "render": function(data, type, row){
					var btn = "<div style='text-align:center'><a href='?module=pelanggan&act=edit&id="+data+"' title='EDIT' class='btn btn-warning btn-xs'>EDIT</a> "+
					"<a href='?module=pelanggan&act=riwayat&id="+data+"' title='Riwayat' class='btn btn-info btn-xs'>RIWAYAT</a> "+
					"<a href=javascript:confirmdelete('modul/mod_pelanggan/aksi_pelanggan.php?module=pelanggan&act=hapus&id="+data+"') title='HAPUS' class='btn btn-danger btn-xs'>HAPUS</a></div>";
					return btn;
				} }
			]
		});
	});
</script>