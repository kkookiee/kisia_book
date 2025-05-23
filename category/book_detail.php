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
                    <form method="post" action="../cart.php" style="display:inline;">
                        <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                        <button type="submit" class="action-button cart-button">장바구니에 담기</button>
                    </form>

                    <div class="book-description">
                        <h3>도서 소개</h3>
                        <p><?php echo $book['description'];?></p>
                    </div>
                </div>
            </div>

            <div class="book-detail-tabs">
                <button class="tab-btn active" data-tab="description">상세 설명</button>
                <button class="tab-btn" data-tab="reviews">리뷰</button>
            </div>

            <div class="tab-content description-tab active">
                <img class="description-image" src="../<?php echo $book['additional_image_path']; ?>" alt="도서 이미지">
            </div>
            <div class="tab-content reviews-tab">
                <h2>리뷰/한줄평</h2>

                <form class="gd_rvCmtWrite" method="post" action="../review.php" enctype="multipart/form-data">
                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                    <input type="hidden" name="rating" id="ratingInput" value="1">

                    <div class="row_rating">
                        <span id="cmt_rating" class="rvCmt_rating">
                            평점
                            <span class="starGrp">
                                <a href="javascript:void(0)" onclick="SetRatingStar(1);"><em class="bgGD rating rating_on" id="rating_1">★</em></a>
                                <a href="javascript:void(0)" onclick="SetRatingStar(2);"><em class="bgGD rating" id="rating_2">★</em></a>
                                <a href="javascript:void(0)" onclick="SetRatingStar(3);"><em class="bgGD rating" id="rating_3">★</em></a>
                                <a href="javascript:void(0)" onclick="SetRatingStar(4);"><em class="bgGD rating" id="rating_4">★</em></a>
                                <a href="javascript:void(0)" onclick="SetRatingStar(5);"><em class="bgGD rating" id="rating_5">★</em></a>
                            </span>
                        </span>
                    </div>

                    <div class="row_txtArea improved-review-form">
                        <textarea id="comment" name="content" placeholder="리뷰를 작성해주세요" maxlength="500"
                            oninput="document.getElementById('ipt_msg_txtLen').querySelector('strong').innerText = this.value.length;"></textarea>
                        <div class="ipt_msg txtLen" id="ipt_msg_txtLen">
                            <strong>0</strong>/500
                        </div>
                        <input type='file' id="image_path" name='image_path' accept="image/*" >
                        <button type="submit" class="action-button cart-button review-submit-btn">리뷰 등록</button>
                    </div>
                </form>
                <div class="review-list">
                    <?php
                        $sql = "SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.book_id = $book_id ORDER BY r.created_at DESC";
                        $result = $conn->query($sql);
                        while($review = $result->fetch_assoc()):
                    ?>
                    <div class="review-item">
                        <div class="review-content-wrapper">
                            <img src="../<?php echo $review['image_path']; ?>" alt="리뷰 이미지">
                            <div class="review-info">
                                <div class="review-rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span class="star"><?php echo $i <= $review['rating'] ? '★' : '☆'; ?></span>
                                    <?php endfor; ?>
                                </div>
                                <span class="review-author"><?php echo $review['username']; ?></span>
                                <span class="review-date"><?php echo date('Y-m-d', strtotime($review['created_at'])); ?></span>
                            </div>
                            <div class="review-content">
                                <?php echo nl2br($review['content']); ?>
                            </div>
                        </div>
                        <div class="review-content">
                            <?php echo nl2br($review['content']); ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
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
                    } else if (button.dataset.tab === 'reviews') {
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

    <?php include '../footer.php'; ?>
</body>
</html> 