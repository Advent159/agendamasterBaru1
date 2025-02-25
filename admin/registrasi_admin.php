<?php
include '../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO admins (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $username, $email, $password);

    if ($stmt->execute()) {
        header('Location: login_admin.php');
        exit;
    } else {
        $error = 'Registrasi gagal!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Magenda Master</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"> 
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-[#5F9EA0] p-4 shadow-md w-full fixed top-0 z-10">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-white text-xl font-semibold flex items-center">
                <i class="fa-solid fa-calendar-check mr-2"></i> Magenda Master
            </a>
           
        </div>
    </nav>

    <!-- Wrapper untuk konten -->
    <div class="flex items-center justify-center flex-grow mt-16">
        
        <div class="bg-white rounded-2xl shadow-lg flex max-w-4xl w-full">
            
            <!-- Bagian Kiri (Gambar) -->
            <div class="hidden md:flex w-1/2 bg-blue-500 rounded-l-2xl">
                <img src="../img/regis.jpg" alt="Illustration"
                    class="w-full h-full object-cover rounded-l-2xl">
            </div>

            <!-- Bagian Kanan (Form Register) -->
            <div class="w-full md:w-1/2 p-8">
                <h2 style="font-family: Cambria; font-size:30px;" align="center" class="text-2xl font-semibold text-gray-800">Register</h2>

                <form action="#" method="POST">
                    <!-- Input Username -->
                    <div class="mb-4">
                        <label class="block text-gray-600 text-sm font-medium">Username</label>
                        <div class="flex items-center border rounded-lg p-2 mt-1 bg-gray-100">
                            <i class="fa-solid fa-user text-blue-500 mr-2"></i>
                            <input type="text" name="username" class="bg-transparent focus:outline-none w-full" placeholder="Enter your username" required>
                        </div>
                    </div>

                    <!-- Input Email -->
                    <div class="mb-4">
                        <label class="block text-gray-600 text-sm font-medium">Email Address</label>
                        <div class="flex items-center border rounded-lg p-2 mt-1 bg-gray-100">
                            <i class="fa-solid fa-envelope text-blue-500 mr-2"></i>
                            <input type="email" name="email" class="bg-transparent focus:outline-none w-full" placeholder="Enter your email" required>
                        </div>
                    </div>

                    <!-- Input Password -->
                    <div class="mb-4">
                        <label class="block text-gray-600 text-sm font-medium">Password</label>
                        <div class="flex items-center border rounded-lg p-2 mt-1 bg-gray-100">
                            <i class="fa-solid fa-lock text-blue-500 mr-2"></i>
                            <input type="password" name="password" class="bg-transparent focus:outline-none w-full" placeholder="Enter your password" required>
                        </div>
                    </div>

                    <!-- Tombol Register -->
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-lg transition-all">
                        Register
                    </button>
                </form>

                <div class="text-center mt-6">
                    <p class="text-gray-600">Sudah punya akun? <a href="login_admin.php" class="text-blue-500 hover:underline">Login</a></p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
