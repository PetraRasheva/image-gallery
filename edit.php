<?php
include 'includes/db.php';
include 'templates/header.php';

if (!isset($_GET['id'])) {
    die("Image ID is required.");
}

$image_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM images WHERE id = ?");
$stmt->execute([$image_id]);
$image = $stmt->fetch();

if (!$image) {
    die("Image not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_description = $_POST['description'] ?? '';

    $update_stmt = $pdo->prepare("UPDATE images SET description = ? WHERE id = ?");
    $update_stmt->execute([$new_description, $image_id]);

    echo "<div class='alert alert-success'>Description updated successfully!</div>";
}

?>

<h2>Edit Description</h2>
<form method="POST">
    <div class="mb-3">
        <label for="description" class="form-label">Image Description</label>
        <textarea name="description" id="description" class="form-control" required><?= htmlspecialchars($image['description']) ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="index.php" class="btn btn-secondary">Cancel</a>
</form>

<?php include 'templates/footer.php'; ?>