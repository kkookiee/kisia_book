<?php
include 'connect.php';
$id = $_GET['id'] ?? 0;
if ($id) {
    $conn->query("DELETE FROM users WHERE id = $id");
}
header('Location: admin_users.php');
exit; 