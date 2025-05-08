<?php
include 'connect.php';

// 검색 기능
$search = $_GET['search'] ?? '';
$sql = "SELECT * FROM books";
if ($search) {
    $search = $conn->real_escape_string($search);
    $sql .= " WHERE title LIKE '%$search%' OR category LIKE '%$search%'";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8" />
  <title>도서 관리</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    .book-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .book-table th, .book-table td { border: 1px solid #ccc; padding: 10px; text-align: left; }
    .book-table th { background: #f5f5f5; }
    .admin-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .search-form input { padding: 5px; }
    .btn { padding: 6px 12px; text-decoration: none; background: #2d3748; color: white; border-radius: 4px; }
    .btn:hover { background: #4a5568; }
  </style>
</head>
<body>
  <div class="admin-container">
    <?php include 'admin_sidebar.php'; ?>
    <main class="main-content">
      <h1>도서 관리</h1>

      <div class="admin-top">
        <form method="get" class="search-form">
          <input type="text" name="search" placeholder="도서명 또는 카테고리 검색" value="<?= ($search) ?>">
          <button type="submit" class="btn">검색</button>
        </form>
        <a href="admin_book_add.php" class="btn">+ 도서 등록</a>
      </div>

      <table class="book-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>제목</th>
            <th>저자</th>
            <th>가격</th>
            <th>카테고리</th>
            <th>비고</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= ($row['title']) ?></td>
            <td><?= ($row['author']) ?></td>
            <td><?= number_format($row['price']) ?>원</td>
            <td><?= ($row['category']) ?></td>
            <td>
              <a href="admin_book_edit.php?id=<?= $row['id'] ?>" class="btn">수정</a>
              <a href="admin_book_delete.php?id=<?= $row['id'] ?>" class="btn" onclick="return confirm('정말 삭제하시겠습니까?');">삭제</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </main>
  </div>
</body>
</html>
