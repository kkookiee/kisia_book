<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>도서 상세 - 온라인 서점</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .book-detail-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .book-info {
            display: flex;
            gap: 40px;
            margin-bottom: 40px;
        }
        .book-image {
            flex: 0 0 300px;
        }
        .book-image img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .book-details {
            flex: 1;
        }
        .book-title {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .book-author {
            color: #666;
            margin-bottom: 20px;
        }
        .book-price {
            font-size: 20px;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 20px;
        }
        .book-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .action-button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .cart-button {
            background-color: #3498db;
            color: white;
        }
        .wishlist-button {
            background-color: #f1f1f1;
            color: #333;
        }
        .book-description {
            margin-top: 40px;
        }
        .book-description h3 {
            margin-bottom: 15px;
        }
        .book-description p {
            line-height: 1.6;
            color: #444;
        }
        .reviews-section {
            margin-top: 40px;
        }
        .review-form {
            margin-bottom: 30px;
        }
        .review-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            min-height: 100px;
        }
        .review-list {
            margin-top: 20px;
        }
        .review-item {
            border-bottom: 1px solid #eee;
            padding: 20px 0;
        }
        .review-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .review-author {
            font-weight: bold;
        }
        .review-date {
            color: #666;
        }
        .review-content {
            line-height: 1.6;
        }
    </style>
    <script src="js/header.js"></script>
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <div class="book-detail-container">
            <div class="book-info">
                <div class="book-image">
                    <img src="images/book-placeholder.jpg" alt="도서 이미지">
                </div>
                <div class="book-details">
                    <h1 class="book-title">도서 제목</h1>
                    <p class="book-author">저자명</p>
                    <p class="book-price">25,000원</p>
                    <div class="book-actions">
                        <button class="action-button cart-button">장바구니에 담기</button>
                        <button class="action-button wishlist-button">위시리스트에 추가</button>
                    </div>
                    <div class="book-description">
                        <h3>도서 소개</h3>
                        <p>도서에 대한 상세한 설명이 들어갑니다. 이 부분은 실제 도서 데이터베이스에서 가져온 내용으로 대체될 것입니다.</p>
                    </div>
                </div>
            </div>

            <div class="reviews-section">
                <h2>리뷰</h2>
                <div class="review-form">
                    <textarea placeholder="리뷰를 작성해주세요"></textarea>
                    <button class="action-button cart-button">리뷰 등록</button>
                </div>
                <div class="review-list">
                    <div class="review-item">
                        <div class="review-header">
                            <span class="review-author">홍길동</span>
                            <span class="review-date">2024-04-29</span>
                        </div>
                        <div class="review-content">
                            매우 좋은 책이었습니다. 많은 도움이 되었습니다.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 온라인 서점. All rights reserved.</p>
        </div>
    </footer>
</body>
</html> 