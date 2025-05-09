<?php 
require_once 'session_start.php';
require_once 'connect.php';

if (!empty($user_id)) {
    // 세션에서 사용자 ID
    $user_id = $_SESSION['user_id'];
    // POST 값 받기
    $book_id = $_POST['book_id'];
    $content = $_POST['content'];
    $rating  = $_POST['rating'];
    $username = $_POST['username'];
    
    // 파일 업로드 처리
    $target_dir = "reviews/";
    
    // 업로드 디렉토리가 없으면 생성
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    // 파일 업로드 검증
    if (isset($_FILES['image_path']) && $_FILES['image_path']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($_FILES['image_path']['type'], $allowed_types)) {
            echo "<script>alert('지원하지 않는 파일 형식입니다. (JPEG, PNG, GIF만 가능)');</script>";
            echo "<script>location.href='../category/book_detail.php?id=$book_id';</script>";
            exit;
        }
        
        if ($_FILES['image_path']['size'] > $max_size) {
            echo "<script>alert('파일 크기는 5MB를 초과할 수 없습니다.');</script>";
            echo "<script>location.href='../category/book_detail.php?id=$book_id';</script>";
            exit;
        }
        
        $file_extension = strtolower(pathinfo($_FILES['image_path']['name'], PATHINFO_EXTENSION));
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
        $image_path = null; // 파일이 업로드되지 않은 경우
    }
    
    // AUTO_INCREMENT인 id는 생략
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