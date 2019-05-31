<?php
require_once __DIR__.'/../../bootstrap.php';
require_once __DIR__.'/../../vendor/autoload.php';
include_once(__DIR__.'/../../dbconnect.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
WHERE ddh.dh_ma = $dh_ma
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

$html = $twig->render('backend/dondathang/excel.html.twig',['dondathang' => $dondathangRow]);

//tạo file để lưu nội dung html
$temp = tmpfile();
$path = stream_get_meta_data($temp)['uri'];
file_put_contents($path, $html);
// Xuất file Excel
$callStartTime = microtime(true);
$objReader = IOFactory::createReader('Html');
$spreadsheet = $objReader->load($path);
// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="bill-excel.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
?>