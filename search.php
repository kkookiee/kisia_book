<?php
require_once 'connect.php';
require_once 'session_start.php';
include 'header.php';

$search_query = $_GET['search_query'] ?? '';
$results = [];

if (!empty($search_query)) {
    $stmt = $conn->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ?");
    $like_query = "%" . $search_query . "%";
    $stmt->bind_param("ss", $like_query, $like_query);
    $stmt->execute();
    $results = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>검색 결과 - 온라인 서점</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/search.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="search-container">
        <div class="search-content">
            <h1>🔍 '<?= $search_query ?>' 검색 결과</h1>

            <?php if (!empty($search_query)): ?>
                <?php if ($results->num_rows > 0): ?>
                    <div class="search-results">
                        <?php while ($book = $results->fetch_assoc()): ?>
                            <div class="book-row">
                                <div class="book-thumb">
                                    <a href="category/book_detail.php?id=<?php echo $book['id']; ?>">
                                        <img src="<?php echo $book['image_path']; ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                                    </a>
                                </div>
                                <div class="book-info">
                                    <div class="book-title">
                                        <a href="category/book_detail.php?id=<?php echo $book['id']; ?>"><?php echo htmlspecialchars($book['title']); ?></a>
                                    </div>
                                    <div class="book-meta">
                                        <a href="category/book_detail.php?id=<?php echo $book['id']; ?>"><?php echo htmlspecialchars($book['author']); ?></a>
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
                    </div>
                <?php else: ?>
                    <p class="no-results">검색 결과가 없습니다.</p>
                <?php endif; ?>
            <?php else: ?>
                <p class="no-results">검색어를 입력해주세요.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
