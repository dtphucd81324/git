<?php
require_once __DIR__.'/../../bootstrap.php';
include_once(__DIR__.'/../../dbconnect.php');

$stt=1;
$sqlSoLuongSanPham = "select count(*) as SoLuong from `sanpham`";

$result = mysqli_query($conn, $sqlSoLuongSanPham);

$dataSoLuongSanPham = [];
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
{
    $dataSoLuongSanPham[] = array(
        'SoLuong' => $row['SoLuong']
    );
}

//Lấy dữ liệu khách hàng
$sttKH = 1;
$sqlSoLuongKhachHang = "SELECT count(*) as SoLuong FROM `khachhang`";

$resultKhachHang = mysqli_query($conn, $sqlSoLuongKhachHang);

$dataSoLuongKhachHang = [];

while($row = mysqli_fetch_array($resultKhachHang, MYSQLI_ASSOC)){
    $dataSoLuongKhachHang[] = array(
        'SoLuong' => $row['SoLuong']
    );
}

echo $twig->render('backend/pages/dashboard.html.twig', [
    'baocaoSanPham' => $dataSoLuongSanPham[0],
    'baocaoKhachHang' => $dataSoLuongKhachHang[0]
]);