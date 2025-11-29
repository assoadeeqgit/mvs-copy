<?php 
include "./include/config.php";
include './include/functions.php';

$nameErr = $emailErr = $passwordErr = $roleErr = "";
$name = $email = $password = $role = "";
$warning = '';
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {  
    $name = $_POST['name'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $role = $_POST['role'] ?? null;
    $err = 0;

    if (empty($name)) {
        $nameErr = "Name is required"; 
        $err = 1;
    } else {
        $name = test_input($name);
    }

    if (empty($email)) {
        $emailErr = "Email is required";
        $err = 1;
    } else {
        $email = test_input($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
            $err = 1;
        }
    }

    if (empty($password)) {
        $passwordErr = "Password is required";
        $err = 1;
    } else {
        $password = test_input($password);
    }

    if (empty($role)) {
        $roleErr = "Role is required";
        $err = 1;
    } else {
        $role = test_input($role);
    }

    if ($err == 0) {
        try {
            $check_sql = "SELECT user_id FROM users WHERE email = :email";
            $check_query = $dbh->prepare($check_sql);
            $check_query->bindParam(':email', $email, PDO::PARAM_STR);
            $check_query->execute();

            if ($check_query->rowCount() > 0) {
                $error = 'Email already exists!';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $sql = "INSERT INTO users (username, email, password, role) 
                        VALUES (:username, :email, :password, :role)";
                $query = $dbh->prepare($sql);
                $query->bindParam(':username', $name, PDO::PARAM_STR);
                $query->bindParam(':email', $email, PDO::PARAM_STR);
                $query->bindParam(':password', $hashed_password, PDO::PARAM_STR);
                $query->bindParam(':role', $role, PDO::PARAM_STR);
                $query->execute();

                if ($query) {
                    $success = "Registration Successful. Click OK to Login.";
                } else {
                    $error = 'Error: Could not register user.';
                }
            }
        } catch (PDOException $e) {
            $error = 'System Error: Please contact administrator.';
        }
    } else {
        $warning = "Please fill all the required fields correctly!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    navy: '#1E3A8A',
                }
            }
        }
    }
    </script>
</head>

<body class="bg-white min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        <h2 class="text-3xl font-bold text-navy mb-6 text-center">Create Account</h2>

        <form method="POST" class="space-y-4">

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" id="name" name="name" required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-navy focus:ring-navy p-3"
                    placeholder="Enter your name">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-navy focus:ring-navy p-3"
                    placeholder="Enter your email">
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Select Role</label>
                <select id="role" name="role" required
                    class="w-full border-gray-300 rounded-md shadow-sm bg-white focus:border-navy focus:ring-navy p-3">
                    <option value="">-- Choose your role --</option>
                    <option value="vendor">Vendor</option>
                    <option value="customer">Customer</option>
                </select>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-navy focus:ring-navy p-3"
                    placeholder="Create a password">
            </div>

            <button type="submit" class="w-full bg-navy text-white py-3 rounded-lg hover:bg-blue-900 transition">
                Register
            </button>
        </form>

        <p class="mt-4 text-center text-sm text-gray-600">
            Already have an account?
            <a href="login.php" class="text-navy font-semibold hover:underline">Login</a>
        </p>
    </div>

    <?php
// Handle SweetAlert for Errors after HTML loads
if (!empty($warning)) {
    echo "<script>
        Swal.fire({
            title: 'Warning!',
            text: '$warning',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
    </script>";
}

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

if (!empty($success)) {
  echo "<script>
      Swal.fire({
          title: 'Success!',
          text: '$success',
          icon: 'success',
          confirmButtonText: 'OK'
      }).then(function() {
        window.location = 'login.php';
      });
  </script>";
}
?>

</body>

</html>