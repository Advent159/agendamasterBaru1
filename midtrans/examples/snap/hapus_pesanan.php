<?php
include "../../../koneksi/koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $order_id = $_POST['order_id'];
    
        // Ambil event ID dan jumlah tiket yang dipesan
        $query = "SELECT event_id, jumlah_tiket FROM payment WHERE order_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $event_id = $data['event_id'];
        $jumlah_tiket = $data['jumlah_tiket'];
    
        // Kembalikan kuota event sesuai jumlah tiket
        $query = "UPDATE events SET kuota = kuota - ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $jumlah_tiket, $event_id);
        $stmt->execute();
    
        // Hapus pesanan
        $query = "DELETE FROM payment WHERE order_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $order_id);
        if ($stmt->execute()) {
            echo "Pesanan berhasil dibatalkan.";
        } else {
            echo "Gagal membatalkan pesanan.";
        }
        $stmt->close();
        $conn->close();
    }
}
?>
