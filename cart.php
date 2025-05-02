<?php 
require_once 'session_start.php';
require_once 'connect.php';

// 로그인된 사용자의 정보를 데이터베이스에서 가져옴
if(!empty($id)) {
    $stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    
    $name = $user_data['name'];
    $email = $user_data['email'];
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>장바구니 - 온라인 서점</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <div class="cart-container">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th style="width:40%;">상품정보</th>
                        <th style="width:15%;">수량</th>
                        <th style="width:15%;">상품금액</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="cart-product-info">
                            <input type="checkbox" />
                            <img src="images/book1.jpg" alt="도서 이미지" class="cart-thumb" />
                            <div class="cart-info-detail">
                                <span class="cart-tag">소득공제</span>
                                <span class="cart-title">[도서] 밀크티와 고양이</span>
                                <div class="cart-meta">
                                    <span class="cart-price-original">16,800원</span>
                                    <span class="cart-price-sale">15,120원</span>
                                    <span class="cart-discount">(10% 할인)</span>
                                    <span class="cart-point">P 840원</span>
                                </div>
                            </div>
                        </td>
                        <td class="cart-qty">
                            <input type="number" value="1" min="1" max="99" />
                            <button class="cart-update-btn">변경</button>
                        </td>
                        <td class="cart-sum">
                            <span class="cart-price-sale">15,120원</span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="cart-summary">
                <div class="summary-item">
                    <span>상품 금액</span>
                    <span>15,120원</span>
                </div>
                <div class="summary-item">
                    <span>배송비</span>
                    <span>3,000원</span>
                </div>
                <div class="summary-item total">
                    <span>총 결제 금액</span>
                    <span>18,120원</span>
                </div>
                <button class="checkout-btn">주문하기</button>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 온라인 서점. All rights reserved.</p>
    </footer>
</body>
</html> 