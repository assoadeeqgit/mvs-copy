<?php
include("./include/config.php");

$user_id = $_SESSION['user_id'];
$username = $_SESSION['user_name'];
$role =  $_SESSION['user_role'];
$user_logged_in = isset($_SESSION['user_id']);

// Get search query from URL and sanitize
$searchQuery = isset($_GET['query']) ? trim($_GET['query']) : '';
$searchQuery = htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8');

// Fetch products matching the search query
$products = [];
$noResults = false;

if (!empty($searchQuery)) {
    try {
        $sql = "SELECT p.product_id, p.name, p.description, p.price, p.stock_quantity, p.is_published, 
                       p.vendor_id, pi.image_url, c.name AS category_name, v.business_name
                FROM products p
                LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
                LEFT JOIN categories c ON p.category_id = c.category_id
                LEFT JOIN vendors v ON p.vendor_id = v.vendor_id
                WHERE (p.name LIKE :query OR p.description LIKE :query) AND p.is_published = 1
                AND p.is_published = 1
                ORDER BY p.name ASC";

        $stmt = $dbh->prepare($sql);
        $stmt->execute(['query' => "%$searchQuery%"]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($products)) {
            $noResults = true;
        }
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}

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

// In vendor_store.php or product detail page
// try {
//     $sql = "INSERT INTO recently_viewed (user_id, product_id, viewed_at) 
//             VALUES (:user_id, :product_id, NOW())
//             ON DUPLICATE KEY UPDATE viewed_at = NOW()";
//     $stmt = $dbh->prepare($sql);
//     $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
// } catch (PDOException $e) {
//     error_log("Recently viewed insert failed: " . $e->getMessage());
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MultiVendor Marketplace</title>
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
                    'bounce-slow': 'bounce 3s infinite',
                    'float': 'float 6s ease-in-out infinite',
                    'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                },
                keyframes: {
                    float: {
                        '0%, 100%': {
                            transform: 'translateY(0)'
                        },
                        '50%': {
                            transform: 'translateY(-10px)'
                        },
                    }
                }
            }
        }
    }
    </script>
    <style>
    .hero-slide {
        display: none;
    }

    .hero-slide.active {
        display: block;
    }

    .category-card:hover .category-overlay {
        opacity: 1;
    }

    .product-card:hover {
        transform: translateY(-5px);
    }

    /* Banner animations */
    .promo-banner {
        transition: all 0.5s ease;
        background-size: cover;
        background-position: center;
        animation: bannerEntry 1s ease-out;
    }

    .promo-banner:hover {
        transform: scale(1.02);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
    }

    /* Animations */
    @keyframes bannerEntry {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes productEntry {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .product-entry {
        animation: productEntry 0.6s ease-out forwards;
    }

    /* Banner specific animations */
    .banner-1 {
        animation-delay: 0.1s;
    }

    .banner-2 {
        animation-delay: 0.3s;
    }

    .banner-3 {
        animation-delay: 0.5s;
    }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <!-- Header Section -->
    <?php include('./include/user_header.php'); ?>

    <!-- Hero Banner -->
    <section class="relative bg-gray-100">
        <div class="hero-slide active">
            <div class="container mx-auto px-4 py-12 md:py-16 flex flex-col md:flex-row items-center">
                <!-- Text Content -->
                <div class="w-full md:w-1/2 flex flex-col justify-center order-2 md:order-1 mt-6 md:mt-0">
                    <h1 class="text-3xl md:text-4xl font-bold text-dark mb-3 leading-tight">Shop from 100+ Trusted
                        Vendors</h1>
                    <p class="text-gray-600 mb-5">One marketplace, endless choices from independent sellers</p>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="#"
                            class="bg-primary text-white px-5 py-2.5 rounded-lg hover:bg-indigo-700 transition inline-block text-center animate-bounce-slow">
                            Shop Now
                        </a>
                        <a href="#"
                            class="border border-primary text-primary px-5 py-2.5 rounded-lg hover:bg-primary hover:text-white transition inline-block text-center">
                            Become a Seller
                        </a>
                    </div>
                </div>

                <!-- Image -->
                <div class="w-full md:w-1/2 order-1 md:order-2 animate-float">
                    <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1099&q=80"
                        alt="Shopping" class="rounded-lg shadow-lg w-full max-w-md mx-auto">
                </div>
            </div>
        </div>

        <!-- Slide Indicators (if using multiple slides) -->
        <div class="absolute bottom-4 left-0 right-0 flex justify-center space-x-2">
            <button class="w-2.5 h-2.5 rounded-full bg-primary"></button>
            <button class="w-2.5 h-2.5 rounded-full bg-gray-300"></button>
            <button class="w-2.5 h-2.5 rounded-full bg-gray-300"></button>
        </div>
    </section>

    </section>
    <!-- After the Hero Banner section, add this Advert Carousel -->
    <section class="container mx-auto px-4 py-8">
        <div class="relative">
            <!-- Carousel Header -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-crown text-yellow-500 mr-2"></i>
                    Featured Vendors
                </h2>
                <div class="flex space-x-2">
                    <button class="ad-prev bg-gray-200 hover:bg-gray-300 p-2 rounded-full transition">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="ad-next bg-gray-200 hover:bg-gray-300 p-2 rounded-full transition">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <!-- Advert Carousel -->
            <div class="relative overflow-hidden">
                <div class="ad-carousel flex transition-transform duration-500 ease-in-out">
                    <!-- Ad 1 -->
                    <div class="ad-slide flex-shrink-0 w-full">
                        <div class="relative rounded-xl overflow-hidden shadow-lg border-2 border-yellow-500">
                            <div
                                class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded-full flex items-center">
                                <i class="fas fa-crown mr-1"></i>
                                <span>Sponsored</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 bg-gradient-to-r from-yellow-50 to-white">
                                <div class="md:col-span-2 p-6 flex flex-col justify-center">
                                    <div class="flex items-center mb-4">
                                        <div
                                            class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-md mr-3">
                                            <img src="https://images.unsplash.com/photo-1599305445671-ac291c95aaa9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1169&q=80"
                                                alt="Vendor Logo" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-lg">Luxury Homes</h3>
                                            <div class="flex text-yellow-400 text-sm">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3">Premium Furniture
                                        Collection</h2>
                                    <p class="text-gray-600 mb-4">Limited time offer - 25% off all items</p>
                                    <a href="#"
                                        class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-6 py-3 rounded-lg hover:shadow-lg transition inline-block w-max">
                                        Shop Now <i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                </div>
                                <div class="hidden md:block">
                                    <img src="https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
                                        alt="Advert Product" class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ad 2 -->
                    <div class="ad-slide flex-shrink-0 w-full">
                        <div class="relative rounded-xl overflow-hidden shadow-lg border-2 border-blue-500">
                            <div
                                class="absolute top-2 right-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full flex items-center">
                                <i class="fas fa-crown mr-1"></i>
                                <span>Sponsored</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 bg-gradient-to-r from-blue-50 to-white">
                                <div class="md:col-span-2 p-6 flex flex-col justify-center">
                                    <div class="flex items-center mb-4">
                                        <div
                                            class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-md mr-3">
                                            <img src="https://images.unsplash.com/photo-1556740738-b6a63e27c4df?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
                                                alt="Vendor Logo" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-lg">TechGadgets</h3>
                                            <div class="flex text-yellow-400 text-sm">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star-half-alt"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3">New Tech Launch Event
                                    </h2>
                                    <p class="text-gray-600 mb-4">Be the first to get our latest gadgets</p>
                                    <a href="#"
                                        class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-3 rounded-lg hover:shadow-lg transition inline-block w-max">
                                        Explore <i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                </div>
                                <div class="hidden md:block">
                                    <img src="https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
                                        alt="Advert Product" class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ad 3 -->
                    <div class="ad-slide flex-shrink-0 w-full">
                        <div class="relative rounded-xl overflow-hidden shadow-lg border-2 border-purple-500">
                            <div
                                class="absolute top-2 right-2 bg-purple-500 text-white text-xs px-2 py-1 rounded-full flex items-center">
                                <i class="fas fa-crown mr-1"></i>
                                <span>Sponsored</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 bg-gradient-to-r from-purple-50 to-white">
                                <div class="md:col-span-2 p-6 flex flex-col justify-center">
                                    <div class="flex items-center mb-4">
                                        <div
                                            class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-md mr-3">
                                            <img src="https://images.unsplash.com/photo-1576566588028-4147f3842f27?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1064&q=80"
                                                alt="Vendor Logo" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-lg">FashionHub</h3>
                                            <div class="flex text-yellow-400 text-sm">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3">Summer Fashion Sale
                                    </h2>
                                    <p class="text-gray-600 mb-4">Up to 40% off on selected items</p>
                                    <a href="#"
                                        class="bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-3 rounded-lg hover:shadow-lg transition inline-block w-max">
                                        View Collection <i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                </div>
                                <div class="hidden md:block">
                                    <img src="https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
                                        alt="Advert Product" class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carousel Indicators -->
            <div class="flex justify-center mt-4 space-x-2">
                <button class="ad-indicator w-3 h-3 rounded-full bg-gray-300"></button>
                <button class="ad-indicator w-3 h-3 rounded-full bg-gray-300"></button>
                <button class="ad-indicator w-3 h-3 rounded-full bg-gray-300"></button>
            </div>
        </div>
    </section>

    <!-- Featured Categories -->
    <section class="container mx-auto px-4 py-12">
        <h2 class="text-3xl font-bold text-center mb-8" data-aos="fade-up">Shop by Category</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Category Card 1 -->
            <a href="#" class="category-card relative rounded-lg overflow-hidden shadow-md hover:shadow-lg transition"
                data-aos="fade-up" data-aos-delay="50">
                <img src="https://images.unsplash.com/photo-1555774698-0b77e0d5fac6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
                    alt="Electronics" class="w-full h-40 object-cover">
                <div
                    class="category-overlay absolute inset-0 bg-black bg-opacity-30 opacity-0 transition flex items-center justify-center">
                    <span class="text-white font-semibold">1,240 products</span>
                </div>
                <div class="p-4 bg-white">
                    <div class="flex items-center">
                        <i class="fas fa-laptop text-primary mr-2"></i>
                        <h3 class="font-semibold">Electronics</h3>
                    </div>
                </div>
            </a>

            <!-- Category Card 2 -->
            <a href="#" class="category-card relative rounded-lg overflow-hidden shadow-md hover:shadow-lg transition"
                data-aos="fade-up" data-aos-delay="100">
                <img src="https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
                    alt="Fashion" class="w-full h-40 object-cover">
                <div
                    class="category-overlay absolute inset-0 bg-black bg-opacity-30 opacity-0 transition flex items-center justify-center">
                    <span class="text-white font-semibold">2,450 products</span>
                </div>
                <div class="p-4 bg-white">
                    <div class="flex items-center">
                        <i class="fas fa-tshirt text-primary mr-2"></i>
                        <h3 class="font-semibold">Fashion</h3>
                    </div>
                </div>
            </a>

            <!-- Category Card 3 -->
            <a href="#" class="category-card relative rounded-lg overflow-hidden shadow-md hover:shadow-lg transition"
                data-aos="fade-up" data-aos-delay="150">
                <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
                    alt="Home & Garden" class="w-full h-40 object-cover">
                <div
                    class="category-overlay absolute inset-0 bg-black bg-opacity-30 opacity-0 transition flex items-center justify-center">
                    <span class="text-white font-semibold">980 products</span>
                </div>
                <div class="p-4 bg-white">
                    <div class="flex items-center">
                        <i class="fas fa-home text-primary mr-2"></i>
                        <h3 class="font-semibold">Home & Garden</h3>
                    </div>
                </div>
            </a>

            <!-- Category Card 4 -->
            <a href="#" class="category-card relative rounded-lg overflow-hidden shadow-md hover:shadow-lg transition"
                data-aos="fade-up" data-aos-delay="200">
                <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
                    alt="Beauty" class="w-full h-40 object-cover">
                <div
                    class="category-overlay absolute inset-0 bg-black bg-opacity-30 opacity-0 transition flex items-center justify-center">
                    <span class="text-white font-semibold">750 products</span>
                </div>
                <div class="p-4 bg-white">
                    <div class="flex items-center">
                        <i class="fas fa-spa text-primary mr-2"></i>
                        <h3 class="font-semibold">Beauty</h3>
                    </div>
                </div>
            </a>
        </div>
    </section>

    <!-- Trending Products -->
    <section class="bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-6" data-aos="fade-up">
                <h2 class="text-3xl font-bold">Trending This Week</h2>
                <a href="#" class="text-primary hover:underline">View All</a>
            </div>

            <div class="relative">
                <div class="overflow-x-auto pb-4">
                    <div class="flex space-x-6">
                        <!-- Product Card 1 -->
                        <div class="product-card bg-white rounded-lg shadow-md overflow-hidden w-64 flex-shrink-0 transition product-entry"
                            style="animation-delay: 0.1s">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
                                    alt="Product" class="w-full h-48 object-cover">
                                <div class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                                    <i class="fas fa-store mr-1"></i> TechHaven
                                </div>
                                <button
                                    class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-lg mb-1">Wireless Earbuds</h3>
                                <div class="flex items-center mb-2">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <span class="text-gray-500 text-sm ml-2">(128)</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-lg">$49.99</span>
                                    <button
                                        class="bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Product Card 2 -->
                        <div class="product-card bg-white rounded-lg shadow-md overflow-hidden w-64 flex-shrink-0 transition product-entry"
                            style="animation-delay: 0.2s">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1546868871-7041f2a55e12?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1064&q=80"
                                    alt="Product" class="w-full h-48 object-cover">
                                <div class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                                    <i class="fas fa-store mr-1"></i> SmartGadgets
                                </div>
                                <button
                                    class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-lg mb-1">Smart Watch</h3>
                                <div class="flex items-center mb-2">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="text-gray-500 text-sm ml-2">(256)</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-lg">$129.99</span>
                                    <button
                                        class="bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Product Card 3 -->
                        <div class="product-card bg-white rounded-lg shadow-md overflow-hidden w-64 flex-shrink-0 transition product-entry"
                            style="animation-delay: 0.3s">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1099&q=80"
                                    alt="Product" class="w-full h-48 object-cover">
                                <div class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                                    <i class="fas fa-store mr-1"></i> CameraWorld
                                </div>
                                <button
                                    class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-lg mb-1">DSLR Camera</h3>
                                <div class="flex items-center mb-2">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="text-gray-500 text-sm ml-2">(89)</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-lg">$599.99</span>
                                    <button
                                        class="bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Product Card 4 -->
                        <div class="product-card bg-white rounded-lg shadow-md overflow-hidden w-64 flex-shrink-0 transition product-entry"
                            style="animation-delay: 0.4s">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
                                    alt="Product" class="w-full h-48 object-cover">
                                <div class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                                    <i class="fas fa-store mr-1"></i> SportZone
                                </div>
                                <button
                                    class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-lg mb-1">Running Shoes</h3>
                                <div class="flex items-center mb-2">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <span class="text-gray-500 text-sm ml-2">(342)</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-lg">$79.99</span>
                                    <button
                                        class="bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Product Card 5 -->
                        <div class="product-card bg-white rounded-lg shadow-md overflow-hidden w-64 flex-shrink-0 transition product-entry"
                            style="animation-delay: 0.5s">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1601784551446-20c9e07cdbdb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1167&q=80"
                                    alt="Product" class="w-full h-48 object-cover">
                                <div class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                                    <i class="fas fa-store mr-1"></i> OutdoorGear
                                </div>
                                <button
                                    class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-lg mb-1">Backpack</h3>
                                <div class="flex items-center mb-2">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="text-gray-500 text-sm ml-2">(176)</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-lg">$59.99</span>
                                    <button
                                        class="bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Product Card 6 -->
                        <div class="product-card bg-white rounded-lg shadow-md overflow-hidden w-64 flex-shrink-0 transition product-entry"
                            style="animation-delay: 0.6s">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1525904097878-94fb15835963?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
                                    alt="Product" class="w-full h-48 object-cover">
                                <div class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                                    <i class="fas fa-store mr-1"></i> BeautySpot
                                </div>
                                <button
                                    class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-lg mb-1">Perfume Set</h3>
                                <div class="flex items-center mb-2">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <span class="text-gray-500 text-sm ml-2">(98)</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-lg">$89.99</span>
                                    <button
                                        class="bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Product Card 7 -->
                        <div class="product-card bg-white rounded-lg shadow-md overflow-hidden w-64 flex-shrink-0 transition product-entry"
                            style="animation-delay: 0.7s">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1560343090-f0409e92791a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1172&q=80"
                                    alt="Product" class="w-full h-48 object-cover">
                                <div class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                                    <i class="fas fa-store mr-1"></i> KitchenPro
                                </div>
                                <button
                                    class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-lg mb-1">Blender</h3>
                                <div class="flex items-center mb-2">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="text-gray-500 text-sm ml-2">(143)</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-lg">$49.99</span>
                                    <button
                                        class="bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Product Card 8 -->
                        <div class="product-card bg-white rounded-lg shadow-md overflow-hidden w-64 flex-shrink-0 transition product-entry"
                            style="animation-delay: 0.8s">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1546054454-aa26e2b734c7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1180&q=80"
                                    alt="Product" class="w-full h-48 object-cover">
                                <div class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                                    <i class="fas fa-store mr-1"></i> BookNook
                                </div>
                                <button
                                    class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-lg mb-1">Book Collection</h3>
                                <div class="flex items-center mb-2">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <span class="text-gray-500 text-sm ml-2">(87)</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-lg">$39.99</span>
                                    <button
                                        class="bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button
                    class="absolute left-0 top-1/2 transform -translate-y-1/2 -ml-4 bg-white p-2 rounded-full shadow-md hover:bg-gray-100 transition">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button
                    class="absolute right-0 top-1/2 transform -translate-y-1/2 -mr-4 bg-white p-2 rounded-full shadow-md hover:bg-gray-100 transition">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Vendor Showcase -->
    <section class="container mx-auto px-4 py-12">
        <h2 class="text-3xl font-bold text-center mb-8" data-aos="fade-up">Featured Vendors</h2>

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

    <!-- Newsletter -->
    <section class="container mx-auto px-4 py-12">
        <div class="bg-gray-100 rounded-lg p-8 text-center" data-aos="fade-up">
            <h2 class="text-2xl font-bold mb-2">Stay Updated</h2>
            <p class="text-gray-600 mb-6 max-w-2xl mx-auto">Subscribe to our newsletter for the latest products and
                deals from our vendors</p>
            <div class="flex max-w-md mx-auto">
                <input type="email" placeholder="Your email address"
                    class="flex-1 py-3 px-4 rounded-l-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                <button class="bg-primary text-white px-6 py-3 rounded-r-lg hover:bg-indigo-700 transition">
                    Subscribe
                </button>
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
                        <li><a href="#" class="text-gray-400 hover:text-white transition">All Products</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Featured</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">New Arrivals</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Deals</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-lg mb-4">Sell</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Become a Vendor</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Vendor Dashboard</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Seller Resources</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Pricing</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-lg mb-4">Contact</h4>
                    <ul class="space-y-2">
                        <li class="flex items-center text-gray-400"><i class="fas fa-map-marker-alt mr-2"></i> 123
                            Market St, City</li>
                        <li class="flex items-center text-gray-400"><i class="fas fa-phone mr-2"></i> (123) 456-7890
                        </li>
                        <li class="flex items-center text-gray-400"><i class="fas fa-envelope mr-2"></i>
                            support@multivendor.com</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 mb-4 md:mb-0">© 2023 MultiVendor. All rights reserved.</p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-pinterest"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
    // Initialize AOS animation library
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });

    // Simple hero slider functionality
    document.addEventListener('DOMContentLoaded', function() {
        const slides = document.querySelectorAll('.hero-slide');
        const indicators = document.querySelectorAll('.hero-banner button');
        let currentSlide = 0;

        function showSlide(index) {
            slides.forEach(slide => slide.classList.remove('active'));
            indicators.forEach(ind => ind.classList.remove('bg-primary'));

            slides[index].classList.add('active');
            indicators[index].classList.add('bg-primary');
            currentSlide = index;
        }

        // Auto-rotate slides every 5 seconds
        setInterval(() => {
            let nextSlide = (currentSlide + 1) % slides.length;
            showSlide(nextSlide);
        }, 5000);

        // Add click events to indicators
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => showSlide(index));
        });

        // Stagger product entry animations
        const products = document.querySelectorAll('.product-entry');
        products.forEach((product, index) => {
            product.style.animationDelay = `${index * 0.1}s`;
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const carousel = document.querySelector('.ad-carousel');
        const slides = document.querySelectorAll('.ad-slide');
        const prevBtn = document.querySelector('.ad-prev');
        const nextBtn = document.querySelector('.ad-next');
        const indicators = document.querySelectorAll('.ad-indicator');

        let currentIndex = 0;
        const slideCount = slides.length;

        // Initialize carousel
        function updateCarousel() {
            carousel.style.transform = `translateX(-${currentIndex * 100}%)`;

            // Update indicators
            indicators.forEach((indicator, index) => {
                indicator.classList.toggle('bg-primary', index === currentIndex);
                indicator.classList.toggle('bg-gray-300', index !== currentIndex);
            });
        }

        // Next slide
        function nextSlide() {
            currentIndex = (currentIndex + 1) % slideCount;
            updateCarousel();
        }

        // Previous slide
        function prevSlide() {
            currentIndex = (currentIndex - 1 + slideCount) % slideCount;
            updateCarousel();
        }

        // Auto-rotate every 5 seconds
        let slideInterval = setInterval(nextSlide, 5000);

        // Pause on hover
        carousel.addEventListener('mouseenter', () => clearInterval(slideInterval));
        carousel.addEventListener('mouseleave', () => {
            slideInterval = setInterval(nextSlide, 5000);
        });

        // Button controls
        nextBtn.addEventListener('click', () => {
            clearInterval(slideInterval);
            nextSlide();
            slideInterval = setInterval(nextSlide, 5000);
        });

        prevBtn.addEventListener('click', () => {
            clearInterval(slideInterval);
            prevSlide();
            slideInterval = setInterval(nextSlide, 5000);
        });

        // Indicator controls
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                clearInterval(slideInterval);
                currentIndex = index;
                updateCarousel();
                slideInterval = setInterval(nextSlide, 5000);
            });
        });

        // Initialize
        updateCarousel();

        function getActiveAds() {
            return sponsoredAds
                .filter(ad => new Date() >= new Date(ad.startDate) && new Date() <= new Date(ad.endDate))
                .sort((a, b) => b.priority - a.priority);
        }
    });

    // Toggle mobile menu
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // Toggle mobile categories submenu
    const mobileCategoriesButton = document.getElementById('mobile-categories-button');
    const mobileCategoriesMenu = document.getElementById('mobile-categories-menu');
    mobileCategoriesButton.addEventListener('click', () => {
        mobileCategoriesMenu.classList.toggle('hidden');
    });
    </script>

</body>

</html>