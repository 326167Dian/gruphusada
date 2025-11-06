<?php
session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href=../css/style.css rel=stylesheet type=text/css>";
  echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
}
else{

$aksi="modul/mod_carabayar/aksi_carabayar.php";
$aksi_carabayar = "masuk/modul/mod_carabayar/aksi_carabayar.php";
switch($_GET[act]){
  // Tampil Siswa
  default:

  
      $tampil_carabayar = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM carabayar ORDER BY id_carabayar ");
      
	  ?>
			
			
			<div class="box box-primary box-solid table-responsive">
				<div class="box-header with-border">
					<h3 class="box-title">JENIS PEMBAYARAN KASIR</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class="box-body">
					<a  class ='btn  btn-success btn-flat' href='?module=carabayar&act=tambah'>TAMBAH</a>
					<br><br>
					
					
					<table id="example11" class="table table-bordered table-striped" >
						<thead>
							<tr>
								<th widht="20px">No</th>
								<th>Jenis</th>
								<th>Urutan</th>
								<th width="70">Aksi</th>
							</tr>
						</thead>
						<tbody>
						<?php 
								$no=1;
								while ($r=mysqli_fetch_array($tampil_carabayar)){
								// 	echo "<tr class='warnabaris' >
								// 			<td width='20px'>$no</td>           
								// 			 <td>$r[nm_carabayar]</td>
								// 			 <td>$r[urutan]</td>
								// 			 <td><a href='?module=carabayar&act=edit&id=$r[id_carabayar]' title='EDIT' class='btn btn-warning btn-xs'>EDIT</a> 
								// 			 <a href=javascript:confirmdelete('$aksi?module=carabayar&act=hapus&id=$r[id_carabayar]') title='HAPUS' class='btn btn-danger btn-xs'>HAPUS</a>
											 
								// 			</td>
								// 		</tr>";
								
								    echo "<tr class='warnabaris' >
											<td width='20px'>$no</td>           
											 <td>$r[nm_carabayar]</td>
											 <td>$r[urutan]</td>
											 <td>
											    <button type='button' class='btn btn-warning btn-xs' id='btn_edit' data-id='$r[id_carabayar]'>EDIT</button>
        									    <button type='button' class='btn btn-danger btn-xs' id='btn_hapus' data-id='$r[id_carabayar]'>HAPUS</button>
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
		  <div class='box box-primary box-solid table-responsive'>
				<div class='box-header with-border'>
					<h3 class='box-title'>TAMBAH</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
				
						<form method=POST action='$aksi?module=carabayar&act=input_carabayar' enctype='multipart/form-data' class='form-horizontal'>
						
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Jenis</label>        		
									 <div class='col-sm-6'>
										<input type=text name='nm_carabayar' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div>
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Urutan</label>        		
									 <div class='col-sm-6'>
										<input type=text name='urutan' class='form-control' required='required' autocomplete='off'>
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
    $edit=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM carabayar WHERE id_carabayar='$_GET[id]'");
    $r=mysqli_fetch_array($edit);
			
		echo "
		  <div class='box box-danger box-solid table-responsive'>
				<div class='box-header with-border'>
					<h3 class='box-title'>UBAH</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
						<form method=POST action=$aksi?module=carabayar&act=update_carabayar  enctype='multipart/form-data' class='form-horizontal' id='frmEditCabay'>
							  <input type=hidden name=id value='$r[id_carabayar]'>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Jenis</label>        		
									 <div class='col-sm-6'>
										<input type=text name='nm_carabayar' class='form-control' value='$r[nm_carabayar]' autocomplete='off'>
									 </div>
							  </div>
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Urutan</label>        		
									 <div class='col-sm-6'>
										<input type=number name='urutan' class='form-control' value='$r[urutan]' autocomplete='off'>
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
                    		url: 'modul/mod_carabayar/aksi_carabayar.php?module=carabayar&act=hapus&id='+id,
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
                    location.href = '?module=carabayar&act=edit&id='+id+'&page='+currentPage;
                });
                        
                $('#btn_cancel').on('click', function(){
                    // var currentPage = $(this).data('page');
                    var currentPage = getPageFromUrl() + 1
                    location.href = '?module=carabayar&page='+currentPage;
                    
                });
            
                $("#frmEditCabay").submit(function(e) {

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
                            location.href = '?module=carabayar&page='+vpage;
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