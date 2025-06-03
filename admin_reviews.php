<?php
include 'connect.php';

// 🚨 Security Misconfiguration: SQL 에러 노출
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// 🚨 Broken Access Control: 세션 체크 없음
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
          <td><?= $row['id'] ?></td>
          <td><?= $row['book_title'] ?? '-' ?></td> <!-- 🚨 XSS 가능 -->
          <td><?= $row['username'] ?? '-' ?></td> <!-- 🚨 XSS 가능 -->
          <td title="<?= $row['content'] ?>">
            <?= isset($row['content']) ? mb_strimwidth($row['content'], 0, 40, '...', 'UTF-8') : '-' ?> <!-- 🚨 XSS 가능 -->
          </td>
          <td><?= isset($row['rating']) ? $row['rating'] : '-' ?></td>
          <td>
            <?php if (isset($row['image']) && $row['image']): ?>
              <img src="<?= $row['image'] ?>" alt="리뷰 이미지" class="review-img"> <!-- 🚨 XSS 가능 -->
            <?php else: ?>
              <span style="color: #aaa;">없음</span>
            <?php endif; ?>
          </td>
          <td>
            <?= isset($row['created_at']) ? date('Y-m-d H:i', strtotime($row['created_at'])) : '-' ?>
          </td>
          <td>
            <div class="btn-group">
              <!--<a href="admin_review_edit.php?id=<?= $row['id'] ?>" class="btn">수정</a> 수정하기 코드 없음 -->
              <a href="admin_review_delete.php?id=<?= $row['id'] ?>" class="btn delete-link" onclick="return confirm('정말 삭제하시겠습니까?')">삭제</a>
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
