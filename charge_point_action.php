<?php
require_once './session_start.php';
require_once './connect.php';

// 로그인 체크
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href='../user/login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$point = isset($_POST['point']) ? (int)$_POST['point'] : 0;

// ✅ 유효성 검사 (100원 이상만 충전 허용)
if ($point < 100) {
    echo "<script>alert('100원 이상부터 충전이 가능합니다.'); history.back();</script>";
    exit;
}

// ⚠️ 취약한 방식: 직접 문자열로 쿼리 만들기
$sql = "UPDATE users SET point = point + $point WHERE id = $user_id";
$conn->query($sql);

if ($conn->affected_rows > 0) {
    echo "<script>alert('포인트가 성공적으로 충전되었습니다.'); location.href='./mypage.php#point';</script>";
} else {
    echo "<script>alert('충전에 실패했습니다. 관리자에게 문의해주세요.'); history.back();</script>";
}

$conn->close();
?>
