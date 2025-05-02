<?php
include 'php/db_connect.php';
include 'php/session_check.php';

$user_id = $_SESSION['user_id'];

// 장바구니 조회
$sql = "
    SELECT c.*, b.title, b.price
    FROM cart c
    JOIN books b ON c.book_id = b.id
    WHERE c.user_id = $user_id
";

$result = $conn->query($sql);

$total_price = 0;

?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>주문 확인 - KISIA_bookStore</title>
  <link rel="stylesheet" href="/css/style.css">
  <link rel="stylesheet" href="/css/cart.css">
</head>
<body>
  <div id="header-container"></div>

  <main>
    <div class="cart-container">
      <h2>주문 확인</h2>
      <table class="cart-table">
        <thead>
          <tr>
            <th style="width:50%;">상품정보</th>
            <th style="width:15%;">수량</th>
            <th style="width:15%;">금액</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()):
            $item_total = $row['price'] * $row['quantity'];
            $total_price += $item_total;
          ?>
          <tr>
            <td class="cart-product-info"><?= htmlspecialchars($row['title']) ?></td>
            <td><?= $row['quantity'] ?></td>
            <td><?= number_format($item_total) ?>원</td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <div class="cart-summary">
        <span>총 결제 금액: <?= number_format($total_price) ?>원</span>
      </div>

      <form action="order_process.php" method="post" class="order-form">
        <h3>배송 정보 입력</h3>
        <label>수령인</label>
        <input type="text" name="recipient" required>

        <label>연락처</label>
        <input type="text" name="phone" required>

        <label>주소</label>
        <input type="text" name="address" required>

        <button type="submit" class="checkout-btn">주문 확정</button>
      </form>
    </div>
  </main>

  <footer>
    <p>&copy; 2025 KISIA_bookStore. All rights reserved.</p>
  </footer>
</body>
</html>