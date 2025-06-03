<?php
include 'connect.php';
include 'session_start.php';

$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->query("DELETE FROM users WHERE id = $user_id");
    session_destroy();
    echo "<script>alert('회원 탈퇴가 완료되었습니다.'); location.href='index.php';</script>";
    exit;
}
?>


<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8" />
  <title>회원 탈퇴</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/withdraw.css">
     
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <?php include 'header.php'; ?>
  <main>
  <div class="withdraw-container"> <!-- 여기 클래스 수정 -->
    <h2>회원 탈퇴</h2>
    <p>정말로 탈퇴하시겠습니까? 모든 정보가 삭제됩니다.</p>
    <form method="post">
      <button type="submit" class="withdraw-btn" onclick="return confirm('정말 탈퇴하시겠습니까?');">탈퇴하기</button>
    </form>
  </div>
</main>

</body>
<?php require_once 'footer.php'; ?>
</html>