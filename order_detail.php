<?php
include 'connect.php';
include 'session_start.php';

$order_id = intval($_GET['order_id'] ?? 0);
$user_id = $_SESSION['user_id'] ?? 0;

// 주문 조회 쿼리
$sql = "
    SELECT oi.id AS item_id, b.title, b.price, b.image_path, oi.quantity
    FROM order_items oi
    JOIN books b ON oi.book_id = b.id
    JOIN orders o ON oi.order_id = o.id
    WHERE oi.order_id = $order_id AND o.user_id = $user_id
";

$result = $conn->query($sql);
$total_price = 0;
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>주문 상세 - 온라인 서점</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/cart.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <?php include 'header.php'; ?>
  <main>
    <div class="container">
      <h2>주문 상세</h2>
      <div class="cart-container">
        <table class="cart-table">
          <thead>
            <tr>
              <th style="width:50%;">상품정보</th>
              <th style="width:15%;">수량</th>
              <th style="width:15%;">상품금액</th>
            </tr>
          </thead>
          <tbody>
          <?php while ($row = $result->fetch_assoc()): 
             $item_total = $row['price'] * $row['quantity'];
              $total_price += $item_total;
            ?>         
            <tr>
            <td class="cart-product-info">
            <img src="<?= $row['image_path'] ?? 'images/default.jpg' ?>">
            <div class="cart-info-detail">
                <span class="cart-title">[도서] <?= ($row['title']) ?></span>
                <div class="cart-meta">
                    <span class="cart-price-sale"><?= number_format($row['price']) ?>원</span>
                </div>
                </div>
            </td>
            <td><?= $row['quantity'] ?>권</td>
            <td><?= number_format($item_total) ?>원</td>
            </tr>
            <?php endwhile; ?>
            </tbody>        </table>
        <div class="cart-summary">
          <div class="summary-item total">
            <span>총 결제 금액</span>
            <?= number_format($total_price) ?>원
          </div>
        </div>
      </div>
    </div>
  </main>
  <footer>
    <div class="container">
      <p>&copy; 2025 온라인 서점. All rights reserved.</p>
    </div>
  </footer>
</body>
</html>