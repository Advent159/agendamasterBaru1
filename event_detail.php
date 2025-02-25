<?php
include 'koneksi/koneksi.php'; // Koneksi ke database

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mengambil detail event berdasarkan ID
    $sql = "SELECT * FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();

    if (!$event) {
        echo "<p>Event tidak ditemukan.</p>";
        exit;
    }
} else {
    echo "<p>ID event tidak valid.</p>";
    exit;
}

// Query untuk mengambil detail user berdasarkan ID
$sql = "SELECT * FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "<p>User tidak ditemukan.</p>";
    exit;
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jumlah_tiket = intval($_POST['jumlah_tiket']);

    // Periksa apakah jumlah tiket yang dipesan tersedia
    if ($event['kuota'] + $jumlah_tiket > $event['total_kuota']) {
        echo "<script>alert('Maaf, kuota event tidak mencukupi.'); window.location.href='event_detail.php?id=$id';</script>";
        exit;
    }
    
    $username = $_POST['username'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];
    $order_id = rand();
    $status = 1;
    $transaction_id = "";
    $price = floatval($_POST['price']) * $jumlah_tiket; // Total harga berdasarkan jumlah tiket
    $event_id = $event['id'];

    // Simpan pesanan ke database
    $sql = "INSERT INTO payment (username, email, no_hp, order_id, status, transaction_id, price, event_id, jumlah_tiket) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssdii', $username, $email, $no_hp, $order_id, $status, $transaction_id, $price, $event_id, $jumlah_tiket);

    if ($stmt->execute()) {
        // Kurangi kuota event sesuai jumlah tiket
        $sql = "UPDATE events SET kuota = kuota + ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $jumlah_tiket, $id);
        $stmt->execute();

        // Redirect ke halaman checkout
        header("Location: ./midtrans/examples/snap/checkout-process-simple-version.php?order_id=$order_id");
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
    <title>Detail Event</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Animasi Loading */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .container-custom {
            max-width: 650px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .event-img {
    max-width: 300px; /* Mengecilkan gambar */
    height: auto;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    display: block;
    margin: 0 auto;
}

        .event-title {
            color: #007bff;
            font-weight: bold;
            text-align: center;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            transition: all 0.3s ease-in-out;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .btn-secondary {
            background-color: #6c757d;
            transition: all 0.3s ease-in-out;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateX(-3px);
        }

        /* Loading Screen */
        #loading-screen {
            position: fixed;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 1;
            transition: opacity 0.5s ease-in-out;
        }

        .loading-animation {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #007bff;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        .hidden {
            opacity: 0;
            visibility: hidden;
        }
    </style>
</head>
<body>

    <!-- Loading Screen -->
    <div id="loading-screen">
        <div class="loading-animation"></div>
    </div>

    <div class="container mt-4">
        <button onclick="" class="btn btn-secondary shadow-sm">
            <a href="list_event_user.php"><i class="fas fa-arrow-left"></i> Kembali</a>
        </button>
    </div>

    <div class="container-custom mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-xl font-bold text-primary">Detail Event</h2>
            <span class="text-muted">Welcome, <?= $_SESSION['username'] ?></span>
            <a href="logout.php" class="btn btn-danger" id="logoutBtn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

        <div class="text-center">
            <img src="uploads/<?= $event['event_image']; ?>" class="event-img mb-4" alt="Event Image">
            <h1 class="event-title"><?= htmlspecialchars($event['title']); ?></h1>
            <p class="text-gray-600"><?= htmlspecialchars($event['description']); ?></p>
            <p><strong>Tanggal:</strong> <?= date('d M Y', strtotime($event['date'])); ?></p>
            <p><strong>Lokasi:</strong> <?= htmlspecialchars($event['location']); ?></p>
            <p class="text-lg font-bold text-green-600">Rp <?= number_format($event['price'], 0, ',', '.'); ?></p>
            <p><strong>Kuota :</strong> <?= $event['kuota']; ?> / <?= $event['total_kuota']; ?> orang</p>
        </div>
        
        <?php if ($event['kuota'] < $event['total_kuota']): ?>
        <div class="mt-4">
            <div class="form-container">
                <form id="ticket-form" action="" method="POST">
                    <input type="hidden" name="username" value="<?= $user['username']; ?>">
                    <input type="hidden" name="email" value="<?= $user['email']; ?>">
                    <input type="hidden" name="price" value="<?= $event['price']; ?>">

                    <div class="mb-3">
                        <label for="jumlah_tiket" class="form-label">Jumlah Tiket :</label>
                        <input type="number" name="jumlah_tiket" id="jumlah_tiket" class="form-control" min="1" max="<?= $event['total_kuota'] - $event['kuota']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="total_harga" class="form-label">Total Harga :</label>
                        <input type="text" id="total_harga" class="form-control" readonly style="background: #e9ecef;">
                    </div>

                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No Handphone :</label>
                        <input type="text" name="no_hp" id="no_hp" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-2">
                        <i class="fa fa-ticket"></i> Pesan Tiket
                    </button>
                </form>
            </div>
        </div>
        <?php else: ?>
            <div class="text-center mt-4">
                <button class="btn btn-danger" disabled>Kuota Penuh</button>
            </div>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const loadingScreen = document.getElementById("loading-screen");
            setTimeout(() => { loadingScreen.classList.add("hidden"); }, 500);

            const jumlahTiket = document.getElementById("jumlah_tiket");
            const totalHarga = document.getElementById("total_harga");

            let hargaTiket = <?= $event['price']; ?>;
            jumlahTiket.addEventListener("input", function () {
                let jumlah = parseInt(jumlahTiket.value) || 0;
                totalHarga.value = "Rp " + (jumlah * hargaTiket).toLocaleString("id-ID");
            });
        });

        document.getElementById("logoutBtn").addEventListener("click", function(event) {
            event.preventDefault();
            Swal.fire({
                title: "Apakah Anda yakin ingin logout?",
                text: "Anda harus login kembali untuk mengakses halaman ini!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Logout!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) window.location.href = "logout.php";
            });
        });

        
    </script>

</body>
</html>

