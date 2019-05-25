<?php 
require_once __DIR__.'/../../bootstrap.php';
include_once(__DIR__.'/../../dbconnect.php');

$sqlDonDatHang = "SELECT count(*) as SoLuong FROM `dondathang`";

$result = mysqli_query($conn, $sqlDonDatHang);

$dataSoLuongDonHang = [];

while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
    $dataSoLuongDonHang[] = array(
        'SoLuong' => $row['SoLuong']
    );
}
echo json_encode($dataSoLuongDonHang[0]);
?>