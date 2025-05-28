<?php
require_once 'session_start.php';
require_once 'connect.php';

// 로그인 여부 확인
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit();
}

// ID 유효성 검사
$inquiry_id = $_GET['id'] ?? null;
if (!is_numeric($inquiry_id)) {
    echo "<script>alert('잘못된 요청입니다.'); location.href='board.php';</script>";
    exit();
}
$inquiry_id = (int)$inquiry_id;

// 문의글 조회
$stmt = $conn->prepare("SELECT inquiries.*, users.username FROM inquiries LEFT JOIN users ON inquiries.user_id = users.id WHERE inquiries.id = ?");
$stmt->bind_param("i", $inquiry_id);
$stmt->execute();
$result = $stmt->get_result();
$inquiry = $result->fetch_assoc();

if (!$inquiry) {
    echo "<script>alert('존재하지 않는 문의글입니다.'); location.href='board.php';</script>";
    exit();
}

// 첨부 이미지 조회
$stmt_images = $conn->prepare("SELECT * FROM inquiries_images WHERE inquiry_id = ?");
$stmt_images->bind_param("i", $inquiry_id);
$stmt_images->execute();
$images = $stmt_images->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($inquiry['title'], ENT_QUOTES, 'UTF-8') ?> - 온라인 서점</title>
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
            <h2>문의사항 상세</h2>
        </div>
        <div class="post-view">
            <div class="post-view-header">
                <h3 class="post-view-title"><?= htmlspecialchars($inquiry['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                <div class="post-view-info">
                    <span class="post-view-author">작성자: <?= htmlspecialchars($inquiry['username'], ENT_QUOTES, 'UTF-8') ?></span>
                    <span class="post-view-date">작성일: <?= htmlspecialchars(date('Y-m-d H:i', strtotime($inquiry['created_at'])), ENT_QUOTES, 'UTF-8') ?></span>
                    <span class="post-view-status">상태: <?= htmlspecialchars($inquiry['inquiry_status'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            </div>
            <div class="post-view-content">
                <?= nl2br(htmlspecialchars($inquiry['content'], ENT_QUOTES, 'UTF-8')) ?>
            </div>

            <?php if ($images): ?>
            <div class="attached-files">
                <h4><i class="fa fa-paperclip"></i> 첨부 파일</h4>
                <ul class="attached-file-list">
                    <?php foreach ($images as $image): 
                        $path = htmlspecialchars($image['image_path'], ENT_QUOTES, 'UTF-8');
                        $filename = basename($path);
                        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                        $safeDownloadPath = 'download.php?file=' . urlencode($filename);
                    ?>
                    <li>
                        <a href="<?= $safeDownloadPath ?>">
                            <i class="fa fa-file-<?= in_array($ext, ['jpg', 'jpeg', 'png', 'gif']) ? 'image' : 'alt' ?>"></i> <?= $filename ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <?php if (!empty($inquiry['answer'])): ?>
            <div class="post-view-answer">
                <h4>답변</h4>
                <div class="answer-content">
                    <?= nl2br(htmlspecialchars($inquiry['answer'], ENT_QUOTES, 'UTF-8')) ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="post-view-actions">
                <a href="board.php" class="btn-back">목록으로</a>
                <?php if ($_SESSION['user_id'] == $inquiry['user_id']): ?>
                    <a href="inquiry_edit.php?id=<?= $inquiry_id ?>" class="btn-edit">수정</a>
                    <form method="POST" action="delete_inquiry.php" style="display:inline;" onsubmit="return confirm('정말 삭제하시겠습니까?');">
                        <input type="hidden" name="id" value="<?= $inquiry_id ?>">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        <button type="submit" class="btn-delete">삭제</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
