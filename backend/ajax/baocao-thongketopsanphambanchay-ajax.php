<?php 
require_once __DIR__.'/../../bootstrap.php';
include_once(__DIR__.'/../../dbconnect.php');

$sql = <<<EOT
    SELECT *
    FROM(
        SELECT sp.sp_ten, COUNT(*) AS SoLuong
        FROM `chitietdonhang` ctdh
        JOIN `sanpham` sp ON ctdh.ctdh_ma = sp.sp_ma
        GROUP BY sp.sp_ten
    ) AS ex
    ORDER BY ex.SoLuong DESC
    LIMIT 5
EOT;

$result = mysqli_query($conn, $sql);

$data = [];

while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
    $data[] = array(
        'TenSanPham' => $row['sp_ten'],
        'SoLuong' => $row['SoLuong']
    );
}
echo json_encode($data);

?>