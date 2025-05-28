<?php
require_once 'connect.php';
require_once 'session_start.php';

// ✅ 로그인 여부 확인
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href = 'login.php';</script>";
    exit;
}

$user_id = (int) $_SESSION['user_id'];

// ✅ CSRF 토큰 생성
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ✅ CSRF 토큰 검증
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo "<script>alert('잘못된 요청입니다.'); location.href = 'withdraw.php';</script>";
        exit;
    }

    // ✅ 탈퇴 처리 (Prepared Statement)
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    session_destroy(); // ✅ 세션 종료
    unset($_SESSION);

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
    <div class="withdraw-container">
      <h2>회원 탈퇴</h2>
      <p>정말로 탈퇴하시겠습니까?<br>탈퇴 시 모든 정보가 삭제되며 복구되지 않습니다.</p>
      <form method="post">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        <button type="submit" class="withdraw-btn" onclick="return confirm('정말 탈퇴하시겠습니까?');">탈퇴하기</button>
      </form>
    </div>
  </main>
  <?php include 'footer.php'; ?>
</body>
</html>
