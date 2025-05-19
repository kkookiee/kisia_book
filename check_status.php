<?php
include 'connect.php';

$token = $_GET['token'] ?? '';
list($token_user_id, $token_order_id) = explode('-', $token);

$sql = "SELECT status FROM orders WHERE id = $token_order_id AND user_id = $token_user_id";
$result = $conn->query($sql);

if ($result && $row = $result->fetch_assoc()) {
    echo json_encode(['status' => $row['status']]);
} else {
    echo json_encode(['status' => 'unknown']);
}
