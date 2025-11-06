<?php
session_start();
if (empty($_SESSION['username']) and empty($_SESSION['passuser'])) {
	echo "<link href=../css/style.css rel=stylesheet type=text/css>";
	echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
} else {

	$aksi = "modul/mod_jenisobat/aksi_jenisobat.php";

	switch ($_GET['act']) {
			// tampil jenis obat
		default:


			$tampil_jenisobat = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM jenis_obat ORDER BY idjenis DESC");

?>


			<div class="box box-primary box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">DATA JENIS OBAT</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div><!-- /.box-tools -->
				</div>
				<div class="box-body">
					<a class='btn  btn-success btn-flat' href='?module=jenisobat&act=tambah'>TAMBAH</a>
					<br><br>


					<table id="example11" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th width="5%">No</th>
								<th>Jenis Obat</th>
								<th>Deskripsi</th>
								<th width="70">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$no = 1;
							while ($r = mysqli_fetch_array($tampil_jenisobat)) {
								// echo "<tr class='warnabaris' >
								// 			<td>$no</td>           
								// 			 <td>$r[jenisobat]</td>
								// 			 <td>$r[ket]</td>
								// 			 <td><a href='?module=jenisobat&act=edit&id=$r[idjenis]' title='EDIT' class='btn btn-warning btn-xs'>EDIT</a> 
								// 			 <a href=javascript:confirmdelete('$aksi?module=jenisobat&act=hapus&id=$r[idjenis]') title='HAPUS' class='btn btn-danger btn-xs'>HAPUS</a>
											 
								// 			</td>
								// 		</tr>";
								
								echo "<tr class='warnabaris' >
											<td>$no</td>           
											 <td>$r[jenisobat]</td>
											 <td>$r[ket]</td>
											 <td>
											    <button type='button' class='btn btn-warning btn-xs' id='btn_edit' data-id='$r[idjenis]'>EDIT</button>
        									    <button type='button' class='btn btn-danger btn-xs' id='btn_hapus' data-id='$r[idjenis]'>HAPUS</button>
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
					<h3 class='box-title'>TAMBAH JENIS OBAT</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
				
						<form method=POST action='$aksi?module=jenisobat&act=input_jenisobat' enctype='multipart/form-data' class='form-horizontal'>
						
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Jenis Obat</label>        		
									 <div class='col-sm-6'>
										<input type=text name='jenisobat' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div>
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Deskripsi</label>        		
									 <div class='col-sm-6'>
										<input type=text name='ket' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'></label>       
										<div class='col-sm-5'>
											<input class='btn btn-primary' type=submit value=SIMPAN>
											<input class='btn btn-danger' type=button value=BATAL onclick=self.history.back()>
										</div>
								</div>
								
							  </form>
							  
				</div> 
				
			</div>";


			break;

		case "edit":
			$edit = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM jenis_obat WHERE jenis_obat.idjenis='$_GET[id]'");



			$r = mysqli_fetch_array($edit);

			echo "
		  <div class='box box-primary box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>UBAH Jenis Obat</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
						<form method=POST action=$aksi?module=jenisobat&act=edit  enctype='multipart/form-data' class='form-horizontal' id='frmEditJenisObat'>
							  <input type=hidden name=idjenis value='$r[idjenis]'>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Jenis Obat</label>        		
									 <div class='col-sm-6'>
										<input type=text name='jenisobat' class='form-control' value='$r[jenisobat]' autocomplete='off'>
									 </div>
							  </div>
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Deskripsi</label>        		
									 <div class='col-sm-6'>
										<input type=text name='ket' class='form-control' value='$r[ket]' autocomplete='off'>
									 </div>
							  </div>
							  <div class='form-group'>
									<label class='col-sm-2 control-label'></label>       
										<div class='col-sm-5'>
											<input class='btn btn-primary' type=submit value=SIMPAN>
											<input class='btn btn-danger' type=button value=BATAL onclick=self.history.back()>
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
                    		url: 'modul/mod_jenisobat/aksi_jenisobat.php?module=jenisobat&act=hapus&id='+id,
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
                    location.href = '?module=jenisobat&act=edit&id='+id+'&page='+currentPage;
                });
                        
                $('#btn_cancel').on('click', function(){
                    // var currentPage = $(this).data('page');
                    var currentPage = getPageFromUrl() + 1
                    location.href = '?module=jenisobat&page='+currentPage;
                    
                });
            
                $("#frmEditJenisObat").submit(function(e) {

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
                            location.href = '?module=jenisobat&page='+vpage;
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
        
<script type="text/javascript">
	$(function() {
		$(".datepicker").datepicker({
			format: 'yyyy-mm-dd',
			autoclose: true,
			todayHighlight: true,
		});
	});
</script>