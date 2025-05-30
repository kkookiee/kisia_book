<?php
session_start();
require_once 'connect.php';
require_once 'admin_sidebar.php';

// ✅ 관리자 인증 확인 먼저!
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
  // 404 페이지로 리다이렉트
  header("Location: /404.php");
  exit();
}

// ✅ 주문 ID 검증
$order_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$order_id) {
    http_response_code(400);
    exit('잘못된 접근입니다.');
}

// ✅ 주문 상세 조회 (Prepared Statement)
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

// ✅ POST 요청일 경우 - 주문 취소 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order'])) {
    $cancel_stmt = $conn->prepare("UPDATE orders SET status = 'cancel' WHERE id = ?");
    $cancel_stmt->bind_param("i", $order_id);
    $cancel_stmt->execute();
    $cancel_stmt->close();

    echo "<script>alert('주문이 취소되었습니다.'); location.href='admin_order_detail.php?id=$order_id';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>주문 상세 - 주문 #<?= htmlspecialchars($order_id) ?></title>
  <link rel="stylesheet" href="/css/style.css">
  <link rel="stylesheet" href="/css/admin.css">
</head>
<body>
  <div class="admin-container">
    <main class="main-content">
      <h1>주문 상세 정보</h1>
      <table class="admin-table">
        <tr><th>주문번호</th><td><?= htmlspecialchars($order['id']) ?></td></tr>
        <tr><th>사용자 ID</th><td><?= htmlspecialchars($order['user_id']) ?></td></tr>
        <tr><th>주문 금액</th><td><?= number_format($order['total_price']) ?>원</td></tr>
        <tr><th>주문 상태</th><td><?= htmlspecialchars($order['status']) ?></td></tr>
        <tr><th>토큰</th><td><?= htmlspecialchars($order['token'] ?? '-') ?></td></tr>
      </table>

      <form method="POST" onsubmit="return confirm('정말 주문을 취소하시겠습니까?');">
        <input type="hidden" name="cancel_order" value="1">
        <button type="submit" class="btn delete-link">❌ 주문 취소</button>
      </form>

      <a href="admin_orders.php" class="btn">← 주문 목록</a>
    </main>
  </div>
</body>
</html>
