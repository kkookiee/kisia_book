<?php require_once 'session_start.php'; ?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시판/리뷰 - 온라인 서점</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/board.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'header.php'; ?>    
    <main>
        <div class="board-container">
            <div class="board-header">
                <h2>게시판/리뷰</h2>
                <div class="board-actions">
                    <button class="write-btn">글쓰기</button>
                </div>
            </div>
            <div class="board-filters">
                <select class="filter-select">
                    <option value="all">전체</option>
                    <option value="notice">공지사항</option>
                    <option value="review">도서 리뷰</option>
                    <option value="qna">Q&A</option>
                </select>
                <div class="search-box">
                    <input type="text" class="search-input" placeholder="검색어를 입력하세요">
                    <button class="search-btn">검색</button>
                </div>
            </div>
            <table class="board-table">
                <thead>
                    <tr>
                        <th class="post-number">번호</th>
                        <th class="post-title">제목</th>
                        <th class="post-author">작성자</th>
                        <th class="post-date">작성일</th>
                        <th class="post-views">조회</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="post-number">1</td>
                        <td class="post-title">도서 리뷰: 인생에 대한 깊은 통찰</td>
                        <td class="post-author">홍길동</td>
                        <td class="post-date">2023-04-01</td>
                        <td class="post-views">42</td>
                    </tr>
                    <tr>
                        <td class="post-number">2</td>
                        <td class="post-title">새로운 도서 출간 안내</td>
                        <td class="post-author">관리자</td>
                        <td class="post-date">2023-03-30</td>
                        <td class="post-views">128</td>
                    </tr>
                    <tr>
                        <td class="post-number">3</td>
                        <td class="post-title">도서 배송 관련 문의</td>
                        <td class="post-author">김철수</td>
                        <td class="post-date">2023-03-29</td>
                        <td class="post-views">56</td>
                    </tr>
                </tbody>
            </table>
            <div class="pagination">
                <a href="#" class="page-link active">1</a>
                <a href="#" class="page-link">2</a>
                <a href="#" class="page-link">3</a>
                <a href="#" class="page-link">4</a>
                <a href="#" class="page-link">5</a>
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html> 