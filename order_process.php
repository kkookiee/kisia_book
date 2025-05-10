<?php
include 'connect.php';
include 'session_start.php';


$user_id = $_SESSION['user_id'];

//post로 전달받은 배송 정보
$recipient = $_POST['recipient'];
$phone = $_POST['phone'];
$address = $_POST['address'];

// 장바구니 조회
$sql = "
    SELECT c.*, b.title, b.price
    FROM cart c
    JOIN books b ON c.book_id = b.id
    WHERE c.user_id = $user_id
";

$result = $conn->query($sql);
if($result->num_rows === 0) {
    echo"장바구니가 비어있습니다.";
    exit;
}
$total_price = 0;
$items = [];

// $result를 가져온 뒤 데이터를 반복문으로 $items에 저장
while($row = $result->fetch_assoc()) {
    $book_id = $row["book_id"];
    $quantity = $row["quantity"];
    $price = $row["price"];
    
    $total_price += $price * $quantity;
    $items[] = ["book_id"=> $book_id, "quantity"=> $quantity,"price"=> $price];
}


$orders_sql = "INSERT INTO orders(user_id, recipient, phone, address, total_price, created_at)
              VALUES($user_id, '$recipient', '$phone', '$address', $total_price, NOW())";
$conn->query($orders_sql);
$order_id = $conn->insert_id;

// order_items 테이블에 INSERT
foreach ($items as $item) {
    $book_id = $item["book_id"];
    $quantity = $item["quantity"];
    $price = $item["price"];

    $item_sql = "INSERT INTO order_items (order_id, book_id, quantity, price)
                 VALUES ($order_id, '$book_id', $quantity, $price)";
    $conn->query($item_sql);
}


// 장바구니 비우기
$delete_cart_sql = "DELETE FROM cart WHERE user_id = $user_id";
$conn->query($delete_cart_sql);

// 완료 메시지 및 리디렉션
// 주문이 성공적으로 완료되어 리디렉션 되면 html 실행하지 않음
echo "<script>alert('주문이 완료되었습니다.'); location.href='order_complete.php';</script>";
exit;
?>