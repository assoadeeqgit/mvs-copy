<?php
include("./include/config.php");

$user_id = $_SESSION['user_id'];
$username = $_SESSION['user_name'];
$role =  $_SESSION['user_role'];
$user_logged_in = isset($_SESSION['user_id']);

// Get cart count if user is logged in
$cartCount = 0;
if ($user_logged_in) {
try {
// Join cart_items with carts to get the correct count for this user
$sql = "SELECT COUNT(ci.cart_item_id)
FROM cart_items ci
JOIN carts c ON ci.cart_id = c.cart_id
WHERE c.user_id = :user_id";
$stmt = $dbh->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$cartCount = $stmt->fetchColumn();
} catch (PDOException $e) {
error_log("Cart count query failed: " . $e->getMessage());
}
}

// Get wish count if user is logged in
$wishCount = 0;
if ($user_logged_in) {
try {
$sql = "SELECT COUNT(*) FROM wishlists WHERE user_id = :user_id";
$stmt = $dbh->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$wishCount = $stmt->fetchColumn();
} catch (PDOException $e) {
error_log("Cart count query failed: " . $e->getMessage());
}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Vendors</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
    @keyframes pulse-slow {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.7;
        }
    }

    .animate-pulse-slow {
        animation: pulse-slow 3s infinite;
    }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Navigation (simplified) -->
    <?php include('./include/user_header.php'); ?>

    <!-- Vendor Section -->
    <section class="py-12 container mx-auto px-4">
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold mb-4" data-aos="fade-up">Our Trusted Vendors</h1>
            <p class="text-gray-600 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                Discover products from our carefully selected vendors who meet our quality standards.
            </p>
        </div>

        <div class="relative">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <!-- Vendor Card 1 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden text-center p-6 hover:shadow-lg transition"
                    data-aos="zoom-in">
                    <div class="w-24 h-24 mx-auto mb-4 rounded-full overflow-hidden border-4 border-primary">
                        <img src="https://images.unsplash.com/photo-1599305445671-ac291c95aaa9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1169&q=80"
                            alt="Vendor" class="w-full h-full object-cover">
                    </div>
                    <h3 class="font-bold text-xl mb-1">EcoFriendly Homes</h3>
                    <div class="flex justify-center items-center mb-3">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="text-gray-500 text-sm ml-2">4.8</span>
                    </div>
                    <p class="text-gray-600 mb-4">126 products</p>
                    <a href="#"
                        class="inline-block bg-gray-100 hover:bg-primary hover:text-white px-4 py-2 rounded-lg transition">
                        Visit Store
                    </a>
                </div>

                <!-- Vendor Card 2 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden text-center p-6 hover:shadow-lg transition"
                    data-aos="zoom-in" data-aos-delay="100">
                    <div class="w-24 h-24 mx-auto mb-4 rounded-full overflow-hidden border-4 border-primary">
                        <img src="https://images.unsplash.com/photo-1556740738-b6a63e27c4df?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
                            alt="Vendor" class="w-full h-full object-cover">
                    </div>
                    <h3 class="font-bold text-xl mb-1">TechHaven</h3>
                    <div class="flex justify-center items-center mb-3">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="text-gray-500 text-sm ml-2">4.6</span>
                    </div>
                    <p class="text-gray-600 mb-4">89 products</p>
                    <a href="#"
                        class="inline-block bg-gray-100 hover:bg-primary hover:text-white px-4 py-2 rounded-lg transition">
                        Visit Store
                    </a>
                </div>

                <!-- Vendor Card 3 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden text-center p-6 hover:shadow-lg transition"
                    data-aos="zoom-in" data-aos-delay="200">
                    <div class="w-24 h-24 mx-auto mb-4 rounded-full overflow-hidden border-4 border-primary">
                        <img src="https://images.unsplash.com/photo-1576566588028-4147f3842f27?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1064&q=80"
                            alt="Vendor" class="w-full h-full object-cover">
                    </div>
                    <h3 class="font-bold text-xl mb-1">FashionHub</h3>
                    <div class="flex justify-center items-center mb-3">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="text-gray-500 text-sm ml-2">4.9</span>
                    </div>
                    <p class="text-gray-600 mb-4">215 products</p>
                    <a href="#"
                        class="inline-block bg-gray-100 hover:bg-primary hover:text-white px-4 py-2 rounded-lg transition">
                        Visit Store
                    </a>
                </div>

                <!-- Vendor Card 4 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden text-center p-6 hover:shadow-lg transition"
                    data-aos="zoom-in" data-aos-delay="300">
                    <div class="w-24 h-24 mx-auto mb-4 rounded-full overflow-hidden border-4 border-primary">
                        <img src="https://images.unsplash.com/photo-1518455027359-f3f8164ba6bd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
                            alt="Vendor" class="w-full h-full object-cover">
                    </div>
                    <h3 class="font-bold text-xl mb-1">HomeDecor</h3>
                    <div class="flex justify-center items-center mb-3">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="text-gray-500 text-sm ml-2">4.7</span>
                    </div>
                    <p class="text-gray-600 mb-4">178 products</p>
                    <a href="#"
                        class="inline-block bg-gray-100 hover:bg-primary hover:text-white px-4 py-2 rounded-lg transition">
                        Visit Store
                    </a>
                </div>

                <!-- Additional Vendor Cards (5-8) -->
                <!-- Vendor Card 5 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden text-center p-6 hover:shadow-lg transition"
                    data-aos="zoom-in" data-aos-delay="400">
                    <div class="w-24 h-24 mx-auto mb-4 rounded-full overflow-hidden border-4 border-primary">
                        <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
                            alt="Vendor" class="w-full h-full object-cover">
                    </div>
                    <h3 class="font-bold text-xl mb-1">BookWorld</h3>
                    <div class="flex justify-center items-center mb-3">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                        <span class="text-gray-500 text-sm ml-2">4.2</span>
                    </div>
                    <p class="text-gray-600 mb-4">342 products</p>
                    <a href="#"
                        class="inline-block bg-gray-100 hover:bg-primary hover:text-white px-4 py-2 rounded-lg transition">
                        Visit Store
                    </a>
                </div>

                <!-- Vendor Card 6 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden text-center p-6 hover:shadow-lg transition"
                    data-aos="zoom-in" data-aos-delay="500">
                    <div class="w-24 h-24 mx-auto mb-4 rounded-full overflow-hidden border-4 border-primary">
                        <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
                            alt="Vendor" class="w-full h-full object-cover">
                    </div>
                    <h3 class="font-bold text-xl mb-1">Gourmet Foods</h3>
                    <div class="flex justify-center items-center mb-3">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="text-gray-500 text-sm ml-2">4.9</span>
                    </div>
                    <p class="text-gray-600 mb-4">67 products</p>
                    <a href="#"
                        class="inline-block bg-gray-100 hover:bg-primary hover:text-white px-4 py-2 rounded-lg transition">
                        Visit Store
                    </a>
                </div>

                <!-- Vendor Card 7 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden text-center p-6 hover:shadow-lg transition"
                    data-aos="zoom-in" data-aos-delay="600">
                    <div class="w-24 h-24 mx-auto mb-4 rounded-full overflow-hidden border-4 border-primary">
                        <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1099&q=80"
                            alt="Vendor" class="w-full h-full object-cover">
                    </div>
                    <h3 class="font-bold text-xl mb-1">GadgetZone</h3>
                    <div class="flex justify-center items-center mb-3">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="text-gray-500 text-sm ml-2">4.5</span>
                    </div>
                    <p class="text-gray-600 mb-4">153 products</p>
                    <a href="#"
                        class="inline-block bg-gray-100 hover:bg-primary hover:text-white px-4 py-2 rounded-lg transition">
                        Visit Store
                    </a>
                </div>

                <!-- Vendor Card 8 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden text-center p-6 hover:shadow-lg transition"
                    data-aos="zoom-in" data-aos-delay="700">
                    <div class="w-24 h-24 mx-auto mb-4 rounded-full overflow-hidden border-4 border-primary">
                        <img src="https://images.unsplash.com/photo-1491553895911-0055eca6402d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1160&q=80"
                            alt="Vendor" class="w-full h-full object-cover">
                    </div>
                    <h3 class="font-bold text-xl mb-1">SportLife</h3>
                    <div class="flex justify-center items-center mb-3">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                        <span class="text-gray-500 text-sm ml-2">4.1</span>
                    </div>
                    <p class="text-gray-600 mb-4">98 products</p>
                    <a href="#"
                        class="inline-block bg-gray-100 hover:bg-primary hover:text-white px-4 py-2 rounded-lg transition">
                        Visit Store
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Value Propositions -->
    <section class="bg-primary text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center" data-aos="fade-up">
                    <div
                        class="w-16 h-16 mx-auto mb-4 bg-white bg-opacity-20 rounded-full flex items-center justify-center animate-pulse-slow">
                        <i class="fas fa-shield-alt text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-xl mb-2">Secure Payments</h3>
                    <p class="opacity-90">100% protected transactions with encrypted payment processing</p>
                </div>

                <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                    <div
                        class="w-16 h-16 mx-auto mb-4 bg-white bg-opacity-20 rounded-full flex items-center justify-center animate-pulse-slow">
                        <i class="fas fa-truck text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-xl mb-2">Fast Shipping</h3>
                    <p class="opacity-90">Get your orders delivered quickly from vendors nationwide</p>
                </div>

                <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                    <div
                        class="w-16 h-16 mx-auto mb-4 bg-white bg-opacity-20 rounded-full flex items-center justify-center animate-pulse-slow">
                        <i class="fas fa-exchange-alt text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-xl mb-2">Easy Returns</h3>
                    <p class="opacity-90">Hassle-free returns within 30 days for most items</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="font-bold text-lg mb-4">YourSite</h3>
                    <p class="text-gray-400">Connecting customers with quality vendors since 2023.</p>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Home</a></li>
                        <li><a href="vendors.html" class="text-gray-400 hover:text-white">Vendors</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Products</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">About Us</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-4">Support</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Contact Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">FAQs</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Shipping Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Returns</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-4">Follow Us</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2023 YourSite. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- AOS Animation JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });
    </script>
</body>

</html>