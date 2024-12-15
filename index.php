<?php include('modal.php'); ?>
<?php include('includes/db.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Gallery</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <!-- Header Section -->
    <?php include('templates/header.php'); ?>

    <!-- Gallery Section -->
    <div class="container mt-5">
        <h1 class="text-center">Image Gallery</h1>
        <div class="row">
            <?php
            $stmt = $conn->prepare("SELECT * FROM images");
            $stmt->execute();
            $images = $stmt->fetchAll();

            foreach ($images as $image):
            ?>
                <div class="col-md-4">
                    <div class="card">
                        <img src="assets/uploads/<?= $image['image_name'] ?>" class="card-img-top" alt="Image">
                        <div class="card-body">
                            <p class="card-text"><?= $image['description'] ?></p>
                            <a href="edit.php?id=<?= $image['id'] ?>" class="btn btn-outline-secondary btn-sm">Edit</a>
                            <a href="#" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#deleteModal" onclick="setDeleteId(<?= $image['id'] ?>)">Delete</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer Section -->
    <?php include('templates/footer.php'); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<script>
    function setDeleteId(imageId) {
        // Set the image ID in the hidden input field of the modal
        document.getElementById('deleteId').value = imageId;
    }
</script>