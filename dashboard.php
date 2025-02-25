<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_username'])) {
    $_SESSION['username'] = htmlspecialchars($_POST['new_username']);
    header("Location: ".$_SERVER['PHP_SELF']); // Refresh halaman setelah update
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Dashboard</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    

   
</head>
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
       /* Promo Image Styling */
.promo-container {
    position: relative;
    max-width: 600px;
    margin: auto;
    overflow: hidden;
    border-radius: 15px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.promo-container img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-radius: 15px;
    transition: transform 0.3s ease-in-out, filter 0.3s ease-in-out;
}

/* Hover effect */
.promo-container:hover img {
    transform: scale(1.05);
    filter: brightness(90%);
}

/* Overlay effect */
.promo-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0, 0, 0, 0.6);
    color: white;
    padding: 20px;
    text-align: center;
    border-radius: 0 0 15px 15px;
    transition: background 0.3s ease-in-out;
}

/* Hover effect untuk overlay */
.promo-container:hover .promo-overlay {
    background: rgba(0, 0, 0, 0.8);
}

    </style>
<body class="bg-gray-100">
    <!-- Loading Screen -->
<div id="loading-screen">
        <div class="loader">
            <div class="spinner"></div>
            <p>Loading...</p>
        </div>
    </div>
    <div class="max-w-screen-xl mx-auto p-6">
        
        <!-- Navbar -->
        <nav class="flex justify-between items-center py-4">
        <h1 class="text-2xl font-bold flex items-center">
    <i class="fa-solid fa-calendar-days mr-2 text-black-500"></i> Magenda Master.
</h1>
            <ul class="flex space-x-6">
            <li class="nav-item"><a class="nav-link" href="#"><i class="fa-solid fa-house"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="list_event_user.php"><i class="fa-solid fa-calendar"></i> Events</a></li>
                    
            </ul>
            




            <!-- Dropdown for profile -->
            <div class="dropdown">
                <button class="btn btn-outline dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-user-circle fa-2x text-black me-2"></i>
                    <span><?= isset($_SESSION['username']) ? $_SESSION['username'] : "Guest"; ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="edit_profile.php">
                            <i class="fa-solid fa-user-gear me-2"></i> Edit Profile
                        </a>
                    </li>
                    <a class="dropdown-item d-flex align-items-center" href="./admin/login_admin.php">
                    <i class="fa-solid fa-shield-halved me-2"></i> Admin Dashboard
                </a>
                 
                        <a class="dropdown-item d-flex align-items-center" href="logout.php" id="logoutBtn">
                            <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center mt-10">
            <div>
                <h2 class="text-4xl font-bold leading-tight ">Magenda Master <span class="text-blue-500">Tempat Menyediakan Berbagai Jenis Event</span></h2>
                <p class="text-gray-600 mt-4">Kami Menyediakan Semua jenis Event Mulai dari Konser musik, kajian, dan Webinar</p>
                <div class="mt-6 flex space-x-4">
                   
                </div>
            </div>
            <div class="relative">
                <img src="./img/dashboard.png.png" alt="Student" class="w-full rounded-lg shadow-lg">
            </div>
        </div>

        <!-- Courses Section -->
        <h3 class="text-2xl font-bold mt-12">Nikmati kegiatan Event bersama magenda master</h3>

      
        <!-- Info Section -->
        <div class="bg-gray-50 p-6 mt-12 rounded-lg shadow-lg">
            <h4 class="font-semibold">Info Kegiatan</h4>
            <p class="text-gray-600">Bergabunglah dengan berbagai kegiatan menarik yang diselenggarakan di platform kami. Setiap event memberikan pengalaman yang berbeda, dari seminar hingga workshop. Jangan lewatkan kesempatan berharga!</p>
        </div>
    </div>

    <div class="promo-container max-w-4xl mx-auto my-8 p-4 bg-white rounded-lg shadow-lg">
    <h3 class="text-xl font-semibold text-center mb-4">Spesial Event</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Promo Card 1 -->
        <div class="relative group overflow-hidden rounded-lg shadow-lg">
            <img src="./img/musik.png" alt="Event 1" class="w-full h-60 object-cover transition-transform duration-300 group-hover:scale-105">
            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white p-3 text-center">
                <h4 class="text-lg font-semibold">Konser Musik</h4>
                <p class="text-sm">Nikmati konser seru dengan artis favorit!</p>
            </div>
        </div>

        <!-- Promo Card 2 -->
        <div class="relative group overflow-hidden rounded-lg shadow-lg">
            <img src="./img/poster.png" alt="Event 2" class="w-full h-60 object-cover transition-transform duration-300 group-hover:scale-105">
            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white p-3 text-center">
                <h4 class="text-lg font-semibold">Webinar UI/UX</h4>
                <p class="text-sm">Tingkatkan skill desain dengan pakar UI/UX.</p>
            </div>
        </div>

        <!-- Promo Card 3 -->
        <div class="relative group overflow-hidden rounded-lg shadow-lg">
            <img src="./img/kajian.png" alt="Event 3" class="w-full h-60 object-cover transition-transform duration-300 group-hover:scale-105">
            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white p-3 text-center">
                <h4 class="text-lg font-semibold">Kajian Agama</h4>
                <p class="text-sm">Perdalam wawasan spiritual dengan kajian agama.</p>
            </div>
        </div>
    </div>
</div>

   
   
      <!-- Footer Section -->
<footer class="bg-gray-800 text-white py-6 mt-12">
    <div class="container mx-auto text-center">
        <div class="flex justify-center space-x-8 mb-4">
            <!-- Social Media Icons -->
            <a href="https://facebook.com" target="_blank" class="text-gray-400 hover:text-white transition duration-300">
                <i class="fab fa-facebook-f fa-2x"></i>
            </a>
            <a href="https://twitter.com" target="_blank" class="text-gray-400 hover:text-white transition duration-300">
                <i class="fab fa-twitter fa-2x"></i>
            </a>
            <a href="https://instagram.com" target="_blank" class="text-gray-400 hover:text-white transition duration-300">
                <i class="fab fa-instagram fa-2x"></i>
            </a>
        </div>

        <!-- Footer Text -->
        <p class="text-sm text-gray-400">© 2025 Magenda Master. All Rights Reserved.</p>
    </div>
</footer>

<!-- Font Awesome for Icons -->
<script src="https://kit.fontawesome.com/a076d05399.js"></script>

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