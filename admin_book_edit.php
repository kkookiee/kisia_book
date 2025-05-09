<?php
include 'connect.php';


$id = $_GET['id'] ?? '';
if (!$id) {
  die("잘못된 접근입니다.");
}

$sql = "SELECT * FROM books WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  die("도서를 찾을 수 없습니다.");
}

$book = $result->fetch_assoc();
$title = $book['title'];
$author = $book['author'];
$price = $book['price'];
$category = $book['category'];
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>도서 수정</title>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/admin.css">
</head>
<body>
  <div class="admin-container">
    <?php include 'admin_sidebar.php'; ?>
<main class="main-content">
  <form method="post" class="edit-form">
    <h1>도서 수정</h1>

    <label for="title">제목</label>
    <input type="text" name="title" id="title" value="<?= $title ?>">

    <label for="author">저자</label>
    <input type="text" name="author" id="author" value="<?= $author ?>">

    <label for="price">가격</label>
    <input type="number" name="price" id="price" step="0.01" value="<?= $price ?>">

    <label for="category">카테고리</label>
    <input type="text" name="category" id="category" value="<?= $category ?>">

    <button type="submit">수정 완료</button>
  </form>
</main>
  </div>
</body>
</html>
