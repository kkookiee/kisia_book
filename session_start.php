<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* 테스트용 강제 로그인 (개발 중일 때만 사용)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // 실제 DB에 있는 user_id여야 함
    $_SESSION['user_name'] = '테스트유저'; // 옵션
    $_SESSION['email'] = 'test@example.com'; // 옵션
    // header("Location: login.php");
    // exit;
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
}
*/
=======
}*/
>>>>>>> Stashed changes
=======
}*/
>>>>>>> Stashed changes
=======
}*/
>>>>>>> Stashed changes

// 세션 데이터 변수로 할당
$user_id   = $_SESSION['user_id'] ?? '';
$user_name = $_SESSION['user_name'] ?? '';
$email     = $_SESSION['email'] ?? '';
?>
