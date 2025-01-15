<?php
session_start();
include('includes/db.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name, email, avatar FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
    $avatar = $_FILES['avatar'] ?? null;

    // Handle avatar upload
    if ($avatar && $avatar['error'] == 0) {
        // Handle file upload
        $avatarPath = 'uploads/avatars/' . basename($avatar['name']);
        if (move_uploaded_file($avatar['tmp_name'], $avatarPath)) {
            // If upload is successful, use the uploaded avatar
            $avatar = $avatarPath;
        } else {
            // If upload fails, retain old avatar
            $avatar = $user['avatar'] ?? 'uploads/avatars/default.png'; // Use default if no avatar exists
        }
    } else {
        // If no avatar is uploaded, use the default avatar if no avatar exists in the database
        $avatar = !empty($user['avatar']) ? $user['avatar'] : 'uploads/avatars/user.png';
        echo 'Avatar Path: ' . htmlspecialchars($avatar);
            }


    if ($password) {
        $updateStmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ?, avatar = ? WHERE id = ?");
        $updateStmt->execute([$name, $email, $password, $avatar, $userId]);
    } else {
        $updateStmt = $conn->prepare("UPDATE users SET name = ?, email = ?, avatar = ? WHERE id = ?");
        $updateStmt->execute([$name, $email, $avatar, $userId]);
    }

    $_SESSION['user_name'] = $name;
    $_SESSION['success_message'] = "Profile updated successfully!";
    header('Location: profile.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-card {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .profile-card img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <?php include('templates/header.php'); ?>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Profile Information</h2>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success_message']; ?></div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <div class="profile-card text-center">
            <!-- Display Avatar -->
            <img src="<?= !empty($user['avatar']) ? htmlspecialchars($user['avatar']) : 'uploads/avatars/default.png' ?>" alt="Avatar">            
            <h3 class="mt-3"><?= htmlspecialchars($user['name']) ?></h3>
            <p class="text-muted"><?= htmlspecialchars($user['email']) ?></p>
            
            <form method="POST" action="profile.php" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="avatar" class="form-label">Avatar</label>
                    <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Leave empty to keep current password">
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>