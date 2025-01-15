<?php
include('deleteModal.php');
include('includes/db.php');
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch user-specific images
$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM images WHERE user_id = ?");
$stmt->execute([$userId]);
$images = $stmt->fetchAll();

// Check if the delete form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $image_id = $_POST['id'];

    // Prepare the delete query
    $delete_stmt = $conn->prepare("DELETE FROM images WHERE id = ?");
    $delete_stmt->execute([$image_id]);

    // Redirect back to the index page after deletion
    header('Location: index.php');
    exit();
}

// Handle description update directly in index.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['image_id']) && isset($_POST['description'])) {
    $image_id = $_POST['image_id'];
    $new_description = $_POST['description'];

    $update_stmt = $conn->prepare("UPDATE images SET description = ? WHERE id = ?");
    $update_stmt->execute([$new_description, $image_id]);

    // Set success message and redirect to prevent form resubmission on refresh
    $_SESSION['success_message'] = 'Description updated successfully!';
    header("Location: index.php");  // Redirect back to index.php
    exit();
}
?>
<!DOCTYPE html>
<?php include('deleteModal.php'); ?><html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Image Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <?php include('templates/header.php'); ?>

    <!-- Display Success Message -->
    <div id="successMessageContainer" style="height: 50px; transition: height 0.2s;">
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" id="successMessage" role="alert">
                <?= $_SESSION['success_message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
    </div>

    <div class="container mt-5">
        <div class="welcome-banner text-center p-4 mb-4 rounded" style="background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%); color: white;">
            <h1 class="display-4 fw-bold">Welcome to Your Image Gallery!</h1>
            <p class="fs-5">A personal space to store, organize, and cherish your favorite moments.</p>
        </div>
        <h1 class="text-center">Your Image Gallery</h1>
        <div class="row">
            <?php if (empty($images)): ?>
                <div class="text-center mt-5">
                    <div class="p-5" style="border: 2px dashed #6c757d; border-radius: 10px; background-color: #f8f9fa;">
                        <h4 class="fw-bold text-secondary mb-3">No Images Found</h4>
                        <p class="text-muted mb-4">Your gallery is currently empty. Upload your first image to get started and showcase your memories!</p>
                        <a href="upload.php" class="btn btn-secondary btn-lg">Upload Image</a>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($images as $image): ?>
                    <div class="col-md-4">
                        <div class="card">
                            <img src="assets/uploads/<?= htmlspecialchars($image['image_name']) ?>" class="card-img-top" alt="Image">
                            <div class="card-body">
                                <p class="card-text"><?= htmlspecialchars($image['description']) ?></p>
                                <button class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $image['id'] ?>">Edit Description</button>
                                <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $image['id'] ?>">Delete</button>
                            </div>
                        </div>
                    </div>
                    <?php include('editModal.php'); ?>
                    <?php include('deleteModal.php'); ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php include('templates/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Automatically hide the success message after 3 seconds
        setTimeout(function() {
            var successMessage = document.getElementById('successMessage');
            if (successMessage) {
                successMessage.classList.remove('show');
                successMessage.classList.add('fade');
                setTimeout(() => successMessage.remove(), 300); // Ensures it's fully removed after fade-out
            }
        }, 3000);
    </script>
</body>

</html>