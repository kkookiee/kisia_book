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

if (isset($_POST['cancel_payment'])) {
  $sql = "UPDATE orders SET status = 'cancel' WHERE id = $order_id";
  mysqli_query($conn, $sql);
  echo "<script>alert('결제가 취소되었습니다.'); location.href='admin_payment_detail.php?id=$order_id';</script>";
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>결제 상세 - 주문 #<?= ($order_id) ?></title>
  <link rel="stylesheet" href="/css/style.css">
  <link rel="stylesheet" href="/css/admin.css">
</head>
<body>
  <div class="admin-container">
    <main class="main-content">
      <h1>결제 상세 정보</h1>
      <table class="admin-table">
        <tr><th>주문번호</th><td><?= $order['id'] ?></td></tr>
        <tr><th>사용자 ID</th><td><?= $order['user_id'] ?></td></tr>
        <tr><th>총 결제금액</th><td><?= number_format($order['total_price']) ?>원</td></tr>
        <tr><th>결제 상태</th><td><?= $order['status'] ?></td></tr>
        <tr><th>결제 토큰</th><td><?= $order['token'] ?? '-' ?></td></tr>
      </table>

      <form method="POST" onsubmit="return confirm('정말 결제를 취소하시겠습니까?');">
        <button type="submit" name="cancel_payment" value="1" class="btn delete-link">❌ 결제 취소</button>
      </form>

      <a href="admin_payments.php" class="btn">← 결제 목록</a>
    </main>
  </div>
</body>
</html>
