<?php
require_once 'phpqrcode/qrlib.php';

$data = $_GET['data'] ?? '';

header('Content-Type: image/png');
QRcode::png($data);
?>
