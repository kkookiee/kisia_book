<?php
require_once 'session_start.php';
require_once 'connect.php';

// ✅ 로그인 확인
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href = 'login.php';</script>";
    exit;
}

// ✅ CSRF 토큰 생성
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ✅ CSRF 토큰 검증
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo "<script>alert('잘못된 요청입니다.'); history.back();</script>";
        exit;
    }

    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $user_id = $_SESSION['user_id'];

    if (empty($title) || empty($content)) {
        echo "<script>alert('제목과 내용을 입력해주세요.'); history.back();</script>";
        exit;
    }

    // ✅ 게시글 저장
    $stmt = $conn->prepare("INSERT INTO inquiries (user_id, title, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $title, $content);
    $stmt->execute();
    $inquiry_id = $stmt->insert_id;
    $stmt->close();

    // ✅ 파일 업로드 처리
    if (isset($_FILES['inquiry_file']) && $_FILES['inquiry_file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['inquiry_file']['tmp_name'];
        $file_size = $_FILES['inquiry_file']['size'];
        $original_name = basename($_FILES['inquiry_file']['name']);
        $file_ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        // ✅ MIME 확인
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file_tmp);
        finfo_close($finfo);

        // ✅ 보안 검증
        if ($file_size <= 5 * 1024 * 1024 && in_array($file_ext, $allowed_ext) && strpos($mime, 'image/') === 0) {
            $upload_dir = __DIR__ . '/uploads';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $new_name = time() . '_' . bin2hex(random_bytes(4)) . '.' . $file_ext;
            $upload_path = 'uploads/' . $new_name;

            if (move_uploaded_file($file_tmp, $upload_path)) {
                $stmt = $conn->prepare("INSERT INTO inquiries_images (inquiry_id, image_path) VALUES (?, ?)");
                $stmt->bind_param("is", $inquiry_id, $upload_path);
                $stmt->execute();
                $stmt->close();
            }
        }
    }

    unset($_SESSION['csrf_token']); // ✅ 토큰 사용 후 삭제

    echo "<script>alert('문의사항이 등록되었습니다.'); location.href = 'board.php';</script>";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include 'header.php'; ?>
<main>
    <div class="board-container">
        <div class="board-header">
            <h2>문의사항 작성</h2>
        </div>
        <form class="post-form" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
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
                <input type="file" id="inquiry_file" name="inquiry_file" class="form-input" accept="image/*">
                <small>※ 최대 5MB, 이미지 파일만 업로드 가능합니다.</small>
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
