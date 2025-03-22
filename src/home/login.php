<?php
include '../database/connect.php'; 

$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM user WHERE U_Email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['U_Password'])) {
            if ($user['U_Status'] == 1) {
                $errorMessage = "Anda hanya dapat memilih sekali!";
            } else {
                session_start();
                $_SESSION['email'] = $user['U_Email'];
                $_SESSION['role'] = $user['U_Role'];

                if ($user['U_Role'] == 0) {
                    header("Location: ../admin/admin.php");
                    exit();
                } elseif ($user['U_Role'] == 1) {
                    header("Location: ../user/bem.php");
                    exit();
                }
            }
        } else {
            $errorMessage = "Email atau Password salah!";
        }
    } else {
        $errorMessage = "Email tidak ditemukan!";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link href="../output.css" rel="stylesheet" />
    <title>Login Page</title>
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

        .thank-you-message {
            background-color: #d1fae5;
            color: #047857;
            border: 1px solid #047857;
            padding: 10px;
            font-weight: bold;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white shadow-lg rounded-lg load">
        
        <div id="thankYouMessage" class="thank-you-message hidden">
            Terima kasih telah memilih
        </div>

        <?php if ($errorMessage != ""): ?>
        <div class="error-message text-center" id="error-message hidden">
            <?= $errorMessage ?>
        </div>
        <?php endif; ?>

        <div class="p-6">
            <h2 class="text-2xl font-bold mb-6 text-center">LOGIN</h2>
            <form action="login.php" method="POST">
                <div class="mb-4">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-700">
                        Email
                    </label>
                    <input
                        type="text"
                        id="email"
                        name="email"
                        placeholder="Masukkan email"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-400"
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
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-400"
                        required
                    />
                </div>
                <button type="submit" class="w-full bg-red-500 text-white py-2 px-4 rounded hover:bg-red-300 transition-colors cursor-pointer">
                    Login
                </button>
            </form>
            <p class="mt-4 text-center text-sm text-gray-600">
                Belum memiliki akun?
                <a href="register.php" class="text-blue-500 hover:underline">Register</a>
            </p>
        </div>
    </div>

    <script>
        if (document.referrer.includes('konfirmasi.php')) {
            document.getElementById('thankYouMessage').classList.remove('hidden');
        }

        if (sessionStorage.getItem('errorMessage')) {
            const errorMessage = sessionStorage.getItem('errorMessage');
            const errorDiv = document.getElementById('error-message');
            errorDiv.textContent = errorMessage;
            errorDiv.classList.remove('hidden');
            sessionStorage.removeItem('errorMessage');
        }
    </script>
</body>
</html>
