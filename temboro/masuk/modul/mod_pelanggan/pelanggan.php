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

			$tampil_pelanggan = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM pelanggan ORDER BY id_pelanggan DESC");

?>


			<div class="box box-primary box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">DATA PELANGGAN</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div><!-- /.box-tools -->
				</div>
				<div class="box-body table-responsive">
					<a class='btn  btn-success btn-flat' href='?module=pelanggan&act=tambah'>TAMBAH</a>
					<br><br>


					<table id="example11" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Pelanggan</th>
								<th>Telepon</th>
								<th>Alamat</th>
								<th>Keterangan</th>
								<th width="70">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$no = 1;
							while ($r = mysqli_fetch_array($tampil_pelanggan)) {
								// echo "<tr class='warnabaris' >
								// 			<td>$no</td>           
								// 			 <td>$r[nm_pelanggan]</td>
								// 			 <td>$r[tlp_pelanggan]</td>
								// 			 <td>$r[alamat_pelanggan]</td>
								// 			 <td>$r[ket_pelanggan]</td>
								// 			 <td>
								// 			 <a href='?module=pelanggan&act=edit&id=$r[id_pelanggan]' title='EDIT' class='btn btn-warning btn-xs'>EDIT</a> 
								// 			 <a href=javascript:confirmdelete('$aksi?module=pelanggan&act=hapus&id=$r[id_pelanggan]') title='HAPUS' class='btn btn-danger btn-xs'>HAPUS</a>
											 
								// 			</td>
								// 		</tr>";
								echo "<tr class='warnabaris' >
											<td>$no</td>           
											 <td>$r[nm_pelanggan]</td>
											 <td>$r[tlp_pelanggan]</td>
											 <td>$r[alamat_pelanggan]</td>
											 <td>$r[ket_pelanggan]</td>
											 <td>
    											 <button type='button' class='btn btn-warning btn-xs' id='btn_edit' data-id='$r[id_pelanggan]'>EDIT</button>
            									 <button type='button' class='btn btn-danger btn-xs' id='btn_hapus' data-id='$r[id_pelanggan]'>HAPUS</button>
											</td>
										</tr>";
								$no++;
							}
							echo "</tbody></table>";
							?>
				</div>
			</div>


<?php

			break;

		case "tambah":

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
				
			</div>";


			break;

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
						<form method=POST action=$aksi?module=pelanggan&act=update_pelanggan  enctype='multipart/form-data' class='form-horizontal' id='frmEditPelanggan'>
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
											<input class='btn btn-danger' type=button value=BATAL id='btn_cancel' data-page='".$_GET['page']."'>
										</div>
								</div>
								
							  </form>
							  
				</div> 
				
			</div>";




			break;
	}
	?>
	    <script>
            $(document).ready(function() {
                var vpage = <?=isset($_GET['page']) ? intval($_GET['page']) : 1;?>;
                var startIndex = (vpage - 1);
                
                var table = $('#example11').DataTable({
                    'lengthChange': false,
                    'displayStart': getPageFromUrl() * 10,
                    'pageLength': 10,
                });
                
                table.on('draw', function () {
                    const info = table.page.info();
                    const currentPage = info.page + 1; // konversi ke 1-based
                    const url = new URL(window.location);
                    url.searchParams.set('page', currentPage);
                    window.history.pushState({}, '', url);
                });
        
                // Tombol hapus
                $('#example11 tbody').on('click', '#btn_hapus', function () {
                    var id = $(this).data('id');
                    var row = $(this).closest('tr');
        
                    if (confirm('Anda yakin ingin menghapus?') == true) {
                        $.ajax({
                    		url: 'modul/mod_pelanggan/aksi_pelanggan.php?module=pelanggan&act=hapus&id='+id,
                    		type: 'POST',
                    	}).success(function() {
                    		// Hapus dari DataTable tanpa mengganti halaman
                            table.row(row).remove().draw(false);
                    	});
                    }
                });
                
                $('#example11 tbody').on('click', '#btn_edit', function () {
                    var id = $(this).data('id');
                    var currentPage = table.page() + 1;
                    location.href = '?module=pelanggan&act=edit&id='+id+'&page='+currentPage;
                });
                        
                $('#btn_cancel').on('click', function(){
                    // var currentPage = $(this).data('page');
                    var currentPage = getPageFromUrl() + 1
                    location.href = '?module=pelanggan&page='+currentPage;
                    
                });
            
                $("#frmEditPelanggan").submit(function(e) {

                    e.preventDefault(); // avoid to execute the actual submit of the form.
                
                    var form = $(this);
                    var actionUrl = form.attr('action');
                    var vpage = getPageFromUrl() + 1;
                    
                    $.ajax({
                        type: "POST",
                        url: actionUrl,
                        data: form.serialize(), // serializes the form's elements.
                        success: function(data)
                        {
                            location.href = '?module=pelanggan&page='+vpage;
                        }
                    });
                    
                });
                
                function getPageFromUrl() {
                    const params = new URLSearchParams(window.location.search);
                    const page = parseInt(params.get("page"));
                    return isNaN(page) ? 0 : page - 1; // DataTables pakai index mulai dari 0
                }
            });
            
                 
        </script>
	<?php
}
?>