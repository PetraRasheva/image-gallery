<?php
include('modal.php');
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
    exit();  // Ensure the script stops here
}
?>
<!DOCTYPE html>
<?php include('modal.php'); ?><html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Image Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <?php include('templates/header.php'); ?>

    <!-- Display Success Message -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div id="successMessage" style="display: block; position: fixed; top: 70px; right: 20px; padding: 10px 20px; font-size: 14px;">
            <?= $_SESSION['success_message']; ?>
        </div>
        <?php unset($_SESSION['success_message']); // Clear the session message after showing it ?>
    <?php endif; ?>

    <div class="container mt-5">
        <h1 class="text-center">Your Image Gallery</h1>
        <div class="row">
            <?php if (empty($images)): ?>
                <div class="text-center mt-5">
                    <div class="border p-4" style="border-radius: 8px;">
                        <h4 class="fw-light">No Images Found</h4>
                        <p class="mb-3">Your gallery is empty. Upload your first image to get started!</p>
                        <a href="upload.php" class="btn btn-outline-dark btn-sm">Upload Image</a>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($images as $image): ?>
                    <div class="col-md-4">
                        <div class="card">
                            <img src="assets/uploads/<?= htmlspecialchars($image['image_name']) ?>" class="card-img-top" alt="Image">
                            <div class="card-body">
                                <p class="card-text"><?= htmlspecialchars($image['description']) ?></p>
                                <!-- Edit Button Triggering Modal -->
                                <button class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $image['id'] ?>">Edit Description</button>
                                
                                <a href="#" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="setDeleteId(<?= $image['id'] ?>)">Delete</a>                                
                            </div>
                        </div>
                    </div>

                    <!-- Edit Modal for This Image -->
                    <div class="modal fade" id="editModal<?= $image['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Image Description</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <!-- Form to Update Description -->
                                <form method="POST" action="index.php">
                                    <div class="modal-body">
                                        <input type="hidden" name="image_id" value="<?= $image['id'] ?>">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea name="description" id="description" class="form-control" rows="4" required><?= htmlspecialchars($image['description']) ?></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-outline-dark">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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
                successMessage.style.display = 'none';
            }
        }, 3000);  // Hide after 3 seconds
    </script>
    <script>
    function setDeleteId(imageId) {
        // Set the image ID in the hidden input field of the modal
        document.getElementById('deleteId').value = imageId;
    }
</script>
</body>
</html>