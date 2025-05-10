<?php
require 'connect.php';
require 'session_start.php';
require 'header.php';


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
  <link rel="stylesheet" href="css/header.css">
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
        <div class="form-group">
          <label for="recipient">수령인</label>
          <input type="text" id="recipient" name="recipient" required>
        </div>

        <div class="form-group">
          <label>휴대폰</label>
          <div style="display:flex; gap:5px;">
            <input type="text" name="phone1" maxlength="3" required>
            <input type="text" name="phone2" maxlength="4" required>
            <input type="text" name="phone3" maxlength="4" required>
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

        <button type="submit" class="checkout-btn">주문 확정</button>
      </form>
    </div>
  </main>
  <?php include 'footer.php'; ?>
</body>
</html>

<!-- 카카오 주소 API 스크립트 -->
<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script>
  function execDaumPostcode() {
    new daum.Postcode({
      oncomplete: function(data) {
        document.getElementById('postcode').value = data.zonecode;
        document.getElementById('roadAddress').value = data.roadAddress;
        document.getElementById('jibunAddress').value = data.jibunAddress;
      }
    }).open();
  }
</script>