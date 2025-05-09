<?php
include 'connect.php';

$sql = "SELECT o.*, u.username 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        ORDER BY o.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>주문 관리</title>
  <link rel="stylesheet" href="css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="admin-container">
  <?php include 'admin_sidebar.php'; ?>
  <main class="main-content">
    <h1>주문 관리</h1>
    <p>주문 목록을 조회하고 관리할 수 있습니다.</p>

    <table class="admin-table">
      <thead>
        <tr>
          <th>주문 ID</th>
          <th>주문자</th>
          <th>수령인</th>
          <th>전화번호</th>
          <th>주소</th>
          <th>총 금액</th>
          <th>주문일</th>
          <th>관리</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= ($row['username']) ?></td>
          <td><?= ($row['recipient']) ?></td>
          <td><?= ($row['phone']) ?></td>
          <td><?= ($row['address']) ?></td>
          <td><?= number_format($row['total_price']) ?>원</td>
          <td><?= $row['created_at'] ?></td>
          <td>
            <a href="admin_order_detail.php?id=<?= $row['id'] ?>" class="btn">상세</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </main>
</div>
</body>
</html>
