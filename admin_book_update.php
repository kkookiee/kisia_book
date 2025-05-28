<?php
session_start();
require_once 'connect.php';



// ✅ 관리자 권한 확인
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    http_response_code(403);
    exit('접근 권한이 없습니다.');
}

// ✅ 요청 방식 검사
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('허용되지 않는 요청 방식입니다.');
}

// ✅ 입력값 필터링
$id = trim($_POST['id'] ?? '');
$price_raw = $_POST['price'] ?? '';
$price = is_numeric($price_raw) ? (int)$price_raw : false;
$title = trim($_POST['title'] ?? '');
$author = trim($_POST['author'] ?? '');
$category = trim($_POST['category'] ?? '');

// ✅ 필수값 검증
if ($id === '' || $price === false || $title === '' || $author === '') {
    http_response_code(400);
    exit('필수 항목이 누락되었거나 잘못된 입력입니다.');
}

// ✅ Prepared Statement로 안전하게 업데이트
$stmt = $conn->prepare("UPDATE books SET title = ?, author = ?, price = ?, category = ? WHERE id = ?");
$stmt->bind_param("ssiss", $title, $author, $price, $category, $id);

if ($stmt->execute()) {
    header("Location: admin_books.php");
    exit;
} else {
    echo "수정 실패: " . $stmt->error;
}

$stmt->close();
?>
