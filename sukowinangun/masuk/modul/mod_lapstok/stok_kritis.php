<?php
session_start();
if (empty($_SESSION['username']) and empty($_SESSION['passuser'])) {
	echo "<link href=../css/style.css rel=stylesheet type=text/css>";
	echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
} else {

	$aksi = "modul/mod_barang/aksi_barang.php";
	$aksi_barang = "masuk/modul/mod_barang/aksi_barang.php";
	switch ($_GET['act']) {
			// Tampil barang
		default:
?>
			<div class="box box-primary box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">STOK KRITIS</h3>
					<!--<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>-->

					<div class="box-tools pull-center">

					</div><!-- /.box-tools -->
				</div>
				<div class="box-body table-responsive">
					<!--<a  class ='btn  btn-success btn-flat' href='?module=barang&act=tambah'>TAMBAH</a>-->
					<CENTER><strong>STOK KRITIS</strong></CENTER><br>
					<hr>
					<form method="POST" action="#" target="_blank" enctype="multipart/form-data" class="form-horizontal">

						<div class="form-group">
							<label class="col-sm-2 control-label">Tanggal Awal</label>
							<div class="col-sm-4">
								<div class="input-group date">
									<div class="input-group-addon">
										<span class="glyphicon glyphicon-th"></span>
									</div>
									<input type="text" class="datepicker" name="tgl_awal" required="required" autocomplete="off" id="awal" value="<?= $_GET['start'] ?>">
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">Tanggal Akhir</label>
							<div class="col-sm-4">
								<div class="input-group date">
									<div class="input-group-addon">
										<span class="glyphicon glyphicon-th"></span>
									</div>
									<input type="text" class="datepicker" name="tgl_akhir" required="required" autocomplete="off" id="akhir" value="<?= $_GET['finish'] ?>">
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label"></label>
							<div class="buttons col-sm-4">
								<input class="btn btn-primary" type="button" id="submit" name="btn" value="SUBMIT">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
								<a class='btn  btn-danger' href='?module=home'>KEMBALI</a>
							</div>
						</div>
					</form>
					<hr />
				</div>
			</div>
			<script>
				$("#submit").on("click", function() {
					var awal = $("#awal").val();
					var akhir = $("#akhir").val();
					location.href = "?module=stok_kritis&act=kritis&start=" + awal + "&finish=" + akhir;
				});
			</script>
		<?php
			break;

		case "kritis":

			//$tampil_barang = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang where stok_barang <= stok_buffer ORDER BY barang.stok_barang ");

		?>


			<div class="box box-primary box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">STOK KRITIS</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div><!-- /.box-tools -->
				</div>
				<div class="box-body table-responsive">
					<a  class ='btn  btn-success btn-flat' href='modul/mod_laporan/cetak_stokkritis.php' target="_blank"><i class="fa fa-print"></i>&nbsp; Cetak</a>
					<br><br>

					<table id="tes1" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>No</th>
								<th>Kategori</th>
								<th>Nama Barang</th>
								<th style="text-align: right; ">Qty/Stok</th>
								<th style="text-align: right; ">T30</th>
								<th style="text-align: center; ">Q30</th>
								<th style="text-align: center; ">SFCmin</th>
								<th style="text-align: center; ">SFCmax</th>
								<th style="text-align: right; ">Satuan</th>
								<!-- <th style="text-align: right; ">Aksi</th> -->

							</tr>
						</thead>
						<tbody>
                        <?php
                        // $no=1;
                        // $query1 = $db->query("select * from barang left join trbmasuk_detail on(barang.id_barang=trbmasuk_detail.id_barang) 
                        //           where trbmasuk_detail.kd_trbmasuk is not null group by barang.id_barang order by barang.nm_barang asc ");
                        // while($r= $query1->fetch_array()){
                        //     $t30 = $r['id_barang'];
                        //     $tgl_awal = date('Y-m-d');
                        //     $tgl_akhir = date('Y-m-d', strtotime('-30 days', strtotime( $tgl_awal)));

                        //     $pass = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir JOIN trkasir_detail
                        //                 ON (trkasir.kd_trkasir=trkasir_detail.kd_trkasir)
                        //                 WHERE trkasir_detail.id_barang = '$t30' AND (tgl_trkasir BETWEEN '$tgl_akhir' and '$tgl_awal')");
                        //     $pass1 = mysqli_num_rows($pass);
                        //     $pass2 = mysqli_fetch_array($pass);
                        //     $tot =mysqli_query($GLOBALS["___mysqli_ston"], "SELECT SUM(trkasir_detail.qty_dtrkasir) as pw from trkasir_detail
                        //           join trkasir ON (trkasir_detail.kd_trkasir=trkasir.kd_trkasir)
                        //                 WHERE id_barang = '$pass2[id_barang]' AND (tgl_trkasir BETWEEN '$tgl_akhir' and '$tgl_awal')") ;
                        //     $t2 = mysqli_fetch_array($tot);
                        //     $q30 = $t2['pw'];
                        //     $sfc = $pass1 - $r['stok_barang'];
                        //     $qfc = $q30 - $r['stok_barang'];
                        // if($r['stok_barang']<=(0.25*$pass1) && $pass1!=0){
                        //     echo"
                        // <tr>
                        //     <td>$no</td>";
                        //     if( $pass1 <= "0"){
                        //         echo" <td style='background-color:#ff003f;'align='center'><strong>MACET</strong></td> ";                                                           }
                        //     elseif ($pass1 > "0" && $pass1 <= "5"){
                        //         echo"  <td style='background-color:#EDFF00;' align='center'><strong>SLOW</strong></td>"; }
                        //     elseif ($pass1 > "5" && $pass1 <= "10"){
                        //         echo"  <td style='background-color:#00ff3f;' align='center'><strong>LANCAR</strong></td>"; }
                        //     elseif ($pass1 > "10" ){
                        //         echo"  <td style='background-color:#00bfff;' align='center'><strong>LAKU</strong></td>"; }
                        //     echo"    
                        //     <td>$r[nm_barang]</td>
                        //     <td align='center'>$r[stok_barang]</td>
                        //     <td align='center'>$pass1</td>
                        //     <td align='center'>$q30</td>                            
                        //     <td style='background-color:#EDFF00;'>$sfc</td>                            
                        //     <td>$qfc</td>
                        //     <td>$r[sat_barang]</td>
                            
                        // </tr>";}
                        //     $no++;
                        // }
                        ?>
						</tbody>
					</table>
				</div>
			</div>

			<script>
				$(document).ready(function () {
                    var table = $('#tes1').DataTable({
                        serverSide: true,
                        // processing: true,
                        lengthChange: false,
                        displayStart: getPageFromUrl() * 10,
                        pageLength: 10,
                        ajax: {
                          url: 'modul/mod_lapstok/datatable_server.php',
                          type: 'POST'
                        },
                        "rowCallback": function(row, data, index) {
                            var q = data['transaksi_30_hari'];
                            if(q <= 0){
                                $(row).find('td:eq(0)').css('background-color','#ff003f');
                            } else if (q <= 5) {
                                $(row).find('td:eq(0)').css('background-color','#EDFF00');
                            } else if (q <= 10) {
                                $(row).find('td:eq(0)').css('background-color','#00ff3f');
                            } else {
                                $(row).find('td:eq(0)').css('background-color','#00bfff');
                            }
                        },
                        columns: [
                        //   { data: 'no' },
                            {
                                "data": null,
                                "orderable": false,
                                "searchable": false,
                                "render": function (data, type, row, meta) {
                                    return meta.row + meta.settings._iDisplayStart + 1;
                                }
                            },
                          { data: 'status' },
                          { data: 'nm_barang' },
                          { data: 'stok_barang' },
                          { data: 'transaksi_30_hari' },
                          { data: 'qty_terjual' },
                          { data: 'selisih_stok_transaksi' },
                          { data: 'selisih_stok_qty' },
                          { data: 'sat_barang' }
                        ]
                    });
                    
                    table.on('draw', function () {
                        const info = table.page.info();
                        const currentPage = info.page + 1; // konversi ke 1-based
                        const url = new URL(window.location);
                        url.searchParams.set('page', currentPage);
                        window.history.pushState({}, '', url);
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

			echo "
		  <div class='box box-primary box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>TAMBAH DATA BARANG</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
				
						<form method=POST action='$aksi?module=barang&act=input_barang' enctype='multipart/form-data' class='form-horizontal'>
						
						<input type=hidden name='id_supplier' id='id_supplier'>
							  							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Kode Barang</label>        		
									 <div class='col-sm-3'>
										<input type=text name='kd_barang' class='form-control' autocomplete='off'>
									 </div>
							  </div>
							  
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Nama Barang</label>        		
									 <div class='col-sm-4'>
										<input type=text name='nm_barang' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Qty/Stok</label>        		
									 <div class='col-sm-3'>
										<input type=number name='stok_barang' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Stok Buffer</label>        		
									 <div class='col-sm-3'>
										<input type=number name='stok_buffer' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div>
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Satuan</label>        		
									 <div class='col-sm-3'>
										<select name='sat_barang' class='form-control' >";
			$tampil = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM satuan ORDER BY nm_satuan ASC");
			while ($rk = mysqli_fetch_array($tampil)) {
				echo "<option value=$rk[nm_satuan]>$rk[nm_satuan]</option>";
			}
			echo "</select>
									 </div>
							  </div> 
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Jenis Obat</label>        		
									 <div class='col-sm-3'>
										<select name='jenis_obat' class='form-control' >";
			$tampil = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM jenis_obat ORDER BY jenisobat ASC");
			while ($rk = mysqli_fetch_array($tampil)) {
				echo "<option value=$rk[jenisobat]>$rk[jenisobat]</option>";
			}
			echo "</select>
									 </div>
							  </div>
							  
							  

							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Harga Beli</label>        		
									 <div class='col-sm-3'>
										<input type=number name='hrgsat_barang' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Harga Jual</label>        		
									 <div class='col-sm-3'>
										<input type=number name='hrgjual_barang' class='form-control' required='required' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Expired Date</label>
										<div class='col-sm-4'>
											<div class='input-group date'>
												<div class='input-group-addon'>
													<span class='glyphicon glyphicon-th'></span>
												</div>
													<input type='text' class='datepicker' name='tgl_expired' required='required' autocomplete='off'>
											</div>
										</div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Keterangan Lain</label>        		
									 <div class='col-sm-4'>
										<textarea name='ket_barang' class='form-control' rows='3'></textarea>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'></label>       
										<div class='col-sm-4'>
											<input class='btn btn-primary' type=submit value=SIMPAN>
											<input class='btn btn-danger' type=button value=BATAL onclick=self.history.back()>
										</div>
								</div>
								
							  </form>
							  
				</div> 
				
			</div>";


			break;

		case "edit":
			$edit = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang 
	WHERE barang.id_barang='$_GET[id]'");
			$r = mysqli_fetch_array($edit);

			echo "
		  <div class='box box-primary box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>UBAH DATA BARANG</h3>
					<div class='box-tools pull-right'>
						<button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    </div><!-- /.box-tools -->
				</div>
				<div class='box-body'>
						<form method=POST method=POST action=$aksi?module=barang&act=update_barang  enctype='multipart/form-data' class='form-horizontal'>
							  <input type=hidden name=id value='$r[id_barang]'>
							  
							 
							 <div class='form-group'>
									<label class='col-sm-2 control-label'>Kode Barang</label>        		
									 <div class='col-sm-3'>
										<input type=text name='kd_barang' class='form-control' required='required' value='$r[kd_barang]' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Nama Barang</label>        		
									 <div class='col-sm-4'>
										<input type=text name='nm_barang' class='form-control' required='required' value='$r[nm_barang]' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Qty/Stok</label>        		
									 <div class='col-sm-3'>
										<input type=number name='stok_barang' class='form-control' required='required' value='$r[stok_barang]' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Stok Buffer</label>        		
									 <div class='col-sm-3'>
										<input type=number name='stok_buffer' class='form-control' required='required' value='$r[stok_buffer]' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Satuan</label>        		
									 <div class='col-sm-3'>
										<select name='sat_barang' class='form-control' >
											 <option  value=$r[sat_barang] selected>$r[sat_barang]</option>";
			$tampil = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM satuan ORDER BY nm_satuan");
			while ($k = mysqli_fetch_array($tampil)) {
				echo "<option value=$k[nm_satuan]>$k[nm_satuan]</option>";
			}
			echo "</select>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Harga Beli</label>        		
									 <div class='col-sm-3'>
										<input type=number name='hrgsat_barang' class='form-control' required='required' value='$r[hrgsat_barang]' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Harga Jual</label>        		
									 <div class='col-sm-3'>
										<input type=number name='hrgjual_barang' class='form-control' required='required' value='$r[hrgjual_barang]' autocomplete='off'>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Expired Date</label>
										<div class='col-sm-4'>
											<div class='input-group date'>
												<div class='input-group-addon'>
													<span class='glyphicon glyphicon-th'></span>
												</div>
													<input type='text' class='datepicker' name='tgl_expired' required='required' value='$r[tgl_expired]' autocomplete='off'>
											</div>
										</div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'>Keterangan Lain</label>        		
									 <div class='col-sm-4'>
										<textarea name='ket_barang' class='form-control' rows='3'>$r[ket_barang]</textarea>
									 </div>
							  </div>
							  
							  <div class='form-group'>
									<label class='col-sm-2 control-label'></label>       
										<div class='col-sm-4'>
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


<script type="text/javascript">
	$(function() {
		$(".datepicker").datepicker({
			format: 'yyyy-mm-dd',
			autoclose: true,
			todayHighlight: true,
		});
	});
</script>