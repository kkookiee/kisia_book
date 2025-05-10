<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>주문 완료 - KISIA_bookStore</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    .complete-container {
      max-width: 600px;
      margin: 50px auto;
      text-align: center;
      padding: 40px;
      border: 1px solid #ccc;
      background: #f9f9f9;
      border-radius: 8px;
    }
    .complete-container h2 {
      margin-bottom: 20px;
      font-size: 28px;
    }
    .complete-container p {
      font-size: 16px;
      color: #555;
    }
    .complete-container a.btn {
      margin-top: 30px;
      display: inline-block;
      padding: 12px 24px;
      background: #007bff;
      color: white;
      text-decoration: none;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <div class="complete-container">
    <h2>주문이 완료되었습니다!</h2>
    <p>주문 내역은 마이페이지에서 확인하실 수 있습니다.</p>
    <a href="mypage.php" class="btn">마이페이지로 이동</a>
  </div>
</body>
</html>
