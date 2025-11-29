<?php
include "./include/config.php";
include './include/functions.php';

$emailErr = $passwordErr = $error = '';
$email = $password = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $email = $_POST['email'] ?? null;
  $password = $_POST['password'] ?? null;
  $err = 0;

  if (empty($email)) {
    $emailErr = "Email is required";
    $err = 1;
  } else {
    $email = test_input($email);
  }

  if (empty($password)) {
    $passwordErr = "Password is required";
    $err = 1;
  } else {
    $password = test_input($password);
  }

  if ($err == 0) {
    try {
      $sql = "SELECT user_id, username, email, password, role FROM users WHERE email = :email";
      $query = $dbh->prepare($sql);
      $query->bindParam(':email', $email, PDO::PARAM_STR);
      $query->execute();

      if ($query->rowCount() == 1) {
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $user['password'])) {
          // Set session variables
          $_SESSION['user_id'] = $user['user_id'];
          $_SESSION['user_name'] = $user['username'];
          $_SESSION['user_role'] = $user['role'];

          // Display success alert and redirect based on role
          $successMessage = "Login successful!";

        } else {
          $error = "Invalid password.";
        }
      } else {
        $error = "No user found with this email.";
      }
    } catch (PDOException $e) {
      $error = $e->getMessage();
      $error = "System Error: Please contact administrator.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-white min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        <h2 class="text-3xl font-bold text-navy mb-6 text-center">Login</h2>

        <form method="POST" class="space-y-4">

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-navy focus:ring-navy p-3"
                    placeholder="Enter your email">
                <span class="text-red-500"><?= $emailErr ?></span>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-navy focus:ring-navy p-3"
                    placeholder="Enter your password">
                <span class="text-red-500"><?= $passwordErr ?></span>
            </div>

            <button type="submit" class="w-full bg-blue-800 text-white py-3 rounded-lg hover:bg-blue-900 transition">
                Login
            </button>
        </form>

        <p class="mt-4 text-center text-sm text-gray-600">
            Don't have an account?
            <a href="register.php" class="text-navy font-semibold hover:underline">Sign Up</a>
        </p>

        <span class="text-red-500"><?= $error ?></span>
    </div>

    <?php
  // Handle SweetAlert for errors after HTML loads
  if (!empty($error)) {
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: '$error',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    </script>";
  }

  if (!empty($successMessage)) {
    echo "<script>
        Swal.fire({
            title: 'Success!',
            text: '$successMessage',
            icon: 'success',
            confirmButtonText: 'OK'
            }).then(function() {
                if ('{$user['role']}' === 'vendor') {
                    window.location = './vendor/vendor.php';
                } else {
                    window.location = 'home.php';
                }
            });
        </script>";
  }
  ?>

</body>

</html>