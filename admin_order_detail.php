<?php
include 'connect.php';

$order_id = $_GET['id'] ?? 0;

// 주문 정보
$order_sql = "SELECT o.*, u.username FROM orders o
              JOIN users u ON o.user_id = u.id
              WHERE o.id = $order_id";
$order_result = $conn->query($order_sql);
$order = $order_result->fetch_assoc();

// 주문 상세 정보
$items_sql = "SELECT oi.*, b.title FROM order_items oi
              JOIN books b ON oi.book_id = b.id
              WHERE oi.order_id = $order_id";
$items_result = $conn->query($items_sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>주문 상세</title>
  <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<div class="admin-container">
  <?php include 'admin_sidebar.php'; ?>
  <main class="main-content">
    <h1>주문 상세 정보</h1>

    <section class="order-info">
      <p><strong>주문 ID:</strong> <?= $order['id'] ?></p>
      <p><strong>주문자:</strong> <?= ($order['username']) ?></p>
      <p><strong>수령인:</strong> <?= ($order['recipient']) ?></p>
      <p><strong>연락처:</strong> <?= ($order['phone']) ?></p>
      <p><strong>주소:</strong> <?= ($order['address']) ?></p>
      <p><strong>총 금액:</strong> <?= number_format($order['total_price']) ?>원</p>
      <p><strong>주문일:</strong> <?= $order['created_at'] ?></p>
    </section>

    <h2>주문 도서 목록</h2>
    <table class="admin-table">
      <thead>
        <tr>
          <th>도서 제목</th>
          <th>수량</th>
          <th>가격</th>
        </tr>
      </thead>
      <tbody>
        <?php while($item = $items_result->fetch_assoc()): ?>
        <tr>
          <td><?= ($item['title']) ?></td>
          <td><?= $item['quantity'] ?></td>
          <td><?= number_format($item['price']) ?>원</td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </main>
</div>
</body>
</html>
