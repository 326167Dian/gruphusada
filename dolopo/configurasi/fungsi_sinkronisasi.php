<?php
include "koneksi.php";

function sinkronisasi_stok($kd_barang){
    global $db;
    $sinkronisasi = "
        UPDATE barang b
        LEFT JOIN (
            SELECT kd_barang, SUM(qty_dtrbmasuk) AS totalbeli
            FROM trbmasuk_detail
            INNER JOIN trbmasuk ON trbmasuk.kd_trbmasuk = trbmasuk_detail.kd_trbmasuk
            GROUP BY kd_barang
        ) beli ON beli.kd_barang = b.kd_barang
        LEFT JOIN (
            SELECT kd_barang, SUM(qty_dtrkasir) AS totaljual
            FROM trkasir_detail
            INNER JOIN trkasir ON trkasir.kd_trkasir = trkasir_detail.kd_trkasir
            GROUP BY kd_barang
        ) jual ON jual.kd_barang = b.kd_barang
        SET b.stok_barang = (COALESCE(beli.totalbeli, 0) - COALESCE(jual.totaljual, 0))
        WHERE b.kd_barang = ?
    ";
    
    mysqli_query($db, $sinkronisasi);
    // try {
        
    //     $stmt = $db->prepare($sinkronisasi);
    //     $stmt->execute([$kd_barang]);
    //     // $affected = $stmt->rowCount();
    
    //     // if ($affected > 0) {
    //     //     $text = "Berhasil! Data stok diperbarui sebanyak $affected baris.";
    //     // } else {
    //     //     $text = "Query berhasil, tetapi tidak ada data yang berubah.";
    //     // }
    // } catch (PDOException $e) {
    //     $text = "Gagal menjalankan query: " . $e->getMessage();
    // }
}
?>