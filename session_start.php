<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 세션 만료 시간 설정 (초) — 예: 30분
$session_timeout = 1800; // 1800초 = 30분

// 세션 유효성 검사 (last_activity 존재 시)
if (isset($_SESSION['last_activity'])) {
    $elapsed_time = time() - $_SESSION['last_activity'];
    if ($elapsed_time > $session_timeout) {
        // 세션 만료 처리
        session_unset();
        session_destroy();
        echo "<script>alert('세션이 만료되었습니다. 다시 로그인해주세요.'); location.href='login.php';</script>";
        exit;
    }
}

// 마지막 활동 시간 갱신
$_SESSION['last_activity'] = time();

/* 테스트용 강제 로그인 (개발 중일 때만 사용)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // 실제 DB에 있는 user_id여야 함
    $_SESSION['user_name'] = '테스트유저'; // 옵션
    $_SESSION['email'] = 'test@example.com'; // 옵션
    // header("Location: login.php");
    // exit;
}
*/

// 세션 데이터 변수로 할당
$user_id   = $_SESSION['user_id'] ?? '';
$user_name = $_SESSION['user_name'] ?? '';
$email     = $_SESSION['email'] ?? '';
?>
