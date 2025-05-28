<?php
session_start();
require_once 'connect.php';

// ✅ 관리자 인증 확인
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    http_response_code(403);
    exit('접근 권한이 없습니다.');
}

// ✅ 요청 방식 검사
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('허용되지 않는 요청 방식입니다.');
}

// ✅ CSRF 토큰 검증
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    exit('CSRF 검증 실패');
}

// ✅ ID 필터링
$id = $_POST['id'] ?? '';
if ($id === '') {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

// ✅ 안전하게 삭제
$stmt = $conn->prepare("DELETE FROM inquiries WHERE id = ?");
$stmt->bind_param("s", $id);
$success = $stmt->execute();
$stmt->close();

if ($success) {
    echo "<script>alert('삭제 완료'); location.href='admin_inquiries.php';</script>";
} else {
    // 내부 에러는 사용자에게 노출하지 않음
    error_log("문의 삭제 실패 - ID: $id, 관리자 ID: {$_SESSION['user_id']}");
    echo "<script>alert('삭제 중 오류가 발생했습니다.'); history.back();</script>";
}
?>
