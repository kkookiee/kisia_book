<?php
require_once '../connect.php';
require_once '../header.php';

// 경제 도서 목록 조회
$sql = "SELECT * FROM books WHERE category = 'economy'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>경제 - 온라인 서점</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/category.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="category-container">
    <div class="category-content">
        <h1>경제</h1>
        <div class="book-list">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php $num = 1; ?>
                <?php while($book = $result->fetch_assoc()): ?>
                    <div class="book-row">
                        <div class="book-number"><?= $num++ ?></div>
                        <div class="book-thumb">
                            <a href="book_detail.php?id=<?= $book['id'] ?>">
                                <img src="../<?= $book['image_path'] ?>" alt="<?= $book['title'] ?>">
                            </a>
                        </div>
                        <div class="book-info">
                            <div class="book-title">
                                <a href="book_detail.php?id=<?= $book['id'] ?>"><?= $book['title'] ?></a>
                            </div>
                            <div class="book-meta">
                                <?= $book['author'] ?>
                            </div>
                            <div class="book-price"><?= number_format($book['price']) ?>원</div>
                            <div class="book-desc">
                                <?php
                                $desc = strip_tags($book['description']);
                                echo mb_strimwidth($desc, 0, 220, '...');
                                ?>
                            </div>
                        </div>
                        <form action="../cart.php" method="POST" class="book-actions">
                            <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                            <input type="hidden" name="quantity" value="1">
                            <input type="hidden" name="user_id" value="<?= $user_id ?>">
                            <input type="hidden" name="title" value="<?= $book['title'] ?>">
                            <input type="hidden" name="price" value="<?= $book['price'] ?>">
                            <input type="hidden" name="image_path" value="<?= $book['image_path'] ?>">
                            <div class="qty-control">
                                <button type="button">-</button>
                                <input type="number" name="quantity" value="1" min="1">
                                <button type="button">+</button>
                            </div>
                            <button type="submit" class="cart-btn">카트에 넣기</button>
                            <button type="submit" class="buy-btn">바로구매</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-books">등록된 경제 도서가 없습니다.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../footer.php'; ?>
</body>
</html>
