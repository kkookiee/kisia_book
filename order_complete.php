<?php
require_once 'connect.php';
session_start();
require_once 'header.php';

// 1. 주문 ID 가져오기
$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
  die('잘못된 접근입니다.');
}

// 2. QR용 token 생성 & 저장
$token = $order_id;
$conn->query("UPDATE orders SET token = '$token', status = 'pending' WHERE id = $order_id");

// 3. QR코드 URL 생성
$qr_url = "http://kisia-book.koreacentral.cloudapp.azure.com:8080/pay.php?token=$token";
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인 - 온라인 서점</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/auth.css">
    <link rel="stylesheet" href="css/order_complete.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
  <script>
    // QR 결제 상태 체크 (3초마다)
    setInterval(() => {
      fetch('check_status.php?token=<?= $token ?>')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'paid') {
            alert('결제가 완료되었습니다!');
            window.location.href = 'mypage.php';
          }
        });
    }, 3000);
  </script>
<body>
  <div class="complete-container">
    <h2>주문이 완료되었습니다!</h2>
    <p>아래 QR 코드를 스캔해 결제를 완료해 주세요.</p>
    <img src="generate_qr.php?data=<?= urlencode($qr_url) ?>" alt="결제 QR코드">
    <br>
    <a href="mypage.php" class="btn">마이페이지로 이동</a>
  </div>
</body>
<?php require_once 'footer.php'; ?>
</html>
