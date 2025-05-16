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

// 1. 장바구니 도서 불러오기
$cart_sql = "
    SELECT c.book_id, c.quantity, b.price
    FROM cart c
    JOIN books b ON c.book_id = b.id
    WHERE c.user_id = ?
";
$cart_stmt = $conn->prepare($cart_sql);
$cart_stmt->bind_param("i", $user_id);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();

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

// 2. 바로 구매 도서가 있을 경우 추가
if (isset($_POST['direct_buy']) && isset($_POST['book_id'], $_POST['price'], $_POST['quantity'])) {
    $book_id = $_POST['book_id'];
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']);

    if (isset($items[$book_id])) {
        // 장바구니에 이미 존재하면 수량 합산
        $items[$book_id]['quantity'] += $quantity;
    } else {
        // 새 항목 추가
        $items[$book_id] = [
            'quantity' => $quantity,
            'price' => $price
        ];
    }
    $total_price += $price * $quantity;
}

// 3. 주문 저장
$order_sql = "INSERT INTO orders (user_id, recipient, phone, address, total_price, created_at, status)
              VALUES (?, ?, ?, ?, ?, NOW(), 'pending')";
$order_stmt = $conn->prepare($order_sql);
$order_stmt->bind_param("isssd", $user_id, $recipient, $phone, $address, $total_price);
$order_stmt->execute();
$order_id = $conn->insert_id;

// 4. 주문 상세 저장
$item_sql = "INSERT INTO order_items (order_id, book_id, quantity, price) VALUES (?, ?, ?, ?)";
$item_stmt = $conn->prepare($item_sql);
foreach ($items as $book_id => $info) {
    $quantity = $info['quantity'];
    $price = $info['price'];
    $item_stmt->bind_param("isii", $order_id, $book_id, $quantity, $price);  // book_id는 string
    $item_stmt->execute();
}

// 5. 장바구니 비우기 (바로 구매 도서가 있더라도 전체 비움)
$delete_cart_sql = "DELETE FROM cart WHERE user_id = ?";
$delete_stmt = $conn->prepare($delete_cart_sql);
$delete_stmt->bind_param("i", $user_id);
$delete_stmt->execute();

// 6. 완료 메시지
echo "<script>alert('주문이 완료되었습니다.'); location.href='order_complete.php?order_id=$order_id';</script>";
exit;
?>