<?php 
require_once 'session_start.php';
require_once 'connect.php';

$username = $_SESSION['username'] ?? '';
if ($username === '') {
    echo "<script>alert('사용자 이름이 없습니다.'); history.back();</script>";
    exit;
}

$user_id  = $_SESSION['user_id'];
$book_id  = trim($_POST['book_id'] ?? '');
$content  = trim($_POST['content'] ?? '');
$rating   = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;

// 도서 유효성 확인
$book_check = $conn->prepare("SELECT id FROM books WHERE id = ?");
$book_check->bind_param("s", $book_id);
$book_check->execute();
$book_result = $book_check->get_result();
if ($book_result->num_rows === 0) {
    exit('잘못된 도서 ID입니다.');
}

// 이미지 업로드 처리
$image_path = null;
$upload_dir = __DIR__ . "/reviews/";
$web_path_prefix = "reviews/";

if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

if (isset($_FILES['image_path']) && $_FILES['image_path']['error'] === UPLOAD_ERR_OK) {
    $file_tmp  = $_FILES['image_path']['tmp_name'];
    $file_name = $_FILES['image_path']['name'];
    $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $mime_type = mime_content_type($file_tmp);

    $allowed_ext  = ['jpg', 'jpeg', 'png', 'gif'];
    $allowed_mime = ['image/jpeg', 'image/png', 'image/gif'];

    if (!in_array($file_ext, $allowed_ext) || !in_array($mime_type, $allowed_mime)) {
        echo "<script>alert('허용되지 않는 파일 형식입니다.'); history.back();</script>";
        exit;
    }

    $new_filename = uniqid('review_', true) . '.' . $file_ext;
    $target_file = $upload_dir . $new_filename;

    if (move_uploaded_file($file_tmp, $target_file)) {
        $image_path = $web_path_prefix . $new_filename;
    } else {
        echo "<script>alert('파일 업로드에 실패했습니다.'); history.back();</script>";
        exit;
    }
}

// null → 빈 문자열 처리
if ($image_path === null) {
    $image_path = '';
}

// DB 저장
$stmt = $conn->prepare("INSERT INTO reviews (user_id, book_id, content, rating, username, image_path) 
                        VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ississ", $user_id, $book_id, $content, $rating, $username, $image_path);

if ($stmt->execute()) {
    echo "<script>alert('리뷰가 등록되었습니다.'); location.href='../category/book_detail.php?id={$book_id}';</script>";
    exit;
} else {
    echo "<script>alert('리뷰 등록에 실패했습니다.'); history.back();</script>";
    exit;
}
?>
