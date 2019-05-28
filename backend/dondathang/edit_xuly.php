<?php 
require_once __DIR__.'/../../bootstrap.php';
include_once(__DIR__.'/../../dbconnect.php');

$dh_ma = $_GET['dh_ma'];
$sqlSelect = <<<EOT
SELECT 
    ddh.dh_ma, ddh.dh_ngaydat, ddh.dh_ngaygiao, ddh.dh_noigiao, ddh.dh_trangthaithanhtoan, httt.httt_ten, kh.kh_hoten, kh.kh_sdt,
    SUM(ctdh.ctdh_soluong * ctdh.ctdh_dongia) AS TongThanhTien
FROM `dondathang` ddh
JOIN `chitietdonhang` ctdh ON ddh.dh_ma = ctdh.dh_ma
JOIN `khachhang` kh ON ddh.kh_ma = kh.kh_ma
JOIN `hinhthucthanhtoan` httt ON ddh.httt_ma = httt.httt_ma
GROUP BY ddh.dh_ma, ddh.dh_ngaydat, ddh.dh_ngaygiao, ddh.dh_noigiao, ddh.dh_trangthaithanhtoan, httt.httt_ten, kh.kh_hoten, kh.kh_sdt 
EOT;

//thực thi
$resultSelect = mysqli_query($conn, $sqlSelect);
$dondathangRow = [];

while($row = mysqli_fetch_array($resultSelect, MYSQLI_ASSOC)){
    $dondathangRow[] = array(
        'dh_ma' => $row['dh_ma'],
        'dh_ngaydat' => date('d/m/Y H:i:s', strtotime($row['dh_ngaydat'])),
        'dh_ngaygiao' => empty($row['dh_ngaygiao']) ? '' : date('d/m/Y H:i:s', strtotime($row['dh_ngaygiao'])),
        'dh_noigiao' => $row['dh_noigiao'],
        'dh_trangthaithanhtoan' => $row['dh_trangthaithanhtoan'],
        'httt_ten' => $row['httt_ten'],
        'kh_hoten' => $row['kh_hoten'],
        'kh_sdt' => $row['kh_sdt'],
        'TongThanhTien' => number_format($row['TongThanhTien'], 2, ".", ",") . ' vnđ'
    );
}

//Nếu người dùng có bấm nút xử lý thì thực thi câu lệnh
if(isset($_POST['btnXuly'])){
    $dh_ngaygiao = $_POST['dh_ngaygiao'];
    $dh_noigiao = $_POST['dh_noigiao'];
    $dh_trangthaithanhtoan = 1;

    //SQL
    $sql = "UPDATE `dondathang` SET dh_ngaygiao = '$dh_ngaygiao', dh_noigiao = '$dh_noigiao', dh_trangthaithanhtoan = $dh_trangthaithanhtoan WHERE dh_ma = $dh_ma;";

    //thực thi
    mysqli_query($conn, $sql);

    //đóng kết nối
    mysqli_close($conn);

    header('location: index.php');
}
echo $twig->render('backend/dondathang/edit.html.twig',['dondathang' => $dondathangRow]);
?>