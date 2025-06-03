<?php
include 'connect.php';

// 🚨 Security Misconfiguration: SQL 에러 노출
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$id = $_GET['id'] ?? 0;
if (!$id) die('잘못된 접근입니다.');

// 🚨 Broken Access Control: 세션 체크 없음
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';

    // 🚨 SQL Injection 가능 (Prepared Statement 제거)
    $sql = "UPDATE users SET name='$name', email='$email', username='$username' WHERE id=$id";
    $conn->query($sql);

    header('Location: admin_users.php');
    exit;
}

// 🚨 SQL Injection 가능
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
      <input type="text" name="username" value="<?= $user['username'] ?>" required> <!-- 🚨 XSS 가능 -->
      <label>이름</label>
      <input type="text" name="name" value="<?= $user['name'] ?>" required> <!-- 🚨 XSS 가능 -->
      <label>이메일</label>
      <input type="email" name="email" value="<?= $user['email'] ?>" required> <!-- 🚨 XSS 가능 -->
      <button type="submit">수정 완료</button>
    </form>
  </main>
</div>
</body>
</html>
