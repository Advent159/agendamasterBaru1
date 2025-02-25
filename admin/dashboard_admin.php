<?php
session_start();

// Cek apakah admin sudah login, jika belum alihkan ke halaman login
if (!isset($_SESSION['admin_id'])) {
    header('Location: login_admin.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/dashboardAdmin.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-user-shield"></i> Admin Dashboard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard_admin.php">
                            <i class="fas fa-table"></i> Data User
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../dashboard.php">
                            <i class="fa fa-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="event_form_admin.php">
                            <i class="fas fa-calendar-plus"></i> Tambah Event
                        </a>
                    </li>
                </ul>
                
                <!-- Admin Info, Dropdown, Notifikasi, Logout -->
                <div class="d-flex align-items-center gap-2">
                    <!-- Welcome Text -->
                    <span class="navbar-text text-white me-3 d-flex align-items-center">
                        <i class="fas fa-user-alt me-2"></i> 
                        <span class="fw-bold">Welcome, <?= $_SESSION['admin_username'] ?>!</span>
                    </span>

                    <!-- Dropdown for profile -->
                    <div class="dropdown">
                        <button class="btn btn-outline dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                            <li>
                            <a class="dropdown-item d-flex align-items-center" href="editProfil_admin.php">
                            <i class="fa-solid fa-user-gear me-2"></i> Edit Profile
                        </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Notifikasi -->
                    <a href="notifications.php" class="btn btn-outline-light position-relative">
                        <i class="fas fa-bell"></i>
                    </a>

                    <!-- Logout -->
                    <a href="logout_admin.php" class="btn btn-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content  -->
    <main class="container my-4 flex-grow-1">
        <section id="manage-events">
            <h2 style="color: #5F9EA0;"><i class="fas fa-table"></i> Data Pendaftar Event</h2>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>No.Handphone</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Hapus</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    include "../koneksi/koneksi.php";
                    $no=1;
                    $query = "SELECT * FROM payment ORDER BY user_id ASC";
                    $result = mysqli_query($conn, $query);

                    while ($data = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>" . $data['username'] . "</td>";
                        echo "<td>" . $data['email'] . "</td>";
                        echo "<td>" . $data['no_hp'] . "</td>";
                        echo "<td>" . $data['price'] . "</td>";
                        echo "<td><b>" . ($data['status'] >=3 ? "Pembayaran Sukses" : ($data['status']>=2 ? "Pembayaran Panding" : "Pembayaran Belum Dilakukan")) . "</b></td>";
                        echo "<td><a href='delete.php?id=".$data['user_id']."' class='btn btn-danger'><i class='bi bi-trash-fill'></i> HAPUS </a></td>";
                        echo "</tr>";
                    }
                ?>
                </tbody>
            </table>
        </section>
    </main>

    <!-- Footer -->
    <footer class="text-white py-3 text-center">
        <div class="container text-center">
            <p>&copy; 2025 Magenda Master. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>