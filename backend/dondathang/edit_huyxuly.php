<?php 
require_once __DIR__.'/../../bootstrap.php';
include_once(__DIR__.'/../../dbconnect.php');

$dh_ma = $_GET['dh_ma'];
$dh_ngaygiao = 'NULL';
$dh_noigiao = 'NULL';
$dh_trangthaithanhtoan = 0; //0 chưa xử lý

// SQL
$sql = "UPDATE `dondathang` SET dh_ngaygiao = '$dh_ngaygiao', dh_noigiao = '$dh_noigiao', dh_trangthaithanhtoan = $dh_trangthaithanhtoan WHERE dh_ma = $dh_ma;";

//thực thi
mysqli_query($conn, $sql);

//đóng kết nối
mysqli_close($conn);

header('location: index.php');
?>