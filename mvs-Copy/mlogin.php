<?php
include "./include/config.php";
include './include/functions.php';

// Initialize variables
$emailErr = $passwordErr = $error = '';
$email = $password = '';
$successMessage = '';

// CSRF Protection
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid request. Please try again.";
    } else {
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;
        $err = 0;

        // Email validation
        if (empty($email)) {
            $emailErr = "Email is required";
            $err = 1;
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
            $err = 1;
        } else {
            $email = test_input($email);
        }

        // Password validation
        if (empty($password)) {
            $passwordErr = "Password is required";
            $err = 1;
        } else {
            $password = test_input($password);
        }

        if ($err == 0) {
            try {
                // Prepared statement to prevent SQL injection
                $sql = "SELECT id, name, email, password, role, is_active FROM users WHERE email = :email";
                $query = $dbh->prepare($sql);
                $query->bindParam(':email', $email, PDO::PARAM_STR);
                $query->execute();

                if ($query->rowCount() == 1) {
                    $user = $query->fetch(PDO::FETCH_ASSOC);

                    // Check if account is active
                    if (!$user['is_active']) {
                        $error = "Account is inactive. Please contact support.";
                    } elseif (password_verify($password, $user['password'])) {
                        // Regenerate session ID to prevent session fixation
                        session_regenerate_id(true);

                        // Set session variables
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_name'] = $user['name'];
                        $_SESSION['user_role'] = $user['role'];
                        $_SESSION['logged_in'] = true;
                        $_SESSION['last_activity'] = time();

                        // Set secure, HttpOnly cookie
                        $cookieParams = session_get_cookie_params();
                        setcookie(
                            session_name(),
                            session_id(),
                            [
                                'expires' => time() + 3600, // 1 hour
                                'path' => $cookieParams['path'],
                                'domain' => $cookieParams['domain'],
                                'secure' => true,
                                'httponly' => true,
                                'samesite' => 'Strict'
                            ]
                        );

                        $successMessage = "Login successful! Redirecting...";
                    } else {
                        // Log failed login attempts
                        error_log("Failed login attempt for email: $email");
                        $error = "Invalid credentials.";
                    }
                } else {
                    $error = "Invalid credentials.";
                }
            } catch (PDOException $e) {
                error_log("Database error: " . $e->getMessage());
                $error = "System Error: Please try again later.";
            }
        }
    }
}

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | MultiVendor Marketplace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280;
        }

        .password-toggle:hover {
            color: #1e40af;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md border border-gray-100">
        <div class="text-center mb-8">
            <img src="assets/logo.png" alt="Logo" class="h-12 mx-auto mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Welcome Back</h2>
            <p class="text-gray-600 mt-2">Login to your account</p>
        </div>

        <form method="POST" class="space-y-5">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

            <div class="space-y-1">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <div class="relative">
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        placeholder="your@email.com">
                    <i class="fas fa-envelope absolute right-3 top-3.5 text-gray-400"></i>
                </div>
                <span class="text-red-500 text-sm"><?= $emailErr ?></span>
            </div>

            <div class="space-y-1">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition pr-10"
                        placeholder="••••••••">
                    <i class="fas fa-eye-slash password-toggle" id="togglePassword"></i>
                </div>
                <span class="text-red-500 text-sm"><?= $passwordErr ?></span>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                </div>
                <a href="forgot-password.php" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200">
                Login
            </button>
        </form>

        <div class="mt-6">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Or continue with</span>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-3">
                <a href="#"
                    class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    <i class="fab fa-google text-red-500 mr-2 mt-0.5"></i> Google
                </a>
                <a href="#"
                    class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    <i class="fab fa-facebook-f text-blue-600 mr-2 mt-0.5"></i> Facebook
                </a>
            </div>
        </div>

        <p class="mt-6 text-center text-sm text-gray-600">
            Don't have an account?
            <a href="register.php" class="text-blue-600 font-medium hover:underline">Sign up</a>
        </p>
    </div>

    <script>
        // Password toggle visibility
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // SweetAlert notifications
        <?php if (!empty($error)): ?>
            Swal.fire({
                title: 'Error',
                text: '<?= addslashes($error) ?>',
                icon: 'error',
                confirmButtonColor: '#3b82f6',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            Swal.fire({
                title: 'Success',
                text: '<?= addslashes($successMessage) ?>',
                icon: 'success',
                confirmButtonColor: '#3b82f6',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = '<?= ($user['role'] === 'vendor' ? 'vendor/dashboard.php' : 'account/dashboard.php') ?>';
            });
        <?php endif; ?>
    </script>
</body>

</html>