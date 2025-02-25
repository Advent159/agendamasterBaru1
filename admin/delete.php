<?php
// Sertakan file koneksi database
include "../koneksi/koneksi.php";

// Pastikan parameter id dikirim
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    
    // Query untuk menghapus data berdasarkan user_id
    $query = "DELETE FROM payment WHERE user_id = '$user_id'";
    
    // Eksekusi query
    if (mysqli_query($conn, $query)) {
        echo "<script>
            setTimeout(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Data berhasil dihapus!',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = 'dashboard_admin.php';
                });
            }, 500);
        </script>";
    } else {
        echo "<script>
            setTimeout(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal menghapus data!',
                    text: '" . mysqli_error($conn) . "'
                }).then(function() {
                    window.history.back();
                });
            }, 500);
        </script>";
    }
} else {
    echo "<script>
        Swal.fire({
            icon: 'warning',
            title: 'ID tidak ditemukan!',
            text: 'Silakan coba lagi.'
        }).then(function() {
            window.history.back();
        });
    </script>";
}
?>

<!-- Tambahkan pustaka SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>