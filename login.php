<?php
require_once 'session_start.php';  // 이 줄을 꼭 login.php 상단에 넣기!
require_once 'connect.php';
require_once 'header.php';
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

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];


        $sql = "SELECT * FROM users WHERE username=? AND password=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // ✅ 로그인 성공 시 세션에 값 저장
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            
            echo "<script>alert('로그인 성공!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('아이디 또는 비밀번호가 틀렸습니다.');</script>";
        }
    }
    ?>

    <main>
        <div class="auth-container">
            <h2>로그인</h2>
            <form class="auth-form" method="POST" action="login.php">
                <div class="form-group">
                    <label for="username">아이디</label>
                    <input type="text" id="username" name="username" required>
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