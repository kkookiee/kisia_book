<?php
require_once 'phpqrcode/qrlib.php';

$data = $_GET['data'] ?? '';

if (!$data) {
    http_response_code(400);
    exit('데이터 없음');
}

$data = urldecode($data);

header('Content-Type: image/png');
QRcode::png($data);
