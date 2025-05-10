<?php
include 'connect.php';

$review_id = $_GET['id'] ?? 0;

if ($review_id > 0) {
    $sql = "DELETE FROM reviews WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $review_id);
    $stmt->execute();
}

header("Location: admin_reviews.php");
exit;
?>
