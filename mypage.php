<?php 
require_once 'session_start.php'; 
require_once 'connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$user_sql = "SELECT name, email FROM users WHERE id = $user_id";
$user_result = $conn->query($user_sql);
$user = $user_result->fetch_assoc();
$name = $user['name'];
$email = $user['email'];

$order_sql = "
    SELECT o.id AS order_id, o.created_at, b.id AS book_id, b.title, b.author, b.price, b.image_path, oi.quantity
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN books b ON oi.book_id = b.id
    WHERE o.user_id = $user_id
    ORDER BY o.created_at DESC
";
$order_result = $conn->query($order_sql);
$orders = [];
while ($row = $order_result->fetch_assoc()) {
    $orders[$row['order_id']]['created_at'] = $row['created_at'];
    $orders[$row['order_id']]['items'][] = $row;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_profile"])) {
    $new_name = trim($_POST["edit_name"]);
    $new_email = trim($_POST["edit_email"]);
    if (!empty($new_name) && !empty($new_email)) {
        $update_sql = "UPDATE users SET name = '$new_name', email = '$new_email' WHERE id = $user_id";
        if ($conn->query($update_sql)) {
            echo "<script>alert('회원 정보가 수정되었습니다.'); location.href='mypage.php';</script>";
            exit;
        } else {
            echo "<script>alert('수정 실패');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>마이페이지 - 온라인 서점</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/mypage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php require_once 'header.php'; ?>

<div class="admin-container">
    <aside class="sidebar">
        <div class="user-info">
            <div class="user-avatar"><i class="fas fa-user"></i></div>
            <h3><?= $name ?></h3>
            <p><?= $email ?></p>
        </div>
        <ul class="sidebar-menu">
            <li><a href="#" class="btn" onclick="showTab('orders')">주문 내역</a></li>
            <li><a href="#" class="btn" onclick="showTab('reviews')">내가 쓴 리뷰</a></li>
            <li><a href="#" class="btn" onclick="showTab('profile')">회원 정보 수정</a></li>
            <li><a href="withdraw.php" class="btn">회원 탈퇴</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div id="orders" class="tab-content">
            <h3>주문 내역</h3>
            <?php foreach ($orders as $order_id => $order): ?>
                <div>
                    <strong>주문번호:</strong> <?= $order_id ?> /
                    <strong>날짜:</strong> <?= $order['created_at'] ?>
                </div>
                <?php foreach ($order['items'] as $item): ?>
                    <div>
                        <img src="<?= $item['image_path'] ?>" width="50">
                        <a href="book_detail.php?id=<?= $item['book_id'] ?>"><?= $item['title'] ?></a>
                        (<?= $item['author'] ?>) / <?= number_format($item['price']) ?>원 x <?= $item['quantity'] ?>개
                    </div>
                <?php endforeach; ?>
                <hr>
            <?php endforeach; ?>
        </div>

        <div id="reviews" class="tab-content" style="display:none;">
            <h3>내가 쓴 리뷰</h3>
            <?php
            $review_sql = "SELECT r.id AS review_id, r.rating, r.content, r.created_at, b.id AS book_id, b.title, b.author, b.image_path FROM reviews r JOIN books b ON r.book_id = b.id WHERE r.user_id = $user_id";
            $review_result = $conn->query($review_sql);
            while ($review = $review_result->fetch_assoc()): ?>
                <div>
                    <img src="<?= $review['image_path'] ?>" width="50">
                    <a href="book_detail.php?id=<?= $review['book_id'] ?>"><?= $review['title'] ?></a>
                    <p><?= str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']) ?></p>
                    <p><?= ($review['content']) ?></p>
                    <p><?= $review['created_at'] ?></p>
                </div>
            <?php endwhile; ?>
        </div>

        <div id="profile" class="tab-content" style="display:none;">
            <h3>회원 정보 수정</h3>
            <form method="POST">
                <label>이름: <input type="text" name="edit_name" value="<?= $name ?>"></label><br>
                <label>이메일: <input type="email" name="edit_email" value="<?= $email ?>"></label><br>
                <button type="submit" name="update_profile">수정하기</button>
            </form>
        </div>
    </main>
</div>

<script>
function showTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
    document.getElementById(tabId).style.display = 'block';
}
</script>

<?php require_once 'footer.php'; ?>
</body>
</html>
