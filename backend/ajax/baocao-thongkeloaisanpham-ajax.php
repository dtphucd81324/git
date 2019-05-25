<?php 
require_once __DIR__.'/../../bootstrap.php';
include_once(__DIR__.'/../../dbconnect.php');

$sql = <<<EOT
    SELECT lsp.lsp_ten, COUNT(*) AS SoLuong
    FROM `sanpham` sp
    JOIN `loaisanpham` lsp ON sp.lsp_ma = lsp.lsp_ma
    GROUP BY sp.lsp_ma
EOT;

$result = mysqli_query($conn, $sql);

$data = [];

while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
    $data[] = array(
        'TenLoaiSanPham' => $row['lsp_ten'],
        'SoLuong' => $row['SoLuong']
    );
}
echo json_encode($data);

?>