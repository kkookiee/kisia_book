<?php
include 'connect.php';
include 'session_start.php';

$user_id = $_SESSION['user_id'];

// POST 요청 처리 (수량 변경 / 삭제)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cart_id = intval($_POST["cart_id"]);

    if (isset($_POST["update_quantity"])) {
        $quantity = intval($_POST["quantity"]);
        if ($quantity > 0) {
            $update_sql = "UPDATE cart SET quantity = $quantity WHERE id = $cart_id";
            $conn->query($update_sql);
        }
    }

    if (isset($_POST["delete_item"])) {
        $delete_sql = "DELETE FROM cart WHERE id = $cart_id";
        $conn->query($delete_sql);
    }

    header("Location: cart.php"); // 새로고침 중복 방지
    exit;
}

// 장바구니 조회
$sql = "
    SELECT c.*, b.title, b.price
    FROM cart c
    JOIN books b ON c.book_id = b.id
    WHERE c.user_id = $user_id
";

$result = $conn->query($sql);
if (!$result) {
    echo "query error: " . $conn->error;
}

$total_price = 0;
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>장바구니 - KISIA_bookStore</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <div class="cart-container">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th style="width:40%;">상품정보</th>
                        <th style="width:15%;">수량</th>
                        <th style="width:15%;">상품금액</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $result->fetch_assoc()):
                    $item_total = $row["price"] * $row["quantity"];
                    $total_price += $item_total;
                ?>
                <tr>
                    <td class="cart-product-info">
                        <img src="/images/book1.jpg" alt="도서 이미지" class="cart-thumb" />
                        <div class="cart-info-detail">
                            <span class="cart-title">[도서] <?= ($row['title']) ?></span>
                            <div class="cart-meta">
                                <span class="cart-price-sale"><?= number_format($row['price']) ?>원</span>
                                <span class="cart-point"><?= number_format($row['price'] * 0.05) ?>포인트</span>
                            </div>
                        </div>
                    </td>
                    <td class="cart-qty">
                        <form method="post" action="cart.php" class="inline-form">
                            <input type="hidden" name="cart_id" value="<?= $row['id'] ?>">
                            <input type="number" name="quantity" value="<?= $row['quantity'] ?>" min="1" max="99" />
                            <button type="submit" name="update_quantity" class="cart-update-btn">변경</button>
                        </form>
                    </td>
                    <td class="cart-sum">
                        <span><?= number_format($item_total) ?>원</span>
                        <form method="post" action="cart.php" class="inline-form">
                            <input type="hidden" name="cart_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="delete_item" class="cart-delete-btn">삭제</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>

            <div class="cart-summary">
                <div class="summary-item total">
                    <span>총 결제 금액</span>
                    <span><?= number_format($total_price) ?>원</span>
                </div>
                <form action="order_confirm.php" method="post">
                    <button type="submit" name="order_submit" class="checkout-btn">주문하기</button>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 KISIA_bookStore. All rights reserved.</p>
    </footer>
</body>
</html>
