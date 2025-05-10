<?php
include 'connect.php';
$id = $_GET['id'] ?? 0;
if (!$id) die('잘못된 접근입니다.');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';
    $sql = "UPDATE users SET name=?, email=?, username=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $name, $email, $username, $id);
    $stmt->execute();
    header('Location: admin_users.php');
    exit;
}
$user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>회원 정보 수정</title>
  <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<div class="admin-container">
  <?php include 'admin_sidebar.php'; ?>
  <main class="main-content">
    <form method="post" class="edit-form">
      <h1>회원 정보 수정</h1>
      <label>아이디</label>
      <input type="text" name="username" value="<?= ($user['username']) ?>" required>
      <label>이름</label>
      <input type="text" name="name" value="<?= ($user['name']) ?>" required>
      <label>이메일</label>
      <input type="email" name="email" value="<?= ($user['email']) ?>" required>
      <button type="submit">수정 완료</button>
    </form>
  </main>
</div>
</body>
</html> 