<?php
session_start();
include '../koneksi/koneksi.php'; // Koneksi ke database

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data event berdasarkan ID untuk menghapus gambar juga
    $sql = "SELECT event_image FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();

    if ($event) {
        // Hapus gambar dari folder uploads
        $image_path = '../uploads/' . $event['event_image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        // Hapus data event dari database
        $sql = "DELETE FROM events WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Event berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus event.";
        }
    } else {
        $_SESSION['error'] = "Event tidak ditemukan.";
    }
} else {
    $_SESSION['error'] = "ID event tidak valid.";
}

// Redirect kembali ke dashboard
header("Location: event_admin.php");
exit;
?>
