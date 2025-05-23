<?php 
require_once 'session_start.php';
require_once 'connect.php';
require_once 'header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// 사용자 정보 가져오기
$user_sql = "SELECT name, email FROM users WHERE id = $user_id";
$user_result = $conn->query($user_sql);
$user = $user_result->fetch_assoc();
$name = $user['name'];
$email = $user['email'];

// 주문 내역 조회
$order_sql = "
    SELECT o.id AS order_id, o.order_seq, o.token, o.created_at,
           b.id AS book_id, b.title, b.author, b.price, b.image_path, oi.quantity
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
    $orders[$row['order_id']]['order_seq'] = $row['order_seq'];  // ✅ 추가
    $orders[$row['order_id']]['token'] = $row['token'];
    $orders[$row['order_id']]['items'][] = $row;

}

// 회원정보 수정
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_profile"])) {
    $new_name = trim($_POST["edit_name"]);
    $new_email = trim($_POST["edit_email"]);
    $current_pw = trim($_POST["current_password"]);
    $new_pw = trim($_POST["new_password"]);

    if (!empty($new_name) && !empty($new_email)) {
        // 기존 비밀번호 가져오기
        $pw_check_sql = "SELECT password FROM users WHERE id = $user_id";
        $pw_result = $conn->query($pw_check_sql);
        $pw_row = $pw_result->fetch_assoc();
        $stored_pw = $pw_row['password'];

        if ($current_pw === $stored_pw) {
            $update_sql = "UPDATE users SET name = '$new_name', email = '$new_email'";

            // 새 비밀번호가 있으면 업데이트
            if (!empty($new_pw)) {
                $update_sql .= ", password = '$new_pw'";
            }

            $update_sql .= " WHERE id = $user_id";

            if ($conn->query($update_sql)) {
                echo "<script>alert('회원 정보가 수정되었습니다.'); location.href='mypage.php';</script>";
                exit;
            } else {
                echo "<script>alert('수정 실패');</script>";
            }
        } else {
            echo "<script>alert('현재 비밀번호가 일치하지 않습니다.');</script>";
        }
    }
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

<main>
    <div class="mypage-container">
        <div class="mypage-content">
            <aside class="sidebar">
                <div class="user-info">
                    <div class="user-avatar"><i class="fas fa-user"></i></div>
                    <h3><?= $name ?></h3>
                    <p><?= $email ?></p>
                </div>
                <ul class="sidebar-menu">
                    <li><button onclick="showTab('orders')">주문 내역</button></li>
                    <li><button onclick="showTab('reviews')">내가 쓴 리뷰</button></li>
                    <li><button onclick="showTab('profile')">회원 정보 수정</button></li>
                    <li><button onclick="location.href='withdraw.php'">회원 탈퇴</button></li>
                </ul>
            </aside>

            <div class="main-content">
                <div id="orders" class="tab-content">
                    <h3>주문 내역</h3>
                    <?php foreach ($orders as $order_id => $order): ?>
                        <div class="order-item">
                            <div class="order-header">
                                <span class="order-number">주문번호: <?= $order['order_seq'] ?></span>
                                <span class="order-date"><?= $order['created_at'] ?></span>
                            </div>
                            <div class="order-details">
                                <?php foreach ($order['items'] as $item): ?>
                                    <div class="order-product">
                                        <img class="product-image" src="<?= $item['image_path'] ?>" alt="표지">
                                        <div class="product-info">
                                            <div class="product-title">
                                                <a href="../category/book_detail.php?id=<?= $item['book_id'] ?>"><?= $item['title'] ?></a>
                                            </div>
                                            <div class="product-author"><?= $item['author'] ?></div>
                                            <div class="product-price"><?= number_format($item['price']) ?>원 x <?= $item['quantity'] ?>개</div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="order-actions" style="text-align:right; margin-top:10px;">
                                <a href="order_detail.php?token=<?= $order['token'] ?>">상세보기</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div id="reviews" class="tab-content" style="display:none;">
                    <h3>내가 쓴 리뷰</h3>
                    <?php
                    $review_sql = "
                        SELECT r.id AS review_id, r.rating, r.content, r.created_at,
                               b.id AS book_id, b.title, b.author, b.image_path
                        FROM reviews r
                        JOIN books b ON r.book_id = b.id
                        WHERE r.user_id = $user_id
                    ";
                    $review_result = $conn->query($review_sql);
                    while ($review = $review_result->fetch_assoc()):
                    ?>
                        <div class="review-item">
                            <img class="product-image" src="<?= $review['image_path'] ?>" alt="표지">
                            <div class="review-content">
                                <a href="../category/book_detail.php?id=<?= $review['book_id'] ?>"><?= $review['title'] ?></a>
                                <div class="review-rating">
                                    <?= str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']) ?>
                                </div>
                                <p><?= $review['content'] ?></p>
                                <p><?= $review['created_at'] ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <div id="profile" class="tab-content" style="display:none;">
                    <h3>회원 정보 수정</h3>
                    <form method="POST">
                        <label>이름:<br><input type="text" name="edit_name" value="<?= $name ?>"></label><br>
                        <label>이메일:<br><input type="email" name="edit_email" value="<?= $email ?>"></label><br>
                        <label>현재 비밀번호:<br><input type="password" name="current_password" required></label><br>
                        <label>새 비밀번호 (변경 시 입력):<br><input type="password" name="new_password"></label><br>
                        <button type="submit" name="update_profile" class="primary-btn">정보 수정</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function showTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
    document.getElementById(tabId).style.display = 'block';
}
</script>

<?php require_once 'footer.php'; ?>
</body>
</html>