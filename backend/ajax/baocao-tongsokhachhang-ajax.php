<?php 
    require_once __DIR__.'/../../bootstrap.php';
    include_once(__DIR__.'/../../dbconnect.php');

    $sqlSoLuongKhachHang = "SELECT count(*) as SoLuong FROM `khachhang`";

    $result = mysqli_query($conn, $sqlSoLuongKhachHang);

    $dataSoLuongKhachHang = [];

    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $dataSoLuongKhachHang[] = array(
            'SoLuong' => $row['SoLuong']
        );
    }

    echo json_encode($dataSoLuongKhachHang[0]);
?>