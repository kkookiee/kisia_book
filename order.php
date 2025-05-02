<?php
include 'php/db_connect.php';
include 'php/session_check.php';

$user_id = $_SESSION['user_id'];

// 장바구니 목록 조회
$sql = "
    SELECT c.*, b.title, b.price
    FROM cart c
    JOIN books b ON c.book_id = b.id
    WHERE c.user_id = $user_id
";

$result = $conn->query($sql);
if ($result->num_rows === 0) {
    echo "장바구니가 비어있습니다.";
    exit;
}

$total_price = 0;
$items = [];

while ($row = $result->fetch_assoc()) {
    $book_id = $row["book_id"];
    $quantity = $row["quantity"];
    $price = $row["price"];

    $total_price += $price * $quantity;
    $items[] = ["bookid" => $book_id, "quantity" => $quantity, "price" => $price];
}

// orders 테이블에 INSERT
$order_sql = "INSERT INTO orders (user_id, total_price, created_at)
              VALUES ($user_id, $total_price, NOW())";
$conn->query($order_sql);
$order_id = $conn->insert_id;

// order_items 테이블에 INSERT
foreach ($items as $item) {
    $book_id = $item["bookid"];
    $quantity = $item["quantity"];
    $price = $item["price"];

    $item_sql = "INSERT INTO order_items (order_id, book_id, quantity, price)
                 VALUES ($order_id, $book_id, $quantity, $price)";
    $conn->query($item_sql);
}

// 장바구니 비우기
$delete_cart_sql = "DELETE FROM cart WHERE user_id = $user_id";
$conn->query($delete_cart_sql);

// 완료 메시지 및 리디렉션
// 주문이 성공적으로 완료되어 리디렉션 되면 html 실행하지 않음
echo "<script>alert('주문이 완료되었습니다.'); location.href='mypage.php';</script>";
exit;
?>

<!--
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>주문하기 - KISIA_bookStore</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="top-header">
            <div class="container">
                <div class="top-links">
                    <a href="#">로그인</a>
                    <a href="#">회원가입</a>
                    <a href="#">고객센터</a>
                </div>
            </div>
        </div>
        <nav>
            <div class="container">
                <div class="nav-wrapper">
                    <div class="logo">
                        <h1>온라인 서점</h1>
                    </div>
                    <div class="search-box">
                        <input type="text" placeholder="검색어를 입력하세요">
                        <button type="button"><i class="fas fa-search"></i></button>
                    </div>
                    <ul class="nav-links">
                        <li><a href="index.html">홈</a></li>
                        <li><a href="books.html">도서</a></li>
                        <li><a href="cart.html">장바구니</a></li>
                        <li><a href="mypage.html">마이페이지</a></li>
                        <li><a href="board.html">게시판/리뷰</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <div class="container">
            <h2>주문하기</h2>
            <p>주문할 상품과 배송 정보를 확인하고 결제해 주세요.</p>
        </div>
    </main>
    <footer>
        <div class="container">
            <p>&copy; 2024 온라인 서점. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
__>