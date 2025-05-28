<?php 
require_once 'session_start.php';
require_once 'connect.php';

if (!empty($_SESSION['user_id'])) {
    $user_id  = $_SESSION['user_id'];
    $book_id  = (int)$_POST['book_id'];
    $content  = trim($_POST['content']);
    $rating   = (int)$_POST['rating'];
    $username = trim($_POST['username']);

    $image_path = null;

    $target_dir = __DIR__ . "reviews/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    if (isset($_FILES['image_path']) && $_FILES['image_path']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['image_path']['tmp_name'];
        $file_name = $_FILES['image_path']['name'];
        $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $mime_type = mime_content_type($file_tmp);

        // ✅ 허용 확장자 및 MIME 검사
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $allowed_mime = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($file_ext, $allowed_ext) || !in_array($mime_type, $allowed_mime)) {
            echo "<script>alert('허용되지 않는 파일 형식입니다.'); history.back();</script>";
            exit;
        }

        $new_filename = uniqid() . '.' . $file_ext;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($file_tmp, $target_file)) {
            $image_path = 'uploads/reviews/' . $new_filename;
        } else {
            echo "<script>alert('파일 업로드에 실패했습니다.'); history.back();</script>";
            exit;
        }
    }

    // ✅ Prepared Statement로 SQL Injection 방지
    $stmt = $conn->prepare("INSERT INTO reviews (user_id, book_id, content, rating, username, image_path) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisisb", $user_id, $book_id, $content, $rating, $username, $image_path);
    $stmt->execute();

    echo "<script>alert('리뷰가 등록되었습니다.'); location.href='../category/book_detail.php?id={$book_id}';</script>";
    exit;
} else {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href='../login.php';</script>";
    exit;
}
