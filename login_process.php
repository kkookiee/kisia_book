<?php
require_once 'session_start.php';
require_once 'connect.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);

    $_SESSION['id'] = $user['id'];
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['password'] = $user['password'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['email'] = $user['email'];

    if ($user['username'] === 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: index.php");
    }
    exit();
} else {
    echo "<script>
        alert('아이디 또는 비밀번호가 잘못되었습니다.');
        window.location.href = 'login.php';
    </script>";
    exit();
}

mysqli_close($conn);
?>