<?php 
require_once 'session_start.php';
require_once 'connect.php';

if (!empty($_SESSION['user_id'])) {
    $user_id  = $_SESSION['user_id'];
    $book_id  = $_POST['book_id'];
    $content  = $_POST['content'];
    $rating   = $_POST['rating'];
    $username = $_POST['username'];

    $target_dir = "reviews/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (isset($_FILES['image_path']) && $_FILES['image_path']['error'] === UPLOAD_ERR_OK) {
        // ✅ 보안 필터 제거: 모든 확장자 허용
        $original_name = $_FILES['image_path']['name'];
        $file_extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($_FILES['image_path']['tmp_name'], $target_file)) {
            $image_path = $target_file;
        } else {
            echo "<script>alert('파일 업로드에 실패했습니다.');</script>";
            echo "<script>location.href='../category/book_detail.php?id=$book_id';</script>";
            exit;
        }
    } else {
        $image_path = null;
    }

    $sql = "INSERT INTO reviews (user_id, book_id, content, rating, username, image_path) 
            VALUES ('$user_id', '$book_id', '$content', '$rating', '$username', '$image_path')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script>alert('리뷰가 등록되었습니다.');</script>";
        echo "<script>location.href='../category/book_detail.php?id=$book_id';</script>";
    } else {
        echo "<script>alert('리뷰 등록에 실패했습니다.');</script>";
        echo "<script>location.href='../category/book_detail.php?id=$book_id';</script>";
    }
} else {
    echo "<script>alert('로그인 후 이용해주세요.');</script>";
    echo "<script>location.href='../login.php';</script>";
    exit;
}
?>
