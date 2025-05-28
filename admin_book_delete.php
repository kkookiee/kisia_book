<?php
session_start();
require_once 'connect.php';

// ✅ 관리자 권한 확인
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    http_response_code(403);
    exit('접근 권한이 없습니다.');
}

// ✅ POST 방식만 허용
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('허용되지 않는 요청 방식입니다.');
}

// ✅ ID 유효성 검사 (문자열 ID 허용)
$id = trim($_POST['id'] ?? '');
if ($id === '') {
    http_response_code(400);
    exit('잘못된 요청입니다.');
}

// ✅ 삭제 전 존재 여부 확인
$check = $conn->prepare("SELECT id FROM books WHERE id = ?");
$check->bind_param("s", $id);
$check->execute();
$check->store_result();
if ($check->num_rows === 0) {
    $check->close();
    http_response_code(404);
    exit('해당 도서를 찾을 수 없습니다.');
}
$check->close();

// ✅ 안전하게 삭제
$stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$stmt->close();

// ✅ 리다이렉션
header("Location: admin_books.php");
exit;
?>
