<?php
session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href=../css/style.css rel=stylesheet type=text/css>";
  echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
}
else{

$aksi="modul/mod_komisi/aksi_komisi.php";

switch($_GET['act']){
  // tampil satuan
  default:

  
      $tampil_komisi = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang WHERE komisi != 0");
      
	  ?>
			
			
			<div class="box box-primary box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">TAMBAH DAN TUTUP KOMISI</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class="box-body table-responsive">
				    <?php if($_SESSION['level'] == 'pemilik'):?>
					<a  class ='btn  btn-success btn-flat' href='?module=komisi&act=tambah'>TAMBAH KOMISI</a>
					<a  class ='btn  btn-warning btn-flat' href='<?=$aksi."?module=komisi&act=hapus&id=all"?>'>HAPUS SEMUA KOMISI</a>
					<a  class ='btn  btn-danger btn-flat' href='?module=komisi&act=tutupkomisi'>TUTUP KOMISI</a>
                    <?php endif;?>

					<br><br>
					
					
					<table id="example11" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>No</th>
								<th>Kode</th>
								<th>Nama Barang</th>
								<th style="text-align: right; ">Qty/Stok</th>
								<th style="text-align: right; ">Satuan</th>
								<th style="text-align: center; ">Jenis Obat</th>
								<th style="text-align: right; ">Harga Jual</th>
								<th style="text-align: right; ">Komisi Pegawai</th>
								<th style="text-align: center; ">Aksi</th>
								<?php
                                // $lupa = $_SESSION['level'];
                                // if($lupa=='pemilik')
                                // { echo "<th>Aksi</th> "; }
                                // else{}
                                ?>
							</tr>
						</thead>
						<tbody>
						    <?php
						      //  $no=1;
						      //  while($r = mysqli_fetch_array($tampil_komisi)):
						      //      $hargajual = format_rupiah($r['hrgjual_barang']);
						      //      $komisi = format_rupiah($r['komisi']);
						    ?>
						  <!--  <tr>-->
								<!--<td><?=$no++?></td>-->
								<!--<td><?=$r['kd_barang']?></td>-->
								<!--<td><?=$r['nm_barang']?></th>-->
								<!--<td style="text-align: center; "><?=$r['stok_barang']?></td>-->
								<!--<td style="text-align: center; "><?=$r['sat_barang']?></td>-->
								<!--<td style="text-align: center; "><?=$r['jenisobat']?></td>-->
								<!--<td style="text-align: right; "><?=$hargajual?></td>-->
								<!--<td style="text-align: right; "><?=$komisi?></td>-->
								<?php
                                // $lupa = $_SESSION['level'];
                                // if($lupa=='pemilik'):
                                ?>
        <!--                        <td style="width: 80px; text-align: center">-->
        <!--                            <a href="?module=komisi&act=editkomisi&id=<?=$r['id_barang']?>" title="EDIT" class="glyphicon glyphicon-pencil">&nbsp</a> -->
								<!--	<a href="javascript:confirmdelete('<?=$aksi."?module=komisi&act=hapus&id=".$r['id_barang']?>')" title="HAPUS" class="glyphicon glyphicon-remove">&nbsp</a>-->
								<!--</td>-->
                                <?php //endif;?>
							<!--</tr>-->
						    <?php
						      //  endwhile;
						    ?>
						</tbody>
					</table>
				</div>
			</div>	
             
    
    <script>
    	$(document).ready(function() {
    	    
    		var table = $('#example11').DataTable({
    			processing: true,
    			serverSide: true,
    			lengthChange: false,
                displayStart: getPageFromUrl() * 10,
                pageLength: 10,
    			ajax: {
    				"url": "modul/mod_komisi/komisi-serverside.php?action=table_data",
    				"dataType": "JSON",
    				"type": "POST"
    			},
    			"rowCallback": function(row, data, index) {
                    let q = (data['hrgjual_barang'] - data['hrgsat_barang']) / data['hrgsat_barang'];
                    
                    if(q <= 0.3){
                        $(row).find('td:eq(7)').css('background-color', '#ff003f');
                        $(row).find('td:eq(7)').css('color', '#ffffff');
                    } else if(q > 0.3 && q <= 1){
                        $(row).find('td:eq(7)').css('background-color', '#f39c12');
                        $(row).find('td:eq(7)').css('color', '#ffffff');
                        
                    } else if(q > 1 && q <= 2){
                        $(row).find('td:eq(7)').css('background-color', '#00ff3f');
                        $(row).find('td:eq(7)').css('color', '#ffffff');
                        
                    } else if(q > 2){
                        $(row).find('td:eq(7)').css('background-color', '#00bfff');
                        $(row).find('td:eq(7)').css('color', '#ffffff');
                        
                    }
                },
    			columns: [{
    					"data": "no",
    					"className": 'text-center'
    				},
    				{
    					"data": "kd_barang"
    				},
    				{
    					"data": "nm_barang"
    				},
    				{
    					"data": "stok_barang",
    					"className": 'text-center'
    				},
    				{
    					"data": "sat_barang",
    					"className": 'text-center'
    				},
    				{
    					"data": "jenisobat",
    					"className": 'text-center'
    				},
    				{
    					"data": "hrgjual_barang",
    					"className": 'text-right',
    					"render": function(data, type, row) {
    						return formatRupiah(data);
    					}
    				},
    				{
    					"data": "komisi",
    					"className": 'text-right',
    					"render": function(data, type, row) {
    						return formatRupiah(data);
    					}
    				},
    				{
    					"data": "aksi",
    					"visible": <?= ($_SESSION['level'] == 'pemilik') ? 'true' : 'false'; ?>,
    					"render": function(data, type, row) {
    				// 		var btn = "<div style='text-align:center'><a href='?module=barang&act=edit&id=" + data + "' title='EDIT' class='btn btn-warning btn-xs'>EDIT</a> <a href=javascript:confirmdelete('modul/mod_barang/aksi_barang.php?module=barang&act=hapus&id=" + data + "') title='HAPUS' class='btn btn-danger btn-xs'>HAPUS</a></div>";
    
                            var btn = "<button type='button' class='btn btn-warning btn-xs' id='btn_edit' data-id='"+data+"'>EDIT</button> <button type='button' class='btn btn-danger btn-xs' id='btn_hapus' data-id='"+data+"'>HAPUS</button></div>";
                            
    						return btn;
    					}
    				},
    			]
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
            
                if (confirm('Anda yakin ingin menghapus?') == true) {
                    $.ajax({
        				url: 'modul/mod_komisi/aksi_komisi.php?module=komisi&act=hapus&id='+id,
        				type: 'POST',
        			}).success(function() {
        			    table.ajax.reload(null, false);
        			});
                } 
            });
    		
    		// Tombol edit
            $('#example11 tbody').on('click', '#btn_edit', function () {
                var id = $(this).data('id');
                var currentPage = table.page() + 1;
                location.href = '?module=komisi&act=editkomisi&id='+id+'&page='+currentPage; 
            });
    		
    		// Tombol cancel form
            $('#btn_cancel').on('click', function(){
                var currentPage = $(this).data('page');
                location.href = '?module=komisi&page='+currentPage;
                        
            });
            
            // Form Edit barang
            $("#frmEditBrg").submit(function(e) {
    
                e.preventDefault(); // avoid to execute the actual submit of the form.
                    
                var form = $(this);
                var actionUrl = form.attr('action');
                var vpage = <?=isset($_GET['page']) ? intval($_GET['page']) : 1;?>;
                        
                $.ajax({
                    type: "POST",
                    url: actionUrl,
                    data: form.serialize(), // serializes the form's elements.
                    success: function(data)
                    {
                        location.href = '?module=barang&page='+vpage;
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
    
    break;
	
	case "tambah":
        
?>        
		  <div class='box box-primary box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>TAMBAH KOMISI</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
				
						<form method="POST" action="<?=$aksi?>?module=komisi&act=input_komisi" enctype="multipart/form-data" class="form-horizontal">
							   
							  <div class='form-group'>
									<label class="col-sm-2 control-label">NAMA BARANG</label>        		
									 <div class="col-sm-3">
										<!--<select name="barang" class="form-control js-example-basic-single" >-->
										<!--	<option value="all">All</option>-->
										    <?php
										      //  $getbarang = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang ORDER BY id_barang ASC");
										      //  while($br = mysqli_fetch_array($getbarang)):
										    ?>
											<!--<option value="<?=$br['id_barang']?>"><?=$br['nm_barang']?></option>-->
										    <?php
										      //  endwhile;
										    ?>
										</select>
										<input type="text" name="barang" id="barang" class="form-control typeahead" required="required" autocomplete="off">
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class="col-sm-2 control-label">&nbsp</label>        		
									 <div class="col-sm-3">
										<select name="metode" class="form-control" >
											<option value="nominal">Nominal</option>
											<option value="persentase">Persentase</option>
										</select>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>JUMLAH KOMISI</label>        		
									 <div class='col-sm-6'>
										<input type="number" name="komisi" class="form-control" required="required" autocomplete="off">
									 </div>
							  </div>
							  
							  <div class="form-group">
									<label class="col-sm-2 control-label"></label>       
										<div class="col-sm-5">
											<input class="btn btn-primary" type="submit" value="SIMPAN">
											<input class="btn btn-danger" type="button" value="BATAL" onclick="self.history.back()">
										</div>
								</div>
								
							  </form>
							  
				</div> 
				
			</div>
			
			<script>
		        $('#barang').typeahead({
            		source: function(query, process) {
            			return $.post('modul/mod_komisi/autonamabarang.php', {
            				query: query
            			}, function(data) {
            
            				data = $.parseJSON(data);
            				return process(data);
            
            			});
            		}
            	});

		    </script>		
		    
					
<?php	
    break;
    case "editkomisi":

        $edit=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang WHERE id_barang='$_GET[id]'");
        $r=mysqli_fetch_array($edit);

?>
        

            <div class='box box-primary box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>EDIT KOMISI</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
				
						<form method="POST" action="<?=$aksi?>?module=komisi&act=update_komisi" enctype="multipart/form-data" class="form-horizontal">
							   
							  <div class='form-group'>
									<label class="col-sm-2 control-label">NAMA BARANG</label>        		
									 <div class="col-sm-3">
										<select name="barang" class="form-control js-example-basic-single" >
											<option value="<?=$r['id_barang']?>"><?=$r['nm_barang']?></option>
										</select>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class="col-sm-2 control-label">&nbsp</label>        		
									 <div class="col-sm-3">
										<select name="metode" class="form-control" >
											<option value="nominal">Nominal</option>
											<option value="persentase">Persentase</option>
										</select>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Jumlah Komisi</label>        		
									 <div class='col-sm-6'>
										<input type="number" name="komisi" class="form-control" required="required" value="<?=$r['komisi']?>" autocomplete="off">
									 </div>
							  </div>
							  
							  <div class="form-group">
									<label class="col-sm-2 control-label"></label>       
										<div class="col-sm-5">
											<input class="btn btn-primary" type="submit" value="SIMPAN">
											<input class="btn btn-danger" type="button" value="BATAL" onclick="self.history.back()">
										</div>
								</div>
								
							  </form>
							  
				</div> 
				
			</div>
		
<?php
        break;

  case "tutupkomisi":
      $staff = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM admin WHERE akses_level = 'petugas' ORDER BY id_admin ASC");
      
?>
      
      <div class="box box-primary box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">TUTUP KOMISI</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class="box-body table-responsive">
				    
					<table id="example1" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama</th>
								<th>Telp/HP</th>
								<th>Start Date</th>
								<th>Finish Date</th>
								<th style="text-align: right; ">Total Komisi</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
						    <?php
						        $no=1;
						        while($r = mysqli_fetch_array($staff)):
						            
						            $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT SUM(ttl_komisi) as total_komisi, MIN(tgl_komisi) as min_date, MAX(tgl_komisi) as max_date
						                FROM komisi_pegawai WHERE id_admin = '$r[id_admin]' AND status_komisi = 'on'");
						          
						            $kms = mysqli_fetch_array($query);
						    ?>
						    <tr>
								<td><?=$no++?></td>
								<td><?=$r['nama_lengkap']?></td>
								<td><?=$r['no_telp']?></th>
								<td style="text-align: center; "><?=$kms['min_date']?></td>
								<td style="text-align: center; "><?=$kms['max_date']?></td>
								<td style="text-align: right; "><?=format_rupiah($kms['total_komisi'])?></td>
								<td style="width: 80px; text-align: center">
								    <?php if($kms['total_komisi'] > 0):?>
                                        <a href="<?=$aksi?>?module=komisi&act=close&id=<?=$r['id_admin']?>" title="closed" class="btn btn-primary">CLOSED</a> 
								    <?php else:?>
                                        <a href="#" title="closed" class="btn btn-primary" disabled>CLOSED</a> 
								    <?php endif;?>
								</td>
                                
							</tr>
						    <?php
						        endwhile;
						    ?>
						</tbody>
					</table>
				</div>
			</div>	
            
<?php
    
    break;




}

}
?>

<script type="text/javascript">
    $(function(){
        $(".datepicker").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });
    });
 
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
</script>

