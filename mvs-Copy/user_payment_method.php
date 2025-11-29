<?php
include("./include/config.php");

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
$username = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : null;
if (!$user_id) {
    header("Location: login.php");
    exit;
}

// Fetch user details
try {
    $sql = "SELECT username, email, phone, created_at FROM users WHERE user_id = :user_id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        throw new Exception("User not found");
    }
    $member_since = date('F Y', strtotime($user['created_at']));
} catch (Exception $e) {
    error_log("User query failed: " . $e->getMessage());
    $user = ['username' => 'User', 'email' => '', 'phone' => '', 'created_at' => ''];
    $member_since = 'Unknown';
}

// Fetch payment methods
$payment_methods = [];
try {
    $sql = "SELECT payment_method_id, type, details, is_default FROM payment_methods WHERE user_id = :user_id ORDER BY is_default DESC, created_at DESC";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    $payment_methods = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Payment methods query failed: " . $e->getMessage());
}

// Handle form submissions
$error_message = '';
$success_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $dbh->beginTransaction();

        if (isset($_POST['add_payment'])) {
            $type = filter_var($_POST['payment_type'], FILTER_SANITIZE_STRING);
            $is_default = isset($_POST['is_default']) ? 1 : 0;

            if ($type === 'card') {
                $card_type = filter_var($_POST['card_type'], FILTER_SANITIZE_STRING);
                $card_number = filter_var($_POST['card_number'], FILTER_SANITIZE_STRING);
                $cardholder_name = filter_var($_POST['cardholder_name'], FILTER_SANITIZE_STRING);
                $expiry = filter_var($_POST['expiry'], FILTER_SANITIZE_STRING);
                $cvc = filter_var($_POST['cvc'], FILTER_SANITIZE_STRING);

                if (!preg_match('/^\d{4}$/', substr($card_number, -4)) || !preg_match('/^\d{3,4}$/', $cvc) || !preg_match('/^(0[1-9]|1[0-2])\/[0-9]{2}$/', $expiry)) {
                    throw new Exception("Invalid card details");
                }

                $details = [
                    'card_type' => $card_type,
                    'last_four' => substr($card_number, -4),
                    'expiry' => $expiry,
                    'cardholder_name' => $cardholder_name
                ];
            } elseif ($type === 'paypal') {
                $paypal_email = filter_var($_POST['paypal_email'], FILTER_SANITIZE_EMAIL);
                if (!filter_var($paypal_email, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("Invalid PayPal email");
                }
                $details = ['email' => $paypal_email];
            } else {
                throw new Exception("Invalid payment type");
            }

            // If setting as default, unset others
            if ($is_default) {
                $sql = "UPDATE payment_methods SET is_default = 0 WHERE user_id = :user_id";
                $stmt = $dbh->prepare($sql);
                $stmt->execute(['user_id' => $user_id]);
            }

            // Insert new payment method
            $sql = "INSERT INTO payment_methods (user_id, type, details, is_default, created_at, updated_at)
                    VALUES (:user_id, :type, :details, :is_default, NOW(), NOW())";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([
                'user_id' => $user_id,
                'type' => $type,
                'details' => json_encode($details),
                'is_default' => $is_default
            ]);

            $success_message = "Payment method added successfully";
        } elseif (isset($_POST['delete_payment'])) {
            $payment_method_id = (int)$_POST['payment_method_id'];
            $sql = "DELETE FROM payment_methods WHERE payment_method_id = :payment_method_id AND user_id = :user_id";
            $stmt = $dbh->prepare($sql);
            $stmt->execute(['payment_method_id' => $payment_method_id, 'user_id' => $user_id]);
            $success_message = "Payment method deleted successfully";
        } elseif (isset($_POST['set_default'])) {
            $payment_method_id = (int)$_POST['payment_method_id'];
            $sql = "UPDATE payment_methods SET is_default = 0 WHERE user_id = :user_id";
            $stmt = $dbh->prepare($sql);
            $stmt->execute(['user_id' => $user_id]);

            $sql = "UPDATE payment_methods SET is_default = 1 WHERE payment_method_id = :payment_method_id AND user_id = :user_id";
            $stmt = $dbh->prepare($sql);
            $stmt->execute(['payment_method_id' => $payment_method_id, 'user_id' => $user_id]);
            $success_message = "Default payment method updated";
        }

        $dbh->commit();
        // Refresh payment methods
        $sql = "SELECT payment_method_id, type, details, is_default FROM payment_methods WHERE user_id = :user_id ORDER BY is_default DESC, created_at DESC";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        $payment_methods = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $dbh->rollBack();
        $error_message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Methods - MultiVendor Marketplace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#4f46e5',
                    secondary: '#10b981',
                    dark: '#1f2937',
                    light: '#f9fafb',
                },
                animation: {
                    'float': 'float 6s ease-in-out infinite',
                },
                keyframes: {
                    float: {
                        '0%, 100%': {
                            transform: 'translateY(0)'
                        },
                        '50%': {
                            transform: 'translateY(-10px)'
                        }
                    }
                }
            }
        }
    }
    </script>
    <style>
    .profile-nav-item.active {
        border-bottom: 2px solid #4f46e5;
        color: #4f46e5;
    }

    .form-input {
        border: 1px solid #e5e7eb;
        padding: 0.5rem;
        border-radius: 0.375rem;
        width: 100%;
    }

    .form-input:focus {
        outline: none;
        box-shadow: 0 0 0 2px #4f46e5;
        border-color: transparent;
    }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <!-- Header Section -->
    <?php include('./include/user_profile_header.php'); ?>

    <!-- Payment Methods Section -->
    <section class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Profile Sidebar -->
            <div class="md:w-1/4">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4" data-aos="fade-up">
                    <div class="flex flex-col items-center mb-6">
                        <div class="relative mb-4">
                            <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80"
                                alt="Profile" class="w-24 h-24 rounded-full object-cover">
                            <button
                                class="absolute bottom-0 right-0 bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition">
                                <i class="fas fa-camera text-sm"></i>
                            </button>
                        </div>
                        <h2 class="font-bold text-xl"><?php echo $user['username']; ?></h2>
                        <p class="text-gray-600 text-sm">Member since <?php echo $member_since; ?></p>
                    </div>

                    <nav class="space-y-2">
                        <a href="user_profile.php"
                            class="profile-nav-item block px-4 py-2 text-gray-700 hover:text-primary transition flex items-center">
                            <i class="fas fa-user-circle mr-3"></i> My Profile
                        </a>
                        <a href="orders.php"
                            class="profile-nav-item block px-4 py-2 text-gray-700 hover:text-primary transition flex items-center">
                            <i class="fas fa-shopping-bag mr-3"></i> My Orders
                        </a>
                        <a href="wishlist.php"
                            class="profile-nav-item block px-4 py-2 text-gray-700 hover:text-primary transition flex items-center">
                            <i class="fas fa-heart mr-3"></i> Wishlist
                        </a>
                        <a href="user_address.php"
                            class="profile-nav-item block px-4 py-2 text-gray-700 hover:text-primary transition flex items-center">
                            <i class="fas fa-map-marker-alt mr-3"></i> Addresses
                        </a>
                        <a href="user_payment.php"
                            class="profile-nav-item active block px-4 py-2 text-gray-700 hover:text-primary transition flex items-center">
                            <i class="fas fa-credit-card mr-3"></i> Payment Methods
                        </a>
                        <a href="notifications.php"
                            class="profile-nav-item block px-4 py-2 text-gray-700 hover:text-primary transition flex items-center">
                            <i class="fas fa-bell mr-3"></i> Notifications
                        </a>
                        <a href="user_setting.php"
                            class="profile-nav-item block px-4 py-2 text-gray-700 hover:text-primary transition flex items-center">
                            <i class="fas fa-cog mr-3"></i> Account Settings
                        </a>
                        <a href="logout.php"
                            class="block px-4 py-2 text-red-500 hover:text-red-700 transition flex items-center">
                            <i class="fas fa-sign-out-alt mr-3"></i> Logout
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Payment Methods Content -->
            <div class="md:w-3/4">
                <div class="bg-white rounded-lg shadow-md p-6" data-aos="fade-up">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Payment Methods</h2>
                        <button id="add-payment-btn"
                            class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                            <i class="fas fa-plus mr-1"></i> Add New
                        </button>
                    </div>

                    <?php if ($error_message): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <?php echo htmlspecialchars($error_message); ?></div>
                    <?php endif; ?>
                    <?php if ($success_message): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        <?php echo htmlspecialchars($success_message); ?></div>
                    <?php endif; ?>

                    <!-- Saved Payment Methods -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php if (empty($payment_methods)): ?>
                        <p class="text-gray-600 col-span-2">No payment methods saved.</p>
                        <?php else: ?>
                        <?php foreach ($payment_methods as $method):
                                $details = json_decode($method['details'], true);
                            ?>
                        <div class="border border-gray-200 rounded-lg p-4 relative hover:border-primary transition">
                            <div class="absolute top-2 right-2 flex space-x-2">
                                <button class="text-gray-400 hover:text-primary edit-payment-btn"
                                    data-method-id="<?php echo $method['payment_method_id']; ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form method="POST" class="inline">
                                    <input type="hidden" name="payment_method_id"
                                        value="<?php echo $method['payment_method_id']; ?>">
                                    <button type="submit" name="delete_payment" class="text-gray-400 hover:text-red-500"
                                        onclick="return confirm('Are you sure you want to delete this payment method?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="flex items-center mb-2">
                                <?php if ($method['type'] === 'card'): ?>
                                <img src="https://via.placeholder.com/40x25?text=<?php echo $details['card_type']; ?>"
                                    alt="<?php echo $details['card_type']; ?>" class="h-6 mr-2">
                                <h3 class="font-semibold"><?php echo htmlspecialchars($details['card_type']); ?> ending
                                    in <?php echo htmlspecialchars($details['last_four']); ?></h3>
                                <?php else: ?>
                                <img src="https://via.placeholder.com/40x25?text=PayPal" alt="PayPal" class="h-6 mr-2">
                                <h3 class="font-semibold">PayPal</h3>
                                <?php endif; ?>
                            </div>
                            <?php if ($method['type'] === 'card'): ?>
                            <p class="text-gray-600">Cardholder:
                                <?php echo htmlspecialchars($details['cardholder_name']); ?></p>
                            <p class="text-gray-600">Expires: <?php echo htmlspecialchars($details['expiry']); ?></p>
                            <?php else: ?>
                            <p class="text-gray-600">Email: <?php echo htmlspecialchars($details['email']); ?></p>
                            <?php endif; ?>
                            <div class="mt-2">
                                <?php if ($method['is_default']): ?>
                                <span
                                    class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Default</span>
                                <?php else: ?>
                                <form method="POST" class="inline">
                                    <input type="hidden" name="payment_method_id"
                                        value="<?php echo $method['payment_method_id']; ?>">
                                    <button type="submit" name="set_default"
                                        class="text-primary hover:text-indigo-700 text-sm">
                                        Set as Default
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Add/Edit Payment Form -->
                    <div id="payment-form" class="hidden mt-6 p-6 bg-gray-50 rounded-lg">
                        <h3 class="text-xl font-bold text-gray-800 mb-4" id="form-title">Add Payment Method</h3>
                        <form method="POST" id="payment-form-inner">
                            <input type="hidden" name="payment_method_id" id="payment_method_id">
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-1">Payment Type</label>
                                <select name="payment_type" id="payment_type" class="form-input" required>
                                    <option value="card">Credit/Debit Card</option>
                                    <option value="paypal">PayPal</option>
                                </select>
                            </div>

                            <!-- Card Fields -->
                            <div id="card-fields" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="card_type" class="block text-gray-700 mb-1">Card Type</label>
                                    <select name="card_type" id="card_type" class="form-input">
                                        <option value="Visa">Visa</option>
                                        <option value="Mastercard">Mastercard</option>
                                        <option value="Amex">American Express</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="card_number" class="block text-gray-700 mb-1">Card Number</label>
                                    <input type="text" name="card_number" id="card_number" class="form-input"
                                        placeholder="1234 5678 9012 3456">
                                </div>
                                <div>
                                    <label for="cardholder_name" class="block text-gray-700 mb-1">Cardholder
                                        Name</label>
                                    <input type="text" name="cardholder_name" id="cardholder_name" class="form-input">
                                </div>
                                <div>
                                    <label for="expiry" class="block text-gray-700 mb-1">Expiry (MM/YY)</label>
                                    <input type="text" name="expiry" id="expiry" class="form-input" placeholder="MM/YY">
                                </div>
                                <div>
                                    <label for="cvc" class="block text-gray-700 mb-1">CVC</label>
                                    <input type="text" name="cvc" id="cvc" class="form-input" placeholder="123">
                                </div>
                            </div>

                            <!-- PayPal Fields -->
                            <div id="paypal-fields" class="hidden">
                                <div class="mb-4">
                                    <label for="paypal_email" class="block text-gray-700 mb-1">PayPal Email</label>
                                    <input type="email" name="paypal_email" id="paypal_email" class="form-input"
                                        placeholder="example@paypal.com">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_default" id="is_default" class="mr-2">
                                    <span>Set as default payment method</span>
                                </label>
                            </div>

                            <div class="flex space-x-4">
                                <button type="submit" name="add_payment"
                                    class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                                    Save Payment Method
                                </button>
                                <button type="button" id="cancel-form"
                                    class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <img src="https://via.placeholder.com/150x50?text=MultiVendor" alt="Logo" class="h-8 mb-4">
                    <p class="text-gray-400">The marketplace for independent sellers and buyers.</p>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-4">Shop</h4>
                    <ul class="space-y-2">
                        <li><a href="home.php" class="text-gray-400 hover:text-white transition">All Products</a></li>
                        <li><a href="home.php#featured" class="text-gray-400 hover:text-white transition">Featured</a>
                        </li>
                        <li><a href="deals.php" class="text-gray-400 hover:text-white transition">Deals</a></li>
                    </ul