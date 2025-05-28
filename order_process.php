<?php
require_once 'connect.php';
require_once 'session_start.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

$recipient = strip_tags(trim($_POST['recipient']));
$phone = $_POST['phone1'] . '-' . $_POST['phone2'] . '-' . $_POST['phone3'];
$postcode = strip_tags(trim($_POST['postcode']));
$road = strip_tags(trim($_POST['road_address']));
$detail = strip_tags(trim($_POST['detail_address']));
$address = "($postcode) $road $detail";

$items = [];
$total_price = 0;

$conn->begin_transaction();

try {
    // 장바구니 항목 불러오기
    $stmt = $conn->prepare("SELECT c.book_id, c.quantity, b.price FROM cart c JOIN books b ON c.book_id = b.id WHERE c.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $book_id = $row['book_id'];
        $quantity = $row['quantity'];
        $price = $row['price'];

        $items[$book_id] = ['quantity' => $quantity, 'price' => $price];
        $total_price += $price * $quantity;
    }
    $stmt->close();

    // 바로 구매 처리 (선택 사항)
    if (isset($_POST['direct_buy'], $_POST['book_id'], $_POST['quantity'])) {
        $book_id = $_POST['book_id'];
        $quantity = intval($_POST['quantity']);

        // 가격은 반드시 DB에서 다시 조회
        $price_stmt = $conn->prepare("SELECT price FROM books WHERE id = ?");
        $price_stmt->bind_param("s", $book_id);
        $price_stmt->execute();
        $price_result = $price_stmt->get_result();
        if ($book = $price_result->fetch_assoc()) {
            $price = $book['price'];
            if (isset($items[$book_id])) {
                $items[$book_id]['quantity'] += $quantity;
            } else {
                $items[$book_id] = ['quantity' => $quantity, 'price' => $price];
            }
            $total_price += $price * $quantity;
        }
        $price_stmt->close();
    }

    // 주문 번호(order_seq) 생성
    $stmt = $conn->prepare("SELECT MAX(order_seq) FROM orders WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($max_seq);
    $stmt->fetch();
    $stmt->close();

    $order_seq = ($max_seq ?? 0) + 1;

    // 주문 저장
    $stmt = $conn->prepare("INSERT INTO orders (user_id, order_seq, recipient, phone, address, total_price, created_at, status) VALUES (?, ?, ?, ?, ?, ?, NOW(), 'pending')");
    $stmt->bind_param("iisssd", $user_id, $order_seq, $recipient, $phone, $address, $total_price);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    // 토큰 저장
    $token = $user_id . '-' . $order_seq;
    $stmt = $conn->prepare("UPDATE orders SET token = ? WHERE id = ?");
    $stmt->bind_param("si", $token, $order_id);
    $stmt->execute();
    $stmt->close();

    // 주문 상세 저장
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, book_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($items as $book_id => $info) {
        $quantity = $info['quantity'];
        $price = $info['price'];
        $stmt->bind_param("isid", $order_id, $book_id, $quantity, $price);
        $stmt->execute();
    }
    $stmt->close();

    // 장바구니 비우기
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    $conn->commit();
    echo "<script>alert('주문이 완료되었습니다.'); location.href='order_complete.php?token=" . urlencode($token) . "';</script>";
    exit;
} catch (Exception $e) {
    $conn->rollback();
    error_log("주문 처리 실패: " . $e->getMessage());
    echo "<script>alert('주문 처리 중 오류가 발생했습니다.'); location.href='cart.php';</script>";
    exit;
}
?>