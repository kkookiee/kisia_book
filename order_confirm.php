<?php
require 'connect.php';
require 'session_start.php';
require 'header.php';

$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;
if ($user_id === 0) {
    die("로그인이 필요합니다.");
}

// 장바구니 조회
$stmt = $conn->prepare("
    SELECT c.*, b.title, b.price
    FROM cart c
    JOIN books b ON c.book_id = b.id
    WHERE c.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total_price = 0;
$cart_items = [];
while ($row = $result->fetch_assoc()) {
    $row['item_total'] = $row['price'] * $row['quantity'];
    $total_price += $row['item_total'];
    $cart_items[] = $row;
}
$stmt->close();

// 사용자 포인트 조회
$stmt = $conn->prepare("SELECT point FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user_point = $user_result->fetch_assoc()['point'] ?? 0;
$stmt->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>주문 확인 - KISIA_bookStore</title>
  <link rel="stylesheet" href="/css/style.css">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="/css/cart.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
          <?php foreach ($cart_items as $row): ?>
          <tr>
            <td class="cart-product-info"><?= htmlspecialchars($row['title']) ?></td>
            <td><?= (int)$row['quantity'] ?></td>
            <td><?= number_format($row['item_total']) ?>원</td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="cart-summary">
        <span>총 결제 금액: <strong id="total-price"><?= number_format($total_price) ?></strong>원</span><br>
        <span>보유 포인트: <strong id="user-point"><?= number_format($user_point) ?></strong>P</span><br>
        <span id="point-warning" style="color:red; font-weight:bold;"></span>
      </div>

      <form action="order_process.php" method="post" class="order-form" id="order-form">
        <h3>배송 정보 입력</h3>
        <div class="form-group">
          <label for="recipient">수령인</label>
          <input type="text" id="recipient" name="recipient" required>
        </div>

        <div class="form-group">
          <label>휴대폰</label>
          <div style="display:flex; gap:5px;">
            <input type="text" name="phone1" maxlength="3" required pattern="\d{2,3}">
            <input type="text" name="phone2" maxlength="4" required pattern="\d{3,4}">
            <input type="text" name="phone3" maxlength="4" required pattern="\d{4}">
          </div>
        </div>

        <div class="form-group">
          <label>배송주소</label>
          <div style="display:flex; gap:8px;">
            <input type="text" id="postcode" name="postcode" placeholder="우편번호" readonly>
            <button type="button" onclick="execDaumPostcode()">주소 찾기</button>
          </div>
          <input type="text" id="roadAddress" name="road_address" placeholder="도로명 주소" readonly>
          <input type="text" id="jibunAddress" name="jibun_address" placeholder="지번 주소" readonly>
          <input type="text" id="detailAddress" name="detail_address" placeholder="상세 주소" required>
        </div>

        <button type="submit" class="checkout-btn" id="checkout-btn">주문 확정</button>
      </form>
    </div>
  </main>
  <?php include 'footer.php'; ?>
</body>
</html>

<script>

  // ✅ 포인트 부족시 주문 차단
  window.addEventListener('DOMContentLoaded', () => {
    const totalPrice = parseInt(document.getElementById('total-price').innerText.replace(/[^0-9]/g, ''));
    const userPoint = parseInt(document.getElementById('user-point').innerText.replace(/[^0-9]/g, ''));
    const warning = document.getElementById('point-warning');
    const btn = document.getElementById('checkout-btn');

    if (userPoint < totalPrice) {
      warning.textContent = '⚠ 포인트가 부족합니다. 충전 후 주문해주세요.';
      btn.disabled = true;
      btn.style.opacity = 0.5;
    }
  });
</script>
