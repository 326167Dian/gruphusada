<?php
session_start();
include "../../../configurasi/koneksi.php";
include "../../../configurasi/fungsi_rupiah.php";

$jenisobat = $_POST['jenisobat'];
$tgl = $_POST['tgl_awal'];

?>

<!--<table id="example11" class="table table-bordered table-striped">-->
<table id="tes2" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th class="text-center">No</th>
            <th class="text-center">Kode Barang</th>
            <th class="text-center">Nama Obat</th>
            <th class="text-center">Satuan</th>
            <?php //if($_SESSION['level']=='pemilik'):?>
            <th class="text-center">Stok Sistem <br>(SS)</th>
            <?php //endif;?>
            <th class="text-center">Stok Fisik <br>(SF)</th>
            <th class="text-center">Exp Date  <br>(SF)</th>
            <th class="text-center">Jml <br>(ED)</th>
            <th class="text-center">Hasil <br>(SF - SS)</th>
            <th class="text-center">Current Time</th>
            <th class="text-center">Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php
        
        // $query1 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM stok_opname a 
        //                     JOIN barang b ON a.id_barang = b.id_barang WHERE b.jenisobat='$jenisobat' AND a.tgl_stokopname = '$tgl' ORDER BY a.id_stok_opname DESC");

        // $nomor = 1;
        // while ($tampil = mysqli_fetch_array($query1)) :

        ?>

            <!--<tr>-->
            <!--    <td class="text-center"><?= $nomor++; ?></td>-->
            <!--    <td class="text-center"><?= $tampil['kd_barang']; ?></td>-->
            <!--    <td class="text-center"><?= $tampil['nm_barang']; ?></td>-->
            <!--    <td class="text-center"><?= $tampil['sat_barang']; ?></td>-->
                <?php //if($_SESSION['level']=='pemilik'):?>
                <!--<td class="text-center"><?= $tampil['stok_sistem']; ?></td>-->
                <?php //endif;?>
                <!--<td class="text-center"><?= $tampil['stok_fisik']; ?></td>-->
                <!--<td class="text-center"><?= $tampil['exp_date']; ?></td>-->
                <!--<td class="text-center"><?= $tampil['jml']; ?></td>-->
                <!--<td class="text-center"><?= $tampil['selisih']; ?></td>-->
                <!--<td class="text-center"><?= date("d M Y - H:i:s", strtotime($tampil['tgl_current'])); ?></td>-->
                <!--<td class="text-center">-->
                    <?php //if ($_SESSION['level'] == 'pemilik'): ?>
                        <!--<button type="button" id="hapus_<?= $tampil['id_stok_opname'] ?>" class="btn btn-danger btn-sm" onclick="javascript:hapus_stok_opname('<?= $tampil['id_stok_opname'] ?>')" data-id_stok="<?= $tampil['id_stok_opname'] ?>">-->
                        <!--    <i class="fa fa-fw fa-trash"></i>HAPUS-->
                        <!--</button>-->
                    <?php //endif; ?>
            <!--    </td>-->
            <!--</tr>-->
        <?php //endwhile; ?>
    </tbody>
</table>

<script>
    $(document).ready(function() {

        $('#example11').dataTable({
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
        
        var table = $('#tes2').DataTable({
            serverSide: true,
            processing: true,
            ordering: false,
            lengthChange: false,
            // displayStart: getPageFromUrl() * 5,
            pageLength: 5,
            ajax: {
                url: 'modul/mod_lapstok/tabel_stokopname_rekap_serverside.php',
                type: 'POST',
                data: {
                    'jenisobat': jenisobat,
                    'tgl_awal': tgl_awal
                }
            },
            columns: [
                { "data": "no", "className": "text-center" },
                { "data": "kd_barang" },
                { "data": "nm_barang" },
                { "data": "sat_barang" },
                { "data": "stok_sistem", "className": "text-center", 
                    "render": function (data, type, row, meta) {
                        return formatRupiah(data);
                    }
                },
                { "data": "stok_fisik", "className": "text-center", 
                    "render": function (data, type, row, meta) {
                        return formatRupiah(data);
                    }
                },
                { "data": "exp_date", "className": "text-center" },
                { "data": "jml", "className": "text-center", 
                    "render": function (data, type, row, meta) {
                        return formatRupiah(data);
                    }
                },
                { "data": "selisih", "className": "text-center", 
                    "render": function (data, type, row, meta) {
                        return formatRupiah(data);
                    }
                },
                { "data": "tgl_current", "className": "text-center" },
                { 
                    "data": "aksi", 
                    "className": "text-center",
                    "visible": <?= ($_SESSION['level'] == 'pemilik') ? 'true' : 'false'; ?>,
                    "render": function (data, type, row, meta) {
                        var aksi = "<button type='button' class='btn btn-danger btn-sm' id='btn_hapus' data-id_stokopname='"+data+"'><i class='fa fa-fw fa-trash'></i>HAPUS</button>";
                        return aksi;
                    }
                }
                // { 
                //     "data": "aksi",
                //     "visible": <?= ($_SESSION['level'] == 'pemilik') ? 'true' : 'false'; ?>,
                //     "render": function (data, type, row, meta) {
                //         var aksi = '<button type="button" id="pilih" class="btn btn-primary btn-sm" data-id_barang="'+data+'" data-kd_barang="'+row['kode']+'" data-hrgsat_barang="'+row['hrgsat_barang']+'"><i class="fa fa-fw fa-check"></i>SIMPAN</button>';
                //         return aksi;
                //     }
                // },
            ]
        });
                    
        // Tombol hapus
        $('#tes2 tbody').on('click', '#btn_hapus', function () {
            var id_stok = $(this).data('id_stokopname');
        
            if (confirm('Anda yakin ingin menghapus?') == true) {
                $.ajax({
                    type: 'post',
                    url: 'modul/mod_lapstok/hapus_stokopname.php',
                    data: {
                        'id_stok': id_stok
                    },
                    success: function(response) {
                        tabel_stokopname();
                        // tabel_stokopname_rekap();
                        table.ajax.reload(null, false);
                    }
                });
            } 
        });
    });
</script>