<?php
session_start();
if (empty($_SESSION['username']) and empty($_SESSION['passuser'])) {
    echo "<link href=../css/style.css rel=stylesheet type=text/css>";
    echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
} else {

    $aksi = "modul/mod_kartustok/aksi_kartustok.php";
    $aksi_barang = "masuk/modul/mod_barang/aksi_barang.php";
    switch ($_GET['act']) {
            // Tampil barang
        default:


            $tampil_barang = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang ORDER BY stok_barang DESC");

?>

            <div class="box box-primary box-solid table-responsive">
                <div class="box-header with-border">
                    <h3 class="box-title">Data Barang / Kartu Stok</h3>
                    <!--<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>-->

                    <div class="box-tools pull-center">

                    </div><!-- /.box-tools -->
                </div>
                <div class="box-body">
                    <a  class ='btn  btn-success btn-flat' href='modul/mod_laporan/cetak_nilai_barang.php' target="_blank"><i class="fa fa-print"></i>&nbsp; Cetak</a>
                    <hr>
                    
                    <table id="tabel1" class="table table-bordered table-striped">
                    <!--<table id="example1" class="table table-bordered table-striped">-->
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th style="text-align: right; ">Qty/Stok</th>
                                <th style="text-align: right; ">T30</th>
                                <th style="text-align: right;">T60</th>
                                <th style="text-align: right;">gr(%)</th>
                                <th style="text-align: right; ">Q30</th>
                                <th style="text-align: center; ">Satuan</th>
                                <th style="text-align: right; ">Harga Beli</th>
                                <th style="text-align: center; ">Nilai Barang</th>
                                <th width="70">Kartu Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                           
        //                     $no = 1;
        //                     while ($r = mysqli_fetch_assoc($tampil_barang)) {
        //                         $t30 = $r['kd_barang'];
        //                         $tgl_awal = date('Y-m-d');
        //                         $tgl_akhir = date('Y-m-d', strtotime('-30 days', strtotime($tgl_awal)));
        //                         $tgl60 = date('Y-m-d', strtotime('-60 days', strtotime($tgl_awal)));
        //                         $hargabeli = format_rupiah($r['hrgsat_barang']);
        //                         $hargajual = format_rupiah($r['hrgjual_barang']);

        //                         $pass = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir JOIN trkasir_detail
        //                                 ON (trkasir.kd_trkasir=trkasir_detail.kd_trkasir)
        //                                 WHERE kd_barang = '$t30' AND (tgl_trkasir BETWEEN '$tgl_akhir' and '$tgl_awal')");
        //                         $pass1 = mysqli_num_rows($pass);

        //                         $pass3 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM trkasir JOIN trkasir_detail
        //                                 ON (trkasir.kd_trkasir=trkasir_detail.kd_trkasir)
        //                                 WHERE kd_barang = '$t30' AND (tgl_trkasir BETWEEN '$tgl60' and '$tgl_awal')");
        //                         $pass4 = mysqli_num_rows($pass3);
        //                         $pass5 = $pass4 - $pass1;
        //                         $pass6 = intval(round(($pass1 / $pass5 * 100) - 100));


        //                         $pass2 = mysqli_fetch_array($pass);
        //                         $tot = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT
        //                                 trkasir_detail.kd_barang,
        //                                 trkasir_detail.id_dtrkasir,
        //                                 trkasir_detail.kd_trkasir,
        //                                 SUM(trkasir_detail.qty_dtrkasir) as pw,
        //                                 trkasir.kd_trkasir,
        //                                 trkasir.tgl_trkasir                            
        //                                 FROM trkasir_detail 
        //                                 JOIN trkasir ON (trkasir_detail.kd_trkasir=trkasir.kd_trkasir)
        //                                 WHERE kd_barang = '$pass2[kd_barang]' AND (tgl_trkasir BETWEEN '$tgl_akhir' and '$tgl_awal')");

        //                         $t2 = mysqli_fetch_array($tot);
        //                         $q30 = $t2['pw'];
        //                         $hargabeli = format_rupiah($r['hrgsat_barang']);
        //                         $hargajual = format_rupiah($r['hrgjual_barang']);
        //                         $nilaibarang = format_rupiah($r['hrgsat_barang'] * $r['stok_barang']);
        //                         $nilaibarang2 = $r['hrgsat_barang'] * $r['stok_barang'];
        //                         $totalbarang += $nilaibarang2;
        //                         $tb = format_rupiah($totalbarang);


        //                         echo "<tr class='warnabaris' >";
        //                         if ($pass1 <= "0") {
        //                             echo " <td style='background-color:#dd4b39;'>$no</td> ";
        //                         } elseif ($pass1 > "0" && $pass1 <= "5") {
        //                             echo "  <td style='background-color:#f39c12;'>$no</td>";
        //                         } elseif ($pass1 > "0" && $pass1 <= "10") {
        //                             echo "  <td style='background-color:#00a65a;'>$no</td>";
        //                         } elseif ($pass1 > "10") {
        //                             echo "  <td style='background-color:#00c0ef;'>$no</td>";
        //                         }

        //                         echo "    											         
								// 			 <td>$r[kd_barang]</td>
								// 			 <td>$r[nm_barang]</td>
								// 			 <td align=right>$r[stok_barang]</td>
									
								// 			 <td align=right>$pass1</td>
								// 			 <td align=right>$pass5</td>
								// 			 ";

        //                         if ($pass6 < -30) {
        //                             echo "<td align=right style='background-color:#dd4b39; color:#ffffff'>$pass6</td>";
        //                         } elseif ($pass6 < 0 && $pass >= -30) {
        //                             echo "<td align=right style='background-color:#f39c12; color:#ffffff'>$pass6</td>";
        //                         } else {
        //                             echo "<td align=right>$pass6</td>";
        //                         }


        //                         if ($q30 < "0") {
        //                             echo "<td align='right'> 0 </td>";
        //                         } else {
        //                             echo "<td align='right'>$q30</td>";
        //                         }

        //                         echo "											
								// 			 <td align=center>$r[sat_barang]</td>
											
								// 			 <td align=right>$hargabeli</td>
								// 			 <td align=right> $nilaibarang </td>
								// 			 <td>
											 
        //                                      <a href='?module=kartustok&act=view&id=$r[kd_barang]' title='Riwayat' class='btn btn-warning btn-xs'>Kartu Stok</a> 
											 
								// 			</td>
											
											 
								// 		</tr>";
        //                         $no++;
        //                     }


                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <script>
                $(document).ready(function() {
                    //var i = 1;
                    var table = $('#tabel1').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "lengthChange": false,
                        "displayStart": getPageFromUrl() * 10,
                        "pageLength": 10,
                        "ajax": {
                            "url": "modul/mod_kartustok/kartu_stok_serverside.php",
                            "type": "post",
                            "dataType": "json",
                            "error": function (xhr, error, thrown) {
                                // console.log("XHR Response:", xhr.responseText);
                                alert("Error: " + xhr.status + " - " + thrown);
                            },
                            // "success": function(dta){
                            //     console.log(dta);
                            // },
                            // "dataSrc": function (json) {
                            //     console.log("DATA DARI SERVER:", json);
                            //     return json.data;
                            // }
                        },
                        "columns": [
                            { "data": "no", "className": "text-center" },
                            { "data": "kd_barang" },
                            { "data": "nm_barang" },
                            { "data": "stok_barang", "className": "text-center" },
                            { "data": "t30", "className": "text-center" },
                            { "data": "t60", "className": "text-center" },
                            { "data": "gr", "className": "text-center" },
                            { "data": "q30", "className": "text-center" },
                            { "data": "sat_barang" },
                            { 
                                "data": "hrgsat_barang", "className": 'text-right',
            					"render": function(data, type, row) {
            						return formatRupiah(data);
            					}
                            },
                            { 
                                "data": "nilai_barang", "className": 'text-right',
            					"render": function(data, type, row) {
            						return formatRupiah(data);
            					} 
                                
                            },
                            { "data": "kartu_stok", "className": "text-center" }
                        ],
                        "rowCallback": function(row, data, index) {
                            var pass1 = data['t30'];
                            
                            if (pass1 <= 0) {
                                $(row).find('td:eq(0)').css('background-color', '#dd4b39');
                            } else if (pass1 > 0 && pass1 <= 5) {
                                $(row).find('td:eq(0)').css('background-color', '#f39c12');
                            } else if (pass1 > 0 && pass1 <= 10) {
                                $(row).find('td:eq(0)').css('background-color', '#00a65a');
                            } else if (pass1 > 10) {
                                $(row).find('td:eq(0)').css('background-color', '#00c0ef');
                            }
                            
                            var pass6 = data['gr'];
                            
                            if (pass6 < -30) {
                                $(row).find('td:eq(6)').css('background-color', '#dd4b39');
                                    // echo "<td align=right style='background-color:#dd4b39; color:#ffffff'>$pass6</td>";
                            } else if (pass6 < 0 && pass6 >= -30) {
                                $(row).find('td:eq(6)').css('background-color', '#f39c12');
                                    // echo "<td align=right style='background-color:#f39c12; color:#ffffff'>$pass6</td>";
                            } 
                            
                        },
                    });
                    
                    table.on('draw', function () {
                        const info = table.page.info();
                        const currentPage = info.page + 1; // konversi ke 1-based
                        const url = new URL(window.location);
                        url.searchParams.set('page', currentPage);
                        window.history.pushState({}, '', url);
                    });
                
                    $('#tabel1 tbody').on('click', '#btn_kartustok', function () {
                        var id = $(this).data('id');
                        var currentPage = table.page() + 1;
                        location.href = '?module=kartustok&act=view&id='+id+'&page='+currentPage;
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

        case "view":

            $kdbarang = $_GET['id'];
            $tampil_barang = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang WHERE kd_barang = '$kdbarang' ");
            $tampil = mysqli_fetch_array($tampil_barang);

        ?>


            <div class="box box-primary box-solid table-responsive">
                <div class="box-header with-border">
                    <h3 class="box-title">KARTU STOK</h3>
                    <!--<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>-->

                    <div class="box-tools pull-center">

                    </div><!-- /.box-tools -->
                </div>
                <div class="box-body">

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-1 col-form-label">Nama Barang</label>
                        <div class="col-sm-10">
                            <!-- <input type="text" class="form-control" id="inputEmail3"> -->
                            <label>: <?= $tampil['nm_barang'] ?></label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-1 col-form-label">Satuan</label>
                        <div class="col-sm-10">
                            <!-- <input type="text" class="form-control" id="inputPassword3"> -->
                            <label>: <?= $tampil['sat_barang'] ?></label>
                        </div>
                    </div>
                    <a  class ='btn  btn-success btn-flat' href='modul/mod_laporan/cetak_kartu_stok.php?id=<?=$_GET['id']?>' target="_blank"><i class="fa fa-print"></i>&nbsp; Cetak</a>
                    <hr />


                    <!--<table id="tes" class="table table-bordered table-striped" >-->
                    <!--<table id="tabel1" class="table table-bordered table-striped">-->
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="text-align: center; ">No</th>
                                <th style="text-align: center; ">Current Time</th>
                                <th style="text-align: center; ">Bulan</th>
                                <th style="text-align: center; ">Nomor Transaksi</th>
                                <th style="text-align: center; ">Qty Masuk (Pembelian)</th>
                                <th style="text-align: center; ">Qty Keluar (Penjualan)</th>
                                <th style="text-align: center; ">Total (Qty Masuk - Qty Keluar)</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $kdbarang = $_GET['id'];
                                $dataQuery = "SELECT * FROM kartu_stok
                                            WHERE kartu_stok.kode_transaksi IN (SELECT trbmasuk_detail.kd_trbmasuk FROM trbmasuk_detail WHERE trbmasuk_detail.kd_barang = '$kdbarang')
                                            OR kartu_stok.kode_transaksi IN (SELECT trkasir_detail.kd_trkasir FROM trkasir_detail WHERE trkasir_detail.kd_barang = '$kdbarang')
                                            ORDER BY kartu_stok.tgl_sekarang ASC";
                                
                                $dataResult = $db->query($dataQuery);
                                
                                $no = 1;
                                $totalStok = 0;
                                $sisa = 0;
                                while ($row = $dataResult->fetch_assoc()) {
                                    $brgMasuk   = $db->query("SELECT qty_dtrbmasuk FROM trbmasuk_detail WHERE kd_trbmasuk = '$row[kode_transaksi]' AND kd_barang = '$kdbarang'");
                                    $masuk      = $brgMasuk->fetch_assoc();
                                    
                                    $brgKeluar  = $db->query("SELECT qty_dtrkasir FROM trkasir_detail WHERE kd_trkasir = '$row[kode_transaksi]' AND kd_barang = '$kdbarang'");
                                    $keluar     = $brgKeluar->fetch_assoc();
                                    
                                    $msk = (int) ($masuk['qty_dtrbmasuk'] ?? 0);
                                    $klr = (int) ($keluar['qty_dtrkasir'] ?? 0);
                                    $totalStok += ($msk - $klr);
                                    
                                    $sisa = $sisa + $msk;
                                    echo '
                                        <tr>
                                            <td style="text-align: center; ">'.$no.'</td>
                                            <td style="text-align: center; ">'.date('Y-m-d H:i:s', strtotime($row['tgl_sekarang'])).'</td>
                                            <td style="text-align: center; ">'.date('F', strtotime($row['tgl_sekarang'])).'</td>
                                            <td style="text-align: center; ">'.$row['kode_transaksi'].'</td>
                                            <td style="text-align: center; ">'.$msk.'</td>
                                            <td style="text-align: center; ">'.$klr.'</td>
                                            <td style="text-align: center; ">'.$totalStok.'</td>
                                        </tr>
                                    ';
                                    
                                    $sisa = $totalStok;
                                    $no++;
                                }
                            // $no = 1;
                            // $getlogs = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM kartu_stok a 
                            // LEFT JOIN trbmasuk_detail b ON a.kode_transaksi=b.kd_trbmasuk
                            // LEFT JOIN trkasir_detail c ON a.kode_transaksi=c.kd_trkasir
                            // WHERE b.kd_barang = '$kdbarang' OR c.kd_barang = '$kdbarang' ORDER BY tgl_sekarang ASC");

                            // $total = 0;
                            // while ($r = mysqli_fetch_array($getlogs)) :
                            //     if ($r['qty_dtrbmasuk']) {
                            //         $total += $r['qty_dtrbmasuk'];
                            //     } elseif ($r['qty_dtrkasir']) {
                            //         $total -= $r['qty_dtrkasir'];
                            //     }

                            ?>
                                <!--<tr>-->
                                <!--    <td class="text-center"><?= $no; ?>.</td>-->
                                <!--    <td><?= date('Y-m-d H:i:s', strtotime($r['tgl_sekarang'])); ?></td>-->
                                <!--    <td class="text-center"><?= date('F', strtotime($r['tgl_sekarang'])); ?></td>-->
                                <!--    <td><?= $r['kode_transaksi']; ?></td>-->
                                <!--    <td class="text-center"><?= ($r['qty_dtrbmasuk'] != null) ? $r['qty_dtrbmasuk'] : 0; ?></td>-->
                                <!--    <td class="text-center"><?= ($r['qty_dtrkasir'] != null) ? $r['qty_dtrkasir'] : 0; ?></td>-->
                                <!--    <td class="text-center"><?= $total; ?></td>-->
                                <!--</tr>-->
                            <?php
                            //     $no++;
                            // endwhile; 
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-center">
                                    <h3>Total Stok Barang</h3>
                                </td>
                                <td colspan="3" class="text-left">
                                    <h3><?= $totalStok; ?></h3>
                                    <!--<h3 id="totalStok"></h3>-->
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <script>
                // $(document).ready(function() {
                //     var table = $('#tabel1').DataTable({
                //         "processing": true,
                //         "serverSide": true,
                //         "ajax": {
                //             "url": "modul/mod_kartustok/detail_kartustok_serverside.php?id=<?=$_GET['id']?>",
                //             "type": "POST",
                //             "dataSrc": function(json) {
                //                 $('#totalStok').text(json.total_stok);
                //                 console.log(json.total_stok);
                //                 return json.data;
                //             }
                //         },
                //         "columns": [
                //             { "data": "no" },
                //             { "data": "current_time" },
                //             { "data": "bulan" },
                //             { "data": "kode_transaksi" },
                //             { "data": "qty_masuk", "className": "text-center" },
                //             { "data": "qty_keluar", "className": "text-center" },
                //             { "data": "total", "className": "text-center" }
                //         ]
                //     });
                    
                //     // table.on('draw', function () {
                //     //     const info = table.page.info();
                //     //     const currentPage = info.page + 1; // konversi ke 1-based
                //     //     const url = new URL(window.location);
                //     //     url.searchParams.set('page', currentPage);
                //     //     window.history.pushState({}, '', url);
                //     // });
                
                //     // $('#tabel1 tbody').on('click', '#btn_kartustok', function () {
                //     //     var id = $(this).data('id');
                //     //     var currentPage = table.page() + 1;
                //     //     location.href = '?module=kartustok&act=view&id='+id+'&page='+currentPage;
                //     // });
                
                //     // function getPageFromUrl() {
                //     //     const params = new URLSearchParams(window.location.search);
                //     //     const page = parseInt(params.get("page"));
                //     //     return isNaN(page) ? 0 : page - 1; // DataTables pakai index mulai dari 0
                //     // }
                // });
            </script>
<?php


            break;
    }
}
?>


<script type="text/javascript">
    $(document).ready(function(){
        $('#tono1').DataTable();
    })
    
    $(function() {
        $(".datepicker").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });
    });
    
</script>