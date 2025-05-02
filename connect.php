<?php
$host = 'db';
$db = 'book_store';
$user = 'user';
$pass = 'user';

$conn = new mysqli($host, $user, $pass, $db);

if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

?>