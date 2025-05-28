<?php
require_once '../connect.php';
require_once '../header.php';

// ✅ SQL Injection 방지: Prepared Statement
$stmt = $conn->prepare("SELECT id, title, author, price, description, image_path FROM books WHERE category = ?");
$category = 'computer_it';
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IT/모바일 - 온라인 서점</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/category.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<div class="category-container">
  <div class="category-content">
    <h1>IT/모바일</h1>
    <div class="book-list">
      <?php if ($result->num_rows > 0): ?>
        <?php $num = 1; ?>
        <?php while ($book = $result->fetch_assoc()): ?>
          <div class="book-row">
            <div class="book-number"><?= $num++ ?></div>
            <div class="book-thumb">
              <a href="book_detail.php?id=<?= urlencode($book['id']) ?>">
                <img src="../<?= htmlspecialchars($book['image_path']) ?>" alt="<?= htmlspecialchars($book['title']) ?>">
              </a>
            </div>
            <div class="book-info">
              <div class="book-title">
                <a href="book_detail.php?id=<?= urlencode($book['id']) ?>"><?= htmlspecialchars($book['title']) ?></a>
              </div>
              <div class="book-meta">
                <a href="book_detail.php?id=<?= urlencode($book['id']) ?>"><?= htmlspecialchars($book['author']) ?></a>
              </div>
              <div class="book-price"><?= number_format((int)$book['price']) ?>원</div>
              <div class="book-desc">
                <?= htmlspecialchars(mb_strimwidth(strip_tags($book['description']), 0, 220, '...')) ?>
              </div>
            </div>

            <form method="POST" class="book-action-form" action="../cart.php">
              <input type="hidden" name="book_id" value="<?= htmlspecialchars($book['id']) ?>">
              <input type="hidden" name="quantity" class="quantity-input" value="1">

              <div class="book-actions">
                <div class="qty-control">
                  <button type="button" onclick="updateQuantity(this, -1)">-</button>
                  <input type="number" class="quantity-display" value="1" min="1" readonly>
                  <button type="button" onclick="updateQuantity(this, 1)">+</button>
                </div>

                <button type="submit" class="cart-btn">카트에 넣기</button>
                <button type="submit" class="buy-btn" name="buy_now" value="1">바로 구매</button>
              </div>
            </form>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="no-books">등록된 도서가 없습니다.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require_once '../footer.php'; ?>
</body>
</html>

<script>
function updateQuantity(button, delta) {
  const form = button.closest('.book-action-form');
  const quantityInput = form.querySelector('.quantity-input');
  const quantityDisplay = form.querySelector('.quantity-display');
  let current = parseInt(quantityInput.value);
  current = isNaN(current) ? 1 : current + delta;
  if (current < 1) current = 1;
  quantityInput.value = current;
  quantityDisplay.value = current;
}
</script>
