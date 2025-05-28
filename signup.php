<?php
require_once 'header.php';
require_once 'connect.php';

$signup_error = '';
$signup_success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['id'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $name = trim($_POST['name'] ?? '');

    // ✅ 아이디 형식 검사 (SQLi 방지 + 정책 적용)
    if (!preg_match('/^[a-zA-Z0-9_]{4,20}$/', $username)) {
        $signup_error = '아이디는 4~20자의 영문자, 숫자, 밑줄(_)만 사용할 수 있습니다.';
    }
    // ✅ 비밀번호 확인 검사
    elseif ($password !== $password_confirm) {
        $signup_error = '비밀번호가 일치하지 않습니다.';
    } else {
        // ✅ 아이디 중복 검사 (Prepared Statement)
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $signup_error = '이미 사용 중인 아이디입니다.';
        } else {
            // ✅ 비밀번호 해시
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // ✅ 회원정보 저장 (Prepared Statement)
            $insert_stmt = $conn->prepare("INSERT INTO users (username, password, name, email) VALUES (?, ?, ?, ?)");
            $insert_stmt->bind_param("ssss", $username, $hashed_password, $name, $email);

            if ($insert_stmt->execute()) {
                echo "<script>alert('회원가입 성공'); location.href='login.php';</script>";
                exit;
            } else {
                $signup_error = '회원가입 실패: ' . $conn->error;
            }

            $insert_stmt->close();
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
    <title>회원가입 - 온라인 서점</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<main>
    <div class="auth-container">
        <h2>회원가입</h2>

        <?php if ($signup_error): ?>
            <p style="color: red;"><?= htmlspecialchars($signup_error) ?></p>
        <?php endif; ?>

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
            <button type="submit" class="auth-button">회원가입</button>
            <p class="auth-switch">
                이미 계정이 있으신가요? <a href="login.php">로그인</a>
            </p>
        </form>
    </div>
</main>

<?php require_once 'footer.php'; ?>
</body>
</html>
