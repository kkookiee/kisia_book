<?php
ob_start();
require_once 'session_start.php';
require_once 'connect.php';

// ✅ 로그인 확인
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

// ✅ ID 유효성 확인
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('올바른 요청이 아닙니다.');
}
$inquiry_id = (int)$_GET['id'];

// ✅ 게시글 조회
$stmt = $conn->prepare("SELECT * FROM inquiries WHERE id = ?");
$stmt->bind_param("i", $inquiry_id);
$stmt->execute();
$result_inquiry = $stmt->get_result();
$inquiry = $result_inquiry->fetch_assoc();
$stmt->close();

if (!$inquiry) {
    echo "<script>alert('존재하지 않는 문의글입니다.'); location.href='board.php';</script>";
    exit;
}

// ✅ 첨부 이미지 조회
$stmt = $conn->prepare("SELECT * FROM inquiries_images WHERE inquiry_id = ?");
$stmt->bind_param("i", $inquiry_id);
$stmt->execute();
$result_images = $stmt->get_result();
$images = $result_images->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// ✅ 수정 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    // 게시글 내용 업데이트
    $stmt = $conn->prepare("UPDATE inquiries SET title = ?, content = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $content, $inquiry_id);
    $stmt->execute();
    $stmt->close();

    $deleteImage = isset($_POST['deleteImage']) && $_POST['deleteImage'] === '1';
    $imageDeleted = false;
    $imageUploaded = false;

    // 새 파일 업로드 처리
    if (isset($_FILES['inquiry_file']) && $_FILES['inquiry_file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['inquiry_file']['tmp_name'];
        $file_name = basename($_FILES['inquiry_file']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file_tmp);
        finfo_close($finfo);

        if (in_array($file_ext, $allowed_ext) && strpos($mime, 'image/') === 0) {
            if (!is_dir('uploads')) {
                mkdir('uploads', 0755, true);
            }

            $new_name = time() . '_' . bin2hex(random_bytes(4)) . '.' . $file_ext;
            $upload_path = 'uploads/' . $new_name;

            if (move_uploaded_file($file_tmp, $upload_path)) {
                // 기존 이미지 삭제
                $stmt = $conn->prepare("DELETE FROM inquiries_images WHERE inquiry_id = ?");
                $stmt->bind_param("i", $inquiry_id);
                $stmt->execute();
                $stmt->close();
                $imageDeleted = true;

                // 새 이미지 등록
                $stmt = $conn->prepare("INSERT INTO inquiries_images (inquiry_id, image_path) VALUES (?, ?)");
                $stmt->bind_param("is", $inquiry_id, $upload_path);
                $stmt->execute();
                $stmt->close();
                $imageUploaded = true;
            }
        }
    } elseif ($deleteImage) {
        // 이미지 삭제 요청 처리 (업로드 없을 경우에만)
        $stmt = $conn->prepare("DELETE FROM inquiries_images WHERE inquiry_id = ?");
        $stmt->bind_param("i", $inquiry_id);
        $stmt->execute();
        $stmt->close();
        $imageDeleted = true;
    }

    echo "<script>alert('문의사항이 수정되었습니다.'); location.href='inquiry_detail.php?id=$inquiry_id';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
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
                       value="<?php echo htmlspecialchars($inquiry['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="content" class="form-label">내용</label>
                <textarea id="content" name="content" class="form-textarea" required><?php 
                    echo htmlspecialchars($inquiry['content']); 
                ?></textarea>
            </div>
            <?php if (!empty($images)): ?>
            <div class="form-group">
                <label class="form-label">현재 첨부된 이미지</label>
                <div class="attached-images">
                    <?php foreach ($images as $image): ?>
                        <?php 
                            $imagePath = htmlspecialchars($image['image_path']);
                            if (!empty($imagePath) && file_exists($imagePath)): 
                        ?>
                        <div class="image-item">
                            <img src="<?php echo $imagePath; ?>" alt="첨부 이미지">
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <div>
                        <label><input type="checkbox" name="deleteImage" value="1"> 이미지 삭제</label>
                    </div>
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
</html>
