<?php
include 'connect.php';

// 검색 기능
$search = $_GET['search'] ?? '';
$sql = "SELECT * FROM books";
if ($search) {
    // 🚨 보호 제거: 사용자 입력을 그대로 쿼리에 삽입
    $sql .= " WHERE title LIKE '%$search%' OR category LIKE '%$search%'";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>도서 관리</title>
  <link rel="stylesheet" href="css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="admin-container">
  <?php include 'admin_sidebar.php'; ?>
  <main class="main-content">
    <h1>도서 관리</h1>
    <p>도서 목록을 조회하고 관리할 수 있습니다.</p>

    <div class="admin-top" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <form method="get" class="search-form" style="display: flex;">
        <input type="text" name="search" placeholder="도서명 또는 카테고리 검색" value="<?= ($search) ?>" style="padding: 8px; width: 250px; border-radius: 6px; border: 1px solid #ccc;">
        <button type="submit" class="btn" style="margin-left: 8px;">검색</button>
      </form>
      <a href="admin_book_add.php" class="btn">+ 도서 등록</a>
    </div>

    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>제목</th>
          <th>저자</th>
          <th>가격</th>
          <th>카테고리</th>
          <th>관리</th>
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
