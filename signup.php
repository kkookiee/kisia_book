<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원가입 - 온라인 서점</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php
    require_once 'connect.php';
    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        if ($password != $password_confirm) {
            echo "<script>alert('비밀번호가 일치하지 않습니다'); window.location.href='signup.php';</script>";
        } else {
            $check_sql = "SELECT * FROM users WHERE id = '$id'"; // 중복 확인
            $check_result = $conn->query($check_sql);
    
            if ($check_result && $check_result->num_rows > 0) {
                echo "<script>alert('이미 사용 중인 아이디입니다.'); window.location.href='signup.php';</script>";
            } else {
                // 회원 가입 실행
                $sql = "INSERT INTO users(id, email, password, name, phone, address) 
                        VALUES('$id','$email','$password','$name','$phone','$address')";
                
                if($conn->query($sql) === TRUE) {
                    echo "<script>alert('회원가입 성공'); window.location.href='index.php';</script>";
                } else {
                    echo "<script>alert('회원가입 실패: " . $conn->error . "'); window.location.href='signup.php';</script>";
                }
            }
        }
    }
    ?>
    <main>
        <div class="auth-container">
            <h2>회원가입</h2>
            <form class="auth-form" method="POST" action="signup.php">
                <div class="form-group">
                    <label for="id">아이디</label>
                    <input type="text" id="id" name="id" required>
                </div>
                <div class="form-group">
                    <label for="email">이메일</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">비밀번호</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="password_confirm">비밀번호 확인</label>
                    <input type="password" id="password_confirm" name="password_confirm" required>
                </div>
                <div class="form-group">
                    <label for="name">이름</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="phone">전화번호</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="address">주소</label>
                    <input type="text" id="address" name="address" required>
                </div>
                <button type="submit" class="auth-button">회원가입</button>
                <p class="auth-switch">
                    이미 계정이 있으신가요? <a href="login.php">로그인</a>
                </p>
            </form>
        </div>
    </main>
    <footer>
        <div class="container">
            <p>&copy; 2024 온라인 서점. All rights reserved.</p>
        </div>
    </footer>
</body>
</html> 