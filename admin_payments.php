<?php
session_start();
require_once 'connect.php';

// ✅ 관리자 인증 확인 먼저!
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
  // 404 페이지로 리다이렉트
  header("Location: /404.php");
  exit();
}

require_once 'admin_sidebar.php';


// ✅ Prepared Statement 사용
$stmt = $conn->prepare("SELECT * FROM orders WHERE status = 'paid' ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>결제 관리</title>
  <link rel="stylesheet" href="/css/style.css">
  <link rel="stylesheet" href="/css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <div class="admin-container">
    <main class="main-content">
      <h1>결제 관리</h1>
      <table class="admin-table">
        <thead>
          <tr>
            <th>주문번호</th>
            <th>사용자 ID</th>
            <th>총 금액</th>
            <th>결제 일시</th>
            <th>결제 토큰</th>
            <th>상세보기</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['id']) ?></td>
              <td><?= htmlspecialchars($row['user_id']) ?></td>
              <td><?= number_format($row['total_price']) ?>원</td>
              <td><?= htmlspecialchars($row['paid_at'] ?? '정보 없음') ?></td>
              <td><?= htmlspecialchars($row['token'] ?? '-') ?></td>
              <td><a href="admin_payment_detail.php?id=<?= urlencode($row['id']) ?>" class="btn">보기</a></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </main>
  </div>
</body>
</html>
