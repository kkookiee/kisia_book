<?php
include 'connect.php';

$search = $_GET['q'] ?? '';
$search_sql = '';
if (!empty($search)) {
    $safe_search = $conn->real_escape_string($search);
    $search_sql = "WHERE name LIKE '%$safe_search%' OR email LIKE '%$safe_search%'";
}

$sql = "SELECT id, name, email, created_at FROM users $search_sql ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>회원 관리 - 관리자 페이지</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: 'Pretendard', sans-serif;
      background: #f5f7fa;
    }
    .admin-container {
      display: flex;
    }
    .sidebar {
      width: 240px;
      background-color: #2d3748;
      color: #fff;
      padding: 20px;
      height: 100vh;
    }
    .sidebar h2 {
      font-size: 24px;
      margin-bottom: 30px;
    }
    .sidebar ul {
      list-style: none;
      padding: 0;
    }
    .sidebar ul li {
      margin-bottom: 20px;
    }
    .sidebar ul li a {
      color: #fff;
      text-decoration: none;
      font-size: 16px;
    }
    .main-content {
      flex: 1;
      padding: 40px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      border-radius: 8px;
      overflow: hidden;
    }
    th, td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    th {
      background: #f2f2f2;
    }
    .search-bar {
      margin-bottom: 20px;
    }
    .search-bar input[type="text"] {
      padding: 8px;
      width: 250px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    .search-bar button {
      padding: 8px 12px;
      border: none;
      background: #4e73df;
      color: #fff;
      border-radius: 6px;
      cursor: pointer;
      margin-left: 5px;
    }
    .delete-link {
      color: red;
      text-decoration: none;
    }
  </style>
</head>
<body>
<div class="admin-container">
  <aside class="sidebar">
    <h2>관리자</h2>
    <ul>
      <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> 대시보드</a></li>
      <li><a href="admin_orders.php"><i class="fas fa-file-alt"></i> 주문 관리</a></li>
      <li><a href="admin_books.php"><i class="fas fa-book"></i> 도서 관리</a></li>
      <li><a href="admin_users.php"><i class="fas fa-users"></i> 회원 관리</a></li>
      <li><a href="admin_reviews.php"><i class="fas fa-comments"></i> 리뷰 관리</a></li>
      <li><a href="admin_board.php"><i class="fas fa-headphones"></i> 고객센터</a></li>
      <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> 로그아웃</a></li>
    </ul>
  </aside>

  <main class="main-content">
    <h1>회원 관리</h1>
    <p>전체 회원 목록을 확인하고 관리할 수 있습니다.</p>

    <form method="get" class="search-bar">
      <input type="text" name="q" placeholder="이름 또는 이메일 검색" value="<?= ($search) ?>">
      <button type="submit">검색</button>
    </form>

    <?php if ($result->num_rows === 0): ?>
      <p>검색 결과가 없습니다.</p>
    <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>이름</th>
          <th>이메일</th>
          <th>가입일</th>
          <th>관리</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= ($row['name']) ?></td>
            <td><?= ($row['email']) ?></td>
            <td><?= $row['created_at'] ?></td>
            
            <td><a href="user_delete.php?id=<?= $row['id'] ?>" class="delete-link" onclick="return confirm('정말 삭제하시겠습니까?');">삭제</a></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </main>
</div>
</body>
</html>