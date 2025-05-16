<?php
require_once 'connect.php';

$token = $_GET['token'] ?? '';
$sql = "SELECT status FROM orders WHERE token = '$token'";
$result = $conn->query($sql);
$status = $result->fetch_assoc()['status'] ?? 'unknown';

echo json_encode(['status' => $status]);
