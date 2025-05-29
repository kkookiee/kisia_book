<?php
require_once 'connect.php';

$token = $_GET['token'] ?? '';

// 토큰 유효성 검사
if (!preg_match('/^[a-zA-Z0-9]{20,}$/', $token)) {
    die("잘못된 접근입니다.");
}

// Prepared Statement 사용 (SQL Injection 방지)
$stmt = $conn->prepare("UPDATE orders SET status = 'paid' WHERE token = ? AND status != 'paid'");

// 쿼리 실행
if ($stmt) {
    $stmt->bind_param("s", $token);
    $stmt->execute();

    // 처리 결과 확인
    if ($stmt->affected_rows > 0) {
        $paymentSuccess = true;
    } else {
        $paymentSuccess = false;
    }

    $stmt->close();
} else {
    die("서버 오류입니다.");
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>결제 <?php echo $paymentSuccess ? '완료' : '실패'; ?></title>
</head>
<body>
  <?php if ($paymentSuccess): ?>
    <h2>결제가 완료되었습니다!</h2>
    <p>이 창은 자동으로 닫힙니다...</p>
    <script>
      setTimeout(() => {
        window.close();
        window.location.href = "about:blank";
      }, 2000);
    </script>
  <?php else: ?>
    <h2>유효하지 않은 요청입니다.</h2>
  <?php endif; ?>
</body>
</html>
