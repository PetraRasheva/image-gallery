<?php
session_start();
include('includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $imageName = $_FILES['image']['name'];
    $imageTmp = $_FILES['image']['tmp_name'];
    $description = $_POST['description'];
    
    $uploadPath = __DIR__ . "/assets/uploads/" . basename($imageName);
    if (move_uploaded_file($imageTmp, $uploadPath)) {
        echo "Image uploaded successfully."; // Debugging message
    } else {
        echo "Error uploading image. Check upload path or permissions.";
        var_dump($_FILES['image']);
        die();
    }
    
    // Insert image info into the database
    $stmt = $conn->prepare("INSERT INTO images (user_id, image_name, description) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $imageName, $description]);

    header('Location: index.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Image</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include('templates/header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Upload Image</h2>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="image" class="form-label">Select Image</label>
            <input type="file" class="form-control" name="image" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>

<?php include('templates/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>