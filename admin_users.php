<?php
include 'connect.php';

// 🚨 Security Misconfiguration: SQL 에러 노출
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// 🚨 Broken Access Control: 세션 체크 없음

$search = $_GET['q'] ?? '';
$search_sql = '';
if (!empty($search)) {
    // 🚨 SQL Injection 가능: real_escape_string 제거
    $search_sql = "WHERE name LIKE '%$search%' OR email LIKE '%$search%'";
}

$sql = "SELECT id, username, name, email, created_at FROM users $search_sql ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>회원 관리 - 관리자 페이지</title>
  <link rel="stylesheet" href="css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="admin-container">
  <?php include 'admin_sidebar.php'; ?>
  <main class="main-content">
    <h1>회원 관리</h1>
    <p>전체 회원 목록을 확인하고 관리할 수 있습니다.</p>

    <form method="get" class="search-form">
      <input type="text" name="q" placeholder="이름 또는 이메일 검색" value="<?= $search ?>">
      <button type="submit">검색</button>
    </form>

    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>아이디</th>
          <th>이름</th>
          <th>이메일</th>
          <th>가입일</th>
          <th>관리</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= $row['username'] ?></td> <!-- 🚨 XSS 가능 -->
          <td><?= $row['name'] ?></td> <!-- 🚨 XSS 가능 -->
          <td><?= $row['email'] ?></td> <!-- 🚨 XSS 가능 -->
          <td><?= $row['created_at'] ?></td>
          <td>
            <a href="admin_user_edit.php?id=<?= $row['id'] ?>" class="btn">수정</a>
            <a href="admin_user_delete.php?id=<?= $row['id'] ?>" class="btn delete-link" onclick="return confirm('정말 삭제하시겠습니까?')">삭제</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </main>
</div>
</body>
</html>
