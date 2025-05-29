<?php
include 'connect.php';
include 'session_start.php';

$token = $_GET['token'] ?? '';
$token = trim($token);

// 토큰 유효성 검증 (64자리 16진수)
if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
    echo "<script>alert('유효하지 않은 접근입니다.'); history.back();</script>";
    exit;
}

// 주문 조회 (토큰 기반, SQL Injection 방지)
$stmt = $conn->prepare("SELECT id, user_id, status, address FROM orders WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result && $order_result->num_rows > 0) {
    $order_row = $order_result->fetch_assoc();
    $order_id = $order_row['id'];
    $user_id = $order_row['user_id'];
    $status = $order_row['status'];
    $address = $order_row['address'];
} else {
    echo "<script>alert('존재하지 않는 주문입니다.'); history.back();</script>";
    exit;
}
$stmt->close();

// 주문 취소 요청 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order'])) {
    $del_items_stmt = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
    $del_items_stmt->bind_param("i", $order_id);
    $del_items_stmt->execute();
    $del_items_stmt->close();

    $del_order_stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $del_order_stmt->bind_param("i", $order_id);
    $del_order_stmt->execute();
    $del_order_stmt->close();

    echo "<script>alert('주문이 취소되었습니다.'); location.href='mypage.php';</script>";
    exit;
}

// 주문 상품 조회
$item_stmt = $conn->prepare("SELECT oi.id AS item_id, b.title, b.price, b.image_path, oi.quantity FROM order_items oi JOIN books b ON oi.book_id = b.id WHERE oi.order_id = ?");
$item_stmt->bind_param("i", $order_id);
$item_stmt->execute();
$result = $item_stmt->get_result();
$total_price = 0;
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>주문 상세 - 온라인 서점</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/order_detail.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <?php include 'header.php'; ?>
  <main>
    <div class="order-detail-wrapper">
      <h2>주문 상세</h2>

      <div class="status-box">
        배송지: <?= htmlspecialchars($address) ?><br>
        결제 상태:
        <?php if ($status === 'paid'): ?>
          <span class="status-paid">결제 완료</span>
        <?php elseif ($status === 'pending'): ?>
          <span class="status-pending">결제 대기중</span>
        <?php else: ?>
          <span><?= htmlspecialchars($status) ?></span>
        <?php endif; ?>
      </div>

      <?php if ($status === 'pending'): ?>
      <div class="qr-toggle-section" style="margin-top: 30px; text-align: center;">
        <button onclick="showQR()" class="btn" style="padding: 10px 20px;">결제 QR 다시 보기</button>

        <div id="qr-box" style="display: none; margin-top: 20px;">
          <?php
          $host = $_SERVER['HTTP_HOST'];  // 현재 도메인 추출
          $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
          $qr_url = "$scheme://$host/pay.php?token=$token";
          ?>
          <p>아래 QR 코드를 스캔하여 결제를 완료해 주세요.</p>
          <img src="generate_qr.php?data=<?= urlencode($qr_url) ?>" alt="QR 결제 코드" style="width: 200px;">
        </div>
      </div>
      <?php endif; ?>

      <form method="post" onsubmit="return confirm('정말 주문을 취소하시겠습니까?');">
        <input type="hidden" name="cancel_order" value="1">
        <button type="submit" class="cancel-btn">주문 전체 취소</button>
      </form>

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
                <img src="<?= $row['image_path'] ?? 'images/default.jpg' ?>" alt="표지">
                <div class="cart-info-detail">
                  <span class="cart-title">[도서] <?= htmlspecialchars($row['title']) ?></span>
                  <div class="cart-meta">
                    <span class="cart-price-sale"><?= number_format($row['price']) ?>원</span>
                  </div>
                </div>
              </td>
              <td><?= $row['quantity'] ?>권</td>
              <td><?= number_format($item_total) ?>원</td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>

        <div class="cart-summary">
          <div class="summary-item total">
            <span>총 결제 금액</span>
            <?= number_format($total_price) ?>원
          </div>
        </div>
      </div>
    </div>
  </main>
  <?php include 'footer.php'; ?>
</body>
</html>

<script>
  function showQR() {
    const qrBox = document.getElementById('qr-box');
    qrBox.style.display = 'block';

    setInterval(() => {
      fetch('check_status.php?token=<?= $token ?>')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'paid') {
            alert('결제가 완료되었습니다!');
            location.reload();
          }
        });
    }, 3000);
  }
</script>
