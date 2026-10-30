<?php
session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href=../css/style.css rel=stylesheet type=text/css>";
  echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
}
else{

$aksi="modul/mod_catatan/aksi_catatan.php";

switch($_GET[act]){
  // tampil catatan
  default:

  
      $tampil_catatan = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM catatan ORDER BY tgl desc 
");
      
	  ?>
			
			
			<div class="box box-primary box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">CATATAN HARIAN</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class="box-body">
					<a  class ='btn  btn-success btn-flat' href='?module=catatan&act=tambah'>TAMBAH</a>
					<br><br>
					
					
					<table id="example11" class="table table-bordered table-striped" >
						<thead>
							<tr>
								<th>No</th>
								<th>tanggal</th>
								<th>Shift</th>
                                <th>Petugas</th>
                                <th>catatan singkat</th>
								<th width="70">Aksi</th>
							</tr>
						</thead>
						<tbody>
						<?php 
								// $no=1;
								// while ($r=mysqli_fetch_array($tampil_catatan)){
								// 	echo "<tr class='warnabaris' >
								// 			<td>$no</td>           
								// 			 <td>$r[tgl]</td>
								// 			 <td>$r[shift]</td>
								// 			 <td>$r[petugas]</td>
								// 			 <td>$r[deskripsi]</td>
								// 			 <td>
								// 			 <a href='?module=catatan&act=edit&id=$r[id_catatan]' title='EDIT' class='btn btn-warning btn-xs'>EDIT</a> 
								// 			 <a href='?module=catatan&act=tampil&id=$r[id_catatan]' title='EDIT' class='btn btn-primary btn-xs'>TAMPIL</a> 
								// 		     <a href=javascript:confirmdelete('$aksi?module=catatan&act=hapus&id=$r[id_catatan]') title='HAPUS' class='btn btn-danger btn-xs'>HAPUS</a>
								// 			</td>
								// 		</tr>";
								// $no++;
								// }
				// 		echo "</tbody></table>";
					?>
					    </tbody>
					</table>
				</div>
                
			</div>	
             
            
<?php
    
    break;
	
	case "tambah":
        $tglharini = date('Y-m-d');
        $petugas = $_SESSION['namalengkap'];
        $cekshift = mysqli_query($GLOBALS["___mysqli_ston"], "select * from waktukerja where tanggal = '$tglharini'
            and status='ON' ");
        $hitung = mysqli_num_rows($cekshift);
        $sshift  = mysqli_fetch_array($cekshift);
        $shift = $sshift['shift'];

        echo "
		  <div class='box box-primary box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>TAMBAH CATATAN</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
				
						<form method=POST action='$aksi?module=catatan&act=input_catatan' enctype='multipart/form-data' class='form-horizontal'>
						 <input type=hidden name='petugas' id='petugas' value='$petugas'>
						 <input type=hidden name='shift' id='shift' value='$shift'>
							 
							 <label class='col-sm-4 control-label'>Tanggal </label>
										<div class='col-sm-6'>
											<div class='input-group date'>
												<div class='input-group-addon'>
													<span class='glyphicon glyphicon-th'></span>
												</div>
													<input type='text' class='datepicker' name='tgl'  value='$tglharini' autocomplete='off'>
											</div>
										</div>
							  
							<br>
							<label style='text-align:left;'>Catatan</label>
										<div class='col-xs-12'>
											<div >	
													<textarea name='deskripsi' class='ckeditor' id='content'></textarea>
											</div>
										</div>
							
							 <br> 
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
      $petugas = $_SESSION['namalengkap'];
      $edit=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM catatan WHERE id_catatan='$_GET[id]'");
        $r=mysqli_fetch_array($edit);
        $lupa = $_SESSION['level'];
        

      if (($petugas !== $r['petugas']) && ($lupa !== 'pemilik')) {
          echo "<script type='text/javascript'>alert('catatan hanya bisa di edit oleh orang yang sama!');history.go(-1);</script>";
      } else {

     	echo "
		  <div class='box box-primary box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>EDIT CATATAN</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
						<form method=POST action=$aksi?module=catatan&act=update_catatan  enctype='multipart/form-data' class='form-horizontal' id='frmEditCatatan'>
							<input type=hidden name=id value='$r[id_catatan]'>
							 <div class='form-group'>
									
											<div class='col-xs-12'>
											<div >	
													<textarea name='deskripsi' class='ckeditor' id='content'>$r[deskripsi]</textarea>
													
											</div>
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
				
			</div>";}
	
    break;
    case "tampil" :
        $edit=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM catatan WHERE id_catatan='$_GET[id]'");
        $r=mysqli_fetch_array($edit);
        echo "$r[deskripsi]
        <input class='btn btn-primary' type=button value=KEMBALI id='btn_cancel' data-page='".$_GET['page']."'>";
    break ;

}
}
?>
<script type="text/javascript" src="vendors/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    var editor = CKEDITOR.replace("content", {
        filebrowserBrowseUrl: '',
        filebrowserWindowWidth: 1000,
        filebrowserWindowHeight: 500
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

<script>
                $(document).ready(function() {
                
                    var table = $('#example11').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "lengthChange": false,
                        "displayStart": getPageFromUrl() * 10,
                        "pageLength": 10,
                        "ajax": {
                              "url": 'modul/mod_catatan/catatan_serverside.php',
                              "type": 'POST'
                            },
                        "columns": [
                            { "data": 'no' },
                            { "data": 'tgl' },
                            { "data": 'shift' },
                            { "data": 'petugas' },
                            { "data": 'deskripsi' },
                            { "data": 'aksi', "orderable": false, "searchable": false, "className":"text-center" }
                        ]
                    });
                    
                    // Tampilkan page di URL saat pagination di klik
                    table.on('draw', function () {
                        const info = table.page.info();
                        const currentPage = info.page + 1; // konversi ke 1-based
                        const url = new URL(window.location);
                        url.searchParams.set('page', currentPage);
                        window.history.pushState({}, '', url);
                    });
                    
                    // Tombol Untuk edit
                    $('#example11 tbody').on('click', '#btn_edit', function () {
                        var id = $(this).data('id');
                        var currentPage = table.page() + 1;
                        location.href = '?module=catatan&act=edit&id='+id+'&page='+currentPage;
                    });
                    
                    // Tombol Untuk tampil
                    $('#example11 tbody').on('click', '#btn_tampil', function () {
                        var id = $(this).data('id');
                        var currentPage = table.page() + 1;
                        location.href = '?module=catatan&act=tampil&id='+id+'&page='+currentPage;
                    });
                    
                    // Tombol cancel form
                    $('#btn_cancel').on('click', function(){
                        var currentPage = $(this).data('page');
                        location.href = '?module=catatan&page='+currentPage;
                                
                    });        
                    
                    // Form Edit barang
                    $("#frmEditCatatan").submit(function(e) {
            
                        e.preventDefault(); // avoid to execute the actual submit of the form.
                            
                        var form = $(this);
                        var actionUrl = form.attr('action');
                        var vpage = <?=isset($_GET['page']) ? intval($_GET['page']) : 1;?>;
                        var vpage = getPageFromUrl() + 1;
                                
                        $.ajax({
                            type: "POST",
                            url: actionUrl,
                            data: form.serialize(), // serializes the form's elements.
                            success: function(data)
                            {
                                location.href = '?module=catatan&page='+vpage;
                            }
                        });
                                
                    });
                    
                    // Tombol hapus
                    $('#example11 tbody').on('click', '#btn_hapus', function () {
                        var id = $(this).data('id');
                    
                        if (confirm('Anda yakin ingin menghapus?') == true) {
                            $.ajax({
                				url: 'modul/mod_catatan/aksi_catatan.php?module=catatan&act=hapus&id='+id,
                				type: 'POST',
                			}).success(function() {
                			    table.ajax.reload(null, false);
                			});
                        } 
                    });
        
                    function getPageFromUrl() {
                        const params = new URLSearchParams(window.location.search);
                        const page = parseInt(params.get("page"));
                        return isNaN(page) ? 0 : page - 1; // DataTables pakai index mulai dari 0
                    }
                });
            </script>