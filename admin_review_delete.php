<?php
session_start();
require_once 'connect.php';

// ✅ 관리자 인증 확인 먼저!
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    // 404 페이지로 리다이렉트
    header("Location: /404.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('허용되지 않는 요청 방식입니다.');
}

$review_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$review_id) {
    http_response_code(400);
    exit('잘못된 요청입니다.');
}

$stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
$stmt->bind_param("i", $review_id);
$stmt->execute();
$stmt->close();

header("Location: admin_reviews.php");
exit;
