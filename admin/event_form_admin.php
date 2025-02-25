<?php
session_start();
include '../koneksi/koneksi.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login_admin.php');
    exit;
}

// Cek apakah sedang mengedit
$event = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_date = $_POST['date'];
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $price = floatval($_POST['price']);
    $kuota = intval($_POST['kuota']); // Input kuota
    $created_by = $_SESSION['admin_id'];
    $total_kuota = isset($_POST['total_kuota']) ? intval($_POST['total_kuota']) : $event['total_kuota'];

    // Upload gambar jika ada
    $event_image = $event['event_image'] ?? null;
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['event_image']['tmp_name'];
        $file_name = uniqid('event_', true) . '.' . pathinfo($_FILES['event_image']['name'], PATHINFO_EXTENSION);
        $file_dest = "../uploads/$file_name";

        if (move_uploaded_file($file_tmp, $file_dest)) {
            $event_image = $file_name;
        }
    }

    // Update atau Tambahkan
    if ($event) {
        $sql = "UPDATE events SET event_image = ?, title = ?, description = ?, date = ?, location = ?, price = ?, kuota = ?, total_kuota = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssdiii', $event_image, $title, $description, $event_date, $location, $price, $kuota, $total_kuota, $id);
    } else {
        $sql = "INSERT INTO events (event_image, title, description, date, location, price, kuota, total_kuota, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssdiii', $event_image, $title, $description, $event_date, $location, $price, $kuota, $total_kuota, $created_by);
    }

    if ($stmt->execute()) {
        header('Location: event_admin.php');
        exit;
    } else {
        $error = 'Gagal menyimpan data.';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Event</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg custom-navbar shadow-lg" style="background-color: #94a3b8;">
        <div class="container">
            <a class="navbar-brand fw-bold" href="dashboard_admin.php">
                <i class="fa-solid fa-user-shield"></i> Admin Panel
            </a>
            <div class="d-flex">
                <span class="text-white me-3">Welcome, <?= $_SESSION['admin_username'] ?>!</span>
                <a href="logout_admin.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Form Tambah Event -->
    <div class="container mt-5">
        <div class="form-container mx-auto col-md-6 shadow-lg p-4 bg-white rounded">
            <h2 class="mb-4"><?= $event ? 'Edit Event' : 'Tambah Event' ?></h2>
            <form action="" method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label for="event_image" class="form-label">Upload Gambar:</label>
                    <input type="file" name="event_image" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="title" class="form-label">Title:</label>
                    <input type="text" name="title" value="<?= $event['title'] ?? '' ?>" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi:</label>
                    <textarea name="description" class="form-control" rows="4" required><?= $event['description'] ?? '' ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="date" class="form-label">Tanggal Event:</label>
                    <input type="datetime-local" name="date" value="<?= isset($event['date']) ? date('Y-m-d\TH:i', strtotime($event['date'])) : '' ?>" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="location" class="form-label">Lokasi:</label>
                    <input type="text" name="location" value="<?= $event['location'] ?? '' ?>" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Harga Event (Rp):</label>
                    <input type="number" name="price" value="<?= $event['price'] ?? '' ?>" class="form-control" step="0.01" required>
                </div>

                <div class="mb-3">
                    <label for="kuota" class="form-label">Batas Kuota:</label>
                    <input type="number" name="kuota" value="<?= $event['kuota'] ?? '' ?>" class="form-control" required>
                </div>

                <?php if (!$event): ?>
                <div class="mb-3">
                    <label for="total_kuota" class="form-label">Total Kuota (Kuota Awal):</label>
                    <input type="number" name="total_kuota" class="form-control" required>
                </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary w-100 mb-2">
                    <i class="bi bi-pencil-square"></i> <?= $event ? 'Update' : 'Tambah' ?>
                </button>
                <a href="event_admin.php" class="btn btn-danger w-100">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </form>
        </div>
    </div>
</body>
</html>
