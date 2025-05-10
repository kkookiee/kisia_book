<?php 
require_once 'session_start.php';
require_once 'connect.php';

<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
if (!empty($id)) {
    $stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    $name = $user_data['name'];
=======
// 로그인된 사용자의 정보를 데이터베이스에서 가져옴
if(!empty($user_id)) {
    $stmt = $conn->prepare("SELECT user_name, email FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    
    $user_name = $user_data['user_name'];
>>>>>>> Stashed changes
=======
// 로그인된 사용자의 정보를 데이터베이스에서 가져옴
if(!empty($user_id)) {
    $stmt = $conn->prepare("SELECT user_name, email FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    
    $user_name = $user_data['user_name'];
>>>>>>> Stashed changes
=======
// 로그인된 사용자의 정보를 데이터베이스에서 가져옴
if(!empty($user_id)) {
    $stmt = $conn->prepare("SELECT user_name, email FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    
    $user_name = $user_data['user_name'];
>>>>>>> Stashed changes
=======
// 로그인된 사용자의 정보를 데이터베이스에서 가져옴
if(!empty($user_id)) {
    $stmt = $conn->prepare("SELECT user_name, email FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    
    $user_name = $user_data['user_name'];
>>>>>>> Stashed changes
    $email = $user_data['email'];
}

$sql = "
    SELECT o.id AS order_id, o.created_at, b.id AS book_id, b.title, b.author, b.price, b.image_path, oi.quantity
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
$orders = [];

while ($row = $result->fetch_assoc()) {
    $orders[$row['order_id']]['created_at'] = $row['created_at'];
    $orders[$row['order_id']]['items'][] = $row;
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_profile"])) {
    $new_name = trim($_POST["edit_name"]);
    $new_email = trim($_POST["edit_email"]);

    if (!empty($new_name) && !empty($new_email)) {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $new_name, $new_email, $id);
        if ($stmt->execute()) {
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>마이페이지 - 온라인 서점</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/mypage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<<<<<<< Updated upstream
<?php include 'header.php'; ?>
<main>
    <div class="mypage-container">
    <?php if (!empty($id)): ?>
        <div class="mypage-content">
            <aside class="sidebar">
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
=======
    <?php include 'header.php'; ?>
    <main>
        <div class="mypage-container">
            <?php
            if(!empty($user_id)): ?>
            <div class="mypage-content">
                <aside class="sidebar">
                    <div class="user-info">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3 class="user-name"><?= ($user_name)?></h3>
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
                    <div class="content-header">
                        <h3>주문 내역</h3>
>>>>>>> Stashed changes
                    </div>
                    <h3 class="user-name"><?= ($name) ?></h3>
                    <p class="user-email"><?= ($email) ?></p>
                </div>
                <ul class="sidebar-menu">
                    <li><button onclick="showTab('orders')"><i class="fas fa-shopping-bag"></i> 주문 내역</button></li>
                    <li><button onclick="showTab('reviews')"><i class="fas fa-comment"></i> 내가 쓴 리뷰</button></li>
                    <li><button onclick="showTab('profile')"><i class="fas fa-cog"></i> 회원 정보 수정</button></li>
                    <li><button onclick="location.href='withdraw.php'"><i class="fas fa-user-slash"></i> 회원 탈퇴</button></li>
                </ul>
            </aside>

            <div class="main-content">
                <div id="orders" class="tab-content">
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
                                        <a href="category/book_detail.php?id=<?php echo $item['book_id']; ?>">
                                            <img src="<?= $item['image_path'] ?? 'images/default.jpg' ?>" alt="도서 이미지" class="product-image">
                                        </a>
                                        <div class="product-info">
                                            <a href="book_detail.php?id=<?php echo $item['book_id']; ?>">
                                                <h4 class="product-title"><?= ($item['title']) ?></h4>
                                            </a>
                                            <p class="product-author"><?= ($item['author']) ?></p>
                                            <p class="product-price"><?= number_format($item['price']) ?>원</p>
                                        </div>
                                    </div>
                                    <div class="order-actions">
                                        <?php if (!empty($item['book_id'])): ?>
                                            <a href="review_write.php?book_id=<?= $item['book_id'] ?>" class="action-btn primary-btn">리뷰 작성</a>
                                        <?php endif; ?>
                                        <a href="order_detail.php?order_id=<?= $order_id ?>" class="action-btn secondary-btn">주문 상세</a>
                                        <a href="order_manage.php?order_id=<?= $order_id ?>" class="action-btn secondary-btn">주문 변경/취소</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>

                <div id="reviews" class="tab-content" style="display:none;">
                    <div class="content-header"><h3>내가 쓴 리뷰</h3></div>
                    <?php
                    $sql = "SELECT r.id AS review_id, r.rating, r.content, r.created_at,
                    b.id AS book_id, b.title, b.author, b.image_path
                    FROM reviews r
                    JOIN books b ON r.book_id = b.id
                    WHERE r.user_id = ?";
     
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    ?>
                    <div class="review-list">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="review-item">
                            <div class="review-left">
                                <a href="category/book_detail.php?id=<?= $row['book_id'] ?>">
                                <img src="<?= $row['image_path'] ?? 'images/default.jpg' ?>" alt="도서 이미지" class="review-book-image">
                                </a>
                            </div>
                            <div class="review-right">
                                <a href="category/book_detail.php?id=<?= $row['book_id'] ?>">
                                <h4 class="review-book-title"><?= $row['title'] ?></h4>
                                </a>
                                <p class="review-book-author"><?= $row['author'] ?></p>
                                <div class="review-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="star"><?= $i <= $row['rating'] ? '★' : '☆' ?></span>
                                <?php endfor; ?>
                                </div>
                                <div class="review-content"><?= nl2br(htmlspecialchars($row['content'])) ?></div>
                                <div class="review-date"><?= date('Y-m-d', strtotime($row['created_at'])) ?></div>
                            </div>
                        </div>

                    <?php endwhile; ?>
                    </div>
                </div>

                <div id="profile" class="tab-content" style="display:none;">
                    <div class="content-header"><h3>회원 정보 수정</h3></div>
                        <form method="POST" action="mypage.php" class="profile-form">
                            <div class="form-group">
                            <label for="edit_username">아이디</label>
                            <input type="text" name="edit_username" id="edit_username" value="<?= htmlspecialchars($id) ?>" required>
                            </div>
                            <div class="form-group">
                            <label for="edit_password">비밀번호</label>
                            <input type="password" name="edit_password" id="edit_password" required>
                            </div>
                            <div class="form-group">
                            <label for="edit_name">이름</label>
                            <input type="text" name="edit_name" id="edit_name" value="<?= htmlspecialchars($name) ?>" required>
                            </div>
                            <div class="form-group">
                            <label for="edit_email">이메일</label>
                            <input type="email" name="edit_email" id="edit_email" value="<?= htmlspecialchars($email) ?>" required>
                            </div>
                            <button type="submit" name="update_profile" class="action-btn primary-btn">수정하기</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<<<<<<< Updated upstream
    <?php else: ?>
        <script>
            alert('로그인 후 이용해주세요.');
            location.href = 'login.php';
        </script>
    <?php endif; ?>
    </div>
</main>
<script>
function showTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(tab => tab.style.display = 'none');
    document.getElementById(tabId).style.display = 'block';
}
</script>
=======
        <?php else: ?>
            <div class="mypage-content">
                <script>alert('로그인 후 이용해주세요.');</script>
                <script>location.href='login.php';</script>
            </div>
        <?php endif; ?>
        <?php include 'footer.php'; ?>
    </main>
>>>>>>> Stashed changes
</body>
</html>
<?php include 'footer.php'; ?>