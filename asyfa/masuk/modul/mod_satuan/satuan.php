<?php
session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href=../css/style.css rel=stylesheet type=text/css>";
  echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
}
else{

$aksi="modul/mod_satuan/aksi_satuan.php";

switch($_GET[act]){
  // tampil satuan
  default:

  
      $tampil_satuan = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM satuan ORDER BY id_satuan DESC");
      
	  ?>
			
			
			<div class="box box-primary box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">DATA SATUAN</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class="box-body">
					<a  class ='btn  btn-success btn-flat' href='?module=satuan&act=tambah'>TAMBAH</a>
					<br><br>
					
					
					<table id="example11" class="table table-bordered table-striped" >
						<thead>
							<tr>
								<th>No</th>
								<th>Satuan</th>
                                <th>Deskripsi</th>
								<th width="70">Aksi</th>
							</tr>
						</thead>
						<tbody>
						<?php 
								$no=1;
								while ($r=mysqli_fetch_array($tampil_satuan)){
								// 	echo "<tr class='warnabaris' >
								// 			<td>$no</td>           
								// 			 <td>$r[nm_satuan]</td>
								// 			 <td>$r[deskripsi]</td>
								// 			 <td><a href='?module=satuan&act=edit&id=$r[id_satuan]' title='EDIT' class='btn btn-warning btn-xs'>EDIT</a> 
								// 			 <a href=javascript:confirmdelete('$aksi?module=satuan&act=hapus&id=$r[id_satuan]') title='HAPUS' class='btn btn-danger btn-xs'>HAPUS</a>
											 
								// 			</td>
								// 		</tr>";
								
									echo "<tr class='warnabaris' >
											<td>$no</td>           
											 <td>$r[nm_satuan]</td>
											 <td>$r[deskripsi]</td>
											 <td>
    											 <button type='button' class='btn btn-warning btn-xs' id='btn_edit' data-id='$r[id_satuan]'>EDIT</button>
            									 <button type='button' class='btn btn-danger btn-xs' id='btn_hapus' data-id='$r[id_satuan]'>HAPUS</button>
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
					<h3 class='box-title'>TAMBAH SATUAN</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
				
						<form method=POST action='$aksi?module=satuan&act=input_satuan' enctype='multipart/form-data' class='form-horizontal'>
						
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Satuan</label>        		
									 <div class='col-sm-6'>
										<input type=text name='nm_satuan' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div>
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Deskripsi</label>        		
									 <div class='col-sm-6'>
										<input type=text name='deskripsi' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'></label>       
										<div class='col-sm-5'>
											<input class='btn btn-primary' type=submit value=SIMPAN>
											<input class='btn btn-danger' type=button value=BATAL id='btn_cancel'>
										</div>
								</div>
								
							  </form>
							  
				</div> 
				
			</div>";
					
	
    break;

  case "edit":
    $edit=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM satuan WHERE id_satuan='$_GET[id]'");
    $r=mysqli_fetch_array($edit);
			
		echo "
		  <div class='box box-primary box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>UBAH SATUAN</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
						<form method=POST action=$aksi?module=satuan&act=update_satuan  enctype='multipart/form-data' class='form-horizontal' id='frmEditSatuan'>
							  <input type=hidden name=id value='$r[id_satuan]'>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Satuan</label>        		
									 <div class='col-sm-6'>
										<input type=text name='nm_satuan' class='form-control' value='$r[nm_satuan]' autocomplete='off'>
									 </div>
							  </div>
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Deskripsi</label>        		
									 <div class='col-sm-6'>
										<input type=text name='deskripsi' class='form-control' value='$r[deskripsi]' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'></label>       
										<div class='col-sm-5'>
											<input class='btn btn-primary' type=submit value=SIMPAN>
											<input class='btn btn-danger' type=button value=BATAL id='btn_cancel'>
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
                    		url: 'modul/mod_satuan/aksi_satuan.php?module=satuan&act=hapus&id='+id,
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
                    location.href = '?module=satuan&act=edit&id='+id+'&page='+currentPage;
                });
                        
                $('#btn_cancel').on('click', function(){
                    // var currentPage = $(this).data('page');
                    var currentPage = getPageFromUrl() + 1;
                    location.href = '?module=satuan&page='+currentPage;
                    
                });
            
                $("#frmEditSatuan").submit(function(e) {

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
                            location.href = '?module=satuan&page='+vpage;
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
 $(function(){
  $(".datepicker").datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true,
      todayHighlight: true,
  });
 });
</script>