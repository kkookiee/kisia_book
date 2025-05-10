<?php
require_once 'session_start.php';  // 세션 시작
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

<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['user_id'];  // HTML 폼의 input name="user_id"는 사실상 username
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];            // ⚠️ 실제 숫자 ID
        $_SESSION['username'] = $user['username'];     // 로그인 아이디
        $_SESSION['email'] = $user['email'];           // 이메일
        $_SESSION['name'] = $user['name'];             // 이름

        echo "<script>alert('로그인 성공!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('아이디 또는 비밀번호가 틀렸습니다.');</script>";
=======
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['user_id'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE user_id='$id' AND password='$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // ✅ 로그인 성공 시 세션에 값 저장
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            $_SESSION['email'] = $email;
            echo "<script>alert('로그인 성공!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('아이디 또는 비밀번호가 틀렸습니다.');</script>";
        }
>>>>>>> Stashed changes
    }
}
?>

<<<<<<< Updated upstream
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
=======
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
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
</body>
</html>
