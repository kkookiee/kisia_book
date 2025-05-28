<?php
session_start();
require_once 'connect.php';

// ✅ 관리자 권한 확인
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    http_response_code(403);
    exit('접근 권한이 없습니다.');
}

// ✅ GET id 유효성 검사
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(400);
    exit('잘못된 접근입니다.');
}

// ✅ POST 요청 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (!$username || !$name || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        exit('입력값이 올바르지 않습니다.');
    }

    // ✅ Prepared Statement로 SQL Injection 방지
    $stmt = $conn->prepare("UPDATE users SET username = ?, name = ?, email = ? WHERE id = ?");
    $stmt->bind_param("sssi", $username, $name, $email, $id);
    $stmt->execute();
    $stmt->close();

    header('Location: admin_users.php');
    exit;
}

// ✅ 사용자 정보 조회
$stmt = $conn->prepare("SELECT username, name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    http_response_code(404);
    exit('해당 사용자를 찾을 수 없습니다.');
}

// ✅ 출력 시 XSS 방지
$username_safe = htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8');
$name_safe = htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8');
$email_safe = htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8');
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
      <input type="text" name="username" value="<?= $username_safe ?>" required>
      <label>이름</label>
      <input type="text" name="name" value="<?= $name_safe ?>" required>
      <label>이메일</label>
      <input type="email" name="email" value="<?= $email_safe ?>" required>
      <button type="submit">수정 완료</button>
    </form>
  </main>
</div>
</body>
</html>
