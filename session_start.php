<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 세션에 저장된 값을 지역 변수로 꺼냄
$id         = $_SESSION['id'] ?? '';
$name  = $_SESSION['user_name'] ?? '';
$email      = $_SESSION['email'] ?? '';
?>