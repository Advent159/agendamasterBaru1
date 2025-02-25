<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_username'])) {
    $_SESSION['username'] = htmlspecialchars($_POST['new_username']);
    header("Location: dashboard.php"); // Redirect setelah update
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2>Edit Profile</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="new_username" class="form-label">New Username</label>
                <input type="text" class="form-control" id="new_username" name="new_username" value="<?= $_SESSION['username']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="dashboard.php" class="btn btn-secondary">Cancel</a> <!-- Button Cancel untuk kembali ke dashboard -->
        </form>
    </div>
</body>
</html>
