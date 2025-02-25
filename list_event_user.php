<?php
session_start();
include 'koneksi/koneksi.php'; // Pastikan file koneksi sudah benar

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Event</title>
    <link rel="stylesheet" href="./css/listEvent_user.css">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
  
    <style>
        /* Loading Screen Styling */
      #loading-screen {
            position: fixed;
            width: 100%;
            height: 100%;
            background: #1E3A8A;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease-in-out;
        }

        .loader {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            color: white;
            font-size: 20px;
            font-weight: bold;
        }

        .spinner {
            width: 60px;
            height: 60px;
            border: 6px solid rgba(255, 255, 255, 0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        /* Navbar Styling */
        .custom-navbar {
        background: linear-gradient(135deg, #1E3A8A, #5f9ea0);
        padding: 15px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }
    .navbar-brand {
        color: white !important;
        font-weight: bold;
        font-size: 1.5rem;
        transition: all 0.3s;
    }
    .navbar-brand:hover {
        color: #d1e8e2 !important;
        transform: scale(1.05);
    }
    .nav-link {
        color: white !important;
        margin-right: 15px;
        transition: all 0.3s;
    }
    .nav-link:hover {
        color: #d1e8e2 !important;
        text-decoration: underline;
        transform: translateY(-2px);
    }
    .user-section {
        display: flex;
        align-items: center;
        color: white;
    }
    .user-name {
        font-size: 1rem;
        margin-right: 15px;
    }
    .logout-button {
        background: red;
        padding: 7px 15px;
        border-radius: 8px;
        color: white;
        text-decoration: none;
        transition: all 0.3s;
    }
    .logout-button:hover {
        background: darkred;
        transform: scale(1.1);
    }
    .navbar.scrolled {
            background: linear-gradient(135deg, #2E8B57, #1E3A8A);
        }
    </style>
</head>
<body>

<!-- Loading Screen -->
<div id="loading-screen">
        <div class="loader">
            <div class="spinner"></div>
            <p>Loading...</p>
        </div>
    </div>
 
 <!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <i class="fa-solid fa-calendar-check me-2"></i> Event Management
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">
                        <i class="fa-solid fa-house me-1"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./admin/event_form_admin.php">
                        <i class="fa-solid fa-calendar me-1"></i> Event
                    </a>
                </li>
            </ul>
            <div class="d-flex align-items-center">
                <i class="fas fa-user-circle me-3 text-white" style="font-size: 1.5rem;"></i>
                <span class="me-4 text-white" style="font-size: 1.1rem;">Welcome, <?= $_SESSION['username'] ?></span>
                <a href="logout.php" class="btn btn-danger d-flex align-items-center px-3 py-2" id="logoutBtn">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Konten -->
<div class="container py-5">
    <h2 class="text-center text-3xl font-bold mb-5 text-gray-800">List Event</h2>

    <?php
    $sql = "SELECT * FROM events ORDER BY date ASC";
    $result = $conn->query($sql);
    ?>

    <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="bg-white shadow-md rounded-lg overflow-hidden max-w-sm mx-auto transform transition duration-300 hover:scale-105">
                    <a href="event_detail.php?id=<?= $row['id']; ?>">
                        <img src="uploads/<?= $row['event_image']; ?>" alt="Event Image" class="w-full h-48 object-cover">
                    </a>
                    <div class="p-3">
                        <!--kuota user-->
                        <h3 class="text-lg font-semibold text-gray-800 mb-1"> <?= htmlspecialchars($row['title']); ?> </h3>
                        <p class="text-gray-600 text-sm mb-1"> <?= htmlspecialchars($row['description']); ?> </p>
                        <p class="text-gray-800 font-semibold text-sm">
                            <i class="fa-solid fa-calendar me-2"></i> <?= date('d M Y', strtotime($row['date'])); ?>
                        </p>
                        <p class="text-gray-800 font-semibold text-sm">
                            <i class="fa-solid fa-map-marker-alt me-2"></i> <?= htmlspecialchars($row['location']); ?>
                        </p>
                        <p class="text-teal-600 font-bold text-sm">
                            <i class="fa-solid fa-money-bill me-2"></i> Rp <?= number_format($row['price'], 2, ',', '.'); ?>
                        </p>
                        <p class="text-teal-600 font-bold text-sm d-flex align-items-center">
                        <i class="fa-solid fa-user-group me-1"></i> 
                        <?= htmlspecialchars($row['kuota']); ?> / <?= htmlspecialchars($row['total_kuota']); ?> Orang
                        </p>
                        <a href="event_detail.php?id=<?= $row['id']; ?>" 
                        class="mt-2 block w-full text-center bg-teal-500 text-black px-3 py-1.5 rounded-md shadow hover:bg-teal-600 transition duration-300">
                       <i class="fa-solid fa-info-circle me-1"></i> Lihat Detail
                        </a>

                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
        window.onload = function() {
            setTimeout(() => {
                document.getElementById('loading-screen').style.opacity = '0';
                setTimeout(() => {
                    document.getElementById('loading-screen').style.display = 'none';
                    document.getElementById('content').style.opacity = '1';
                }, 500);
            }, 1000);
        };

        document.addEventListener("DOMContentLoaded", function() {
        let navbar = document.querySelector(".custom-navbar");
        window.addEventListener("scroll", function() {
            if (window.scrollY > 50) {
                navbar.style.background = "#1E3A8A";
                navbar.style.boxShadow = "0px 4px 10px rgba(0, 0, 0, 0.3)";
            } else {
                navbar.style.background = "linear-gradient(135deg, #1E3A8A, #5f9ea0)";
                navbar.style.boxShadow = "none";
            }
        });
    });

    window.addEventListener('scroll', function() {
        document.querySelector('.navbar').classList.toggle('scrolled', window.scrollY > 50);
    });
    </script>
    <script>
document.getElementById("logoutBtn").addEventListener("click", function(event) {
    event.preventDefault(); // Mencegah langsung logout

    Swal.fire({
        title: "Apakah Anda yakin ingin logout?",
        text: "Anda harus login kembali untuk mengakses halaman ini!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, Logout!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "logout.php"; // Redirect jika user mengonfirmasi logout
        }
    });
});
</script>



</body>
</html>