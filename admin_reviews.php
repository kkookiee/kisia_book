<?php
session_start();
require_once 'connect.php';

// ✅ 관리자 인증 확인 먼저!
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
  // 404 페이지로 리다이렉트
  header("Location: /404.php");
  exit();
}

// ✅ SQL 에러 노출 제거 (운영 환경에서는 로그만 남기도록 설정)
mysqli_report(MYSQLI_REPORT_OFF);

// ✅ 리뷰 데이터 조회
$sql = "SELECT r.*, b.title AS book_title, u.username 
        FROM reviews r 
        JOIN books b ON r.book_id = b.id 
        LEFT JOIN users u ON r.user_id = u.id 
        ORDER BY r.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>리뷰 관리</title>
  <link rel="stylesheet" href="css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    .admin-table td, .admin-table th { vertical-align: middle; }
    .admin-table td { max-width: 220px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .review-img { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; }
    .btn-group { display: flex; gap: 4px; }
    .admin-table .btn { padding: 4px 10px; font-size: 13px; }
  </style>
</head>
<body>
<div class="admin-container">
  <?php include 'admin_sidebar.php'; ?>
  <main class="main-content">
    <h1>리뷰 관리</h1>
    <p>작성된 도서 리뷰를 확인하고 관리할 수 있습니다.</p>
    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>도서명</th>
          <th>작성자</th>
          <th>내용</th>
          <th>평점</th>
          <th>이미지</th>
          <th>작성일</th>
          <th>관리</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['id']) ?></td>
          <td><?= htmlspecialchars($row['book_title'] ?? '-') ?></td>
          <td><?= htmlspecialchars($row['username'] ?? '-') ?></td>
          <td title="<?= htmlspecialchars($row['content'] ?? '') ?>">
            <?= isset($row['content']) ? mb_strimwidth(htmlspecialchars($row['content']), 0, 40, '...', 'UTF-8') : '-' ?>
          </td>
          <td><?= htmlspecialchars($row['rating'] ?? '-') ?></td>
          <td>
            <?php if (!empty($row['image'])): ?>
              <img src="<?= htmlspecialchars($row['image']) ?>" alt="리뷰 이미지" class="review-img">
            <?php else: ?>
              <span style="color: #aaa;">없음</span>
            <?php endif; ?>
          </td>
          <td><?= isset($row['created_at']) ? date('Y-m-d H:i', strtotime($row['created_at'])) : '-' ?></td>
          <td>
            <div class="btn-group">
              <form action="admin_review_delete.php" method="POST" onsubmit="return confirm('정말 삭제하시겠습니까?');">
                <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                <button type="submit" class="btn">삭제</button>
              </form>
            </div>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </main>
</div>
</body>
</html>
