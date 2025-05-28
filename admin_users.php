<?php
session_start();
require_once 'connect.php';

// ✅ 관리자 인증 확인
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    http_response_code(403);
    exit('접근 권한이 없습니다.');
}

// ✅ 검색어 처리
$search = trim($_GET['q'] ?? '');
$search_param = '%' . $search . '%';

// ✅ SQL 준비
if ($search) {
    $stmt = $conn->prepare("SELECT id, username, name, email, created_at FROM users WHERE name LIKE ? OR email LIKE ? ORDER BY created_at DESC");
    $stmt->bind_param("ss", $search_param, $search_param);
} else {
    $stmt = $conn->prepare("SELECT id, username, name, email, created_at FROM users ORDER BY created_at DESC");
}

$stmt->execute();
$result = $stmt->get_result();
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
      <input type="text" name="q" placeholder="이름 또는 이메일 검색"
             value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
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
          <td><?= htmlspecialchars($row['id']) ?></td>
          <td><?= htmlspecialchars($row['username']) ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= htmlspecialchars($row['created_at']) ?></td>
          <td>
            <a href="admin_user_edit.php?id=<?= $row['id'] ?>" class="btn">수정</a>
            <a href="admin_user_delete.php?id=<?= $row['id'] ?>" class="btn delete-link"
               onclick="return confirm('정말 삭제하시겠습니까?')">삭제</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </main>
</div>
</body>
</html>
