<?php
session_start();
require_once 'connect.php';

// ✅ 관리자 인증 확인 먼저!
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    // 404 페이지로 리다이렉트
    header("Location: /404.php");
    exit();
}

// ✅ POST 방식만 허용
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('허용되지 않는 요청 방식입니다.');
}

// ✅ 입력값 검증
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(400);
    exit('잘못된 요청입니다.');
}

// ✅ Prepared Statement로 안전하게 삭제
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header('Location: admin_users.php');
exit;
