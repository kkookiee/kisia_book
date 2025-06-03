<?php include 'session_start.php'; ?>
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
    <?php include 'header.php'; ?>
    <main>
        <div class="auth-container">
            <h2>로그인</h2>
            <form class="auth-form" method="POST" action="login_process.php">
                <div class="form-group">
                    <label for="username">아이디</label>
                    <input type="text" id="username" name="username" >
                </div>
                <div class="form-group">
                    <label for="password">비밀번호</label>
                    <input type="password" id="password" name="password" >
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
