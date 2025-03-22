<?php
include '../database/connect.php'; 

$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $errorMessage = "Password dan konfirmasi password tidak sama!";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $role = 1; 
        $status = null; 
        $bem = null; 
        $blm = null; 

        $emailQuery = "SELECT * FROM user WHERE U_Email = ?";
        $stmt = $conn->prepare($emailQuery);
        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $errorMessage = "Email sudah terdaftar!";
            } else {
                $insertQuery = "INSERT INTO user (U_Email, U_Password, U_Role, U_BEM, U_BLM, U_Status) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt_insert = $conn->prepare($insertQuery);
                if ($stmt_insert) {
                    $stmt_insert->bind_param('ssiiss', $email, $hashedPassword, $role, $bem, $blm, $status);

                    if ($stmt_insert->execute()) {
                        header("Location: login.php?register=success");
                        exit();
                    } else {
                        $errorMessage = "Terjadi kesalahan saat registrasi!";
                    }

                    $stmt_insert->close();
                } else {
                    $errorMessage = "Terjadi kesalahan saat persiapan query insert!";
                }
            }

            $stmt->close();
        } else {
            $errorMessage = "Terjadi kesalahan saat persiapan query!";
        }
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="../output.css" rel="stylesheet" />
    <title>Register Page</title>
    <style>
        .load {
            animation: transitionIn 0.75s;
        }

        @keyframes transitionIn {
            from {
                opacity: 0;
                transform: rotateX(-10deg);
            }

            to {
                opacity: 1;
                transform: rotateX(0);
            }
        }

        .error-message {
            background-color: #fddede;
            color: #9b2c2c;
            padding: 10px;
            border: 1px solid #9b2c2c;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white shadow-lg rounded-lg load">
        <?php if ($errorMessage != ""): ?>
            <div class="error-message text-center" id="error-message hidden">
                <?= $errorMessage ?>
            </div>
        <?php endif; ?>

        <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-md load">
            <h2 class="text-2xl font-bold mb-6 text-center">REGISTER</h2>
            <form action="register.php" method="POST" id="registerForm">
                <div class="mb-4 relative">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-700">
                        Email
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="Masukkan email"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required 
                    />
                </div>
                <div class="mb-4">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-700">
                        Password
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Masukkan password"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required 
                    />
                </div>
                <div class="mb-4 relative">
                    <label for="confirm_password" class="block mb-2 text-sm font-medium text-gray-700">
                        Konfirmasi Password
                    </label>
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        placeholder="Ulangi password"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required 
                    />
                </div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-400 transition-colors cursor-pointer">
                    Buat
                </button>
            </form>

            <p class="mt-4 text-center text-sm text-gray-600">
                Sudah memiliki akun?
                <a href="login.php" class="text-blue-500 hover:underline">Login</a>
            </p>
        </div>
    </div>
    

    <script>
        const form = document.getElementById('registerForm');
        const emailInput = document.getElementById('email');
        const emailWarning = document.getElementById('emailWarning');

        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('confirm_password');
        const passwordWarning = document.getElementById('passwordWarning');

        form.addEventListener('submit', function (event) {
            emailWarning.textContent = '';
            emailWarning.classList.add('hidden');

            passwordWarning.textContent = '';
            passwordWarning.classList.add('hidden');

            const emailValue = emailInput.value.trim();
            const atPos = emailValue.indexOf('@');

            const passwordValue = passwordInput.value.trim();
            const confirmValue = confirmInput.value.trim();
            if (passwordValue !== confirmValue) {
                event.preventDefault();
                passwordWarning.textContent = 'Password tidak sama';
                passwordWarning.classList.remove('hidden');
                return;
            }
        });
    </script>
</body>

</html>
