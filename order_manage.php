<?php
include 'connect.php';
include 'session_start.php';

$order_id = intval($_GET['order_id'] ?? 0);
$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD']==='POST'){
    if (isset($_POST['update_qty'])){
        $item_id = intval($_POST['item_id']);
        $qty = max(1, intval($_POST['quantity']));
        $conn->query("UPDATE order_items SET quantity = $qty WHERE id = $item_id");
    } elseif (isset($_POST['cancel_order'])){
        $conn->query("DELETE FROM order_items WHERE order_id = $order_id");
        $conn->query("DELETE FROM orders WHERE id = $order_id");
        echo"<script>alert('주문이 취소 되었습니다. '); location.href='mypage.php';</script>";
            exit;
    }
}

//주문 조회
$sql = "
    SELECT oi.id AS item_id, b.title, b.price, oi.quantity
    FROM order_items oi
    JOIN books b ON oi.book_id = b.id
    JOIN orders o ON oi.order_id = o.id
    WHERE oi.order_id = $order_id AND o.user_id = $user_id
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>주문 취소/변경 - 온라인 서점</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
  <div class="container">
    <h2>주문 취소/변경</h2>
    <form method="post">
      <table class="cart-table">
        <thead>
          <tr><th>도서명</th><th>수량</th><th>금액</th><th>변경</th></tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= ($row['title']) ?></td>
            <td>
              <input type="number" name="quantity" value="<?= $row['quantity'] ?>" min="1" />
              <input type="hidden" name="item_id" value="<?= $row['item_id'] ?>">
            </td>
            <td><?= number_format($row['price'] * $row['quantity']) ?>원</td>
            <td>
              <button type="submit" name="update_qty" class="action-btn secondary-btn">수정</button>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </form>

    <form method="post" style="margin-top: 20px;">
      <button type="submit" name="cancel_order" class="checkout-btn"
              onclick="return confirm('정말 주문을 취소하시겠습니까?');">
        주문 전체 취소
      </button>
    </form>
  </div>
</main>
    <footer>
        <div class="container">
            <p>&copy; 2024 온라인 서점. All rights reserved.</p>
        </div>
    </footer>
</body>
</html> 