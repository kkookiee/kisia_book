<?php
include 'connect.php';

// 🚨 Security Misconfiguration: 에러 노출
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$inquiry_id = $_GET['id'] ?? 0;

// 🚨 Broken Access Control: 세션 체크 없음
// 🚨 SQL Injection 가능
$sql = "SELECT i.*, u.username FROM inquiries i 
        JOIN users u ON i.user_id = u.id 
        WHERE i.id = $inquiry_id";
$result = $conn->query($sql);
$inquiry = $result->fetch_assoc();

// 🚨 SQL Injection 가능
$img_sql = "SELECT image_path FROM inquiries_images WHERE inquiry_id = $inquiry_id";
$img_result = $conn->query($img_sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>문의 답변</title>
  <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<div class="admin-container">
  <?php include 'admin_sidebar.php'; ?>
  <main class="main-content">
    <h1>문의 답변</h1>

    <p><strong>작성자:</strong> <?= $inquiry['username'] ?></p>
    <p><strong>제목:</strong> <?= $inquiry['title'] ?></p>
    <p><strong>내용:</strong><br><?= nl2br($inquiry['content']) ?></p> <!-- 🚨 XSS 가능 -->

    <?php if ($img_result->num_rows > 0): ?>
    <h3>첨부 이미지</h3>
    <div style="display: flex; gap: 10px;">
        <?php while ($img = $img_result->fetch_assoc()): ?>
            <img src="<?= $img['image_path'] ?>" alt="첨부 이미지" style="width:80px;height:80px;object-fit:cover;border:1px solid #ccc;">
        <?php endwhile; ?>
    </div>
    <?php endif; ?>

    <form action="admin_inquiry_reply_process.php" method="post" style="margin-top: 20px;">
      <input type="hidden" name="id" value="<?= $inquiry['id'] ?>">
      <label for="answer">답변 내용</label><br>
      <textarea name="answer" rows="6" style="width:100%;"><?= $inquiry['answer'] ?></textarea><br><br>
      <button type="submit" class="btn">답변 저장</button>
    </form>
  </main>
</div>
</body>
</html>
