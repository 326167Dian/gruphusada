<?php
session_start();
include "../../../configurasi/koneksi.php";

$shift      = $_POST['shift'];
$tgl_awal   = $_POST['tgl_awal'];


?>
<input type="hidden" id="shift" value="<?=$shift?>">
<input type="hidden" id="tgl_awal" value="<?=$tgl_awal?>">
<!--<table id="example10" class="table table-bordered table-striped">-->
<table id="tes1" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th class="text-center">No</th>
            <th class="text-center">Kode Barang</th>
            <th class="text-center">Nama Obat</th>
            <th class="text-center">Satuan</th>
            <th class="text-center">Rak Obat</th>
            <th class="text-center">Qty Terjual</th>
            <?php //if($_SESSION['level']=='pemilik'):?>
            <th class="text-center">Stok Sistem</th>
            <?php //endif;?>
            <th class="text-center">Stok Fisik</th>
            <th class="text-center">Submit</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang a WHERE a.jenisobat='$jenisobat' AND a.id_barang NOT IN (SELECT id_barang as idb FROM stok_opname b WHERE b.id_barang = a.id_barang AND b.tgl_current = '$time') ORDER BY a.nm_barang");
        // $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang a WHERE a.jenisobat='$jenisobat'  ORDER BY a.nm_barang");
        
        // $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT trkasir_detail.*, trkasir.*, barang.*,SUM(trkasir_detail.qty_dtrkasir) as ttlqty FROM trkasir_detail 
        //     JOIN trkasir ON trkasir_detail.kd_trkasir = trkasir.kd_trkasir
        //     JOIN barang ON trkasir_detail.id_barang = barang.id_barang
        //     WHERE trkasir.tgl_trkasir = '$tgl_awal' AND shift = '$shift'
        //     GROUP BY trkasir_detail.kd_barang");

        // $no = 1;
        // while ($lihat = mysqli_fetch_array($query)) :

        //     $stokopname = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT a.*, b.* FROM barang a 
        //         JOIN stok_opname b ON a.kd_barang = b.kd_barang 
        //         WHERE a.id_barang=$lihat[id_barang] AND b.shift=$shift AND tgl_stokopname = '$tgl_awal'");
        //     $stok = mysqli_num_rows($stokopname);
        //     $barang = mysqli_fetch_array($stokopname);
        //     $terjual = $lihat['ttlqty'];

        //     if ($stok == 0) :

        //         $beli = "SELECT trbmasuk.tgl_trbmasuk,                                           
        //                               SUM(trbmasuk_detail.qty_dtrbmasuk) AS totalbeli                                            
        //                               FROM trbmasuk_detail join trbmasuk 
        //                               on (trbmasuk_detail.kd_trbmasuk=trbmasuk.kd_trbmasuk)
        //                               WHERE id_barang =$lihat[id_barang]";
        //         $buy = mysqli_query($GLOBALS["___mysqli_ston"], $beli);
        //         $buy2 = mysqli_fetch_array($buy);

        //         $jual = "SELECT trkasir.tgl_trkasir,                                
        //                                 sum(trkasir_detail.qty_dtrkasir) AS totaljual
        //                                 FROM trkasir_detail join trkasir 
        //                                 on (trkasir_detail.kd_trkasir=trkasir.kd_trkasir)
        //                                 WHERE id_barang =$lihat[id_barang]";

        //         $jokul = mysqli_query($GLOBALS["___mysqli_ston"], $jual);
        //         $sell = mysqli_fetch_array($jokul);
        //         $selisih = $buy2['totalbeli'] - $sell['totaljual'];
    
        //         if($selisih > 0):
        ?>

                <!--<tr>-->
                <!--    <td class="text-center"><?= $no++; ?></td>-->
                <!--    <td class="text-center"><?= $lihat['kd_barang']; ?></td>-->
                <!--    <td class="text-left"><?= $lihat['nm_barang']; ?></td>-->
                <!--    <td class="text-center"><?= $lihat['sat_barang']; ?></td>-->
                <!--    <td class="text-center"><?= $lihat['jenisobat']; ?></td>-->
                <!--    <td class="text-center"><?= $terjual; ?></td>-->
                    <?php //if($_SESSION['level']=='pemilik'):?>
                <!--    <td class="text-center"><?= $selisih; ?></td>-->
                    <?php //endif;?>
                <!--    <td class="text-center">-->
                <!--        <input type="number" min="0" class="form-control text-center" name="stok_fisik_<?= $no ?>" id="stok_fisik_<?= $no ?>" value="0">-->
                <!--    </td>-->
                <!--    <td class="text-center">-->
                <!--        <button type="button" id="pilih_<?= $no ?>" class="btn btn-primary btn-sm" onclick="javascript:simpan_stok_opname('<?= $no ?>')" -->
                <!--            data-id_barang="<?= $lihat['id_barang']; ?>" -->
                <!--            data-kd_barang="<?= $lihat['kd_barang']; ?>" -->
                <!--            data-hrgsat_barang="<?= $lihat['hrgsat_barang']; ?>"-->
                <!--            data-shift="<?= $lihat['shift']; ?>">-->
                <!--            <i class="fa fa-fw fa-check"></i>-->
                <!--            SIMPAN</button>-->
                <!--    </td>-->
                <!--</tr>-->

        <?php
        //         endif;
        //     endif;
        // endwhile; 
        ?>
    </tbody>
</table>

<script>
    $(document).ready(function() {
        $('#example10').dataTable({
            "aLengthMenu": [
                [5, 25, 50, 75, -1],
                [5, 25, 50, 75, "All"]
            ],
            "iDisplayLength": 5
        });

    });
    
    $(document).ready(function () {
        var shift       = document.getElementById('shift').value;
        var tgl_awal    = document.getElementById('tgl_awal').value;
        
        var table = $('#tes1').DataTable({
            serverSide: true,
            processing: true,
            ordering: false,
            lengthChange: false,
            // displayStart: getPageFromUrl() * 5,
            pageLength: 5,
            ajax: {
                url: 'modul/mod_stokopname/tabel_stokopname_serverside.php',
                type: 'POST',
                data: {
                    'shift': shift,
                    'tgl_awal': tgl_awal
                }
            },
            columns: [
                {
                    "data": null,
                    "orderable": false,
                    "searchable": false,
                    "className": "text-center",
                    "render": function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { "data": "kd_barang" },
                { "data": "nm_barang" },
                { "data": "sat_barang" },
                { "data": "jenisobat" },
                { "data": "terjual", "className": "text-center" },
                { "data": "stok_sistem", 
                    "className": "text-center",
                    "visible": <?= ($_SESSION['level'] == 'pemilik') ? 'true' : 'false'; ?>,
                    
                },
                { 
                    "data": "stok_fisik",
                    "render": function (data, type, row, meta) {
                        var sf = '<input type="number" min="0" class="form-control text-center" name="stok_fisik_'+data+'" id="stok_fisik_'+data+'" value="0">';
                        return sf;
                    }
                },
                { 
                    "data": "aksi",
                    "render": function (data, type, row, meta) {
                        var aksi = '<button type="button" id="pilih" class="btn btn-primary btn-sm" data-id_barang="'+data+'" data-kd_barang="'+row['kd_barang']+'" data-hrgsat_barang="'+row['hrgsat_barang']+'" data-shift="'+row['shift']+'"><i class="fa fa-fw fa-check"></i>SIMPAN</button>';
                        return aksi;
                    }
                },
            ]
        });
                    
        // Tombol submit
        $('#tes1 tbody').on('click', '#pilih', function () {
            var id_barang       = $(this).data('id_barang');
            var kd_barang       = $(this).data('kd_barang');
            var hrgsat_barang   = $(this).data('hrgsat_barang');
            var shift           = $(this).data('shift');
            var tgl_awal        = document.getElementById('tgl_awal').value;
            
            var stok_fisik      = $('#stok_fisik_' + id_barang).val();
            var currentPage     = table.page() + 1;
            
            $.ajax({
                type: 'post',
                url: 'modul/mod_stokopname/simpan_stokopname.php',
                data: {
                    'id_barang'     : id_barang,
                    'kd_barang'     : kd_barang,
                    'stok_fisik'    : stok_fisik,
                    'hrgsat_barang' : hrgsat_barang,
                    'shift'         : shift,
                    'tgl_awal'      : tgl_awal
                },
                success: function(response) {
                    // tabel_stokopname();
                    table.ajax.reload(null, false);
                    tabel_stokopname_rekap();
                }
            });
            
        });
    });
</script>