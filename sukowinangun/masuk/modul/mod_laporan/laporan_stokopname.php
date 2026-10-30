<?php
session_start();
if (empty($_SESSION['username']) and empty($_SESSION['passuser'])) {
    echo "<link href=../css/style.css rel=stylesheet type=text/css>";
    echo "<div class='error msg'>Untuk mengakses Modul anda harus login.</div>";
} else {

    switch ($_GET['act']) {
        default:

            ?>


            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">LAPORAN STOK OPNAME</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools
                    -->
                </div>
                <div class="box-body">

                    <!-- <form method="POST" action="modul/mod_laporan/tampil_lap_stokopname.php" target="_blank" enctype="multipart/form-data" class="form-horizontal"> -->
                    <form method="POST" action="?module=lapstokopname&act=laporanbulanan" enctype="multipart/form-data"
                        class="form-horizontal">


                        </br></br>


                        <div class="form-group">
                            <label class="col-sm-2 control-label">Tanggal Awal</label>
                            <div class="col-sm-4">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                    <input type="text" required="required" class="datepicker" name="tgl_awal" id="tgl_awal" autocomplete="off">
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
                                    <input type="text" required="required" class="datepicker" name="tgl_akhir" id="tgl_akhir" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class='form-group'>
                            <label class='col-sm-2 control-label'>WAKTU STOK OPNAME</label>
                            <div class='col-sm-3'>
                                <select name='shift' class='form-control' id="shift">
                                    <option value="0">SO BULANAN</option>
                                    <option value="1">Pagi</option>
                                    <option value="2">Sore</option>
                                    <option value="3">Malam</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label"></label>
                                <div class="buttons col-sm-6">
                                    <input class="btn btn-primary" type="submit" name="btn" value="TAMPIL">&nbsp
                                    <a class='btn btn-success' onclick='javascript:exportExcel()' target='_blank'>
                                        <i class='fa fa-fw fa-file-excel-o'></i> EXPORT EXCEL
                                    </a>&nbsp
                                    <a class='btn btn-danger' onclick='javascript:exportPDF()' target='_blank'>
                                        <i class='fa fa-fw fa-file-pdf-o'></i> CETAK PDF
                                    </a>&nbsp
                                    <a class='btn btn-warning' href='?module=home'>KEMBALI</a>
                                </div>
                        </div>


                    </form>
                </div>
                <div>
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
							<tr>
                                <td>No.</td>
                                <td>Tanggal SO</td>
                                <td>Jenis SO</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $so = $db->query("SELECT DISTINCT tgl_stokopname,shift FROM stok_opname ORDER BY tgl_stokopname DESC");
                            while ($r = $so->fetch_array()) {
                                if($r['shift']==0){
                                    $jenis="SO BULANAN";
                                }elseif($r['shift']==1){
                                    $jenis="PAGI";
                                }elseif($r['shift']==2){
                                    $jenis="SORE";
                                }elseif($r['shift']==3){
                                    $jenis="MALAM";
                                }
                                echo "
                                <tr>
                                    <td>$no</td>
                                    <td>$r[tgl_stokopname]</td>
                                    <td>$jenis</td>
                                </tr>
                                ";
                                $no++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <script>
                function exportExcel() {
                    let tgl_awal = $('#tgl_awal').val()
                    let tgl_akhir = $('#tgl_akhir').val()
                    let shift = $('#shift').val()

                    window.open('modul/mod_laporan/cetak_stokopname_excel.php?tgl_awal=' + tgl_awal + '&tgl_akhir=' + tgl_akhir +'&shift='+shift, '_blank');
                }
                function exportPDF() {
                let tgl_awal = $('#tgl_awal').val()
                let tgl_akhir = $('#tgl_akhir').val()
                let shift = $('#shift').val()               
                window.open('modul/mod_laporan/tampil_lap_stokopname.php?tgl_awal=' + tgl_awal + '&tgl_akhir=' + tgl_akhir + '&shift=' + shift, '_blank');
    }
            </script>

            <?php
            break;
        case "laporanbulanan":
            $tgl_awal = $_POST['tgl_awal'];
            $tgl_akhir = $_POST['tgl_akhir'];
            $shift = $_POST['shift'];
            $so = $db->query("SELECT * FROM stok_opname 
            	JOIN barang ON barang.id_barang = stok_opname.id_barang 
            	LEFT JOIN admin ON admin.id_admin = stok_opname.id_admin WHERE stok_opname.shift='$shift' and stok_opname.tgl_stokopname ='$tgl_awal' and stok_opname.selisih = 0 ORDER BY barang.nm_barang ASC");

            ?>
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">LAPORAN STOK OPNAME TANGGAL <?= $tgl_awal ?> SD <?= $tgl_akhir ?></h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools -->
                </div>
                <?php
                echo "
                <a class='btn  btn-success btn-flat' target='_blank' href='?module=lapstokopname&act=sinkron_min&tgl_awal=$tgl_awal&tgl_akhir=$tgl_akhir&shift=$shift'>SINKRONISASI STOK MINUS</a> 
                <a class='btn  btn-success btn-warning' target='_blank' href='?module=lapstokopname&act=sinkron_plus&tgl_awal=$tgl_awal&tgl_akhir=$tgl_akhir&shift=$shift'>SINKRONISASI STOK PLUS</a>                 
                
                ";
                ?>
                <a class='btn btn-danger' onclick='javascript:exportPDF()' target='_blank'>
                    <i class='fa fa-fw fa-file-pdf-o'></i> CETAK PDF
                    <input type="hidden" id="tgl_awal" value="<?=$tgl_awal?>">
                    <input type="hidden" id="tgl_akhir" value="<?=$tgl_akhir?>">
                    <input type="hidden" id="shift" value="<?=$shift?>">
                </a>
                <br>
                <?php
                echo "
                    <a class='btn btn-success' target='_blank' href='?module=lapstokopname&act=barang_minus&tgl_awal=$tgl_awal&tgl_akhir=$tgl_akhir&shift=$shift'>BARANG MINUS</a>
                    <a class='btn btn-warning' target='_blank' href='?module=lapstokopname&act=barang_plus&tgl_awal=$tgl_awal&tgl_akhir=$tgl_akhir&shift=$shift'>BARANG PLUS</a>
                ";
                ?>
                <div class="box-body table-responsive">
                    <form action="modul/mod_barang/hapus_barang.php" method="post">

                        <table id="example100" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Petugas</th>
                                    <th style="text-align: left; ">Nama Barang</th>
                                    <th style="text-align: right; ">Satuan</th>
                                    <th style="text-align: center; ">Jenis Obat</th>
                                    <th style="text-align: center; ">Exp</th>
                                    <th style="text-align: right; ">JmlED</th>
                                    <th style="text-align: right; ">SS</th>
                                    <th style="text-align: center; ">SF</th>
                                    <th style="text-align: center; ">Hasil</th>
                                    <th style="text-align: right; ">Harga</th>
                                    <th style="text-align: right; ">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                while ($r = $so->fetch_array()) {
                                    $hargabeli = format_rupiah($r['hrgsat_barang']);
                                    $total = format_rupiah($r['ttl_hrgbrg']);
                                    $petugas = $db->query("select nama_lengkap from admin where id_admin='$r[id_admin]'");
                                    $ptg = $petugas->fetch_array();
                                    
                                    $barang = $db->query("SELECT jenisobat FROM barang 
                                                        WHERE id_barang='$r[id_barang]' AND kd_barang='$r[kd_barang]'");
                                    $brg = $barang->fetch_array();
                                    
                                    if ($r['ttl_hrgbrg'] < 0) {
                                        echo "
                                <tr style='background-color:#FF00FF'>
                                <td>$no</td>
                                <td>$ptg[nama_lengkap]</td>                               
                                <td>$r[nm_barang]</td>
                                <td>$r[sat_barang]</td>
                                <td>$brg[jenisobat]</td>
                                <td>$r[exp_date]</td>
                                <td style='text-align: center;'>$r[jml]</td>
                                <td style='text-align: center;'>$r[stok_sistem]</td>
                                <td style='text-align: center;'>$r[stok_fisik]</td>
                                <td style='text-align: center;'>$r[selisih]</td>                                
                                <td style='text-align: right;'>$hargabeli</td>
                                <td style='text-align: right;font-weight:bold;'>$total</td>
                                </tr>
                                ";
                                    } else {
                                        echo "
                                <tr>
                                <td>$no</td>
                                <td>$ptg[nama_lengkap]</td>                               
                                <td>$r[nm_barang]</td>
                                <td>$r[sat_barang]</td>
                                <td>$brg[jenisobat]</td>
                                <td>$r[exp_date]</td>
                                <td style='text-align: center;'>$r[jml]</td>
                                <td style='text-align: center;'>$r[stok_sistem]</td>
                                <td style='text-align: center;'>$r[stok_fisik]</td>
                                <td style='text-align: center;'>$r[selisih]</td>                                
                                <td style='text-align: right;'>$hargabeli</td>
                                <td style='text-align: right;font-weight:bold;'>$total</td>
                                </tr>
                                ";
                                    }

                                    $no++;
                                }
                                ?>
                            </tbody>
                            <tfoot style="background-color:aqua; font-weight:bold; font-size:large;">
                                <?php
                                $minus = $db->query("select sum(ttl_hrgbrg) as min from stok_opname
                                where ttl_hrgbrg<0 and shift='$shift' and tgl_stokopname between '$tgl_awal' and '$tgl_akhir' ");
                                $kurang = $minus->fetch_array();
                                $kurmin = format_rupiah(round($kurang['min'], 0));

                                $lebih = $db->query("select sum(ttl_hrgbrg) as plus from stok_opname
                                where ttl_hrgbrg>0 and shift='$shift' and tgl_stokopname between '$tgl_awal' and '$tgl_akhir' ");
                                $lbh = $lebih->fetch_array();
                                $plus = format_rupiah(round($lbh['plus'], 0));


                                // echo "
                                // <tr>
                                //     <td colspan='9' style='text-align: right;' >Total Barang Minus</td>
                                //     <td colspan='2' style='text-align: right;'>Rp. &nbsp; $kurmin</td>
                                // </tr>
                                // <tr>
                                //     <td colspan='9' style='text-align: right;'>Total Barang Lebih</td>
                                //     <td colspan='2' style='text-align: right;'>Rp. &nbsp; $plus</td>
                                // </tr>
                                // ";
                                ?>
                            </tfoot>

                        </table>
                    </form>
                </div>
            </div>
            <script>
                function exportExcel() {
                    let tgl_awal = $('#tgl_awal').val()
                    let tgl_akhir = $('#tgl_akhir').val()
                    let shift = $('#shift').val()

                    window.open('modul/mod_laporan/cetak_stokopname_excel.php?tgl_awal=' + tgl_awal + '&tgl_akhir=' + tgl_akhir +'&shift='+shift, '_blank');
                }
                function exportPDF() {
                    let tgl_awal = $('#tgl_awal').val()
                    let tgl_akhir = $('#tgl_akhir').val()
                    let shift = $('#shift').val()               
                    window.open('modul/mod_laporan/tampil_lap_stokopname.php?tgl_awal=' + tgl_awal + '&tgl_akhir=' + tgl_akhir + '&shift=' + shift, '_blank');
                }
            </script>
            <?PHP
            break;
            
        case "barang_minus":
            $tgl_awal = $_GET['tgl_awal'];
            $tgl_akhir = $_GET['tgl_akhir'];
            $shift = $_GET['shift'];
            $so = $db->query("SELECT * FROM stok_opname 
        	JOIN barang ON barang.id_barang = stok_opname.id_barang 
        	LEFT JOIN admin ON admin.id_admin = stok_opname.id_admin WHERE stok_opname.shift='$shift' and stok_opname.tgl_stokopname ='$tgl_awal' and stok_opname.selisih < 0 ORDER BY barang.nm_barang ASC");

        ?>
        <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">LAPORAN STOK OPNAME BARANG MINUS TANGGAL <?= $tgl_awal ?> SD <?= $tgl_akhir ?></h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools -->
                </div>
                <a class='btn btn-danger' onclick='javascript:exportPDF()' target='_blank'>
                    <i class='fa fa-fw fa-file-pdf-o'></i> CETAK PDF
                    <input type="hidden" id="tgl_awal" value="<?=$tgl_awal?>">
                    <input type="hidden" id="tgl_akhir" value="<?=$tgl_akhir?>">
                    <input type="hidden" id="shift" value="<?=$shift?>">
                </a>
                <div class="box-body table-responsive">
                    <form action="modul/mod_barang/hapus_barang.php" method="post">

                        <table id="example100" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Petugas</th>
                                    <th style="text-align: left; ">Nama Barang</th>
                                    <th style="text-align: right; ">Satuan</th>
                                    <th style="text-align: center; ">Jenis Obat</th>
                                    <th style="text-align: center; ">Exp</th>
                                    <th style="text-align: right; ">JmlED</th>
                                    <th style="text-align: right; ">SS</th>
                                    <th style="text-align: center; ">SF</th>
                                    <th style="text-align: center; ">Hasil</th>
                                    <th style="text-align: right; ">Harga</th>
                                    <th style="text-align: right; ">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                while ($r = $so->fetch_array()) {
                                    $hargabeli = format_rupiah($r['hrgsat_barang']);
                                    $total = format_rupiah($r['ttl_hrgbrg']);
                                    $petugas = $db->query("select nama_lengkap from admin where id_admin='$r[id_admin]'");
                                    $ptg = $petugas->fetch_array();
                                    
                                    $barang = $db->query("SELECT jenisobat FROM barang 
                                                        WHERE id_barang='$r[id_barang]' AND kd_barang='$r[kd_barang]'");
                                    $brg = $barang->fetch_array();
                                    
                                    if ($r['ttl_hrgbrg'] < 0) {
                                        echo "
                                <tr style='background-color:#FF00FF'>
                                <td>$no</td>
                                <td>$ptg[nama_lengkap]</td>                               
                                <td>$r[nm_barang]</td>
                                <td>$r[sat_barang]</td>
                                <td>$brg[jenisobat]</td>
                                <td>$r[exp_date]</td>
                                <td style='text-align: center;'>$r[jml]</td>
                                <td style='text-align: center;'>$r[stok_sistem]</td>
                                <td style='text-align: center;'>$r[stok_fisik]</td>
                                <td style='text-align: center;'>$r[selisih]</td>                                
                                <td style='text-align: right;'>$hargabeli</td>
                                <td style='text-align: right;font-weight:bold;'>$total</td>
                                </tr>
                                ";
                                    } else {
                                        echo "
                                <tr>
                                <td>$no</td>
                                <td>$ptg[nama_lengkap]</td>                               
                                <td>$r[nm_barang]</td>
                                <td>$r[sat_barang]</td>
                                <td>$brg[jenisobat]</td>
                                <td>$r[exp_date]</td>
                                <td style='text-align: center;'>$r[jml]</td>
                                <td style='text-align: center;'>$r[stok_sistem]</td>
                                <td style='text-align: center;'>$r[stok_fisik]</td>
                                <td style='text-align: center;'>$r[selisih]</td>                                
                                <td style='text-align: right;'>$hargabeli</td>
                                <td style='text-align: right;font-weight:bold;'>$total</td>
                                </tr>
                                ";
                                    }

                                    $no++;
                                }
                                ?>
                            </tbody>
                            <tfoot style="background-color:aqua; font-weight:bold; font-size:large;">
                                <?php
                                $minus = $db->query("select sum(ttl_hrgbrg) as min from stok_opname
                                where ttl_hrgbrg<0 and shift='$shift' and tgl_stokopname between '$tgl_awal' and '$tgl_akhir' ");
                                $kurang = $minus->fetch_array();
                                $kurmin = format_rupiah(round($kurang['min'], 0));

                                $lebih = $db->query("select sum(ttl_hrgbrg) as plus from stok_opname
                                where ttl_hrgbrg>0 and shift='$shift' and tgl_stokopname between '$tgl_awal' and '$tgl_akhir' ");
                                $lbh = $lebih->fetch_array();
                                $plus = format_rupiah(round($lbh['plus'], 0));


                                echo "
                                <tr>
                                    <td colspan='9' style='text-align: right;' >Total Barang Minus</td>
                                    <td colspan='2' style='text-align: right;'>Rp. &nbsp; $kurmin</td>
                                </tr>
                                ";
                                ?>
                            </tfoot>

                        </table>
                    </form>
                </div>
            </div>
            <script>
                function exportExcel() {
                    let tgl_awal = $('#tgl_awal').val()
                    let tgl_akhir = $('#tgl_akhir').val()
                    let shift = $('#shift').val()

                    window.open('modul/mod_laporan/cetak_stokopname_excel.php?tgl_awal=' + tgl_awal + '&tgl_akhir=' + tgl_akhir +'&shift='+shift, '_blank');
                }
                function exportPDF() {
                    let tgl_awal = $('#tgl_awal').val()
                    let tgl_akhir = $('#tgl_akhir').val()
                    let shift = $('#shift').val()               
                    window.open('modul/mod_laporan/tampil_lap_stokopname_minus.php?tgl_awal=' + tgl_awal + '&tgl_akhir=' + tgl_akhir + '&shift=' + shift, '_blank');
                }
            </script>
        <?php
            break;
            
        case "barang_plus":
            $tgl_awal = $_GET['tgl_awal'];
            $tgl_akhir = $_GET['tgl_akhir'];
            $shift = $_GET['shift'];
            $so = $db->query("SELECT * FROM stok_opname 
        	JOIN barang ON barang.id_barang = stok_opname.id_barang 
        	LEFT JOIN admin ON admin.id_admin = stok_opname.id_admin WHERE stok_opname.shift='$shift' and stok_opname.tgl_stokopname ='$tgl_awal' and stok_opname.selisih > 0 ORDER BY barang.nm_barang ASC");

        ?>
        <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">LAPORAN STOK OPNAME BARANG PLUS TANGGAL <?= $tgl_awal ?> SD <?= $tgl_akhir ?></h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div><!-- /.box-tools -->
                </div>
                <a class='btn btn-danger' onclick='javascript:exportPDF()' target='_blank'>
                    <i class='fa fa-fw fa-file-pdf-o'></i> CETAK PDF
                    <input type="hidden" id="tgl_awal" value="<?=$tgl_awal?>">
                    <input type="hidden" id="tgl_akhir" value="<?=$tgl_akhir?>">
                    <input type="hidden" id="shift" value="<?=$shift?>">
                </a>
                <div class="box-body table-responsive">
                    <form action="modul/mod_barang/hapus_barang.php" method="post">

                        <table id="example100" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Petugas</th>
                                    <th style="text-align: left; ">Nama Barang</th>
                                    <th style="text-align: right; ">Satuan</th>
                                    <th style="text-align: center; ">Jenis Obat</th>
                                    <th style="text-align: center; ">Exp</th>
                                    <th style="text-align: right; ">JmlED</th>
                                    <th style="text-align: right; ">SS</th>
                                    <th style="text-align: center; ">SF</th>
                                    <th style="text-align: center; ">Hasil</th>
                                    <th style="text-align: right; ">Harga</th>
                                    <th style="text-align: right; ">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                while ($r = $so->fetch_array()) {
                                    $hargabeli = format_rupiah($r['hrgsat_barang']);
                                    $total = format_rupiah($r['ttl_hrgbrg']);
                                    $petugas = $db->query("select nama_lengkap from admin where id_admin='$r[id_admin]'");
                                    $ptg = $petugas->fetch_array();
                                    
                                    $barang = $db->query("SELECT jenisobat FROM barang 
                                                        WHERE id_barang='$r[id_barang]' AND kd_barang='$r[kd_barang]'");
                                    $brg = $barang->fetch_array();
                                    
                                    if ($r['ttl_hrgbrg'] < 0) {
                                        echo "
                                <tr style='background-color:#FF00FF'>
                                <td>$no</td>
                                <td>$ptg[nama_lengkap]</td>                               
                                <td>$r[nm_barang]</td>
                                <td>$r[sat_barang]</td>
                                <td>$brg[jenisobat]</td>
                                <td>$r[exp_date]</td>
                                <td style='text-align: center;'>$r[jml]</td>
                                <td style='text-align: center;'>$r[stok_sistem]</td>
                                <td style='text-align: center;'>$r[stok_fisik]</td>
                                <td style='text-align: center;'>$r[selisih]</td>                                
                                <td style='text-align: right;'>$hargabeli</td>
                                <td style='text-align: right;font-weight:bold;'>$total</td>
                                </tr>
                                ";
                                    } else {
                                        echo "
                                <tr>
                                <td>$no</td>
                                <td>$ptg[nama_lengkap]</td>                               
                                <td>$r[nm_barang]</td>
                                <td>$r[sat_barang]</td>
                                <td>$brg[jenisobat]</td>
                                <td>$r[exp_date]</td>
                                <td style='text-align: center;'>$r[jml]</td>
                                <td style='text-align: center;'>$r[stok_sistem]</td>
                                <td style='text-align: center;'>$r[stok_fisik]</td>
                                <td style='text-align: center;'>$r[selisih]</td>                                
                                <td style='text-align: right;'>$hargabeli</td>
                                <td style='text-align: right;font-weight:bold;'>$total</td>
                                </tr>
                                ";
                                    }

                                    $no++;
                                }
                                ?>
                            </tbody>
                            <tfoot style="background-color:aqua; font-weight:bold; font-size:large;">
                                <?php
                                $minus = $db->query("select sum(ttl_hrgbrg) as min from stok_opname
                                where ttl_hrgbrg<0 and shift='$shift' and tgl_stokopname between '$tgl_awal' and '$tgl_akhir' ");
                                $kurang = $minus->fetch_array();
                                $kurmin = format_rupiah(round($kurang['min'], 0));

                                $lebih = $db->query("select sum(ttl_hrgbrg) as plus from stok_opname
                                where ttl_hrgbrg>0 and shift='$shift' and tgl_stokopname between '$tgl_awal' and '$tgl_akhir' ");
                                $lbh = $lebih->fetch_array();
                                $plus = format_rupiah(round($lbh['plus'], 0));


                                echo "
                                <tr>
                                    <td colspan='9' style='text-align: right;'>Total Barang Lebih</td>
                                    <td colspan='2' style='text-align: right;'>Rp. &nbsp; $plus</td>
                                </tr>
                                ";
                                ?>
                            </tfoot>

                        </table>
                    </form>
                </div>
            </div>
            <script>
                function exportExcel() {
                    let tgl_awal = $('#tgl_awal').val()
                    let tgl_akhir = $('#tgl_akhir').val()
                    let shift = $('#shift').val()

                    window.open('modul/mod_laporan/cetak_stokopname_excel.php?tgl_awal=' + tgl_awal + '&tgl_akhir=' + tgl_akhir +'&shift='+shift, '_blank');
                }
                function exportPDF() {
                    let tgl_awal = $('#tgl_awal').val()
                    let tgl_akhir = $('#tgl_akhir').val()
                    let shift = $('#shift').val()               
                    window.open('modul/mod_laporan/tampil_lap_stokopname_plus.php?tgl_awal=' + tgl_awal + '&tgl_akhir=' + tgl_akhir + '&shift=' + shift, '_blank');
                }
            </script>
        <?php
            break;
        case "lap_sinkron_min":
        ?>
        <div class="box box-primary box-solid">
    		<div class="box-header with-border">
    			<h3 class="box-title">SINKRONISASI MINUS</h3>
    			<div class="box-tools pull-right">
    				<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div><!-- /.box-tools -->
    		</div>
    		<?php
    		    $tgl_awal   = $_GET['tgl_awal'];
    		    $tgl_akhir  = $_GET['tgl_akhir'];
    		    $shift      = $_GET['shift'];
    		    
    		    echo "<a class='btn btn-success' href='?module=lapstokopname&act=sinkron_min&tgl_awal=$tgl_awal&tgl_akhir=$tgl_akhir&shift=$shift'>SINKRONISASI STOK MINUS</a>";
    		?>
    		
    		<div class="box-body table-responsive">
    			<a  class ='btn  btn-warning  btn-flat' href='#'></a>
                <small>* Pembayaran belum lunas</small>
                <div></div>
    			<br><br>
    					
    			<table id="example11" class="table table-bordered table-striped" >
    				<thead>
    					<tr>
    						<th width="5%">No</th>
    						<th>Kode</th>
    						<th>Tanggal</th>
    						<th>Pelanggan</th>
    						<th>Kasir</th>
    						<th>Cara Bayar</th>
    						<th>Total</th>
                            <th width="70">Aksi</th>
    					</tr>
    				</thead>
    				<tbody>
    				</tbody>
                </table>
    		</div>
    	</div>
    
        <script>
            $(document).ready(function() {
                var table = $('#example11').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "lengthChange": false,
                    "displayStart": getPageFromUrl() * 10,
                    "pageLength": 10,
                    "order": [[ 0, "desc" ]],
                    "ajax": {
                        "url": "modul/mod_laporan/trkasir_minus_serverside.php?tgl_awal=<?=$tgl_awal?>&tgl_akhir=<?=$tgl_akhir?>",
                        "type": "post",
                        "dataType": "json",
                        "error": function (xhr, error, thrown) {
                            console.log("XHR Response:", xhr.responseText);
                            alert("Error: " + xhr.status + " - " + thrown);
                        },
                        // "success": function(dta){
                        //     console.log(dta);
                        // },
                    },
                    "columns": [
                        { "data": "no", "className": "text-center" },
                        { "data": "kd_trkasir" },
                        { "data": "tgl_trkasir" },
                        { "data": "nm_pelanggan" },
                        { "data": "petugas" },
                        { "data": "nm_carabayar", "className": "text-center" },
                        { "data": "ttl_trkasir", "className": "text-right",
                            "render": function(data, type, row) {
        						return formatRupiah(data);
        					}
                        },
                        { "data": "aksi", "orderable": false, "searchable": false, "className":"text-center" }
                    ],
                    "rowCallback": function(row, data, index) {
                        let q = data['carabayar'];
                        
                        if(q == 3){
                            $(row).find('td:eq(0)').css('background-color', '#ffbf00');
                            $(row).find('td:eq(1)').css('background-color', '#ffbf00');
                            
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
                
                // button edit
                $('#example11 tbody').on('click', '#btn_edit', function(e){
                    var id = $(this).data('id');
                    var currentPage = table.page() + 1;
                    location.href = '?module=trkasir&act=ubah&id='+id+'&page='+currentPage; 
                });
                
                // button print
                $('#example11 tbody').on('click', '#btn_print', function(e){
                    var kode = $(this).data('kd_trkasir');
                    var currentPage = table.page() + 1;
                    window.open('modul/mod_laporan/struk.php?kd_trkasir='+kode,'popup','width=500,height=600') 
                });
                
                // button faktur
                $('#example11 tbody').on('click', '#btn_faktur', function(e){
                    var kode = $(this).data('kd_trkasir');
                    var currentPage = table.page() + 1;
                    window.open('modul/mod_laporan/faktur.php?kd_trkasir='+kode, '_blank') 
                });
                
                // Tombol hapus
                $('#example11 tbody').on('click', '#btn_hapus', function () {
                    var id = $(this).data('id');
                
                    if (confirm('Anda yakin ingin menghapus?') == true) {
                        $.ajax({
            				url: 'modul/mod_trkasir/aksi_trkasir.php?module=trkasir&act=hapus&id='+id,
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
        <?php
            break;
        
        case "lap_sinkron_plus":
        ?>
        
        <div class="box box-primary box-solid">
    		<div class="box-header with-border">
    			<h3 class="box-title">SINKRONISASI MINUS</h3>
    			<div class="box-tools pull-right">
    				<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div><!-- /.box-tools -->
    		</div>
    		<?php
    		    $tgl_awal   = $_GET['tgl_awal'];
    		    $tgl_akhir  = $_GET['tgl_akhir'];
    		    $shift      = $_GET['shift'];
    		    
    		    echo "<a class='btn btn-warning' href='?module=lapstokopname&act=sinkron_plus&tgl_awal=$tgl_awal&tgl_akhir=$tgl_akhir&shift=$shift'>SINKRONISASI STOK PLUS</a>";
    		?>
    		
    		<div class="box-body table-responsive">
    			<a  class ='btn  btn-warning  btn-flat' href='#'></a>
                <small>* Pembayaran belum lunas</small>
                <div></div>
    			<br><br>
    					
    			<table id="example11" class="table table-bordered table-striped" >
    				<thead>
    					<tr>
							<th width="5%">No</th>
							<th>Kode</th>
							<th>Petugas</th>
							<th>Tanggal</th>
							<th>Supplier</th>
							<th>No Faktur</th>
							<th>Total Tagihan</th>
							<th>Status Pembayaran</th>
							<th width="70">Aksi</th>
						</tr>
    				</thead>
    				<tbody>
    				</tbody>
                </table>
    		</div>
    	</div>
    
        <script>
            $(document).ready(function() {
                var table = $('#example11').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "lengthChange": false,
                    "displayStart": getPageFromUrl() * 10,
                    "pageLength": 10,
                    "order": [[ 0, "desc" ]],
                    "ajax": {
                        "url": "modul/mod_laporan/trbmasuk_plus_serverside.php?action=table_data&tgl_awal=<?=$tgl_awal?>&tgl_akhir=<?=$tgl_akhir?>",
                        "type": "post",
                        "dataType": "json",
                        "error": function (xhr, error, thrown) {
                            console.log("XHR Response:", xhr.responseText);
                            alert("Error: " + xhr.status + " - " + thrown);
                        },
                        
                    },
                    "columns": [
                        { "data": "no", "className": "text-center" },
                        { "data": "kd_trbmasuk" },
                        { "data": "petugas" },
                        { "data": "tgl_trbmasuk" },
                        { "data": "nm_supplier" },
                        { "data": "ket_trbmasuk", "className": "text-left" },
                        { "data": "sisa_bayar", "className": "text-right",
                            "render": function(data, type, row) {
        						return formatRupiah(data);
        					}
                        },
                        { "data": "carabayar", "className": "text-center" },
                        { "data": "aksi", "orderable": false, "searchable": false, "className":"text-center" }
                    ],
                    
                });
                
                table.on('draw', function () {
                    const info = table.page.info();
                    const currentPage = info.page + 1; // konversi ke 1-based
                    const url = new URL(window.location);
                    url.searchParams.set('page', currentPage);
                    window.history.pushState({}, '', url);
                });
                
                // button edit
                $('#example11 tbody').on('click', '#btn_edit', function(e){
                    var id = $(this).data('id');
                    var currentPage = table.page() + 1;
                    location.href = '?module=trbmasuk&act=ubah&id='+id+'&page='+currentPage; 
                });
                
                $('#example11 tbody').on('click', '#btn_pbf', function(e){
                    var id = $(this).data('id');
                    var currentPage = table.page() + 1;
                    location.href = '?module=byrkredit&act=ubah&id='+id+'&page='+currentPage; 
                });
                
                // button print
                $('#example11 tbody').on('click', '#btn_print', function(e){
                    var kode = $(this).data('kd_trkasir');
                    var currentPage = table.page() + 1;
                    window.open('modul/mod_laporan/struk.php?kd_trkasir='+kode,'popup','width=500,height=600') 
                });
                
                // button faktur
                $('#example11 tbody').on('click', '#btn_cetak', function(e){
                    var kode = $(this).data('kd_trbmasuk');
                    var currentPage = table.page() + 1;
                    window.open('modul/mod_laporan/faktur_trbmasuk.php?kd_trbmasuk='+kode, '_blank') 
                });
                
                
                // Tombol hapus
                $('#example11 tbody').on('click', '#btn_hapus', function () {
                    var id = $(this).data('id');
                
                    if (confirm('Anda yakin ingin menghapus?') == true) {
                        $.ajax({
            				url: 'modul/mod_trbmasuk/aksi_trbmasuk.php?module=trbmasuk&act=hapus&id='+id,
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
        
        <?php
            break;
            
        case "sinkron_min":
            $tgl_awal = $_GET['tgl_awal'];
            $tgl_akhir = $_GET['tgl_akhir'];
            $shift = $_GET['shift'];
            $kdunik = date('dmyHis');
            $kdtransaksi = "MINUS-" . $kdunik;
            $petugas = $_SESSION['namalengkap'];

            $conn = $GLOBALS["___mysqli_ston"];
            mysqli_begin_transaction($conn);
            $success = true;

            $sominus = $db->query("SELECT * FROM stok_opname
                WHERE ttl_hrgbrg<0 and shift='$shift' and tgl_stokopname between '$tgl_awal' and '$tgl_akhir' ");
            $no1 = 1;
            $waktu = date('Y-m-d H:i:s', time());

            $ttlharga = 0;
            while ($success && ($so = $sominus->fetch_array())) {
                $barang = $db->query("select nm_barang,sat_barang,hrgjual_barang from barang where id_barang='$so[id_barang]' ");
                $brg = $barang->fetch_array();
                $qtymin = abs($so['selisih']);
                $hrgtot = $brg['hrgjual_barang'] * $qtymin;
                $ttlharga += $hrgtot;

                $kdBarangEscaped = mysqli_real_escape_string($conn, $so['kd_barang']);
                $batchQuery = mysqli_query($conn, "SELECT tb.no_batch, tb.exp_date,
                        SUM(tb.qty_dtrbmasuk) - COALESCE(jual.terjual, 0) AS sisa
                    FROM trbmasuk_detail tb
                    LEFT JOIN (
                        SELECT no_batch, SUM(qty_dtrkasir) AS terjual
                        FROM trkasir_detail
                        WHERE kd_barang = '$kdBarangEscaped'
                          AND no_batch IS NOT NULL AND no_batch != ''
                        GROUP BY no_batch
                    ) jual ON jual.no_batch = tb.no_batch
                    WHERE tb.kd_barang = '$kdBarangEscaped'
                      AND tb.no_batch IS NOT NULL AND tb.no_batch != ''
                    GROUP BY tb.no_batch, tb.exp_date
                    HAVING sisa > 0
                    ORDER BY (tb.exp_date IS NULL OR tb.exp_date IN ('0000-00-00','1970-01-01')) ASC,
                             tb.exp_date ASC, tb.no_batch ASC");

                if (!$batchQuery) {
                    $success = false;
                    break;
                }

                $qtyBelumDialokasikan = $qtymin;
                while ($success && $qtyBelumDialokasikan > 0 && ($batch = mysqli_fetch_assoc($batchQuery))) {
                    $qtyBatch = min($qtyBelumDialokasikan, (float) $batch['sisa']);
                    if ($qtyBatch <= 0) {
                        continue;
                    }

                    $noBatchEscaped = mysqli_real_escape_string($conn, $batch['no_batch']);
                    $expDate = $batch['exp_date'];
                    $expDateSql = (!empty($expDate) && $expDate != '0000-00-00' && $expDate != '1970-01-01')
                        ? "'" . mysqli_real_escape_string($conn, $expDate) . "'"
                        : "NULL";
                    $hargaBatch = $brg['hrgjual_barang'] * $qtyBatch;
                    $namaBarangEscaped = mysqli_real_escape_string($conn, $brg['nm_barang']);
                    $satuanEscaped = mysqli_real_escape_string($conn, $brg['sat_barang']);

                    $success = (bool) mysqli_query($conn, "INSERT INTO trkasir_detail(
                                kd_trkasir, id_barang, kd_barang, nmbrg_dtrkasir,
                                qty_dtrkasir, sat_dtrkasir, no_batch, exp_date,
                                hrgjual_dtrkasir, hrgttl_dtrkasir, waktu)
                            VALUES (
                                '$kdtransaksi', '$so[id_barang]', '$kdBarangEscaped',
                                '$namaBarangEscaped', '$qtyBatch', '$satuanEscaped',
                                '$noBatchEscaped', $expDateSql,
                                '$brg[hrgjual_barang]', '$hargaBatch', '$waktu')");

                    $qtyBelumDialokasikan -= $qtyBatch;
                }

                if ($success && $qtyBelumDialokasikan > 0) {
                    $namaBarangEscaped = mysqli_real_escape_string($conn, $brg['nm_barang']);
                    $satuanEscaped = mysqli_real_escape_string($conn, $brg['sat_barang']);
                    $hargaSisa = $brg['hrgjual_barang'] * $qtyBelumDialokasikan;
                    $success = (bool) mysqli_query($conn, "INSERT INTO trkasir_detail(
                                kd_trkasir, id_barang, kd_barang, nmbrg_dtrkasir,
                                qty_dtrkasir, sat_dtrkasir, no_batch, exp_date,
                                hrgjual_dtrkasir, hrgttl_dtrkasir, waktu)
                            VALUES (
                                '$kdtransaksi', '$so[id_barang]', '$kdBarangEscaped',
                                '$namaBarangEscaped', '$qtyBelumDialokasikan',
                                '$satuanEscaped', NULL, NULL,
                                '$brg[hrgjual_barang]', '$hargaSisa', '$waktu')");
                }

                $no1++;
            }

            $tglharini = date('Y-m-d');
            if ($success) {
                $cekshift = mysqli_query($conn, "select * from waktukerja where tanggal = '$tglharini'
                and status='ON' ");
                $sshift = mysqli_fetch_array($cekshift);
                $shiftin = $sshift['shift'];

                $success = (bool) mysqli_query($conn, "insert into trkasir(
                                kd_trkasir,
                                petugas,
                                shift,
                                tgl_trkasir,
                                nm_pelanggan,
                                id_carabayar,
                                ttl_trkasir)
                                values (
                                '$kdtransaksi',
                                '$petugas',
                                '$shiftin',
                                '$tglharini',
                                'SINKRONISASI MINUS',
                                '1',
                                '$ttlharga')
                ");
            }

            if ($success) {
                $success = (bool) mysqli_query($conn, "INSERT IGNORE INTO kartu_stok(kode_transaksi, tgl_sekarang) VALUES('$kdtransaksi','$waktu')");
            }

            if ($success) {
                mysqli_commit($conn);
                echo '<script>window.location.href = "?module=trkasir";</script>';
            } else {
                mysqli_rollback($conn);
                echo '<script>alert("Sinkronisasi gagal, transaksi dibatalkan !");window.history.back();</script>';
            }
            break;
        case "sinkron_plus":
            $tgl_awal = $_GET['tgl_awal'];
            $tgl_akhir = $_GET['tgl_akhir'];
            $shift = $_GET['shift'];
            $kdunik = date('dmyHis');
            $kdtransaksi = "PLUS-" . $kdunik;
            $petugas = $_SESSION['namalengkap'];

            $conn = $GLOBALS["___mysqli_ston"];
            mysqli_begin_transaction($conn);
            $success = true;

            $soplus = $db->query("SELECT * FROM stok_opname
                WHERE selisih>0 and shift='$shift' and tgl_stokopname between '$tgl_awal' and '$tgl_akhir' ");
            $no1 = 1;
            $waktu = date('Y-m-d H:i:s', time());

            $ttlharga=0;
            while ($success && ($so = $soplus->fetch_array())) {
                $barang = $db->query("select nm_barang,sat_barang from barang where id_barang='$so[id_barang]' ");
                $brg = $barang->fetch_array();

                $ttlharga += $so['ttl_hrgbrg'];
                $success = (bool) mysqli_query($conn, "insert into trbmasuk_detail(
                                kd_trbmasuk,
                                id_barang,
                                kd_barang,
                                nmbrg_dtrbmasuk,
                                qty_dtrbmasuk,
                                sat_dtrbmasuk,
                                hrgsat_dtrbmasuk,
                                hrgttl_dtrbmasuk,
                                waktu)
                                values (
                                '$kdtransaksi',
                                '$so[id_barang]',
                                '$so[kd_barang]',
                                '$brg[nm_barang]',
                                '$so[selisih]',
                                '$brg[sat_barang]',
                                '$so[hrgsat_barang]',
                                '$so[ttl_hrgbrg]',
                                '$waktu')
               ");
                $no1++;
            }

            $tglharini = date('Y-m-d');

            if ($success) {
                $success = (bool) mysqli_query($conn, "insert into trbmasuk(
                                id_resto,
                                kd_trbmasuk,
                                petugas,
                                tgl_trbmasuk,
                                id_supplier,
                                nm_supplier,
                                carabayar,
                                jenis,
                                ttl_trbmasuk,
                                sisa_bayar)
                                values (
                                'pusat',
                                '$kdtransaksi',
                                '$petugas',
                                '$tglharini',
                                '0',
                                'SINKRONISASI PLUS',
                                'TUNAI',
                                'nonpbf',
                                '$ttlharga',
                                '$ttlharga')
                ");
            }

            if ($success) {
                $success = (bool) mysqli_query($conn, "INSERT IGNORE INTO kartu_stok(kode_transaksi, tgl_sekarang) VALUES('$kdtransaksi','$waktu')");
            }

            if ($success) {
                mysqli_commit($conn);
                echo '<script>window.location.href = "?module=byrkredit";</script>';
            } else {
                mysqli_rollback($conn);
                echo '<script>alert("Sinkronisasi gagal, transaksi dibatalkan !");window.history.back();</script>';
            }

            break;
        
        // case "tes":
            
        //     $trkasir = $db->query( 
        //                 "SELECT trkasir.kd_trkasir, trkasir.tgl_trkasir 
        //                 FROM trkasir
        //                 JOIN trkasir_detail
        //                 ON trkasir.kd_trkasir = trkasir_detail.kd_trkasir
        //                 WHERE trkasir.kd_trkasir NOT IN (SELECT kode_transaksi FROM kartu_stok)"
        //             );
                    
        //     while($r1 = $trkasir->fetch_array()){
                
        //         $trdetail = $db->query("SELECT kd_trkasir, waktu
        //                                 FROM trkasir_detail WHERE kd_trkasir = '$r1[kd_trkasir]'
        //                                 ORDER BY waktu DESC
        //                                 LIMIT 1");
        //         $rdetail = $trdetail->fetch_array();
                
        //         mysqli_query($GLOBALS["___mysqli_ston"], "INSERT IGNORE INTO kartu_stok(kode_transaksi, tgl_sekarang) VALUES('$r1[kd_trkasir]','$rdetail[waktu]')");
                
        //     }
            
        //     $trbmasuk = $db->query( 
        //                 "SELECT trbmasuk.kd_trbmasuk, trbmasuk.tgl_trbmasuk 
        //                 FROM trbmasuk
        //                 JOIN trbmasuk_detail
        //                 ON trbmasuk.kd_trbmasuk = trbmasuk_detail.kd_trbmasuk
        //                 WHERE trbmasuk.kd_trbmasuk NOT IN (SELECT kode_transaksi FROM kartu_stok)"
        //             );
                    
        //     while($r2 = $trbmasuk->fetch_array()){
        //         $trbdetail = $db->query("SELECT kd_trbmasuk, waktu
        //                                 FROM trbmasuk_detail WHERE kd_trbmasuk = '$r2[kd_trbmasuk]'
        //                                 ORDER BY waktu DESC
        //                                 LIMIT 1");
        //         $rbdetail = $trbdetail->fetch_array();
                
        //         mysqli_query($GLOBALS["___mysqli_ston"], "INSERT IGNORE INTO kartu_stok(kode_transaksi, tgl_sekarang) VALUES('$r2[kd_trbmasuk]','$rbdetail[waktu]')");
        //     }
            
        //     break;
    }
}

?>


<script type="text/javascript">
    $(function () {
        $(".datepicker").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });
    });
    
</script>