<?php
include 'connect.php';

$id = $_GET['id'] ?? 0;

if ($id) {
    $conn->query("DELETE FROM books WHERE id = $id");
}

header('Location: admin_books.php');
exit;
?>
