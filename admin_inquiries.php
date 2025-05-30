<?php
session_start();
require_once 'connect.php';

// ✅ 관리자 인증 확인 먼저!
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
  // 404 페이지로 리다이렉트
  header("Location: /404.php");
  exit();
}

// ✅ 운영 환경용 에러 출력 제거
mysqli_report(MYSQLI_REPORT_OFF);

// ✅ 문의 목록 조회
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
          <td><?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?></td>
          <td>
            <span class="status-badge <?= $row['inquiry_status'] === '답변완료' ? 'status-completed' : 'status-pending' ?>">
              <?= htmlspecialchars($row['inquiry_status'], ENT_QUOTES, 'UTF-8') ?>
            </span>
          </td>
          <td><?= htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
          <td style="display: flex; gap: 5px;">
            <a href="admin_inquiry_reply.php?id=<?= urlencode($row['id']) ?>" class="btn">답변</a>
            <form method="POST" action="admin_inquiry_delete.php" onsubmit="return confirm('정말 삭제하시겠습니까?')">
              <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
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
