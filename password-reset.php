<?php
include 'header.php';
require_once 'connect.php';

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['email'])) {
    $user_email = $_POST['email'];
    $sql = "SELECT * FROM users WHERE email = '$user_email'";

    $result = $conn->query($sql);

    if($result->num_rows > 0) {

        $mail = new PHPMailer(true);    
        $user_id = $result->fetch_assoc()['user_id'];

        try {
            $mail->isSMTP(); // SMTP 방식으로 메일 전송
            $mail->Host = 'smtp.gmail.com'; // 메일 서버 주소
            $mail->SMTPAuth = true; // SMTP 로그인 인증 사용
            $mail->Username = 'projectkisia@gmail.com'; // 보내는 메일 주소
            $mail->Password = 'ahla bkdy edxg akud'; // 앱 비밀번호
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS 보안 사용
            $mail->Port = 587; // 포트 번호

            $mail->setFrom('projectkisia@gmail.com', 'kisia project');
            $mail->addAddress($user_email);

            $mail->isHTML(true);
            $mail->Subject = '=?UTF-8?B?' . base64_encode('비밀번호 재설정 링크') . '?=';
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            $mail->Body = '
                <h3>비밀번호 재설정 안내</h3>
                <p>아래 링크를 클릭하여 새 비밀번호를 설정하세요:</p>
                <a href="http://localhost:8080/reset_password.php?user_id=' . $user_id . '">비밀번호 재설정하기</a>
            ';

            $mail->send();
            echo '<script>alert("메일이 성공적으로 전송되었습니다.");</script>';
        } catch (Exception $e) {
            echo '<script>alert("메일 전송 실패: ' . addslashes($mail->ErrorInfo) . '");</script>';
        }
    } else {
        echo '<script>alert("존재하지 않는 이메일입니다.");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>비밀번호 재설정 - 온라인 서점</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <main>
        <div class="auth-container">
            <div class="auth-box">
                <h2>비밀번호 재설정</h2>
                <p class="auth-description">가입하신 이메일 주소를 입력하시면 비밀번호 재설정 링크를 보내드립니다.</p>
                <form class="auth-form" id="passwordResetForm" method="POST" action="">
                    <div class="form-group">
                        <label for="email">이메일 주소</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <button type="submit" class="auth-button">비밀번호 재설정 링크 받기</button>
                </form>
                <div class="auth-links">
                    <a href="login.php">로그인으로 돌아가기</a>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>