<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>도서 목록 - 온라인 서점</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/books.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <div class="books-container">
            <div class="books-header">
                <h2>도서 목록</h2>
                <div class="books-filter">
                    <select id="category">
                        <option value="">전체 카테고리</option>
                        <option value="novel">소설</option>
                        <option value="essay">에세이</option>
                        <option value="self-help">자기계발</option>
                        <option value="history">역사</option>
                    </select>
                    <div class="search-box">
                        <input type="text" placeholder="도서 검색...">
                        <button type="button">검색</button>
                    </div>
                </div>
            </div>

            <div class="book-grid">
                <!-- 도서 카드 예시 -->
                <div class="book-card">
                    <img src="images/book1.jpg" alt="도서 이미지">
                    <h4>도서 제목 1</h4>
                    <p>저자명</p>
                    <p class="price">15,000원</p>
                    <button>장바구니 담기</button>
                </div>
                <div class="book-card">
                    <img src="images/book2.jpg" alt="도서 이미지">
                    <h4>도서 제목 2</h4>
                    <p>저자명</p>
                    <p class="price">20,000원</p>
                    <button>장바구니 담기</button>
                </div>
                <div class="book-card">
                    <img src="images/book3.jpg" alt="도서 이미지">
                    <h4>도서 제목 3</h4>
                    <p>저자명</p>
                    <p class="price">18,000원</p>
                    <button>장바구니 담기</button>
                </div>
                <!-- 추가 도서 카드 -->
            </div>

            <div class="pagination">
                <button class="page-btn">이전</button>
                <button class="page-btn active">1</button>
                <button class="page-btn">2</button>
                <button class="page-btn">3</button>
                <button class="page-btn">다음</button>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html> 