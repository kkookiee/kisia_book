<?php
require_once 'session_start.php';
require_once 'connect.php';
require_once 'header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['user_id'];
    $password = $_POST['password'];

    // 💀 SQL Injection 테스트용 (보안 처리 제거)
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['name'] = $user['name'];

        echo "<script>alert('로그인 성공!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('아이디 또는 비밀번호가 틀렸습니다.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인 - 온라인 서점</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<main>
    <div class="auth-container">
        <h2>로그인</h2>
        <form class="auth-form" method="POST" action="login.php">
            <div class="form-group">
                <label for="user_id">아이디</label>
                <input type="text" id="user_id" name="user_id" required>
            </div>
            <div class="form-group">
                <label for="password">비밀번호</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-links">
                <a href="password-reset.php">비밀번호 찾기</a>
            </div>
            <button type="submit" class="auth-button">로그인</button>
            <p class="auth-switch">
                계정이 없으신가요? <a href="signup.php">회원가입</a>
            </p>
        </form>
    </div>
</main>

<?php require_once 'footer.php'; ?>
</body>
</html>
