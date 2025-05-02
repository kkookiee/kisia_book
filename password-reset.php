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
                <p class="auth-description">가입하신 이메일 주소를 입력하시면 비밀번호 재설정 링크를 보내드립니다.</p>
                <form class="auth-form" id="passwordResetForm">
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

    <footer>
        <div class="container">
            <p>&copy; 2024 온라인 서점. All rights reserved.</p>
        </div>
    </footer>
</body>
</html> 