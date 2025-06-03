<?php require_once 'session_start.php'; ?>
<?php require_once 'connect.php'; ?>
<?php

$inquiry_id = $_GET['id'];

$sql = "DELETE FROM inquiries WHERE id = $inquiry_id";
$conn->query($sql);

echo "<script>alert('문의글이 삭제되었습니다.');</script>";
echo "<script>window.location.href='board.php';</script>";
exit();

?>