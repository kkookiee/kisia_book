<?php
session_start();
require_once 'connect.php';

// 사용자 ID를 URL에서 가져옴
$id = $_GET['user_id'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "비밀번호가 일치하지 않습니다.";
    } else {
        // 평문으로 저장
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $stmt->bind_param("ss", $password, $user_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $success = "비밀번호가 성공적으로 변경되었습니다.";
            } else {
                $error = "존재하지 않는 사용자입니다.";
            }
        } else {
            $error = "비밀번호 변경 중 오류가 발생했습니다.";
        }
        $stmt->close();
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
<?php include 'header.php'; ?>
<main>
    <div class="auth-container">
        <div class="auth-box">
            <h2>비밀번호 재설정</h2>

            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <?= $success ?>
                    <p><a href="login.php">로그인 페이지로 이동</a></p>
                </div>
            <?php else: ?>
                <?php if (isset($error)): ?>
                    <div class="alert alert-error">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form class="auth-form" method="POST" action="">
                    <input type="hidden" name="user_id" value="<?= ($id) ?>">
                    <div class="form-group">
                        <label for="new_password">새 비밀번호</label>
                        <input type="password" id="new_password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">비밀번호 확인</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="auth-button">비밀번호 변경</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php include 'footer.php'; ?>
</body>
</html>