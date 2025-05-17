<?php
include 'connect.php';

$order_id = $_GET['id'] ?? null;
if (!$order_id) {
  die("잘못된 접근입니다.");
}

// 주문 정보 조회
$sql = "SELECT * FROM orders WHERE id = $order_id"; 
$result = mysqli_query($conn, $sql);
$order = mysqli_fetch_assoc($result);

if (!$order) {
  die("해당 주문을 찾을 수 없습니다.");
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>결제 상세 - 주문 #<?= ($order_id) ?></title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/admin.css">
</head>
<body>
  <div class="admin-container">
    <main class="main-content">
      <h1>결제 상세 정보</h1>
      <table class="admin-detail-table">
        <tr><th>주문번호</th><td><?= $order['id'] ?></td></tr>
        <tr><th>사용자 ID</th><td><?= $order['user_id'] ?></td></tr>
        <tr><th>총 결제금액</th><td><?= $order['total_price'] ?>원</td></tr>
        <tr><th>결제 상태</th><td><?= $order['status'] ?></td></tr>
        <tr><th>결제 일시</th><td><?= $order['paid_at'] ?></td></tr>
        <tr><th>결제 토큰</th><td><?= $order['token'] ?></td></tr>
      </table>

      <!-- ❌ 누구나 결제 상태를 변경 가능 -->
      <form method="POST">
        <button type="submit" name="cancel_payment" value="1">❌ 결제 취소</button>
      </form>

      <a href="admin_payments.php" class="admin-back-btn">← 결제 목록</a>
    </main>
  </div>
</body>
</html>

<?php
if (isset($_POST['cancel_payment'])) {
  $sql = "UPDATE orders SET status = 'cancel' WHERE id = $order_id";
  mysqli_query($conn, $sql);
  echo "<script>alert('결제 상태를 취소로 변경했습니다.'); location.href='admin_payment_detail.php?id=$order_id';</script>";
}
?>
