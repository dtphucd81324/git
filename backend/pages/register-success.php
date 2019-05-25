<?php 
    require_once __DIR__.'/../../bootstrap.php';
    include_once(__DIR__.'/../../dbconnect.php');

    $kh_tendangnhap = $_GET['kh_tendangnhap'];
    $sql = "SELECT * FROM `khachhang` WHERE kh_tendangnhap = '$kh_tendangnhap';";

    $result = mysqli_query($conn, $sql);
    $khachhangRow = mysqli_fetch_array($result, MYSQLI_ASSOC);

    echo $twig->render('backend/pages/register-success.html.twig', ['khachhang' => $khachhangRow]);
?>