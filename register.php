<?php
include 'koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $birthdate = $_POST['tanggal_lahir'];

    // Hitung usia pengguna
    $today = new DateTime();
    $dob = new DateTime($birthdate);
    $age = $today->diff($dob)->y;

    if ($age < 18) {
        $error = 'Registrasi gagal! Anda harus berusia minimal 18 tahun.';
    } else {
        // Cek apakah username atau email sudah digunakan
        $checkQuery = "SELECT * FROM user WHERE username = ? OR email = ?";
        $stmtCheck = $conn->prepare($checkQuery);
        $stmtCheck->bind_param('ss', $username, $email);
        $stmtCheck->execute();
        $result = $stmtCheck->get_result();

        if ($result->num_rows > 0) {
            $error = 'Username atau email sudah digunakan!';
        } else {
            $sql = "INSERT INTO user (username, email, password, tanggal_lahir) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssss', $username, $email, $password, $birthdate);

            if ($stmt->execute()) {
                header('Location: login.php');
                exit;
            } else {
                $error = 'Registrasi gagal!';
            }
        }
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
    <style>
        @keyframes slideIn {
            0% { transform: translateY(-50px) scale(0.5); opacity: 0; }
            80% { transform: translateY(10px) scale(1.05); opacity: 1; }
            100% { transform: translateY(0) scale(1); opacity: 1; }
        }

        @keyframes fadeOut {
            0% { opacity: 1; transform: scale(1); }
            100% { opacity: 0; transform: scale(0.8); }
        }

        .show-notif {
            animation: slideIn 0.5s ease-out forwards;
        }

        .hide-notif {
            animation: fadeOut 0.4s ease-in forwards;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <nav class="bg-[#346ADF] p-4 shadow-md w-full fixed top-0 z-10">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-white text-xl font-semibold flex items-center">
                <i class="fa-solid fa-calendar-check mr-2"></i> Magenda Master
            </a>
        </div>
    </nav>
    <!-- Notifikasi -->
<div id="notif-container" class="fixed top-5 left-1/2 transform -translate-x-1/2 z-50 hidden">
    <div id="notif-box" class="bg-red-500 text-white px-5 py-3 rounded-lg shadow-lg flex items-center space-x-3 opacity-0 scale-75 transition-all duration-500">
        <i class="fa-solid fa-triangle-exclamation text-xl"></i>
        <span id="notif-text"></span>
        <button id="close-notif" class="text-xl font-bold hover:text-gray-300">&times;</button>
    </div>
</div>



    <div class="flex items-center justify-center flex-grow mt-16">
        <div class="bg-white rounded-2xl shadow-lg flex max-w-4xl w-full">
            <div class="hidden md:flex w-1/2 bg-blue-500 rounded-l-2xl">
                <img src="./img/regis.jpg" alt="Illustration" class="w-full h-full object-cover rounded-l-2xl">
            </div>
            <div class="w-full md:w-1/2 p-8">
                <h2 align="center" class="text-2xl font-semibold text-gray-800">Register</h2>
                <form action="#" method="POST">
                    <div class="mb-4">
                        <label class="block text-gray-600 text-sm font-medium">Username</label>
                        <div class="flex items-center border rounded-lg p-2 mt-1 bg-gray-100">
                            <i class="fa-solid fa-user text-blue-500 mr-2"></i>
                            <input type="text" name="username" class="bg-transparent focus:outline-none w-full" required placeholder="Masukan username">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-600 text-sm font-medium">Email</label>
                        <div class="flex items-center border rounded-lg p-2 mt-1 bg-gray-100">
                            <i class="fa-solid fa-envelope text-blue-500 mr-2"></i>
                            <input type="email" name="email" class="bg-transparent focus:outline-none w-full" required placeholder="Masukan email">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-600 text-sm font-medium">Password</label>
                        <div class="flex items-center border rounded-lg p-2 mt-1 bg-gray-100">
                            <i class="fa-solid fa-lock text-blue-500 mr-2"></i>
                            <input type="password" name="password" class="bg-transparent focus:outline-none w-full" required placeholder="Masukan password">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-600 text-sm font-medium">Tanggal Lahir</label>
                        <div class="flex items-center border rounded-lg p-2 mt-1 bg-gray-100">
                            <i class="fa-solid fa-calendar text-blue-500 mr-2"></i>
                            <input type="date" name="tanggal_lahir" class="bg-transparent focus:outline-none w-full" required>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-lg transition-all">Register</button>
                    </form>
                
                <div class="text-center mt-6">
                    <p class="text-gray-600">Sudah punya akun? <a href="login.php" class="text-blue-500 hover:underline">Login</a></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
    const notifContainer = document.getElementById("notif-container");
    const notifBox = document.getElementById("notif-box");
    const closeNotif = document.getElementById("close-notif");

    function showNotification(message) {
        document.getElementById("notif-text").textContent = message;
        notifContainer.classList.remove("hidden");
        notifBox.classList.add("show-notif");

        setTimeout(() => hideNotification(), 5000);
    }

    function hideNotification() {
        notifBox.classList.add("hide-notif");
        setTimeout(() => {
            notifContainer.classList.add("hidden");
            notifBox.classList.remove("show-notif", "hide-notif");
        }, 400);
    }

    closeNotif.addEventListener("click", hideNotification);

    <?php if (isset($error)) { ?>
        showNotification("<?php echo addslashes($error); ?>");
    <?php } ?>
});

    </script>
</body>
</html>
