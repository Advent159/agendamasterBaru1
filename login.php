<?php
session_start();
include 'koneksi/koneksi.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: dashboard.php');
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
    <style>
        .error-box {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #ff4d4d;
            color: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
    
    <!-- Notifikasi Error -->
    <?php if (!empty($error)) : ?>
        <div id="error-box" class="error-box">
            <strong>⚠️ Error:</strong> <?= $error ?>
            <button onclick="closeError()" class="ml-4 text-white font-bold">✖</button>
        </div>
        <script>
            document.getElementById("error-box").style.display = "block";
            function closeError() {
                document.getElementById("error-box").style.display = "none";
            }
            setTimeout(closeError, 5000);
        </script>
    <?php endif; ?>
    
    <nav class="shadow-lg bg-blue-600 p-4 text-white flex justify-between items-center">
        <a href="#" class="text-xl font-bold flex items-center">
            <i class="fa-solid fa-calendar-check mr-2"></i> Magenda Master
        </a>
    </nav>
    
    <div class="flex flex-1 items-center justify-center">
        <div class="bg-white rounded-2xl shadow-lg flex max-w-4xl w-full">
            <div class="hidden md:flex w-1/2 bg-blue-500 rounded-l-2xl">
                <img src="./img/Loginn.jpg" alt="Illustration" class="w-full h-full object-cover rounded-l-2xl">
            </div>
            <div class="w-full md:w-1/2 p-8 flex flex-col justify-center">
                <h2 class="text-2xl font-semibold text-gray-800 text-center">Login</h2>
                <form action="#" method="POST" class="mt-6">
                    <div class="mb-4">
                        <label class="block text-gray-600 text-sm font-medium">Email</label>
                        <div class="flex items-center border rounded-lg p-3 mt-1 bg-gray-100">
                            <i class="fa-solid fa-envelope text-blue-500 mr-3"></i>
                            <input type="email" name="email" class="bg-transparent focus:outline-none w-full" placeholder="Enter your email" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-600 text-sm font-medium">Password</label>
                        <div class="flex items-center border rounded-lg p-3 mt-1 bg-gray-100">
                            <i class="fa-solid fa-lock text-blue-500 mr-3"></i>
                            <input type="password" name="password" class="bg-transparent focus:outline-none w-full" placeholder="Enter your password" required>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-lg transition-all mt-4">
                        Login
                    </button>
                </form>
                <div class="text-center mt-6">
                    <p class="text-gray-600">Don't have an account? <a href="register.php" class="text-blue-500 hover:underline">Register</a></p>
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
</html>
