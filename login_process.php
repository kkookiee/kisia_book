<?php
require_once 'session_start.php';
require_once 'connect.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// ✅ Prepared Statement로 SQL Injection 방지
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    // ❗ 실제 사용 시엔 password_verify()로 암호화 비교
    if ($user['password'] === $password) {
        // ✅ 세션 저장
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['is_admin'] = $user['is_admin']; // ✅ 중요!

        // ✅ 관리자 여부로 분기
        if ($user['is_admin']) {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit;
    }
}

// 로그인 실패
echo "<script>
    alert('아이디 또는 비밀번호가 잘못되었습니다.');
    window.location.href = 'login.php';
</script>";
exit;
?>
