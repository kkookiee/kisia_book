<?php
require_once 'session_start.php';
require_once 'connect.php';

// 보안 헤더
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("Content-Security-Policy: default-src 'self';");

// 요청 방식 확인
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('허용되지 않는 요청 방식입니다.');
}

// CSRF 토큰 확인
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
    http_response_code(403);
    exit('잘못된 요청입니다.');
}

// 입력값 정리
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// 사용자 조회
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    if (password_verify($password, $user['password'])) {
        // 로그인 성공
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['is_admin'] = $user['is_admin'];
        $_SESSION['is_login'] = true; // ✅ 추가 필요


        // 관리자 여부 분기
        $redirect = $user['is_admin'] ? "admin_dashboard.php" : "index.php";
        header("Location: $redirect");
        exit;
    }
}

// 실패 시
header("Location: login.php?error=1");
exit;
?>
