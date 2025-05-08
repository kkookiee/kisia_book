<?php
include 'connect.php';

// 전체 수치 가져오기
$book_result = $conn->query("SELECT COUNT(*) AS total_books FROM books");
$book_data = $book_result->fetch_assoc();
$total_books = $book_data['total_books'];

$user_result = $conn->query("SELECT COUNT(*) AS total_users FROM users");
$user_data = $user_result->fetch_assoc();
$total_users = $user_data['total_users'];

$order_result = $conn->query("SELECT COUNT(*) AS total_orders FROM orders");
$order_data = $order_result->fetch_assoc();
$total_orders = $order_data['total_orders'];

$sales_result = $conn->query("SELECT SUM(total_price) AS total_sales FROM orders");
$sales_data = $sales_result->fetch_assoc();
$total_sales = $sales_data['total_sales'] ?? 0;
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>관리자 대시보드</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      margin: 0;
      font-family: 'Pretendard', sans-serif;
      background: #f5f7fa;
    }
    .admin-container {
      display: flex;
    }
    .sidebar {
      width: 240px;
      background-color: #2d3748;
      color: #fff;
      padding: 20px;
      height: 100vh;
    }
    .sidebar h2 {
      font-size: 24px;
      margin-bottom: 30px;
    }
    .sidebar ul {
      list-style: none;
      padding: 0;
    }
    .sidebar ul li {
      margin-bottom: 20px;
    }
    .sidebar ul li a {
      color: #fff;
      text-decoration: none;
      font-size: 16px;
    }
    .main-content {
      flex: 1;
      padding: 40px;
    }
    .card-container {
      display: flex;
      gap: 20px;
      margin-top: 20px;
    }
    .card {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      flex: 1;
      text-align: center;
    }
    .card h3 {
      font-size: 18px;
      margin-bottom: 10px;
    }
    .card span {
      font-size: 24px;
      font-weight: bold;
    }
    .chart-container {
      margin-top: 30px;
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      height: 300px; /* ✅ 고정 높이 추가 */
    }
    .chart-container canvas {
      height: 100% !important; /* ✅ 캔버스를 container에 맞춤 */
    }
  </style>
</head>
<body>
<div class="admin-container">
  <aside class="sidebar">
    <h2>관리자</h2>
    <ul>
      <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> 대시보드</a></li>
      <li><a href="admin_orders.php"><i class="fas fa-file-alt"></i> 주문 관리</a></li>
      <li><a href="admin_books.php"><i class="fas fa-book"></i> 도서 관리</a></li>
      <li><a href="admin_users.php"><i class="fas fa-users"></i> 회원 관리</a></li>
      <li><a href="admin_reviews.php"><i class="fas fa-comments"></i> 리뷰 관리</a></li>
      <li><a href="admin_board.php"><i class="fas fa-headphones"></i> 고객센터</a></li>
      <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> 로그아웃</a></li>
    </ul>
  </aside>
  <main class="main-content">
    <h1>관리자 대시보드</h1>
    <p>총괄 현황 및 시스템 관리</p>

    <div class="card-container">
      <div class="card">
        <h3>총 도서 수</h3>
        <span><?= $total_books ?>권</span>
      </div>
      <div class="card">
        <h3>총 회원 수</h3>
        <span><?= $total_users ?>명</span>
      </div>
      <div class="card">
        <h3>총 주문 수</h3>
        <span><?= $total_orders ?>건</span>
      </div>
      <div class="card">
        <h3>총 매출액</h3>
        <span><?= number_format($total_sales) ?>원</span>
      </div>
    </div>

    <div class="chart-container">
      <canvas id="adminChart"></canvas>
    </div>
  </main>
</div>

<script>
const ctx = document.getElementById('adminChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['도서 수', '회원 수', '주문 수', '매출액'],
        datasets: [{
            label: '통계',
            data: [<?= $total_books ?>, <?= $total_users ?>, <?= $total_orders ?>, <?= round($total_sales / 10000, 1) ?>],
            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'],
            borderRadius: 6,
        }]
    },
    options: {
        maintainAspectRatio: false, // ✅ 비율 유지 끔
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                  stepSize: 1
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(ctx) {
                        let label = ctx.label;
                        let value = ctx.raw;
                        if (label === '매출액') return value + '만 원';
                        return value + '건';
                    }
                }
            }
        }
    }
});
</script>
</body>
</html>
