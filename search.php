<?php
require_once 'connect.php';
require_once 'session_start.php';
include 'header.php';

$search_input = trim($_POST['search_query'] ?? '');  // 원본 검색어
$search_like = "%" . $search_input . "%";             // LIKE용 검색어
$results = null;

if (!empty($search_input)) {
    $sql = "SELECT * FROM books WHERE title LIKE ? OR author LIKE ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      $stmt->bind_param('ss', $search_like, $search_like);
      if ($stmt->execute()) {
          $results = $stmt->get_result();
      }
  }
}

?>


<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>검색 결과 - 온라인 서점</title>
  <link rel="stylesheet" href="/css/style.css">
  <link rel="stylesheet" href="/css/header.css">
  <link rel="stylesheet" href="/css/search.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<main class="search-container">
  <div class="search-content">
  <h1>🔍 '<?= htmlspecialchars($search_input, ENT_QUOTES, 'UTF-8') ?>' 검색 결과</h1>

    <?php if (!empty($search_input)): ?>
     <?php if ($results && $results->num_rows > 0): ?>
        <div class="search-results">
          <?php while ($book = $results->fetch_assoc()): ?>
            <div class="book-row">
              <div class="book-thumb">
                <a href="/category/book_detail.php?id=<?= $book['id'] ?>">
                <img src="<?= htmlspecialchars($book['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8') ?>">
                </a>
              </div>
              <div class="book-info">
                <div class="book-title">
                <a href="/category/book_detail.php?id=<?= $book['id'] ?>">
                  <?= htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8') ?>
                </a>
                </div>
                <div class="book-meta">
                <a href="/category/book_detail.php?id=<?= $book['id'] ?>">
                  <?= htmlspecialchars($book['author'], ENT_QUOTES, 'UTF-8') ?>
                </a>
                </div>
                <div class="book-price"><?= number_format($book['price']) ?>원</div>
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
                <form method="post" action="/add_to_cart.php">
                  <input type="hidden" name="book_id" value="<?= htmlspecialchars($book['id'], ENT_QUOTES, 'UTF-8') ?>">
                  <button type="submit" class="cart-btn">카트에 넣기</button>
                </form>
                <a href="/checkout.php?book_id=<?= urlencode($book['id']) ?>" class="buy-btn">바로구매</a>
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
</main>

<?php include 'footer.php'; ?>
</body>
</html>
