<?php
include 'connect.php';

// 🚨 Security Misconfiguration: 에러 노출
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$review_id = $_GET['id'] ?? 0;

if ($review_id > 0) {
    // 🚨 SQL Injection 가능 + CSRF 가능 (GET 요청으로 삭제)
    $sql = "DELETE FROM reviews WHERE id = $review_id";
    $conn->query($sql);
}

header("Location: admin_reviews.php");
exit;
?>
