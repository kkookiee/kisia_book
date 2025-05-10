<?php require_once 'session_start.php'; ?>
<?php require_once 'connect.php'; ?>
<?php

$inquiry_id = $_GET['id'];

// 게시글 정보 조회
$sql = "SELECT inquiries.*, users.username 
        FROM inquiries 
        LEFT JOIN users ON inquiries.user_id = users.id 
        WHERE inquiries.id = $inquiry_id";
$result_inquiry = $conn->query($sql);
$inquiry = $result_inquiry->fetch_assoc();

if (!$inquiry) {
    echo "<script>alert('존재하지 않는 문의글입니다.');</script>";
    echo "<script>window.location.href='board.php';</script>";
    exit();
}

$sql = "SELECT * FROM inquiries_images WHERE inquiry_id = $inquiry_id";
$result_images = $conn->query($sql);
$images = $result_images->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $inquiry['title']; ?> - 온라인 서점</title>
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
                    <h3 class="post-view-title"><?php echo $inquiry['title']; ?></h3>
                    <div class="post-view-info">
                        <span class="post-view-author">작성자: <?php echo $inquiry['username']; ?></span>
                        <span class="post-view-date">작성일: <?php echo date('Y-m-d H:i', strtotime($inquiry['created_at'])); ?></span>
                        <span class="post-view-status">상태: <?php echo $inquiry['inquiry_status']; ?></span>
                    </div>
                </div>
                <div class="post-view-content">
                    <?php echo nl2br(($inquiry['content'])); ?>
                </div>
                <?php if ($images): ?>
                <div class="post-view-images">
                    <?php foreach ($images as $image): ?>
                    <div class="post-image">
                        <img src="<?php echo $image['image_path']; ?>" alt="첨부 이미지">
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <?php if ($inquiry['answer']): ?>
                <div class="post-view-answer">
                    <h4>답변</h4>
                    <div class="answer-content">
                        <?php echo nl2br($inquiry['answer']); ?>
                    </div>
                </div>
                <?php endif; ?>
                <div class="post-view-actions">
                    <a href="board.php" class="btn-back">목록으로</a>
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $inquiry['user_id']): ?>
                    <a href="inquiry_edit.php?id=<?php echo $inquiry['id']; ?>" class="btn-edit">수정</a>
                    <button class="btn-delete" onclick="deleteInquiry(<?php echo $inquiry['id']; ?>)">삭제</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>
    <script>
    function deleteInquiry(id) {
        if (confirm('정말 삭제하시겠습니까?')) {
            location.href = 'inquiry_delete.php?id=' + id;
        }
    }
    </script>
</body>
</html>