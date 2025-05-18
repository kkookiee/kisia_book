<?php
require_once 'connect.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $sql = "DELETE FROM inquiries WHERE id = $id";
    $conn->query($sql);

    echo "<script>alert('삭제 완료'); location.href='admin_inquiry.php';</script>";
} else {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
}
?>
