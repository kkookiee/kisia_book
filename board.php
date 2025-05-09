<?php require_once 'session_start.php'; ?>
<?php require_once 'connect.php'; ?>
<?php
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

$search_sql = '';
if ($search_query) {
    $search_sql = "WHERE inquiries.title LIKE '%$search_query%'";
}

$per_page = 5;
$offset = ($page - 1) * $per_page;

$sql = "SELECT inquiries.*, users.username FROM inquiries LEFT JOIN users ON inquiries.user_id = users.id $search_sql ORDER BY inquiries.id DESC LIMIT $per_page OFFSET $offset";
$result = $conn->query($sql);
$inquiries = $result->fetch_all(MYSQLI_ASSOC);

$count_sql = "SELECT COUNT(*) FROM inquiries";
$count_result = $conn->query($count_sql);
$total_count = $count_result->fetch_assoc()['COUNT(*)'];
$total_pages = ceil($total_count / $per_page);
?>
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
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="inquiry_write.php"><button class="write-btn">글쓰기</button></a>
                    <?php endif; ?>
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
                    <button class="search-btn" onclick="window.location.href='?search=' + document.querySelector('.search-input').value">검색</button>
                </div>
            </div>
            <table class="board-table">
                <thead>
                    <tr>
                        <th class="post-number">번호</th>
                        <th class="post-title">제목</th>
                        <th class="post-author">작성자</th>
                        <th class="post-date">작성일</th>
                        <th class="post-status">상태</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($inquiries): ?>
                    <?php foreach ($inquiries as $inquiry): ?>
                    <tr onclick="window.location.href='inquiry_detail.php?id=<?php echo $inquiry['id']; ?>'">
                        <td class="post-number"><?php echo $inquiry['id']; ?></td>
                        <td class="post-title"><?php echo $inquiry['title']; ?></td>
                        <td class="post-author"><?php echo $inquiry['username']; ?></td>
                        <td class="post-date"><?php echo $inquiry['created_at']; ?></td>
                        <td class="post-status"><?php echo $inquiry['inquiry_status']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="4" class="no-data">문의사항이 없습니다.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="pagination">
                <?php
                for ($i = 1; $i <= $total_pages; $i++) {
                    $active = $i == $page ? 'active' : '';
                    echo "<a href='?page=$i' class='page-link $active'>$i</a>";}
                if ($page < $total_pages) {
                    $next_page = $page + 1;
                    echo "<a href='?page=$next_page' class='next'><i class='fas fa-chevron-right'></i></a>";}
                ?>
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html> 