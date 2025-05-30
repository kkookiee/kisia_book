<?php
session_start();
require_once 'connect.php';

// ✅ 관리자 인증 확인 먼저!
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
  // 404 페이지로 리다이렉트
  header("Location: /404.php");
  exit();
}

require_once 'admin_sidebar.php';


// ✅ GET 파라미터 유효성 검사
$order_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$order_id) {
  http_response_code(400);
  exit('잘못된 접근입니다.');
}

// ✅ 주문 조회 (Prepared Statement)
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if (!$order) {
  http_response_code(404);
  exit('해당 주문을 찾을 수 없습니다.');
}

// ✅ 결제 취소 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_payment'])) {
  $stmt = $conn->prepare("UPDATE orders SET status = 'cancel' WHERE id = ?");
  $stmt->bind_param("i", $order_id);
  $stmt->execute();
  $stmt->close();
  echo "<script>alert('결제가 취소되었습니다.'); location.href='admin_payment_detail.php?id=" . urlencode($order_id) . "';</script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>결제 상세 - 주문 #<?= htmlspecialchars($order_id) ?></title>
  <link rel="stylesheet" href="/css/style.css">
  <link rel="stylesheet" href="/css/admin.css">
</head>
<body>
  <div class="admin-container">
    <main class="main-content">
      <h1>결제 상세 정보</h1>
      <table class="admin-table">
        <tr><th>주문번호</th><td><?= htmlspecialchars($order['id']) ?></td></tr>
        <tr><th>사용자 ID</th><td><?= htmlspecialchars($order['user_id']) ?></td></tr>
        <tr><th>총 결제금액</th><td><?= number_format($order['total_price']) ?>원</td></tr>
        <tr><th>결제 상태</th><td><?= htmlspecialchars($order['status']) ?></td></tr>
        <tr><th>결제 토큰</th><td><?= htmlspecialchars($order['token'] ?? '-') ?></td></tr>
      </table>

      <form method="POST" onsubmit="return confirm('정말 결제를 취소하시겠습니까?');">
        <input type="hidden" name="cancel_payment" value="1">
        <button type="submit" class="btn delete-link">❌ 결제 취소</button>
      </form>

      <a href="admin_payments.php" class="btn">← 결제 목록</a>
    </main>
  </div>
</body>
</html>
