<?php
include 'connect.php';
include 'session_start.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

// 배송 정보
$recipient = $_POST['recipient'];
$phone = $_POST['phone1'] . '-' . $_POST['phone2'] . '-' . $_POST['phone3'];
$postcode = $_POST['postcode'];
$road = $_POST['road_address'];
$detail = $_POST['detail_address'];
$address = "($postcode) $road $detail";

$items = [];
$total_price = 0;

// 🚀 [1] 장바구니 도서 불러오기
$cart_sql = "
    SELECT c.book_id, c.quantity, b.price
    FROM cart c
    JOIN books b ON c.book_id = b.id
    WHERE c.user_id = $user_id
";
$cart_result = $conn->query($cart_sql);

if ($cart_result) {
    while ($row = $cart_result->fetch_assoc()) {
        $book_id = $row['book_id'];
        $quantity = $row['quantity'];
        $price = $row['price'];

        $items[$book_id] = [
            'quantity' => $quantity,
            'price' => $price
        ];
        $total_price += $price * $quantity;
    }
}

// 🚀 [2] 바로구매 처리 (옵션)
if (isset($_POST['direct_buy']) && isset($_POST['book_id'], $_POST['price'], $_POST['quantity'])) {
    $book_id = $_POST['book_id'];
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']);

    if (isset($items[$book_id])) {
        $items[$book_id]['quantity'] += $quantity;
    } else {
        $items[$book_id] = [
            'quantity' => $quantity,
            'price' => $price
        ];
    }
    $total_price += $price * $quantity;
}


// 1. 회원별 order_seq 계산
$seq_sql = "SELECT MAX(order_seq) AS max_seq FROM orders WHERE user_id = $user_id";
$seq_result = $conn->query($seq_sql);
$max_seq = $seq_result->fetch_assoc()['max_seq'] ?? 0;
$order_seq = $max_seq + 1;

// 2. 주문 저장 (order_seq 포함)
$order_sql = "
    INSERT INTO orders (user_id, order_seq, recipient, phone, address, total_price, created_at, status)
    VALUES ($user_id, $order_seq, '$recipient', '$phone', '$address', $total_price, NOW(), 'pending')
";
$conn->query($order_sql);
$order_id = $conn->insert_id;

// 3. token = user_id-order_seq
$token = $user_id . '-' . $order_seq;
$update_token_sql = "UPDATE orders SET token = '$token' WHERE id = $order_id";
$conn->query($update_token_sql);

// 4. 주문 상세 저장 (변경 없음)
foreach ($items as $book_id => $info) {
    $quantity = $info['quantity'];
    $price = $info['price'];

    $item_sql = "
        INSERT INTO order_items (order_id, book_id, quantity, price)
        VALUES ($order_id, '$book_id', $quantity, $price)
    ";
    $conn->query($item_sql);
}

// 5. 장바구니 비우기 (변경 없음)
$delete_cart_sql = "DELETE FROM cart WHERE user_id = $user_id";
$conn->query($delete_cart_sql);

// 6. 완료 메시지
echo "<script>alert('주문이 완료되었습니다.'); location.href='order_complete.php?token=$token';</script>";
exit;
?>