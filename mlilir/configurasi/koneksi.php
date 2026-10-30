<?php

date_default_timezone_set('Asia/jakarta');
$server = "localhost";
$user = "u725913413_gudang"; 
$password = "7390091979Dian&&";
$database = "u725913413_gudangapotek";
set_time_limit(1800);

$percobaan = 0;
do {
    $GLOBALS["___mysqli_ston"] = @mysqli_connect($server, $user, $password);
    if ($GLOBALS["___mysqli_ston"]) break;
    $percobaan++;
    if ($percobaan < 3) usleep(300000);
} while ($percobaan < 3);
$GLOBALS["___mysqli_ston"] or die("Koneksi gagal");
mysqli_select_db($GLOBALS["___mysqli_ston"], $database) or die("Database tidak ditemukan");
$koneksi = $GLOBALS["___mysqli_ston"];
$db = $GLOBALS["___mysqli_ston"];

?>
