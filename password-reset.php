<?php
require_once 'connect.php';
require 'vendor/autoload.php';
require 'header.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['email'])) {
    $user_email = trim($_POST['email']);
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $user_id = $row['id'];

        // ✅ 토큰 생성 및 저장
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', time() + 600); // 10분분 후 만료
        $update = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE id = ?");
        $update->bind_param("ssi", $token, $expiry, $user_id);
        $update->execute();

        // ✅ PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;

            $mail->Username = 'projectkisia@gmail.com';
            $mail->Password = 'ahla bkdy edxg akud';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('projectkisia@gmail.com', 'kisia project');
            $mail->addAddress($user_email);
            $mail->isHTML(true);
            $mail->Subject = '=?UTF-8?B?' . base64_encode('비밀번호 재설정 링크') . '?=';
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            // ✅ 안전한 링크 전송
            $link = "http://secure-kisia-book.koreacentral.cloudapp.azure.com:8080//reset_password.php?token=$token";
            $mail->Body = "
                <h3>비밀번호 재설정 안내</h3>
                <p>아래 링크를 클릭하여 새 비밀번호를 설정하세요 (10분 내 유효)</p>
                <a href='$link'>비밀번호 재설정하기</a>
            ";

            $mail->send();
            echo "<script>alert('메일이 성공적으로 전송되었습니다.');</script>";
        } catch (Exception $e) {
            echo '<script>alert("메일 전송 실패: ' . addslashes($mail->ErrorInfo) . '");</script>';

            // 에러 로깅만 하고 사용자에겐 자세히 알리지 않음
            error_log('Mailer Error: ' . $mail->ErrorInfo);
        }
    } else {
        echo "<script>alert('존재하지 않는 이메일입니다.');</script>";
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