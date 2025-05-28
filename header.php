<?php 
require_once 'session_start.php'; 
require_once 'connect.php';
#echo '현재 client 문자셋: ' . $conn->character_set_name();

$user_id = $_SESSION['user_id'] ?? null;
$user_name = '';
$cart_count = 0;

// 사용자 이름 조회
if ($user_id) {
    $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($name);
    if ($stmt->fetch()) {
        $user_name = $name;
    }
    $stmt->close();
}

// 장바구니 수량 계산 (보안 방식)
if ($user_id) {
    $stmt = $conn->prepare("SELECT SUM(quantity) as total_items FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($total_items);
    $stmt->fetch();
    $cart_count = $total_items ?? 0;
    $stmt->close();
}

//search_input 사용자가 입력한 원래 값 "자바"
//search_query SQL에서 부분 검색하려고 %를 붙인 값 "%자바%"

$search_input = $_POST['search_query'] ?? ''; // 원본 검색어
$search_query = "%" . $search_input . "%";    // LIKE용 검색어

if ($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($search_input)) {
    $sql = "SELECT * FROM books WHERE title LIKE ? OR author LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $search_query, $search_query);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo '<script>alert("검색 결과가 없습니다.");</script>';
    } else {
        echo "<script>window.location.href='search.php?query=" . urlencode($search_input) . "';</script>";
    }
}

?>
<header>
    <div class="top-header">
        <div class="container">
            <div class="top-links">
                <div class="left-links">
                    <a href="/index.php" class="active">국내도서</a>
                    <a href="#" onclick="alert('준비중입니다'); return false;">외국도서</a>
                    <a href="#" onclick="alert('준비중입니다'); return false;">음반/DVD</a>
                    <a href="#" onclick="alert('준비중입니다'); return false;">기프트</a>
                </div>
                <div class="right-links">
                    <?php if (!empty($user_id)): ?>
                        <span class="welcome-text"><?= htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8') ?>님 환영합니다!</span>
                        <a href="/logout.php">로그아웃</a>
                    <?php else: ?>
                        <a href="/login.php">로그인</a>
                        <a href="/signup.php">회원가입</a>
                    <?php endif; ?>
                    <a href="#" onclick="alert('준비중입니다'); return false;">고객센터</a>
                </div>
            </div>
        </div>
    </div>
    <nav>
        <div class="container">
            <div class="nav-wrapper">
                <div class="logo">
                    <a href="/index.php"><h1>온라인 서점</h1></a>
                </div>
                <div class="search-box">
                    <select class="search-category">
                        <option value="all">전체</option>
                    </select>
                    <form action="/search.php" method="GET">
                        <input type="text" placeholder="검색어를 입력하세요" name="search_query" value="<?= htmlspecialchars($_GET['search_query'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                <div class="nav-right">
                <a href="/cart.php" class="nav-icon-link">
                    <i class="fas fa-shopping-cart"></i>
                    <?php if ($cart_count > 0): ?>
                        <span class="cart-count"><?= $cart_count ?></span>
                    <?php else: ?>
                        <span class="cart-count">0</span>
                    <?php endif; ?>
                    <span class="nav-text">장바구니</span>
                </a>

                    <a href="/mypage.php" class="nav-icon-link">
                        <i class="fas fa-user"></i>
                        <span class="nav-text">마이페이지</span>
                    </a>
                    <a href="/board.php" class="nav-icon-link">
                        <i class="fas fa-comments"></i>
                        <span class="nav-text">문의사항</span>
                    </a>
                </div>
            </div>
            <div class="category-menu">
                <ul class="main-categories">
                    <li><a href="#" class="category-active">카테고리</a></li>
                    <li><a href="/category/novel.php">소설/시/희곡</a></li>
                    <li><a href="/category/economy.php">경제/경영</a></li>
                    <li><a href="/category/self_improvement.php">자기계발</a></li>
                    <li><a href="/category/science.php">과학/기술</a></li>
                    <li><a href="/category/computer_it.php">컴퓨터/IT</a></li>
                    <li><a href="/category/children.php">어린이</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>
