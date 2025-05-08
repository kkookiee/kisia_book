<?php
require_once '../connect.php';
require_once '../header.php';

    $book_id = $_GET['id'];
    $sql = "SELECT * FROM books WHERE id = $book_id";
    $result = $conn->query($sql);
    $book = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>소설 - 온라인 서점</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/book_detail.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <main>
        <div class="book-detail-container">
            <div class="book-info">
                <div class="book-image">
                    <img src="../<?php echo $book['image_path']; ?>" alt="도서 이미지">
                </div>
                <div class="book-details">
                    <h1 class="book-title"><?php echo ($book['title']); ?></h1>
                    <p class="book-author"><?php echo ($book['author']);?></p>
                    <p class="book-price"><?php echo number_format($book['price']);?>원</p>
                    <div class="book-actions">
                        <button class="action-button cart-button">장바구니에 담기</button>
                        <button class="action-button wishlist-button">위시리스트에 추가</button>
                    </div>
                    <div class="book-description">
                        <h3>도서 소개</h3>
                        <p><?php echo $book['description'];?></p>
                    </div>
                </div>
            </div>

            <div class="book-detail-tabs">
                <button class="tab-btn active" data-tab="description">상세 설명</button>
                <button class="tab-btn" data-tab="reviews">리뷰</button>
                <div class="tab-content active" id=" add-book-image">
                    <img src="../<?php echo $book['additional_image_path']; ?>" alt="도서 이미지">
                </div>
                <div class="tab-content" id="reviews">
                    <p>리뷰</p>
                    <div class="review-form">
                        <textarea placeholder="리뷰를 작성해주세요"></textarea>
                        <button class="action-button cart-button">리뷰 등록</button>
                    </div>
                </div>
            </div>

            <!--<div class="reviews-section">
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
        </div>-->
    </main>

    <?php include '../footer.php'; ?>
</body>
</html> 