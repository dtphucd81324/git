<?php 
require_once __DIR__.'/../../bootstrap.php';
include_once(__DIR__.'/../../dbconnect.php');

$dh_ma = $_GET['dh_ma'];

//câu sql
$sqlchitietdonhang = "DELETE FROM `chitietdonhang` WHERE dh_ma=$dh_ma;";

//thực thi
$resultchitietdonhang = mysqli_query($sqlchitietdonhang, $sqlchitietdonhang);

//lệnh Delete trong bảng dondathang
$sql = "DELETE FROM `dondathang` WHERE dh_ma = $dh_ma;";

$result = mysqli_query($conn, $sql);

//đóng kết nối
mysqli_close($conn);

header('location: index.php');
?>