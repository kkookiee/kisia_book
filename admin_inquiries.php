<?php
include 'connect.php';

// 🚨 Security Misconfiguration: 모든 SQL 에러 노출
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// 🚨 Broken Access Control: 세션 체크 제거
// 원래는 if (!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }

$sql = "SELECT i.*, u.username 
        FROM inquiries i
        JOIN users u ON i.user_id = u.id
        ORDER BY i.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>문의 관리</title>
  <link rel="stylesheet" href="css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="admin-container">
  <?php include 'admin_sidebar.php'; ?>
  <main class="main-content">
    <h1>문의 관리</h1>
    <p>고객 문의글을 확인하고 답변할 수 있습니다.</p>

    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>작성자</th>
          <th>제목</th>
          <th>상태</th>
          <th>작성일</th>
          <th>관리</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= $row['username'] ?></td>
          <td><?= $row['title'] ?></td>
          <td>
            <span class="status-badge <?= $row['inquiry_status'] === '답변완료' ? 'status-completed' : 'status-pending' ?>">
              <?= $row['inquiry_status'] ?>
            </span>
          </td>
          <td><?= $row['created_at'] ?></td>
          <td>
            <a href="admin_inquiry_reply.php?id=<?= $row['id'] ?>" class="btn">답변</a>
            <a href="admin_inquiry_delete.php?id=<?= $row['id'] ?>" class="btn delete-link" onclick="return confirm('정말 삭제하시겠습니까?')">삭제</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </main>
</div>
</body>
</html>
