<?php
require_once '../connect.php';
require_once '../header.php';

// 소설 도서 목록 조회
$sql = "SELECT * FROM books WHERE category = 'novel'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>소설 - 온라인 서점</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/category.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="category-container">
        <div class="category-content">
            <h1>소설</h1>
            <div class="book-list">
                <?php if ($result->num_rows > 0): ?>
                    <?php $num = 1; ?>
                    <?php while($book = $result->fetch_assoc()): ?>
                        <div class="book-row">
                            <div class="book-number"><?php echo $num++; ?></div>
                            <div class="book-thumb">
                                <a href="book_detail.php?id=<?php echo $book['id']; ?>">
                                    <img src="../<?php echo $book['image_path']; ?>" alt="<?php echo $book['title']; ?>">
                                </a>
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
                            <form id="bookActionForm" method="POST">
                                <!-- 공통 도서 정보 -->
                                <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                <input type="hidden" name="title" value="<?php echo $book['title']; ?>">
                                <input type="hidden" name="price" value="<?php echo $book['price']; ?>">
                                <input type="hidden" name="image_path" value="<?php echo $book['image_path']; ?>">
                                <input type="hidden" name="quantity" id="quantityInput" value="1">

                                <div class="book-actions">
                                    <!-- 수량 조절 UI -->
                                    <div class="qty-control">
                                    <button type="button" onclick="updateQuantity(-1)">-</button>
                                    <input type="number" id="quantityDisplay" value="1" min="1" readonly>
                                    <button type="button" onclick="updateQuantity(1)">+</button>
                                    </div>

                                    <!-- 장바구니 버튼은 기본 액션 (form action은 cart.php) -->
                                    <button type="submit" class="cart-btn" formaction="../cart.php">카트에 넣기</button>

                                    <!-- 바로구매 버튼은 JS로 order_confirm.php로 이동 -->
                                    <button type="button" class="buy-btn" onclick="submitDirectPurchase()">바로 구매</button>
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
    function updateQuantity(change) {
    const quantityInput = document.getElementById('quantityInput');
    const quantityDisplay = document.getElementById('quantityDisplay');
    let current = parseInt(quantityInput.value);
    current = isNaN(current) ? 1 : current + change;
    if (current < 1) current = 1;
    quantityInput.value = current;
    quantityDisplay.value = current;
    }

    function submitDirectPurchase() {
    const form = document.getElementById('bookActionForm');
    form.action = '../order_confirm.php';
    form.submit();
    }
</script>
