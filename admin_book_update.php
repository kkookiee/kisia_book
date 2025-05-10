<?php
include 'connect.php';

$id = intval($_POST['id']);
$title = $_POST['title'];
$author = $_POST['author'];
$price = intval($_POST['price']);
$category = $_POST['category'];

// 간단한 유효성 검사
if (!$id || !$title || !$author) {
    echo "필수 항목 누락!";
    exit;
}

// 업데이트
$sql = "UPDATE books SET 
          title = '$title', 
          author = '$author', 
          price = $price, 
          category = '$category'
        WHERE id = $id";

if ($conn->query($sql)) {
    header("Location: admin_books.php");
} else {
    echo "수정 실패: " . $conn->error;
}
?>
