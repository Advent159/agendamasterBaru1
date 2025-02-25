<?php
session_start();
include '../koneksi/koneksi.php';


// Cek apakah admin sudah login, jika belum alihkan ke halaman login
if (!isset($_SESSION['admin_id'])) {
    header('Location: login_admin.php');
    exit;
}
// Ambil data event dari database
$sql = "SELECT * FROM events";
$result = $conn->query($sql);

if (isset($_SESSION['message'])) {
    echo "<p style='color: green;'>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']);
}

if (isset($_SESSION['error'])) {
    echo "<p style='color: red;'>" . $_SESSION['error'] . "</p>";
    unset($_SESSION['error']);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Data Anggota</title>
    <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Custom CSS -->
<link rel="stylesheet" href="../css/eventAdmin.css">

   
</head>
<body>
    
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

   
    
   

    <div class="container table-container">
    <div class="d-flex justify-content-between">
        <div align="center"></div>
        <h2 class="fw-bold ">Rekap Data Event</h2>
        <a href="event_form_admin.php" class="btn btn-add-event"><i class="fa-solid fa-plus"></i> Tambah Event</a>
    </div>
    <div></div>
    <table class="table table-hover table-bordered shadow">
        <thead class="table-primary text-center">
            <tr>
                <th>ID</th>
                <th>Gambar</th>
                <th>Title</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Tanggal</th>
                <th>Lokasi</th>
                <th>Total Kuota</th>
                <th>Kuota</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr class="text-center align-middle">
                <td><?= $row['id'] ?></td>
                <td>
                    <img src="../uploads/<?= $row['event_image'] ?>" class="img-thumbnail" style="width: 100px; height: auto;">
                </td>
                <td><?= $row['title'] ?></td>
                <td><?= $row['description'] ?></td>
                <td class="text-success fw-bold">Rp <?= number_format($row['price'], 2, ',', '.'); ?></td>
                <td><?= $row['date'] ?></td>
                <td><?= $row['location'] ?></td>
                <td><?= $row['total_kuota'] ?></td>
                <td><?= $row['kuota'] ?></td>
                <td>
                    <a href="event_form_admin.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning btn-action">
                        <i class="fa-solid fa-pen"></i> Edit
                    </a>
                    <a href="#" class="btn btn-sm btn-danger btn-action delete-event" data-id="<?= $row['id'] ?>">
    <i class="fa-solid fa-trash"></i> Delete
</a>

                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // SweetAlert for Delete Confirmation
    document.addEventListener('DOMContentLoaded', () => {
        const deleteButtons = document.querySelectorAll('.delete-event');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const eventId = this.dataset.id;

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Event ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect to delete URL
                        window.location.href = `event_delete.php?id=${eventId}`;
                    }
                });
            });
        });
    });
</script>




</body>
</html>