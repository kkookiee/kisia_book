<?php
session_start();
require_once 'connect.php';

// ✅ 관리자 인증 확인 먼저!
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    // 404 페이지로 리다이렉트
    header("Location: /404.php");
    exit();
}

// ✅ 요청 방식 검사
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('허용되지 않는 요청 방식입니다.');
}

// ✅ ID 필터링 (books.id가 VARCHAR면 "s", 숫자면 "i")
$id = $_POST['id'] ?? null;

if ($id === null || $id === '') {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

// ✅ Prepared Statement로 안전하게 삭제
$stmt = $conn->prepare("DELETE FROM inquiries WHERE id = ?");
$stmt->bind_param("s", $id);
$success = $stmt->execute();
$stmt->close();

if ($success) {
    echo "<script>alert('삭제 완료'); location.href='admin_inquiries.php';</script>";
} else {
    echo "<script>alert('삭제 실패'); history.back();</script>";
}
?>
