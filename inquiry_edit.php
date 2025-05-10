<?php require_once 'session_start.php'; ?>
<?php require_once 'connect.php'; ?>
<?php

$inquiry_id = $_GET['id'];
// 게시글 정보 조회
$sql = "SELECT * FROM inquiries WHERE id = $inquiry_id";
$result_inquiry = $conn->query($sql);
$inquiry = $result_inquiry->fetch_assoc();

// 첨부 이미지 조회
$sql = "SELECT * FROM inquiries_images WHERE inquiry_id = $inquiry_id";
$result_images = $conn->query($sql);
$images = $result_images->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $inquiry_file = $_FILES['inquiry_file'];
    $deleteImage = $_POST['deleteImage'];

    if ($deleteImage) {
        $sql = "DELETE FROM inquiries_images WHERE inquiry_id = $inquiry_id";
        $conn->query($sql);
    }
    
    $sql = "UPDATE inquiries SET title = '$title', content = '$content' WHERE id = $inquiry_id";
    $conn->query($sql);

    if (isset($inquiry_file['name']) && $_FILES['inquiry_file']['error'] === UPLOAD_ERR_OK){

        $sql = "DELETE FROM inquiries_images WHERE inquiry_id = $inquiry_id";
        $conn->query($sql);

        $file_name = time() . '_' . $_FILES['inquiry_file']['name'];
        $upload_path = 'uploads/' . $file_name;

        if (move_uploaded_file($_FILES['inquiry_file']['tmp_name'], $upload_path)) {
            $sql_image = "INSERT INTO inquiries_images (inquiry_id, image_path) VALUES ($inquiry_id, '$upload_path')";
            $conn->query($sql_image);
        }
    }
    echo "<script>alert('문의사항이 수정되었습니다.');</script>";
    echo "<script>location.href = 'inquiry_detail.php?id=$inquiry_id';</script>";
    exit;
}

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>문의사항 수정 - 온라인 서점</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/board.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <div class="board-container">
            <div class="board-header">
                <h2>문의사항 수정</h2>
            </div>
            <form class="post-form" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title" class="form-label">제목</label>
                    <input type="text" id="title" name="title" class="form-input" 
                           value="<?php echo $inquiry['title']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="content" class="form-label">내용</label>
                    <textarea id="content" name="content" class="form-textarea" required><?php 
                        echo $inquiry['content']; 
                    ?></textarea>
                </div>
                <?php if ($images): ?>
                <div class="form-group">
                    <label class="form-label">현재 첨부된 이미지</label>
                    <div class="attached-images">
                        <?php foreach ($images as $image): ?>
                        <div class="image-item">
                            <img src="<?php echo $image['image_path']; ?>" alt="첨부 이미지">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="image-actions">
                        <input type="checkbox" class="select-btn" name="deleteImage" onclick="deleteImage()">기존 이미지 삭제
                    </div>
                </div>
                <?php endif; ?>
                <div class="form-group">
                    <label for="inquiry_file" class="form-label">새 파일 첨부</label>
                    <input type="file" id="inquiry_file" name="inquiry_file" class="form-input" accept="image/*">
                </div>
                <div class="form-actions">
                    <a href="inquiry_detail.php?id=<?php echo $inquiry_id; ?>" class="cancel-btn">취소</a>
                    <button type="submit" class="submit-btn">수정</button>
                </div>
            </form>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
<script>
    function deleteImage() {
        if (confirm('이미지를 삭제하시겠습니까?')) {
            const attachment = document.querySelector('.attached-images');
            attachment.style.display = 'none';
        }
    }
</script>
</html>