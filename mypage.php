<?php 
require_once 'session_start.php';
require_once 'connect.php';

// 로그인된 사용자의 정보를 데이터베이스에서 가져옴
if (!empty($id)) {
    $stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();

    $name = $user_data['name'];
    $email = $user_data['email'];}

$sql = "
    SELECT o.id AS order_id, o.created_at, b.id AS book_id, b.title, b.author, b.price, oi.quantity
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN books b ON oi.book_id = b.id
    WHERE o.user_id = ?
    ORDER BY o.created_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$current_order_id = null;

    // 주문별로 묶기
    while ($row = $result->fetch_assoc()) {
        $orders[$row['order_id']]['created_at'] = $row['created_at'];
        $orders[$row['order_id']]['items'][] = $row;
    }
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>마이페이지 - 온라인 서점</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/mypage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <div class="mypage-container">
            <?php
            if(!empty($id)): ?>
            <div class="mypage-content">
                <aside class="sidebar">
                    <div class="user-info">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3 class="user-name"><?= ($name)?></h3>
                        <p class="user-email"><?= ($email)?></p>
                    </div>
                    <ul class="sidebar-menu">
                        <li><a href="#" class="active"><i class="fas fa-shopping-bag"></i> 주문 내역</a></li>
                        <li><a href="#"><i class="fas fa-heart"></i> 찜 목록</a></li>
                        <li><a href="#"><i class="fas fa-comment"></i> 내가 쓴 리뷰</a></li>
                        <li><a href="#"><i class="fas fa-cog"></i> 회원 정보 수정</a></li>
                        <li><a href="#"><i class="fas fa-sign-out-alt"></i> 로그아웃</a></li>
                    </ul>
                </aside>
                <div class="main-content">
                <div class="content-header"><h3>주문 내역</h3></div>
                <div class="order-list">
                <?php foreach ($orders as $order_id => $order): ?>
    <div class="order-item">
        <div class="order-header">
            <span class="order-number">주문번호: <?= $order_id ?></span>
            <span class="order-date"><?= $order['created_at'] ?></span>
            <span class="order-status status-completed">배송완료</span>
        </div>

        <?php foreach ($order['items'] as $item): ?>
        <div class="order-details">
            <div class="order-product">
            <img src="<?= $item['image_path'] ?? 'images/default.jpg' ?>" alt="도서 이미지" class="product-image">
                       <div class="product-info">
                    <h4 class="product-title"><?= ($item['title']) ?></h4>
                    <p class="product-author"><?= ($item['author']) ?></p>
                    <p class="product-price"><?= number_format($item['price']) ?>원</p>
                </div>
            </div>
            <div class="order-actions">
            <?php if (!empty($item['book_id'])): ?>
             <a href="review_write.php?book_id=<?= $item['book_id'] ?>" class="action-btn primary-btn">리뷰 작성</a>
            <?php endif; ?>            
        </a>
            <a href="order_detail.php?order_id=<?= $order_id ?>" class="action-btn secondary-btn">주문 상세
            </a>
        </div>
        </div>
        <?php endforeach; ?>
        </div>
            <?php endforeach; ?>
                    <?php else: ?>
                        <script>
                            alert('로그인 후 이용해주세요.');
                            location.href = 'login.php';
                        </script>
                    <?php endif; ?>
    </div>
</main>
</body>
</html>