<?php
require_once '../connect.php';
require_once '../header.php';


// 중고도서 목록 조회
$sql = "SELECT * FROM books WHERE category = 'reference'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>장바구니 - 온라인 서점</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1>참고서</h1>
        
        <div class="book-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while($book = $result->fetch_assoc()): ?>
                    <div class="book-card">
                        <img src="../<?php echo htmlspecialchars($book['image_path']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                        <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                        <p class="author"><?php echo htmlspecialchars($book['author']); ?></p>
                        <p class="price"><?php echo number_format($book['price']); ?>원</p>
                        <p class="description"><?php echo htmlspecialchars($book['description']); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>등록된 참고서가 없습니다.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php require_once '../footer.php'; ?> 