<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 테스트용 강제 로그인 (개발 중일 때만 사용)
/*if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // 실제 DB에 있는 user_id여야 함
    $_SESSION['user_name'] = '테스트유저'; // 옵션
    $_SESSION['email'] = 'test@example.com'; // 옵션
    // header("Location: login.php");
    // exit;
}*/

    $user_id    = $_SESSION['user_id'] ?? null;       // DB의 AUTO_INCREMENT id
    $username   = $_SESSION['username'] ?? '';        // 사용자가 입력한 로그인 아이디
    $name       = $_SESSION['name'] ?? '';            // 실제 이름
    $email      = $_SESSION['email'] ?? '';
    $id         = $user_id; // header.php 호환용
?>