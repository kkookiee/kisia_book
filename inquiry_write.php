<?php require_once 'session_start.php'; ?>
<?php require_once 'connect.php'; ?>
<?php
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인 후 이용해주세요.');</script>";
    echo "<script>location.href = 'login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO inquiries (user_id, title, content) VALUES ($user_id, '$title', '$content')";
    $conn->query($sql);
    $inquiry_id = $conn->insert_id;

    // 파일 업로드 처리
    if (isset($_FILES['inquiry_file']) && $_FILES['inquiry_file']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/uploads';

        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $original_name = $_FILES['inquiry_file']['name'];
        $file_name = time() . '_' . basename($original_name);
        $upload_path = 'uploads/' . $file_name;

        if (move_uploaded_file($_FILES['inquiry_file']['tmp_name'], $upload_path)) {
            $sql_image = "INSERT INTO inquiries_images (inquiry_id, image_path) VALUES ($inquiry_id, '$upload_path')";
            $conn->query($sql_image);
        }
    }

    echo "<script>alert('문의사항이 등록되었습니다.');</script>";
    echo "<script>location.href = 'board.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>문의사항 작성 - 온라인 서점</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/board.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <div class="board-container">
            <div class="board-header">
                <h2>문의사항 작성</h2>
            </div>
            <form class="post-form" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title" class="form-label">제목</label>
                    <input type="text" id="title" name="title" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="content" class="form-label">내용</label>
                    <textarea id="content" name="content" class="form-textarea" required></textarea>
                </div>
                <div class="form-group">
                    <label for="inquiry_file" class="form-label">파일 첨부</label>
                    <!-- accept 속성 제거: 모든 파일 가능 -->
                    <input type="file" id="inquiry_file" name="inquiry_file" class="form-input">
                </div>
                <div class="form-actions">
                    <a href="board.php" class="cancel-btn">취소</a>
                    <button type="submit" class="submit-btn">등록</button>
                </div>
            </form>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
