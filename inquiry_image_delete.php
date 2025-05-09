<?php require_once 'connect.php'; ?>
<?php

$imageId = $_POST['imageId'];
error_log('$imageId:!!!! ' . $imageId);

$sql = "DELETE FROM inquiries_images WHERE id = $imageId";
$delete_inquiry_image = $conn->query($sql);

if ($delete_inquiry_image) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

$conn->close();
?>
