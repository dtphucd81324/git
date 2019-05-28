<?php 
require_once __DIR__.'/../../bootstrap.php';
include_once(__DIR__.'/../../dbconnect.php');

//câu sql
$sql = <<<EOT
SELECT
    ddh.dh_ma, ddh.dh_ngaydat, ddh.dh_ngaygiao, ddh.dh_noigiao, ddh.dh_trangthaithanhtoan, httt.httt_ten, kh.kh_hoten, kh.kh_sdt,
    SUM(ctdh.ctdh_soluong * ctdh.ctdh_dongia) AS TongThanhTien
FROM `dondathang` ddh
JOIN `chitietdonhang` ctdh ON ddh.dh_ma = ctdh.dh_ma
JOIN `khachhang` kh ON ddh.kh_ma = kh.kh_ma
JOIN `hinhthucthanhtoan` httt ON ddh.httt_ma = httt.httt_ma
GROUP BY ddh.dh_ma, ddh.dh_ngaydat, ddh.dh_ngaygiao, ddh.dh_noigiao, ddh.dh_trangthaithanhtoan, httt.httt_ten, kh.kh_hoten, kh.kh_sdt 
EOT;

$result = mysqli_query($conn, $sql);

$data = [];

while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
    $data[] = array(
        'dh_ma' => $row['dh_ma'],
        'dh_ngaydat' => date('d/m/Y H:i:s', strtotime($row['dh_ngaydat'])),
        'dh_ngaygiao' => empty($row['dh_ngaygiao']) ? '' : date('d/m/Y H:i:s', strtotime($row['dh_ngaygiao'])),
        'dh_noigiao' => $row['dh_noigiao'],
        'dh_trangthaithanhtoan' => $row['dh_trangthaithanhtoan'],
        'httt_ten' => $row['httt_ten'],
        'kh_ten' =>$row['kh_ten'],
        'kh_sdt' => $row['kh_sdt'],
        'TongThanhTien' => number_format($row['TongThanhTien'], 2, ".", ",") . ' vnđ'
    );
}
echo $twig->render('backend/dondathang/index.html.twig',['ds_dondathang' => $data]);
?>