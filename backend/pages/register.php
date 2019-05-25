<?php
    require_once __DIR__.'/../../bootstrap.php';
    include_once(__DIR__.'/../../dbconnect.php');
    include_once(__DIR__.'/../../app/helpers.php');
    // include 'sendMailPHP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
  
    if(isset($_POST['btnDangky'])){
        $kh_tendangnhap = $_POST['kh_tendangnhap'];
        $kh_matkhau = $_POST['kh_matkhau'];
        $kh_hoten = $_POST['kh_hoten'];
        $kh_email = $_POST['kh_email'];
        $kh_diachi = $_POST['kh_diachi'];
        $kh_sdt = $_POST['kh_sdt'];
        if(isset($_POST['kh_gioitinh'])){
            $kh_gioitinh = $_POST['kh_gioitinh'];
        }
        $kh_ngaysinh = $_POST['kh_ngaysinh'];
        $kh_makichhoat = sha1(time());
        $kh_trangthai = 0;

        // if($_POST['txtTentk']=="" || $_POST['txtMatkhau']=="" || $_POST['txtNhaplai']==""
        // || $_POST['txtName']==""|| $_POST['txtemail']=="" || $_POST['txtDiachi']=="" || !isset($kh_gioitinh)){
        //     $loi .="<li>Vui lòng nhập đầy đủ thông tin *</li>";
        // }
        // if($_POST['txtMatkhau'] != $_POST['txtNhaplai']){
        //     $loi .="<li>Hai mật khẩu phải trùng nhau.</li>";
        // }
        // if(strlen($_POST['txtMatkhau']) <=5){
        //     $loi .="<li>Độ dài mật khẩu phải hơn 5 ký tự.</li>";
        // }
        // if(strpos($_POST['txtemail'], "@") == false){
        //     $loi .="<li>Địa chỉ email không hợp lệ.</li>";
        // }
        // if($_POST['slYear'] == 0){
        //     $loi .="<li>Chưa chọn năm sinh.</li>";
        // }
        // if($loi!= ""){
        //     echo "<ul> class = 'cssLoi'".$loi."</ul>";
        // }
        // else{
            
        $sql = "INSERT INTO khachhang (kh_tendangnhap, kh_matkhau, kh_hoten, kh_gioitinh, kh_diachi, 
        kh_sdt, kh_email, kh_ngaysinh, kh_makichhoat, kh_trangthai)
        VALUES ('$kh_tendangnhap', '$kh_matkhau', '$kh_hoten', '$kh_gioitinh', '$kh_diachi', '$kh_sdt', '$kh_email', 
        '$kh_ngaysinh', '$kh_makichhoat', '$kh_trangthai')";
        
        $result = mysqli_query($conn, $sql);

        mysqli_close($conn);
        
        $mail = new PHPMailer(true);
        try{
            $mail->IsSMTP(); //Thiet lap mailer de su dung SMTP
            //$mail->SMTPDebug = 2;
            $mail->SMTPAuth = true; // Bat chung thuc SMTP 
            $mail->SMTPSecure = "tsl";
            $mail->Host = "smtp.gmail.com"; // Chi dinh Server chinh va Server phu
            $mail->Port = 587; // Thiet lap cong de su dung
            $mail->Username = 'yanki060595@gmail.com'; // username (Dia chi mail) cua nguoi gui 
            $mail->Password = 'oxrkkjimykybhqve';// Password cua nguoi gui
            $mail->CharSet = 'UTF-8';
            
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'veryfy_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->setFrom('yanki060595@gmail.com','Test Mail');
            $mail->addAddress('yanki060595@gmail.com');
            $mail->addReplyTo('yanki060595@gmail.com', 'Test Mail');

            $mail->isHTML(true);
            $mail->Subject = 'Thông báo đăng ký thành công tài khoản';
            $siteUrl = siteURL();
            $mail->Body = <<<EOT
            Cám ơn bạn đã tin tưởng Website của chúng tôi! <b>Congratulation</b>.
            Vui lòng click vào liên kết sau để kích hoạt tài khoản!
            <a href="$siteUrl/shop/backend/pages/kichhoattaikhoan.php?kh_tendangnhap=$kh_tendangnhap&kh_makichhoat=$kh_makichhoat">Kích hoạt tài khoản</a>
EOT;

            $mail->send();
        }catch(Exception $e){
            echo 'Lỗi khi gởi mail', $mail->ErrorInfo;
        }
    header("location:register-success.php?kh_tendangnhap=$kh_tendangnhap");
    }             

echo $twig->render('backend/pages/register.html.twig');
	
?>

