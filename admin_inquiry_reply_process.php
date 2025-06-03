<?php
include 'connect.php';

// 🚨 Security Misconfiguration: 에러 노출
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$inquiry_id = $_POST['id'] ?? 0;
$answer = $_POST['answer'] ?? '';

if ($inquiry_id > 0) {
    $status = $answer ? '답변 완료' : '답변 대기';
    
    // 🚨 SQL Injection 가능: 사용자 입력값을 직접 SQL에 삽입
    $sql = "UPDATE inquiries 
            SET answer = '$answer', inquiry_status = '$status', answer_at = NOW() 
            WHERE id = $inquiry_id";
    
    $conn->query($sql);
}

header("Location: admin_inquiries.php");
exit;
?>
