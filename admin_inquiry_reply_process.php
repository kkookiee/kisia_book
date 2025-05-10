<?php
include 'connect.php';

$inquiry_id = $_POST['id'] ?? 0;
$answer = $_POST['answer'] ?? '';

if ($inquiry_id > 0) {
    $status = $answer ? '답변 완료' : '답변 대기';
    $sql = "UPDATE inquiries 
            SET answer = ?, inquiry_status = ?, answer_at = NOW() 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $answer, $status, $inquiry_id);
    $stmt->execute();
}

header("Location: admin_inquiries.php");
exit;
?>
