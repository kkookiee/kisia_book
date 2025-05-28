<?php
session_start();
require_once 'connect.php';

// ✅ 관리자 인증 확인
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    http_response_code(403);
    exit('접근 권한이 없습니다.');
}

// ✅ ID 유효성 검사 (숫자 기준, books.id가 VARCHAR일 경우 "s" 사용)
$inquiry_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$inquiry_id) {
    http_response_code(400);
    exit('잘못된 요청입니다.');
}

// ✅ 문의 정보 조회 (Prepared Statement)
$stmt = $conn->prepare("
    SELECT i.*, u.username 
    FROM inquiries i 
    JOIN users u ON i.user_id = u.id 
    WHERE i.id = ?
");
$stmt->bind_param("i", $inquiry_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    exit('해당 문의를 찾을 수 없습니다.');
}
$inquiry = $result->fetch_assoc();
$stmt->close();

// ✅ 첨부 이미지 조회 (Prepared Statement)
$img_stmt = $conn->prepare("SELECT image_path FROM inquiries_images WHERE inquiry_id = ?");
$img_stmt->bind_param("i", $inquiry_id);
$img_stmt->execute();
$img_result = $img_stmt->get_result();
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

    <p><strong>작성자:</strong> <?= htmlspecialchars($inquiry['username']) ?></p>
    <p><strong>제목:</strong> <?= htmlspecialchars($inquiry['title']) ?></p>
    <p><strong>내용:</strong><br><?= nl2br(htmlspecialchars($inquiry['content'])) ?></p>

    <?php if ($img_result->num_rows > 0): ?>
    <h3>첨부 이미지</h3>
    <div style="display: flex; gap: 10px;">
        <?php while ($img = $img_result->fetch_assoc()): ?>
            <img src="<?= htmlspecialchars($img['image_path']) ?>" alt="첨부 이미지"
                 style="width:80px;height:80px;object-fit:cover;border:1px solid #ccc;">
        <?php endwhile; ?>
    </div>
    <?php endif; ?>
    <?php $img_stmt->close(); ?>

    <form action="admin_inquiry_reply_process.php" method="post" style="margin-top: 20px;">
      <input type="hidden" name="id" value="<?= $inquiry_id ?>">
      <label for="answer">답변 내용</label><br>
      <!--<textarea name="answer" rows="6" style="width:100%;"><?= htmlspecialchars($inquiry['answer']) ?></textarea>-->
      <textarea name="answer"><?= htmlspecialchars($inquiry['answer'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
      <br><br>
      <button type="submit" class="btn">답변 저장</button>
    </form>
  </main>
</div>
</body>
</html>
