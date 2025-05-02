<?php require_once 'session_start.php'; ?>
<header>
    <div class="top-header">
        <div class="container">
            <div class="top-links">
                <div class="left-links">
                    <a href="#" class="active">국내도서</a>
                    <a href="#">외국도서</a>
                    <a href="#">음반/DVD</a>
                    <a href="#">기프트</a>
                </div>
                <div class="right-links">
                    <?php if (!empty($id)): ?>
                        <span class="welcome-text"><?= htmlspecialchars($id) ?>님 환영합니다!</span>
                        <a href="/mypage.php">마이페이지</a>
                        <a href="/logout.php">로그아웃</a>
                    <?php else: ?>
                        <a href="/login.php">로그인</a>
                        <a href="/signup.php">회원가입</a>
                    <?php endif; ?>
                    <a href="#">고객센터</a>
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
                        <option value="book">도서</option>
                        <option value="music">음반</option>
                        <option value="dvd">DVD</option>
                    </select>
                    <input type="text" placeholder="검색어를 입력하세요">
                    <button type="button"><i class="fas fa-search"></i></button>
                </div>
                <div class="nav-right">
                    <a href="/cart.php" class="nav-icon-link">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count">0</span>
                        <span class="nav-text">장바구니</span>
                    </a>
                    <a href="/mypage.php" class="nav-icon-link">
                        <i class="fas fa-user"></i>
                        <span class="nav-text">마이페이지</span>
                    </a>
                    <a href="/board.php" class="nav-icon-link">
                        <i class="fas fa-comments"></i>
                        <span class="nav-text">게시판/리뷰</span>
                    </a>
                </div>
            </div>
            <div class="category-menu">
                <ul class="main-categories">
                    <li><a href="/category/novel.php">소설/시/희곡</a></li>
                    <li><a href="/category/economy.php">경제/경영</a></li>
                    <li><a href="/category/self_improvement.php">자기계발</a></li>
                    <li><a href="/category/science.php">과학/기술</a></li>
                    <li><a href="/category/computer_it.php">컴퓨터/IT</a></li>
                    <li><a href="/category/used.php">중고도서</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>
