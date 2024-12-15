<?php
session_start();
include('includes/db.php');

// Check if the ID is passed in the POST request
if (!isset($_POST['id']) || empty($_POST['id'])) {
    die("Invalid request: No ID provided.");
}

$imageId = intval($_POST['id']); // Ensure the ID is an integer

// Fetch the image record from the database
$stmt = $conn->prepare("SELECT image_name FROM images WHERE id = ?");
$stmt->execute([$imageId]);
$image = $stmt->fetch();

if (!$image) {
    die("Image not found.");
}

// Path to the image file
$imagePath = __DIR__ . "/assets/uploads/" . $image['image_name'];

// Delete the file from the filesystem
if (file_exists($imagePath)) {
    if (!unlink($imagePath)) {
        die("Error: Unable to delete the image file.");
    }
}

// Delete the record from the database
$stmt = $conn->prepare("DELETE FROM images WHERE id = ?");
$stmt->execute([$imageId]);

// Redirect back to the index page
header("Location: index.php");
exit();
?>