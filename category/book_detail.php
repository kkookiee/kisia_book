<?php
require_once '../connect.php';
require_once '../session_start.php';
require_once '../header.php';

// GET 파라미터 유효성 검사
$book_id = $_GET['id'] ?? '';
if (!preg_match('/^[0-9a-zA-Z_-]+$/', $book_id)) {
    http_response_code(400);
    exit('잘못된 요청입니다.');
}

// 도서 조회
$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->bind_param("s", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    exit('도서를 찾을 수 없습니다.');
}
$book = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($book['title']) ?> - 온라인 서점</title>
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
                <img src="../<?= htmlspecialchars($book['image_path']) ?>" alt="도서 이미지">
            </div>
            <div class="book-details">
                <h1 class="book-title"><?= htmlspecialchars($book['title']) ?></h1>
                <p class="book-author"><?= htmlspecialchars($book['author']) ?></p>
                <p class="book-price"><?= number_format($book['price']) ?>원</p>
                <form method="post" action="/cart.php" style="display:inline;">
                    <input type="hidden" name="book_id" value="<?= htmlspecialchars($book['id']) ?>">
                    <button type="submit" class="action-button cart-button">장바구니에 담기</button>
                </form>

                <div class="book-description">
                    <h3>도서 소개</h3>
                    <p><?= nl2br(htmlspecialchars($book['description'])) ?></p>
                </div>
            </div>
        </div>

        <div class="book-detail-tabs">
            <button class="tab-btn active" data-tab="description">상세 설명</button>
            <button class="tab-btn" data-tab="reviews">리뷰</button>
        </div>

        <div class="tab-content description-tab active">
            <img class="description-image" src="../<?= htmlspecialchars($book['additional_image_path']) ?>" alt="도서 이미지">
        </div>

        <div class="tab-content reviews-tab">
            <h2>리뷰/한줄평</h2>
            <form class="gd_rvCmtWrite" method="post" action="/review.php" enctype="multipart/form-data">
                <input type="hidden" name="book_id" value="<?= htmlspecialchars($book['id']) ?>">
                <input type="hidden" name="rating" id="ratingInput" value="1">

                <div class="row_rating">
                    <span id="cmt_rating" class="rvCmt_rating">
                        평점
                        <span class="starGrp">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <a href="javascript:void(0)" onclick="SetRatingStar(<?= $i ?>);">
                                    <em class="bgGD rating<?= $i == 1 ? ' rating_on' : '' ?>" id="rating_<?= $i ?>">★</em>
                                </a>
                            <?php endfor; ?>
                        </span>
                    </span>
                </div>

                <div class="row_txtArea improved-review-form">
                    <textarea id="comment" name="content" placeholder="리뷰를 작성해주세요" maxlength="500"
                        oninput="document.getElementById('ipt_msg_txtLen').querySelector('strong').innerText = this.value.length;"></textarea>
                    <div class="ipt_msg txtLen" id="ipt_msg_txtLen"><strong>0</strong>/500</div>
                    <input type='file' id="image_path" name='image_path' accept="image/*">
                    <button type="submit" class="action-button cart-button review-submit-btn">리뷰 등록</button>
                </div>
            </form>

            <div class="review-list">
                <?php
                $review_stmt = $conn->prepare("
                    SELECT r.*, u.username 
                    FROM reviews r 
                    JOIN users u ON r.user_id = u.id 
                    WHERE r.book_id = ? 
                    ORDER BY r.created_at DESC
                ");
                $review_stmt->bind_param("s", $book_id);
                $review_stmt->execute();
                $reviews = $review_stmt->get_result();

                while ($review = $reviews->fetch_assoc()):
                ?>
                <div class="review-item">
                    <div class="review-content-wrapper">
                        <?php if (!empty($review['image_path'])): ?>
                            <img src="/<?= htmlspecialchars($review['image_path']) ?>" alt="리뷰 이미지">
                        <?php endif; ?>
                        <div class="review-info">
                            <div class="review-info-header">
                                <div>
                                    <div class="review-rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span class="star"><?= $i <= $review['rating'] ? '★' : '☆' ?></span>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="review-author"><?= htmlspecialchars($review['username']) ?></div>
                                </div>
                                <div class="review-date"><?= date('Y-m-d', strtotime($review['created_at'])) ?></div>
                            </div>
                            <div class="review-content"><?= nl2br(htmlspecialchars($review['content'])) ?></div>
                        </div>
                    </div>
                </div>
                <?php endwhile;
                $review_stmt->close(); ?>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const descriptionTab = document.querySelector('.description-tab');
    const reviewsTab = document.querySelector('.reviews-tab');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            tabButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            if (button.dataset.tab === 'description') {
                descriptionTab.classList.add('active');
                reviewsTab.classList.remove('active');
            } else {
                descriptionTab.classList.remove('active');
                reviewsTab.classList.add('active');
            }
        });
    });
});

function SetRatingStar(score) {
    document.getElementById('ratingInput').value = score;
    for (let i = 1; i <= 5; i++) {
        const star = document.getElementById('rating_' + i);
        star.classList.toggle('rating_on', i <= score);
    }
}
</script>

<?php require_once '../footer.php'; ?>
</body>
</html>
