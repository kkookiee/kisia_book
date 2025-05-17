<?php
include 'connect.php';
include 'admin_sidebar.php';

$order_id = $_GET['id'] ?? null;
if (!$order_id) {
  die("잘못된 접근입니다.");
}

$sql = "SELECT * FROM orders WHERE id = $order_id";
$result = mysqli_query($conn, $sql);
$order = mysqli_fetch_assoc($result);

if (!$order) {
  die("해당 주문을 찾을 수 없습니다.");
}

if (isset($_POST['cancel_order'])) {
  $sql = "UPDATE orders SET status = 'cancel' WHERE id = $order_id";
  mysqli_query($conn, $sql);
  echo "<script>alert('주문이 취소되었습니다.'); location.href='admin_order_detail.php?id=$order_id';</script>";
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>주문 상세 - 주문 #<?= ($order_id) ?></title>
  <link rel="stylesheet" href="/css/style.css">
  <link rel="stylesheet" href="/css/admin.css">
</head>
<body>
  <div class="admin-container">
    <main class="main-content">
      <h1>주문 상세 정보</h1>
      <table class="admin-table">
        <tr><th>주문번호</th><td><?= $order['id'] ?></td></tr>
        <tr><th>사용자 ID</th><td><?= $order['user_id'] ?></td></tr>
        <tr><th>주문 금액</th><td><?= number_format($order['total_price']) ?>원</td></tr>
        <tr><th>주문 상태</th><td><?= $order['status'] ?></td></tr>
        <tr><th>토큰</th><td><?= $order['token'] ?? '-' ?></td></tr>
      </table>

      <form method="POST" onsubmit="return confirm('정말 주문을 취소하시겠습니까?');">
        <button type="submit" name="cancel_order" value="1" class="btn delete-link">❌ 주문 취소</button>
      </form>

      <a href="admin_orders.php" class="btn">← 주문 목록</a>
    </main>
  </div>
</body>
</html>
