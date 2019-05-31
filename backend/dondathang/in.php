<?php 
require_once __DIR__.'/../../bootstrap.php';
include_once(__DIR__.'/../../dbconnect.php');

//truy vấn dữ liệu sản phẩm theo khóa chính
$dh_ma = $_GET['dh_ma'];

$sqlSelectdondathang = <<<EOT
SELECT
    ddh.dh_ma, ddh.dh_ngaydat, ddh.dh_ngaygiao, ddh.dh_noigiao, ddh.dh_trangthaithanhtoan, httt.httt_ten, kh.kh_hoten, kh.kh_sdt,
    SUM(ctdh.ctdh_soluong * ctdh.ctdh_dongia) AS TongThanhTien
FROM `dondathang` ddh
JOIN `chitietdonhang` ctdh ON ddh.dh_ma = ctdh.dh_ma
JOIN `khachhang` kh ON ddh.kh_tendangnhap = kh.kh_tendangnhap
JOIN `hinhthucthanhtoan` httt ON ddh.httt_ma = httt.httt_ma
GROUP BY ddh.dh_ma, ddh.dh_ngaydat, ddh.dh_ngaygiao, ddh.dh_noigiao, ddh.dh_trangthaithanhtoan, httt.httt_ten, kh.kh_hoten, kh.kh_sdt 
EOT;

$resultSelectdondathang = mysqli_query($conn, $sqlSelectdondathang);
$dondathangRow;

while($row = mysqli_fetch_array($resultSelectdondathang, MYSQLI_ASSOC)){
    $dondathangRow = array(
        'dh_ma' => $row['dh_ma'],
        'dh_ngaydat' => date('d/m/Y H:i:s', strtotime($row['dh_ngaydat'])),
        'dh_ngaygiao' => empty($row['dh_ngaygiao']) ? '' : date('d/m/Y H:i:s', strtotime($row['dh_ngaygiao'])),
        'dh_noigiao' => $row['dh_noigiao'],
        'dh_trangthaithanhtoan' => $row['dh_trangthaithanhtoan'],
        'httt_ten' => $row['httt_ten'],
        'kh_hoten' =>$row['kh_hoten'],
        'kh_sdt' => $row['kh_sdt'],
        'TongThanhTien' => number_format($row['TongThanhTien'], 2, ".", ",") . ' vnđ'
    );
}

//lấy dữ liệu sản phẩm đơn đặt hàng
$sqlSelectsanpham = <<<EOT
SELECT
    sp.sp_ten, ctdh.ctdh_soluong, ctdh.ctdh_dongia, lsp.lsp_ten, ncc.ncc_ten
FROM `chitietdonhang` ctdh
JOIN `sanpham` sp ON ctdh.ctdh_ma = sp.sp_ma
JOIN `loaisanpham` lsp ON sp.lsp_ma = lsp.lsp_ma
JOIN `nhacungcap` ncc ON sp.ncc_ma = ncc.ncc_ma
WHERE ctdh.dh_ma = $dh_ma
EOT;

$resultSelectsanpham = mysqli_query($conn, $sqlSelectsanpham);
$dataSanpham = [];

while($row = mysqli_fetch_array($resultSelectsanpham, MYSQLI_ASSOC)){
    $dataSanpham[] = array(
        'sp_ten' => $row['sp_ten'],
        'sp_gia' => $row['ctdh_dongia'],
        'sp_soluong' => $row['ctdh_soluong'],
        'lsp_ten' => $row['lsp_ten'],
        'ncc_ten' => $row['ncc_ten']
    );
}

//hiệu chỉnh dữ liệu theo cấu trúc 
$dondathangRow['danhsachsanpham'] = $dataSanpham;

echo $twig->render('backend/dondathang/in.html.twig',['dondathang' => $dondathangRow]);


?>