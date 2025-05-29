<?php
require_once 'session_start.php';
require_once 'connect.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search_query = isset($_GET['search']) ? str_replace('%', '', trim($_GET['search'])) : '';
$search_query_sql = '%' . str_replace('%', '\%', $search_query) . '%'; // SQL LIKE용 값만 따로 구성
$per_page = 5;
$offset = ($page - 1) * $per_page;

// SQL 구성
$params = [];
$where_clause = '';
if ($search_query !== '') {
    $where_clause = "WHERE inquiries.title LIKE ?";
    $params[] = $search_query_sql; // SQL에는 와일드카드 포함된 값 전달
}

// 목록 조회 (Prepared Statement)
$sql = "
    SELECT inquiries.*, users.username 
    FROM inquiries 
    LEFT JOIN users ON inquiries.user_id = users.id 
    $where_clause 
    ORDER BY inquiries.id DESC 
    LIMIT ? OFFSET ?
";
$params[] = $per_page;
$params[] = $offset;

$stmt = $conn->prepare($sql);

// bind_param 동적 처리
$types = str_repeat('s', count($params) - 2) . 'ii';
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$inquiries = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// 전체 개수 조회 (검색조건 포함)
$count_sql = "SELECT COUNT(*) as total FROM inquiries $where_clause";
$count_stmt = $conn->prepare($count_sql);
if ($search_query !== '') {
    $count_stmt->bind_param("s", $params[0]);
}
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_count = $count_result->fetch_assoc()['total'];
$count_stmt->close();

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
                <h2>문의사항</h2>
                <div class="board-actions">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="inquiry_write.php"><button class="write-btn">글쓰기</button></a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="board-filters">
                <select class="filter-select">
                    <option value="all">전체</option>
                </select>
                <div class="search-box">
                    <input type="text" class="search-input" placeholder="검색어를 입력하세요">
                    <button class="search-btn" onclick="window.location.href='?search=' + encodeURIComponent(document.querySelector('.search-input').value)">검색</button>
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
                        <tr onclick="window.location.href='inquiry_detail.php?id=<?= (int)$inquiry['id'] ?>'">
                            <td class="post-number"><?= (int)$inquiry['id'] ?></td>
                            <td class="post-title"><?= htmlspecialchars($inquiry['title'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="post-author"><?= htmlspecialchars($inquiry['username'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="post-date"><?= htmlspecialchars($inquiry['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="post-status"><?= htmlspecialchars($inquiry['inquiry_status'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="no-data">문의사항이 없습니다.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
            <div class="pagination">
                <?php
                for ($i = 1; $i <= $total_pages; $i++) {
                    $active = $i == $page ? 'active' : '';
                    echo "<a href='?page=$i&search=" . urlencode($search_query) . "' class='page-link $active'>$i</a>";
                }
                if ($page < $total_pages) {
                    $next_page = $page + 1;
                    echo "<a href='?page=$next_page&search=" . urlencode($search_query) . "' class='next'><i class='fas fa-chevron-right'></i></a>";
                }
                ?>
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
