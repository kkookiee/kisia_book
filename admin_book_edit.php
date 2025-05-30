<?php
session_start();
require_once 'connect.php';

// ✅ 관리자 인증 확인 먼저!
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    // 404 페이지로 리다이렉트
    header("Location: /404.php");
    exit();
}

// ✅ GET 파라미터 유효성 검사
$id = $_GET['id'] ?? '';
if (!$id) {
    http_response_code(400);
    exit('잘못된 요청입니다.');
}

// ✅ Prepared Statement를 통한 SQL 실행 (SQL Injection 방지)
$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->bind_param("s", $id); // 문자열 ID에 맞게 수정
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    exit('도서를 찾을 수 없습니다.');
}

$book = $result->fetch_assoc();
$stmt->close();

// ✅ 출력 시 XSS 방지
$title = htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8');
$author = htmlspecialchars($book['author'], ENT_QUOTES, 'UTF-8');
$price = htmlspecialchars($book['price'], ENT_QUOTES, 'UTF-8');
$category = htmlspecialchars($book['category'], ENT_QUOTES, 'UTF-8');
?>

<!-- ✅ 수정 폼 HTML -->
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>도서 수정</title>
</head>
<body>
    <h2>도서 정보 수정</h2>
    <form action="admin_book_update.php" method="POST">
  <input type="hidden" name="id" value="<?= $book['id'] ?>">
  
  <label for="title">제목:</label>
  <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>

  <label for="author">저자:</label>
  <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>" required>

  <label for="price">가격:</label>
  <input type="number" name="price" value="<?= htmlspecialchars($book['price']) ?>" required>

  <label for="price">카테고리리:</label>
  <input type="text" name="category" value="<?= htmlspecialchars($book['category']) ?>" required>

  <button type="submit">수정하기</button>
</form>

</body>
</html>