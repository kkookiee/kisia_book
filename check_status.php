<?php
include 'connect.php';
header('Content-Type: application/json');

// JSON POST 데이터 읽기
$input = json_decode(file_get_contents('php://input'), true);
$token = $input['token'] ?? '';

// 단일 문자열 토큰 유효성 검사 (SHA-256 해시처럼 64자리)
if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
    echo json_encode(['status' => 'invalid']);
    exit;
}

// Prepared Statement로 안전하게 조회
$stmt = $conn->prepare("SELECT status FROM orders WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(['status' => $row['status']]);
} else {
    echo json_encode(['status' => 'unknown']);
}
