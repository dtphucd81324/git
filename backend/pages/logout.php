<?php 
    require_once __DIR__.'/../../bootstrap.php';
    include_once(__DIR__.'/../../dbconnect.php');

    if(isset($_SESSION['username'])){
        unset($_SESSION['username']);
        header('location:login.php');
    }
    else{
        echo 'Người dùng chưa đăng nhập. Không thể đăng xuất được!'; die;
    }
?>