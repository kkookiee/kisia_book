<?php
require_once 'phpqrcode/qrlib.php';

$data = $_GET['data'] ?? '';

if (!$data) {
    http_response_code(400);
    exit('데이터 없음');
}

// URL 디코딩
$data = urldecode($data);

// output buffering 제거 (이미지 깨짐 방지)
if (ob_get_level()) {
    ob_end_clean();
}

// Content-Type 설정
header('Content-Type: image/png');

// QR 출력
// 내부적으로 float → int 변환하는 부분에서 에러 안 나게 하려면, qrlib.php 내부도 수정 필요
QRcode::png($data);
exit;
