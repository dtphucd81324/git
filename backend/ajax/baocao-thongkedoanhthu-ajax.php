<?php 
require_once __DIR__.'/../../bootstrap.php';
include_once(__DIR__.'/../../dbconnect.php');

$sql = <<<EOT
    SELECT DATE(ddh.dh_ngaydat) as NgayTaoDonHang, SUM(ctdh_soluong *ctdh_dongia) as TongThanhTien
    FROM `dondathang` ddh
    JOIN `chitietdonhang` ctdh ON ctdh.dh_ma = ddh.dh_ma
    GROUP BY DATE(ddh.dh_ngaydat)
EOT;

$result = mysqli_query($conn, $sql);

$data = [];

while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
    $data[] = array(
        'NgayTaoDonHang' => date('d/m/Y', strtotime($row['NgayTaoDonHang'])),
        'TongThanhTien' => $row['TongThanhTien']
    );
}
echo json_encode($data);
?>