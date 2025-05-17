<?php
include 'connect.php';
include 'admin_sidebar.php';

// 결제 완료된 주문만 조회
$sql = "SELECT * FROM orders WHERE status = 'paid' ORDER BY created_at DESC"; // paid_at → created_at
$result = mysqli_query($conn, $sql);
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
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?= ($row['id']) ?></td>
              <td><?= ($row['user_id']) ?></td>
              <td><?= number_format($row['total_price']) ?>원</td>
              <td><?= $row['paid_at'] ?? '정보 없음' ?></td>
              <td><?= $row['token'] ?? '-' ?></td>
              <td><a href="admin_payment_detail.php?id=<?= $row['id'] ?>">보기</a></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </main>
  </div>
</body>
</html>
