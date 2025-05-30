<?php
ob_start(); // 출력 버퍼링 시작

include 'session_start.php';
include 'connect.php';

$buy_now = isset($_POST['buy_now']) && $_POST['buy_now'] == '1';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id']) && !isset($_POST['cart_id'])) {
    $book_id = $_POST['book_id'];
    $quantity = $_POST['quantity'] ?? 1;

    $check_stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND book_id = ?");
    $check_stmt->bind_param('is', $user_id, $book_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $existing = $check_result->fetch_assoc();
        $new_qty = $existing['quantity'] + $quantity;

        $cart_id = $existing['id']; // cart_id 추출

        $update_stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $update_stmt->bind_param("iii", $new_qty, $cart_id, $user_id);
        $update_stmt->execute();
        $update_stmt->close();
    } else {
        $insert_stmt = $conn->prepare("INSERT INTO cart (user_id, book_id, quantity) VALUES (?, ?, ?)");
        $insert_stmt->bind_param('isi', $user_id, $book_id, $quantity);
        $insert_stmt->execute();
        $insert_stmt->close();
    }

    $check_stmt->close();

    if ($buy_now) {
        header("Location: order_confirm.php?book_id=" . urlencode($book_id) . "&quantity=" . urlencode($quantity));
        exit;
    }

    header("Location: cart.php");
    exit;
}

// 수량 변경 (보안 강화: user_id 확인)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_quantity"])) {
    $cart_id = (int)$_POST["cart_id"];
    $quantity = (int)$_POST["quantity"];

    if ($quantity >= 1 && $quantity <= 99) {
        $update_stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $update_stmt->bind_param('iii', $quantity, $cart_id, $user_id);
        $update_stmt->execute();
        $update_stmt->close();
    } else {
        echo "<script>alert('수량은 1~99 사이여야 합니다.'); history.back();</script>";
        exit;
    }

    header("Location: cart.php");
    exit;
}

// 항목 삭제 (보안 강화: user_id 확인)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_item"])) {
    $cart_id = (int)$_POST["cart_id"];
    $delete_stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $delete_stmt->bind_param('ii', $cart_id, $user_id);
    $delete_stmt->execute();
    $delete_stmt->close();
    header("Location: cart.php");
    exit;
}

// 장바구니 조회
$stmt = $conn->prepare("
    SELECT 
        c.id AS cart_id,
        c.quantity,
        c.created_at,
        b.title,
        b.price,
        b.image_path
    FROM cart c
    JOIN books b ON c.book_id = b.id
    WHERE c.user_id = ?
    ORDER BY c.created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total_price = 0;
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>장바구니 - KISIA_bookStore</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/cart.css">
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
                    <img src="<?= htmlspecialchars($row['image_path'], ENT_NOQUOTES, 'UTF-8') ?>" alt="도서 이미지" class="cart-thumb">
                    <div class="cart-info-detail">
                        <span class="cart-title">[도서] <?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?></span>
                        <div class="cart-meta">
                            <span class="cart-price-sale"><?= number_format($row['price']) ?>원</span>
                            <span class="cart-point"><?= number_format($row['price'] * 0.05) ?>포인트</span>
                        </div>
                    </div>
                </td>
                <td class="cart-qty">
                    <form method="post" action="cart.php" class="inline-form">
                        <input type="hidden" name="cart_id" value="<?= htmlspecialchars($row['cart_id'], ENT_QUOTES, 'UTF-8') ?>">
                        <input type="number" name="quantity" value="<?= htmlspecialchars($row['quantity'], ENT_QUOTES, 'UTF-8') ?>" min="1" max="99" required />
                        <button type="submit" name="update_quantity" class="cart-update-btn">변경</button>
                    </form>
                </td>
                <td class="cart-sum">
                    <span><?= number_format($item_total) ?>원</span>
                    <form method="post" action="cart.php" class="inline-form">
                        <input type="hidden" name="cart_id" value="<?= htmlspecialchars($row['cart_id'], ENT_QUOTES, 'UTF-8') ?>">
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
<?php require_once 'footer.php'; ?>
</body>
</html>
