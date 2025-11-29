<?php
// Database connection
$host = '127.0.0.1';
$dbname = 'mvs';
$username = 'root'; // Update with your DB username
$password = ''; // Update with your DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch support email from admin_settings
$supportEmail = 'support@multivendor.com'; // Default fallback
try {
    $sql = "SELECT setting_value FROM admin_settings WHERE setting_key = 'support_email'";
    $stmt = $pdo->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $supportEmail = $result['setting_value'];
    }
} catch (PDOException $e) {
    // Log error silently; use fallback email
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">
    <title>Help Center - MultiVendor Marketplace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            }
        }
    }
    </script>
</head>

<body class="bg-gray-50 font-sans">
    <!-- Header -->
    <header class="sticky top-0 z-50 bg-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center">
                <img src="https://via.placeholder.com/150x50?text=MultiVendor" alt="Logo" class="h-8">
            </div>
            <div class="hidden md:flex flex-1 mx-4">
                <form action="search-results.php" method="GET" class="relative w-full">
                    <input type="text" name="query" placeholder="Search for products..."
                        class="w-full py-3 px-6 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-base">
                    <button type="submit" class="absolute right-0 top-0 h-full px-4 text-gray-500">
                        <i class="fas fa-search text-lg"></i>
                    </button>
                </form>
            </div>
            <div class="flex items-center space-x-4">
                <a href="user_profile.php" class="p-2 text-gray-700 hover:text-primary"><i
                        class="fas fa-user text-lg"></i></a>
                <a href="cart.php" class="p-2 text-gray-700 hover:text-primary relative">
                    <i class="fas fa-shopping-cart text-lg"></i>
                    <span
                        class="absolute -top-1 -right-1 bg-primary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                </a>
                <button class="md:hidden p-2 text-gray-700"><i class="fas fa-bars text-lg"></i></button>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-light py-12">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Help Center</h1>
            <p class="text-lg text-gray-600 mb-8">Find answers to your questions or contact our support team.</p>
            <form action="#" method="GET" class="max-w-xl mx-auto">
                <div class="relative">
                    <input type="text" name="help_query" placeholder="Search help articles..."
                        class="w-full py-3 px-6 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-base">
                    <button type="submit" class="absolute right-0 top-0 h-full px-4 text-gray-500">
                        <i class="fas fa-search text-lg"></i>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="container mx-auto px-4 py-12">
        <h2 class="text-3xl font-bold mb-8">Frequently Asked Questions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Shopping -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold mb-4">Shopping</h3>
                <div class="space-y-4">
                    <div>
                        <h4 class="font-medium text-gray-800">How do I place an order?</h4>
                        <p class="text-gray-600">Browse products, add items to your cart, and proceed to checkout.
                            Follow the prompts to enter your shipping and payment details.</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800">What payment methods are accepted?</h4>
                        <p class="text-gray-600">We accept credit/debit cards, PayPal, Apple Pay, Google Pay, and bank
                            transfers. Check the checkout page for all options.</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800">How can I track my order?</h4>
                        <p class="text-gray-600">Once your order is shipped, you’ll receive a tracking link via email.
                            You can also check the status in your account under “Orders.”</p>
                    </div>
                </div>
                <a href="#" class="mt-4 inline-block text-primary hover:underline">View all shopping FAQs</a>
            </div>
            <!-- Selling -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold mb-4">Selling</h3>
                <div class="space-y-4">
                    <div>
                        <h4 class="font-medium text-gray-800">How do I become a vendor?</h4>
                        <p class="text-gray-600">Sign up for a vendor account, submit your business details, and wait
                            for approval. Once approved, you can start listing products.</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800">What are the fees for selling?</h4>
                        <p class="text-gray-600">Vendors pay a commission on each sale. Check the Vendor Dashboard for
                            detailed pricing information.</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800">How do I manage my inventory?</h4>
                        <p class="text-gray-600">Use the Vendor Dashboard to add, edit, or remove products. You can also
                            track stock levels and sales.</p>
                    </div>
                </div>
                <a href="#" class="mt-4 inline-block text-primary hover:underline">View all selling FAQs</a>
            </div>
            <!-- Account Management -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold mb-4">Account Management</h3>
                <div class="space-y-4">
                    <div>
                        <h4 class="font-medium text-gray-800">How do I reset my password?</h4>
                        <p class="text-gray-600">Click “Forgot Password” on the login page and follow the instructions
                            to reset your password via email.</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800">How do I update my profile?</h4>
                        <p class="text-gray-600">Go to your account settings to update your name, email, phone, or
                            address details.</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800">How do I delete my account?</h4>
                        <p class="text-gray-600">Contact our support team at
                            <?php echo htmlspecialchars($supportEmail); ?> to request account deletion.</p>
                    </div>
                </div>
                <a href="#" class="mt-4 inline-block text-primary hover:underline">View all account FAQs</a>
            </div>
            <!-- Shipping & Returns -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold mb-4">Shipping & Returns</h3>
                <div class="space-y-4">
                    <div>
                        <h4 class="font-medium text-gray-800">What are the shipping costs?</h4>
                        <p class="text-gray-600">Shipping costs vary by vendor and location. Check the product page or
                            checkout for details.</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800">Can I return a product?</h4>
                        <p class="text-gray-600">Most products can be returned within 30 days. Contact the vendor
                            through your order history to initiate a return.</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800">How long does shipping take?</h4>
                        <p class="text-gray-600">Shipping times depend on the vendor and method chosen. Typical delivery
                            is 3-7 business days.</p>
                    </div>
                </div>
                <a href="#" class="mt-4 inline-block text-primary hover:underline">View all shipping FAQs</a>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="bg-light py-12">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-6">Still Need Help?</h2>
            <p class="text-lg text-gray-600 mb-8">Contact our support team for personalized assistance.</p>
            <div class="flex flex-col md:flex-row justify-center items-center space-y-4 md:space-y-0 md:space-x-4">
                <a href="mailto:<?php echo htmlspecialchars($supportEmail); ?>"
                    class="bg-primary text-white py-3 px-6 rounded-lg hover:bg-indigo-700 transition flex items-center">
                    <i class="fas fa-envelope mr-2"></i> Email Support
                </a>
                <a href="#"
                    class="bg-secondary text-white py-3 px-6 rounded-lg hover:bg-green-700 transition flex items-center">
                    <i class="fas fa-comment mr-2"></i> Live Chat
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include('./include/footer.php'); ?>

</body>

</html>