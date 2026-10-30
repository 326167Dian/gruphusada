<?php
session_start();
include "../../../configurasi/koneksi.php";

$jenisobat = $_POST['jenisobat'];
$tgl = $_POST['tgl_awal'];

?>
<input type="hidden" id="jenisobat" value="<?=$jenisobat?>">
<input type="hidden" id="tgl_awal" value="<?=$tgl?>">
<!--<table id="example10" class="table table-bordered table-striped">-->
<table id="tes1" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th class="text-center" width="5%">No</th>
            <th class="text-center">Kode Barang</th>
            <th class="text-center">Nama Obat</th>
            <th class="text-center">Satuan</th>
            <?php //if($_SESSION['level']=='pemilik'):?>
            <th class="text-center">Stok Sistem</th>
            <?php //endif;?>
            <th class="text-center">Stok Fisik</th>
            <th class="text-center">Exp Date</th>
            <th class="text-center">jumlah</th>
            <th class="text-center">Submit</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang a WHERE a.jenisobat='$jenisobat' AND a.id_barang NOT IN (SELECT id_barang as idb FROM stok_opname b WHERE b.id_barang = a.id_barang AND b.tgl_current = '$time') ORDER BY a.nm_barang");

        // $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang a WHERE a.jenisobat='$jenisobat'  ORDER BY a.nm_barang");

        // $no = 1;
        // while ($lihat = mysqli_fetch_array($query)) :

        //     $stokopname = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM stok_opname a WHERE a.id_barang=$lihat[id_barang] AND a.tgl_stokopname = '$tgl'");
        //     $stok = mysqli_num_rows($stokopname);

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

                
        ?>

                <!--<tr>-->
                <!--    <td class="text-center"><?= $no++; ?></td>-->
                <!--    <td class="text-center"><?= $lihat['kd_barang']; ?></td>-->
                <!--    <td class="text-left"><?= $lihat['nm_barang']; ?></td>-->
                <!--    <td class="text-center"><?= $lihat['sat_barang']; ?></td>-->
                    <?php// if($_SESSION['level']=='pemilik'):?>
                    <!--<td class="text-center"><?= $selisih; ?></td>-->
                    <?php //endif;?>
                <!--    <td class="text-center">-->
                <!--        <input type="number" min="0" class="form-control text-center" name="stok_fisik_<?= $no ?>" id="stok_fisik_<?= $no ?>" value="0">-->
                <!--    </td>-->
                <!--    <td class="text-center">-->
                <!--        <input type="date" class="form-control text-center" name="exp_date_<?= $no ?>" id="exp_date_<?= $no ?>" >-->
                <!--    </td>-->
                <!--    <td class="text-center">-->
                <!--        <input type="number" min="0" class="form-control text-center" name="jml_<?= $no ?>" id="jml_<?= $no ?>" value="0">-->
                <!--    </td>-->
                <!--    <td class="text-center">-->
                <!--        <button type="button" id="pilih_<?= $no ?>" class="btn btn-primary btn-sm" onclick="javascript:simpan_stok_opname('<?= $no ?>')" data-id_barang="<?= $lihat['id_barang']; ?>" data-kd_barang="<?= $lihat['kd_barang']; ?>" data-hrgsat_barang="<?= $lihat['hrgsat_barang']; ?>">-->
                <!--            <i class="fa fa-fw fa-check"></i>-->
                <!--            SIMPAN</button>-->
                <!--    </td>-->
                <!--</tr>-->

        <?php
                
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
        var jenisobat   = document.getElementById('jenisobat').value;
        var tgl_awal    = document.getElementById('tgl_awal').value;
        
        var table = $('#tes1').DataTable({
            serverSide: true,
            processing: true,
            ordering: false,
            lengthChange: false,
            // displayStart: getPageFromUrl() * 5,
            pageLength: 5,
            ajax: {
                url: 'modul/mod_lapstok/table_stokopname_serverside.php',
                type: 'POST',
                data: {
                    'jenisobat': jenisobat,
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
                { "data": "kode" },
                { "data": "nm_barang" },
                { "data": "sat_barang" },
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
                    "data": "exp_date",
                    "render": function (data, type, row, meta) {
                        var exp = '<input type="date" class="form-control text-center" name="exp_date_'+data+'" id="exp_date_'+data+'" >';
                        return exp;
                    }
                },
                { 
                    "data": "jumlah",
                    "render": function (data, type, row, meta) {
                        var jml = '<input type="number" min="0" class="form-control text-center" name="jml_'+data+'" id="jml_'+data+'" value="0">';
                        return jml;
                    }
                },
                { 
                    "data": "aksi",
                    "render": function (data, type, row, meta) {
                        var aksi = '<button type="button" id="pilih" class="btn btn-primary btn-sm" data-id_barang="'+data+'" data-kd_barang="'+row['kode']+'" data-hrgsat_barang="'+row['hrgsat_barang']+'"><i class="fa fa-fw fa-check"></i>SIMPAN</button>';
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
            var tgl_awal        = document.getElementById('tgl_awal').value;
            
            var stok_fisik      = $('#stok_fisik_' + id_barang).val();
            var exp_date        = $('#exp_date_' + id_barang).val();
            var jml             = $('#jml_' + id_barang).val();
            var currentPage     = table.page() + 1;
            
            $.ajax({
                type: 'post',
                url: 'modul/mod_lapstok/simpan_stokopname.php',
                data: {
                    'id_barang': id_barang,
                    'kd_barang': kd_barang,
                    'stok_fisik': stok_fisik,
                    'exp_date': exp_date,
                    'jml': jml,
                    'hrgsat_barang': hrgsat_barang,
                    'tgl_awal': tgl_awal
                },
                success: function(response) {
                    table.ajax.reload(null, false);
                    // tabel_stokopname();
                    tabel_stokopname_rekap();
                }
            });        
            // location.href = '?module=barang&act=edit&id='+id+'&page='+currentPage; 
            // console.log('ID Barang = '+id_barang+"\nKode Barang = "+kd_barang+"\nHarga Barang = "+hrgsat_barang+"\nStok Fisik = "+stok_fisik+"\nExp Date = "+exp_date+"\nJumlah = "+jml+"\nCurrent Page = "+currentPage);
        });
    });
</script>