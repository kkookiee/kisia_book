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

// 포인트 충전
$stmt = $conn->prepare("UPDATE users SET point = point + ? WHERE id = ?");
$stmt->bind_param("ii", $point, $user_id);

if ($stmt->execute()) {
    echo "<script>alert('포인트가 성공적으로 충전되었습니다.'); location.href='./mypage.php#point';</script>";
} else {
    echo "<script>alert('충전에 실패했습니다. 관리자에게 문의해주세요.'); history.back();</script>";
}

$stmt->close();
$conn->close();
?>
