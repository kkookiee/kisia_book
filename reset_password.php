<?php
session_start();
require_once 'connect.php';

$token = $_GET['token'] ?? '';
$token_valid = false;
$user_id = null;

// ✅ 1. 토큰 유효성 확인
if ($token) {
    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $user_id = $row['id'];
        $token_valid = true;
    }
    $stmt->close();
}

// ✅ 2. 비밀번호 변경 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "비밀번호가 일치하지 않습니다.";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed, $user_id);
        $stmt->execute();

        $success = "비밀번호가 성공적으로 변경되었습니다.";
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
                <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                <p><a href="login.php">로그인 페이지로 이동</a></p>
            </div>
        <?php else: ?>
            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="new_password">새 비밀번호</label>
                    <input type="password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">비밀번호 확인</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <button type="submit">비밀번호 변경</button>
            </form>
        <?php endif; ?>

        </div>
    </div>
</main>
<?php include 'footer.php'; ?>
</body>
</html>