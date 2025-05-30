<?php 
require_once 'session_start.php';
require_once 'connect.php';
require_once 'header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$name = '';
$email = '';
$point = 0;

// 사용자 정보 조회
$stmt = $conn->prepare("SELECT name, email, point FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    $name = $user['name'];
    $email = $user['email'];
    $point = $user['point'];
}
$stmt->close();

// 주문 내역 조회 (token 제거됨)
$order_sql = "
    SELECT o.id AS order_id, o.order_seq, o.created_at,
           b.id AS book_id, b.title, b.author, b.price, b.image_path, oi.quantity
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN books b ON oi.book_id = b.id
    WHERE o.user_id = ?
    ORDER BY o.created_at DESC
";
$stmt = $conn->prepare($order_sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$order_result = $stmt->get_result();

$orders = [];
while ($row = $order_result->fetch_assoc()) {
    $orders[$row['order_id']]['created_at'] = $row['created_at'];
    $orders[$row['order_id']]['order_seq'] = $row['order_seq'];
    $orders[$row['order_id']]['items'][] = $row;
}
$stmt->close();

// 회원정보 수정 처리
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_profile"])) {
    $new_name = trim($_POST["edit_name"]);
    $new_email = trim($_POST["edit_email"]);
    $current_pw = trim($_POST["current_password"]);
    $new_pw = trim($_POST["new_password"]);

    if (!empty($new_name) && !empty($new_email)) {
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $pw_row = $result->fetch_assoc();
        $stored_pw = $pw_row['password'];

        if (password_verify($current_pw, $stored_pw)) {
            $new_hashed_pw = !empty($new_pw) ? password_hash($new_pw, PASSWORD_DEFAULT) : $stored_pw;

            $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
            $stmt->bind_param('sssi', $new_name, $new_email, $new_hashed_pw, $user_id);

            if ($stmt->execute()) {
                echo "<script>alert('회원 정보가 수정되었습니다.'); location.href='mypage.php';</script>";
                exit;
            } else {
                echo "<script>alert('수정 실패'); window.location.hash = '#profile'; location.reload();</script>";
                exit;
            }
        } else {
            echo "<script>alert('현재 비밀번호가 일치하지 않습니다.'); window.location.hash = '#profile'; location.reload();</script>";
            exit;
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
                    <h3><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></h3>
                    <p><?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?></p>
                </div>
                <ul class="sidebar-menu">
                    <li><a href="#orders" onclick="showTab('orders')">주문 내역</a></li>
                    <li><a href="#reviews" onclick="showTab('reviews')">내가 쓴 리뷰</a></li>
                    <li><a href="#profile" onclick="showTab('profile')">회원 정보 수정</a></li>
                    <li><a href="#point" onclick="showTab('point')">포인트 관리</a></li>
                    <li><a href="withdraw.php">회원 탈퇴</a></li>
                </ul>
            </aside>

            <div class="main-content">
                <!-- 주문 내역 -->
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
                                        <img class="product-image" src="<?= htmlspecialchars($item['image_path']) ?>" alt="표지">
                                        <div class="product-info">
                                            <div class="product-title">
                                                <a href="../category/book_detail.php?id=<?= $item['book_id'] ?>">
                                                    <?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?>
                                                </a>
                                            </div>
                                            <div class="product-author"><?= htmlspecialchars($item['author'], ENT_QUOTES, 'UTF-8') ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="order-actions" style="text-align:right;">
                                <a href="order_detail.php?order_id=<?= $order_id ?>">상세보기</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- 내가 쓴 리뷰 -->
                <div id="reviews" class="tab-content" style="display:none;">
                    <h3>내가 쓴 리뷰</h3>
                    <?php
                    $review_sql = "
                        SELECT r.id AS review_id, r.rating, r.content, r.created_at,
                               b.id AS book_id, b.title, b.author, b.image_path
                        FROM reviews r
                        JOIN books b ON r.book_id = b.id
                        WHERE r.user_id = ?
                    ";
                    $stmt = $conn->prepare($review_sql);
                    $stmt->bind_param('i', $user_id);
                    $stmt->execute();
                    $review_result = $stmt->get_result();

                    while ($review = $review_result->fetch_assoc()):
                    ?>
                        <div class="review-item">
                            <img class="product-image" src="<?= htmlspecialchars($review['image_path']) ?>" alt="표지">
                            <div class="review-content">
                                <a href="../category/book_detail.php?id=<?= $review['book_id'] ?>">
                                    <?= htmlspecialchars($review['title'], ENT_QUOTES, 'UTF-8') ?>
                                </a>
                                <div class="review-rating">
                                    <?= str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']) ?>
                                </div>
                                <p><?= htmlspecialchars($review['content'], ENT_QUOTES, 'UTF-8') ?></p>
                                <p><?= $review['created_at'] ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- 회원 정보 수정 -->
                <div id="profile" class="tab-content" style="display:none;">
                    <h3>회원 정보 수정</h3>
                    <form method="POST">
                        <label>이름:<br><input type="text" name="edit_name" value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"></label><br>
                        <label>이메일:<br><input type="email" name="edit_email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"></label><br>
                        <label>현재 비밀번호:<br><input type="password" name="current_password" required></label><br>
                        <label>새 비밀번호 (변경 시 입력):<br><input type="password" name="new_password"></label><br>
                        <button type="submit" name="update_profile" class="primary-btn">정보 수정</button>
                    </form>
                </div>

                <!-- 포인트 관리 -->
                <div id="point" class="tab-content" style="display:none;">
                    <h3>포인트 관리</h3>
                    <div class="profile-form-container">
                        <p><strong>현재 보유 포인트 :</strong> <?= htmlspecialchars(number_format($point), ENT_QUOTES, 'UTF-8') ?> P</p>
                        <form action="./charge_point_action.php" method="post">
                            <div class="mypage-form-group">
                                <label for="charge_amount">충전할 포인트</label>
                                <input type="number" name="point" id="point" step="100" min="0" required>
                            </div>
                            <button type="submit" class="save-btn">충전</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function showTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
    const tab = document.getElementById(tabId);
    if (tab) tab.style.display = 'block';
}

window.addEventListener('DOMContentLoaded', () => {
    const hash = window.location.hash.substring(1);
    if (['profile', 'reviews', 'point'].includes(hash)) {
        showTab(hash);
    } else {
        showTab('orders');
    }
});
</script>

<?php require_once 'footer.php'; ?>
</body>
</html>
