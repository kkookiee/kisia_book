<?php
require_once 'connect.php';

$token = $_GET['token'] ?? '';
$token = trim($token);

// ✅ 토큰 유효성 검사 (64자리 16진수로 제한)
if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
    $paymentSuccess = false;
} else {
    // ✅ SQL Injection 방지 - Prepared Statement 사용
    $stmt = $conn->prepare("UPDATE orders SET status = 'paid' WHERE token = ? AND status != 'paid'");

    if ($stmt) {
        $stmt->bind_param("s", $token);
        $stmt->execute();

        // ✅ 실제로 결제 상태가 변경되었는지 확인
        $paymentSuccess = $stmt->affected_rows > 0;
        $stmt->close();
    } else {
        // DB 오류
        $paymentSuccess = false;
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>결제 <?= $paymentSuccess ? '완료' : '실패'; ?></title>
</head>
<body>
  <?php if ($paymentSuccess): ?>
    <h2>✅ 결제가 완료되었습니다!</h2>
    <p>이 창은 자동으로 닫힙니다...</p>
    <script>
      setTimeout(() => {
        window.close();
        window.location.href = "about:blank";
      }, 2000);
    </script>
  <?php else: ?>
    <h2>❌ 유효하지 않은 요청입니다.</h2>
    <p>이미 결제된 주문이거나, 유효하지 않은 QR 코드일 수 있습니다.</p>
  <?php endif; ?>
</body>
</html>
