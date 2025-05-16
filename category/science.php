<?php
require_once '../connect.php';
require_once '../header.php';

// 자연과학 도서 목록 조회
$sql = "SELECT * FROM books WHERE category = 'science'";
$result = mysqli_query($conn, $sql);
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>자연과학 - 온라인 서점</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/category.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="category-container">
        <div class="category-content">
            <h1>자연과학</h1>
            <div class="book-list">
        <?php if ($result->num_rows > 0): ?>
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
                <a href="book_detail.php?id=<?= $book['id'] ?>"><?= $book['author'] ?></a>
              </div>
              <div class="book-price"><?= number_format($book['price']) ?>원</div>
              <div class="book-desc">
                <?= mb_strimwidth(strip_tags($book['description']), 0, 220, '...') ?>
              </div>
            </div>

            <form method="POST" class="book-action-form">
              <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
              <input type="hidden" name="title" value="<?= $book['title'] ?>">
              <input type="hidden" name="price" value="<?= $book['price'] ?>">
              <input type="hidden" name="image_path" value="<?= $book['image_path'] ?>">
              <input type="hidden" name="quantity" class="quantity-input" value="1">

              <div class="book-actions">
                <div class="qty-control">
                  <button type="button" onclick="updateQuantity(this, -1)">-</button>
                  <input type="number" class="quantity-display" value="1" min="1" readonly>
                  <button type="button" onclick="updateQuantity(this, 1)">+</button>
                </div>

                <button type="submit" class="cart-btn" formaction="../cart.php">카트에 넣기</button>
                <button type="submit" class="buy-btn" formaction="../cart.php" name="buy_now" value="1">바로 구매</button>
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

function submitDirectPurchase(button) {
  const form = button.closest('.book-action-form');
  const input = document.createElement('input');
  input.type = 'hidden';
  input.name = 'direct_buy';
  input.value = '1';
  form.appendChild(input);

  form.action = '../order_process.php';
  form.method = 'POST';
  form.submit();
}

</script>