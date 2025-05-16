<?php
include 'connect.php';

// 🚨 Security Misconfiguration: 모든 SQL 에러 노출
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// 🚨 Broken Access Control: 세션 확인 없음
// 원래는 if (!isset($_SESSION['admin'])) { header('Location: login.php'); }

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
  <title>관리자 대시보드 (취약)</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <!-- 🚨 Vulnerable Component: CDN으로 chart.js 최신 X 버전 사용 -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
      <li><a href="admin_inquiries.php"><i class="fas fa-headphones"></i> 게시판 관리</a></li>
      <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> 로그아웃</a></li>
    </ul>
  </aside>
  <main class="main-content">
    <h1>관리자 대시보드 (접근제어 없음)</h1>
    <p>총괄 현황 및 시스템 관리 (모든 사용자 접근 가능)</p>

    <div class="card-container">
      <div class="card"><h3>총 도서 수</h3><span><?= $total_books ?>권</span></div>
      <div class="card"><h3>총 회원 수</h3><span><?= $total_users ?>명</span></div>
      <div class="card"><h3>총 주문 수</h3><span><?= $total_orders ?>건</span></div>
      <div class="card"><h3>총 매출액</h3><span><?= number_format($total_sales) ?>원</span></div>
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
        maintainAspectRatio: false,
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } }
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
