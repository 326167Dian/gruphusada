<?php
session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{

include "../../../configurasi/koneksi.php";
include "../../../configurasi/fungsi_thumb.php";
include "../../../configurasi/library.php";

// $tampil_barang = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM barang ORDER BY barang.id_barang ");
// $no=1;

// while ($r=mysqli_fetch_array($tampil_barang)) {

//     $beli = "SELECT trbmasuk.tgl_trbmasuk,                                           
//                                       SUM(trbmasuk_detail.qty_dtrbmasuk) AS totalbeli                                            
//                                       FROM trbmasuk_detail join trbmasuk 
//                                       on (trbmasuk_detail.kd_trbmasuk=trbmasuk.kd_trbmasuk)
//                                       WHERE kd_barang =$r[kd_barang]";
//     $buy = mysqli_query($GLOBALS["___mysqli_ston"], $beli);
//     $buy2 = mysqli_fetch_array($buy);
//     $jual = "SELECT trkasir.tgl_trkasir,                                
//                                         sum(trkasir_detail.qty_dtrkasir) AS totaljual
//                                         FROM trkasir_detail join trkasir 
//                                         on (trkasir_detail.kd_trkasir=trkasir.kd_trkasir)
//                                         WHERE kd_barang =$r[kd_barang]";
//     $jokul = mysqli_query($GLOBALS["___mysqli_ston"], $jual);
//     $sell = mysqli_fetch_array($jokul);
//     $selisih = $buy2['totalbeli'] - $sell['totaljual'];
//     $stokbarang = $r['stok_barang'];

//     if($stokbarang != $selisih)
//     {
//         mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE barang SET stok_barang = '$selisih' where kd_barang =$r[kd_barang] and ($selisih>=0)");
//     }
//     else{}

// }
//         header('location:../../media_admin.php?module=trkasir');
    
    // $query = "UPDATE barang b
    //             LEFT JOIN (
    //                 SELECT kd_barang, SUM(qty_dtrbmasuk) AS totalbeli
    //                 FROM trbmasuk_detail
    //                 INNER JOIN trbmasuk ON trbmasuk.kd_trbmasuk = trbmasuk_detail.kd_trbmasuk
    //                 GROUP BY kd_barang
    //             ) beli ON beli.kd_barang = b.kd_barang
    //             LEFT JOIN (
    //                 SELECT kd_barang, SUM(qty_dtrkasir) AS totaljual
    //                 FROM trkasir_detail
    //                 INNER JOIN trkasir ON trkasir.kd_trkasir = trkasir_detail.kd_trkasir
    //                 GROUP BY kd_barang
    //             ) jual ON jual.kd_barang = b.kd_barang
    //             SET b.stok_barang = (COALESCE(beli.totalbeli, 0) - COALESCE(jual.totaljual, 0))
    //             WHERE b.stok_barang <> (COALESCE(beli.totalbeli, 0) - COALESCE(jual.totaljual, 0))
    //               OR b.stok_barang IS NULL
    //         ";
    
    $query = "UPDATE 
                  barang b 
                  LEFT JOIN (
                    SELECT 
                      beli.kd_barang, 
                      beli.totalbeli, 
                      COALESCE(jual.totaljual, 0) AS totaljual 
                    FROM 
                      (
                        SELECT 
                          d.kd_barang, 
                          SUM(d.qty_dtrbmasuk) AS totalbeli 
                        FROM 
                          trbmasuk_detail d 
                          INNER JOIN trbmasuk m ON m.kd_trbmasuk = d.kd_trbmasuk 
                        GROUP BY 
                          d.kd_barang
                      ) AS beli 
                      LEFT JOIN (
                        SELECT 
                          d.kd_barang, 
                          SUM(d.qty_dtrkasir) AS totaljual 
                        FROM 
                          trkasir_detail d 
                          INNER JOIN trkasir k ON k.kd_trkasir = d.kd_trkasir 
                        GROUP BY 
                          d.kd_barang
                      ) AS jual ON beli.kd_barang = jual.kd_barang
                  ) AS transaksi ON b.kd_barang = transaksi.kd_barang 
                SET 
                  b.stok_barang = (
                    COALESCE(transaksi.totalbeli, 0) - COALESCE(transaksi.totaljual, 0)
                  ) 
                WHERE 
                  b.stok_barang <> (
                    COALESCE(transaksi.totalbeli, 0) - COALESCE(transaksi.totaljual, 0)
                  )
            ";
    mysqli_query($GLOBALS["___mysqli_ston"], $query);        
    header('location:../../media_admin.php?module=trkasir');
}
?>
