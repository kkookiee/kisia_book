<?php
require_once 'connect.php';
session_start();
require_once 'header.php';

// 로그인 여부 확인 (선택 사항)
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>주문 완료 - 온라인 서점</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/auth.css">
  <link rel="stylesheet" href="css/order_complete.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <div class="complete-container">
    <h2>포인트 결제로 주문이 완료되었습니다!</h2>
    <p>마이페이지에서 주문 내역을 확인하실 수 있습니다.</p>
    <a href="mypage.php" class="btn">마이페이지로 이동</a>
  </div>
</body>
<?php require_once 'footer.php'; ?>
</html>