<?php 
    require_once __DIR__.'/../../bootstrap.php';
    include_once(__DIR__.'/../../dbconnect.php');

    if(isset($_POST['btnThem'])){
        $tenloai = $_POST['lsp_ten'];
        $mota = $_POST['lsp_mota'];

        $errorss = [];
        //required
        if(empty($tenloai)){
            $errors['lsp_ten'][] =[
                'rule' => 'required',
                'rule_value' => true,
                'value' => $tenloai,
                'msg' => 'Vui lòng nhập tên loại sản phẩm'
            ]; 
        }

        //length < 3
        if(!empty($tenloai) && strlen($tenloai) < 3){
            $errors['lsp_ten'][]= [
                'rule' => 'minlength',
                'rule_value' => 3,
                'value' => $tenloai,
                'msg' => 'Tên loại sản phẩm phải có ít nhất 3 ký tự'
            ];
        }

        //maxlength 50
        if(!empty($tenloai) && strlen($tenloai) > 50){
            $errors['lsp_ten'][] = [
                'rule' => 'maxlength',
                'rule_value' => 50,
                'value' => $tenloai,
                'msg' => 'Tên loại sản phẩm không được vượt quá 50 ký tự'
            ];
        }

        //required
        if(empty($mota)){
            $errors['lsp_mota'][] = [
                'rule' => 'required',
                'rule_value' => true,
                'value' => $mota,
                'msg' => 'Vui lòng nhập mô tả'
            ];
        }

        //minlength
        if(!empty($mota) && strlen($mota) < 3){
            $errors['lsp_mota'][]= [
                'rule' => 'minlength',
                'rule_value' => 3,
                'value' => $mota,
                'msg' => 'Mô tả sản phẩm phải có ít nhất 3 ký tự'
            ];
        }

        //maxlength
        if(!empty($mota) && strlen($mota) < 3){
            $errors['lsp_mota'][]= [
                'rule' => 'maxlength',
                'rule_value' => 255,
                'value' => $mota,
                'msg' => 'Mô tả sản phẩm không được vượt quá 255 ký tự'
            ];
        }

        if(!empty($errors)){
            echo $twig->render('backend/loaisanpham/create.html.twig',[
                'errors' => $errors,
                'lsp_ten_oldvalue' => $tenloai,
                'lsp_mota_oldvalue' => $mota
            ]);
        }
        else{
            //câu lệnh sql
            $sql = "INSERT INTO `loaisanpham` (lsp_ten, lsp_mota) VALUES ('" . $tenloai . "', '" . $mota . "')";
            mysqli_query($conn, $sql);

            //Đóng kết nối
            mysqli_close($conn);
            header('location: index.php');
        }        
    }   else{
        echo $twig->render('backend/loaisanpham/create.html.twig');
    }
    
?>