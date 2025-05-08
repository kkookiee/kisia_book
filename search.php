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
    <title>검색 결과 - 온라인 서점</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
</head>
<body>
<main class="container">
    <h1>🔍 '<?= htmlspecialchars($search_query) ?>' 검색 결과</h2>

    <?php if (!empty($search_query)): ?>
        <?php if ($results->num_rows > 0): ?>
            <div class="book-grid">
                <?php while ($book = $results->fetch_assoc()): ?>
                    <div class="book-card">
                        <img src="<?= htmlspecialchars($book['image_path']) ?>" alt="도서 이미지" class="book-image">
                        <div class="book-info">
                            <h4><?= htmlspecialchars($book['title']) ?></h4>
                            <p class="author"><?= htmlspecialchars($book['author']) ?></p>
                            <p class="price"><?= number_format($book['price']) ?>원</p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>검색 결과가 없습니다.</p>
        <?php endif; ?>
    <?php else: ?>
        <p>검색어를 입력해주세요.</p>
    <?php endif; ?>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
