<?php
session_start();
require_once 'connect.php';

// ✅ 관리자 인증 확인
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    http_response_code(403);
    exit('접근 권한이 없습니다.');
}

// ✅ POST 방식만 허용
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('허용되지 않는 요청 방식입니다.');
}

// ✅ 입력값 필터링 및 유효성 검사
$inquiry_id = $_POST['id'] ?? '';
$answer = trim($_POST['answer'] ?? '');
$status = $answer !== '' ? '답변 완료' : '답변 대기';

if ($inquiry_id === '') {
    http_response_code(400);
    exit('잘못된 요청입니다.');
}

// ✅ Prepared Statement로 SQL Injection 방지
$stmt = $conn->prepare("UPDATE inquiries 
                        SET answer = ?, inquiry_status = ?, answer_at = NOW() 
                        WHERE id = ?");
$stmt->bind_param("ssi", $answer, $status, $inquiry_id);

if ($stmt->execute()) {
    header("Location: admin_inquiries.php");
    exit;
} else {
    echo "<script>alert('답변 저장 실패'); history.back();</script>";
}
?>
