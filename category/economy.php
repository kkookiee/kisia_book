<?php
require_once '../connect.php';
require_once '../header.php';

// 경제/경영 도서 목록 조회
$sql = "SELECT * FROM books WHERE category = 'economy'";
$result = mysqli_query($conn, $sql);

// 에러 체크 추가
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// 디버깅을 위한 데이터 출력
echo "<!-- Debug: Number of rows: " . ($result ? $result->num_rows : 0) . " -->";
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>경제/경영 - 온라인 서점</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/category.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="category-container">
        <div class="category-content">
            <h1>경제/경영</h1>
            <div class="book-list">
                <?php if ($result->num_rows > 0): ?>
                    <?php $num = 1; ?>
                    <?php while($book = $result->fetch_assoc()): ?>
                        <div class="book-row">
                            <div class="book-number"><?php echo $num++; ?></div>
                            <div class="book-thumb">
                                <img src="../<?php echo $book['image_path']; ?>" alt="<?php echo $book['title']; ?>">
                            </div>
                            <div class="book-info">
                                <div class="book-title">
                                    <a href = "book_detail.php?id=<?php echo $book['id']; ?>"><?php echo $book['title']; ?></a>
                                </div>
                                <div class="book-meta">
                                    <a href = "book_detail.php?id=<?php echo $book['id']; ?>"><?php echo $book['author']; ?></a>
                                </div>
                                <div class="book-price"><?php echo number_format($book['price']); ?>원</div>
                                <div class="book-desc">
                                    <?php
                                        $desc = strip_tags($book['description']);
                                        echo mb_strimwidth($desc, 0, 220, '...');
                                    ?>
                                </div>
                            </div>
                            <div class="book-actions">
                                <div class="qty-control">
                                    <button>-</button>
                                    <input type="number" value="1" min="1">
                                    <button>+</button>
                                </div>
                                <button class="cart-btn" data-id="<?php echo $book['id']; ?>">카트에 넣기</button>
                                <button class="buy-btn">바로구매</button>
                                <button class="wish-btn">리스트에 넣기</button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="no-books">등록된 경제/경영 도서가 없습니다.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php require_once '../footer.php'; ?>
</body>
</html> 