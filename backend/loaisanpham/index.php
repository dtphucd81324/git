<?php 
    require_once __DIR__.'/../../bootstrap.php';
    include_once(__DIR__.'/../../dbconnect.php');
    include_once(__DIR__.'/../../Paginator.php');

    //Câu truy vấn SQL
    $stt = 1;
    $sql = "select * from `loaisanpham`";

    //thực thi câu truy vấn 
    // $result = mysqli_query($conn,$sql);
    // $data = [];

    $limit      = (isset($_GET['limit'])) ? $_GET['limit'] : 5;
    $page       = (isset($_GET['page'])) ? $_GET['page'] : 1;
    $paginator  = new Paginator($twig, $conn, $sql);
    $data       = $paginator->getData($limit, $page);
    // while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
    //     $data[] = array(
    //        'lsp_ma' => $row['lsp_ma'],
    //        'lsp_ten' => $row['lsp_ten'],
    //        'lsp_mota' => $row['lsp_mota'] 
    //     );
    // }
    echo $twig->render('backend/loaisanpham/index.html.twig', [
        'DSloaisanpham' => $data,
        'paginator' => $paginator
        ]);
?>