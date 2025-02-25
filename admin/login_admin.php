<?php
session_start();
include '../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query untuk mengambil data admin berdasarkan email
    $sql = "SELECT * FROM admins WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    // Cek apakah email dan password cocok
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header('Location: dashboard_admin.php');
        exit;
    } else {
        $error = 'Email atau password salah!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Magenda Master</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class=" shadow-lg " style="background-color: #5F9EA0;">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            
            <!-- Logo -->
            <a href="#" class="text-white text-xl font-bold flex items-center">
                <i class="fa-solid fa-calendar-check mr-2"></i> Magenda Master
            </a>

            <!-- Tombol Menu untuk Mobile -->
            <button id="menu-btn" class="text-white text-xl md:hidden focus:outline-none">
                <i class="fa-solid fa-bars"></i>
            </button>

          
        </div>
    </nav>

    <!-- Login Container -->
    <div class="flex flex-1 items-center justify-center">
        <div class="bg-white rounded-2xl shadow-lg flex max-w-4xl w-full">
            
            <!-- Bagian Kiri (Gambar) -->
            <div class="hidden md:flex w-1/2 bg-blue-500 rounded-l-2xl">
                <img src="../img/Loginn.jpg" alt="Illustration" class="w-full h-full object-cover rounded-l-2xl">
            </div>

            <!-- Bagian Kanan (Form Login) -->
            <div class="w-full md:w-1/2 p-8 flex flex-col justify-center">
                <h2 style="font-family: Cambria; font-size:30px;" class="text-2xl font-semibold text-gray-800 text-center">Login Admin</h2>

                <form action="#" method="POST" class="mt-6">
                    <!-- Input Email -->
                    <div class="mb-4">
                        <label class="block text-gray-600 text-sm font-medium">Email</label>
                        <div class="flex items-center border rounded-lg p-3 mt-1 bg-gray-100">
                            <i class="fa-solid fa-envelope text-blue-500 mr-3"></i>
                            <input type="email" name="email" class="bg-transparent focus:outline-none w-full" placeholder="Enter your email" required>
                        </div>
                    </div>

                    <!-- Input Password -->
                    <div class="mb-4">
                        <label class="block text-gray-600 text-sm font-medium">Password</label>
                        <div class="flex items-center border rounded-lg p-3 mt-1 bg-gray-100">
                            <i class="fa-solid fa-lock text-blue-500 mr-3"></i>
                            <input type="password" name="password" class="bg-transparent focus:outline-none w-full" placeholder="Enter your password" required>
                        </div>
                    </div>

                    <!-- Tombol Login -->
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-lg transition-all mt-4">
                        Login
                    </button>
                </form>

                <div class="text-center mt-6">
                    <p class="text-gray-600">Don't have an account? <a href="registrasi_admin.php" class="text-blue-500 hover:underline">Register</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Script untuk Toggle Menu Mobile -->
    <script>
        document.getElementById('menu-btn').addEventListener('click', function() {
            document.getElementById('menu').classList.toggle('hidden');
        });
    </script>

</body>
