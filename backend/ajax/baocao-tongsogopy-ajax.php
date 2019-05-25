<?php 
require_once __DIR__.'/../../bootstrap.php';
include_once(__DIR__.'/../../dbconnect.php');

$sqlSoLuongGopY = "SELECT count(*) as SoLuong FROM `gopy`";

$result = mysqli_query($conn, $sqlSoLuongGopY);

$dataSoLuongGopY = [];

while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
    $dataSoLuongGopY[] = array(
        'SoLuong' => $row['SoLuong']
    );
}
echo json_encode($dataSoLuongGopY[0]);
?>