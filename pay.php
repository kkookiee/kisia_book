<?php
require_once 'connect.php';

$token = $_GET['token'] ?? '';

if (!$token) {
    die("잘못된 접근입니다.");
}

$sql = "UPDATE orders SET status = 'paid' WHERE token = '$token'";
$conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>결제 완료</title>
</head>
<body>
  <h2>결제가 완료되었습니다!</h2>
  <p>이 창은 자동으로 닫힙니다...</p>
  <script>
    // 2초 후 창 닫기
    setTimeout(() => {
      window.close();

      // window.close()가 팝업 차단으로 안 될 경우 대비
      window.location.href = "about:blank";
    }, 2000);
  </script>
</body>
</html>
