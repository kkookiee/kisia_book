<?php
session_start();
require_once 'connect.php';

// ✅ 관리자 인증 확인 먼저!
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
  // 404 페이지로 리다이렉트
  header("Location: /404.php");
  exit();
}

$search = trim($_GET['search'] ?? '');
$search_param = "%{$search}%";

// ✅ Prepared Statement로 검색 처리
if ($search) {
    $stmt = $conn->prepare("SELECT * FROM books WHERE title LIKE ? OR category LIKE ?");
    $stmt->bind_param("ss", $search_param, $search_param);
} else {
    $stmt = $conn->prepare("SELECT * FROM books");
}
$stmt->execute();
$result = $stmt->get_result();
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
        <input type="text" name="search" placeholder="도서명 또는 카테고리 검색"
          value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>"
          style="padding: 8px; width: 250px; border-radius: 6px; border: 1px solid #ccc;">
        <button type="submit" class="btn" style="margin-left: 8px;">검색</button>
      </form>
<a href="#" onclick="alert('도서 등록 기능은 아직 구현되지 않았습니다.'); return false;" class="btn">+ 도서 등록</a>
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
          <td><?= htmlspecialchars($row['id']) ?></td>
          <td><?= htmlspecialchars($row['title']) ?></td>
          <td><?= htmlspecialchars($row['author']) ?></td>
          <td><?= number_format($row['price']) ?>원</td>
          <td><?= htmlspecialchars($row['category']) ?></td>
          <td style="display: flex; gap: 5px;">
            <a href="admin_book_edit.php?id=<?= urlencode($row['id']) ?>" class="btn">수정</a>
            <!-- ✅ POST 방식 삭제 -->
            <form action="admin_book_delete.php" method="POST" onsubmit="return confirm('정말 삭제하시겠습니까?');">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <button type="submit" class="btn">삭제</button>
            </form>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </main>
</div>
</body>
</html>
