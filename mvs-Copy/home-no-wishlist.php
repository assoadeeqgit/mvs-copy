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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
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
                    'tilt': 'tilt 10s infinite alternate',
                    'fade-in': 'fadeIn 0.5s ease-out',
                    'glow': 'glow 2s ease-in-out infinite alternate',
                    'wave': 'wave 2s ease-in-out infinite',
                    'bounce-in': 'bounceIn 0.8s',
                    'shake': 'shake 0.5s',
                },
                keyframes: {
                    float: {
                        '0%, 100%': {
                            transform: 'translateY(0)'
                        },
                        '50%': {
                            transform: 'translateY(-15px)'
                        },
                    },
                    tilt: {
                        '0%': {
                            transform: 'rotate(0deg)'
                        },
                        '100%': {
                            transform: 'rotate(2deg)'
                        },
                    },
                    fadeIn: {
                        '0%': {
                            opacity: '0',
                            transform: 'translateY(10px)'
                        },
                        '100%': {
                            opacity: '1',
                            transform: 'translateY(0)'
                        },
                    },
                    glow: {
                        '0%': {
                            'box-shadow': '0 0 5px rgba(79, 70, 229, 0.5)'
                        },
                        '100%': {
                            'box-shadow': '0 0 20px rgba(79, 70, 229, 0.8)'
                        },
                    },
                    wave: {
                        '0%, 100%': {
                            transform: 'rotate(0deg)'
                        },
                        '25%': {
                            transform: 'rotate(5deg)'
                        },
                        '75%': {
                            transform: 'rotate(-5deg)'
                        },
                    }
                },
                boxShadow: {
                    'glow': '0 0 15px rgba(79, 70, 229, 0.3)',
                    'card': '0 4px 20px rgba(0, 0, 0, 0.1)',
                    'deep': '0 10px 30px rgba(0, 0, 0, 0.15)',
                },
                backgroundImage: {
                    'gradient-overlay': 'linear-gradient(to right, rgba(0,0,0,0.5), rgba(0,0,0,0.2))',
                    'subtle-pattern': 'url("data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' viewBox=\'0 0 40 40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23f3f4f6\' fill-opacity=\'0.4\' fill-rule=\'evenodd\'%3E%3Cpath d=\'M0 40L40 0H20L0 20M40 40V20L20 40\'/%3E%3C/g%3E%3C/svg%3E")',
                    'radial-gradient': 'radial-gradient(circle, rgba(79,70,229,0.1) 0%, rgba(255,255,255,0) 70%)',
                }
            }
        }
    }
    </script>
    <style>
    .hero-slide {
        display: none;
        background: transparent;
    }

    .hero-slide.active {
        display: block;
    }

    .category-card:hover .category-overlay {
        opacity: 1;
        transform: scale(1.05);
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .promo-banner {
        transition: all 0.5s ease;
        background-size: cover;
        background-position: center;
        animation: bannerEntry 1s ease-out;
    }

    .promo-banner:hover {
        transform: scale(1.03);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
    }

    @keyframes bannerEntry {
        0% {
            opacity: 0;
            transform: translateY(30px);
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
        animation: productEntry 0.8s ease-out forwards;
    }

    .banner-1 {
        animation-delay: 0.1s;
    }

    .banner-2 {
        animation-delay: 0.3s;
    }

    .banner-3 {
        animation-delay: 0.5s;
    }

    .back-to-top {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #4f46e5;
        color: white;
        padding: 12px;
        border-radius: 50%;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        transition: opacity 0.3s, transform 0.3s;
        opacity: 0;
        transform: translateY(100px);
    }

    .back-to-top.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .loading-spinner {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        border: 4px solid #f3f3f3;
        border-top: 4px solid #4f46e5;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        z-index: 1000;
        display: none;
    }

    @keyframes spin {
        0% {
            transform: translate(-50%, -50%) rotate(0deg);
        }

        100% {
            transform: translate(-50%, -50%) rotate(360deg);
        }
    }

    .parallax-bg {
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }

    .floating-cart-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 999;
        animation: float 4s ease-in-out infinite;
    }

    .cart-bubble {
        position: absolute;
        top: -5px;
        right: -5px;
        background-color: #ef4444;
        color: white;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
        animation: pulse-slow 2s infinite;
    }

    .hover-3d {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-3d:hover {
        transform: perspective(1000px) rotateX(5deg) rotateY(5deg) scale(1.03);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .gradient-text {
        background: linear-gradient(90deg, #4f46e5, #10b981);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .scroll-progress {
        position: fixed;
        top: 0;
        left: 0;
        height: 4px;
        background: linear-gradient(90deg, #4f46e5, #10b981);
        z-index: 1000;
        transition: width 0.1s;
    }

    .ripple {
        position: relative;
        overflow: hidden;
    }

    .ripple-effect {
        position: absolute;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.7);
        transform: scale(0);
        animation: ripple 0.6s linear;
        pointer-events: none;
    }

    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }

    .typing-effect::after {
        content: '|';
        animation: blink 1s infinite;
    }

    @keyframes blink {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0;
        }
    }

    /* Smooth transition for all animated elements */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Gradient overlay for image */
    .bg-gradient-to-t {
        background-image: linear-gradient(to top, rgba(0, 0, 0, 0.2), transparent);
    }

    /* Shadow with primary color tint */
    .shadow-primary\/20 {
        box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.2);
    }
    </style>
</head>

<body class="bg-gray-50 font-sans relative">
    <!-- Scroll Progress Bar -->
    <div class="scroll-progress" id="scroll-progress"></div>

    <!-- Loading Spinner -->
    <div class="loading-spinner" id="loading-spinner"></div>

    <!-- Header Section -->
    <?php include('./include/user_header.php'); ?>

    <!-- Hero Banner with Particle Background -->
    <section class="relative bg-gray-100 overflow-hidden min-h-[400px] md:min-h-[500px]">
        <div id="particles-js" class="absolute inset-0 z-0"></div>
        <div class="hero-slide active relative">
            <div
                class="container mx-auto px-4 py-12 md:py-16 flex flex-col md:flex-row items-center relative z-10 h-full">
                <!-- Text Content with Hover Effects -->
                <div class="w-full md:w-1/2 flex flex-col justify-center order-2 md:order-1 mt-6 md:mt-0 group"
                    data-aos="fade-right">
                    <h1
                        class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-dark mb-4 leading-tight transition-all duration-300 group-hover:text-primary">
                        Discover <span class="gradient-text typing-effect" id="typing-text"></span>
                    </h1>
                    <p class="text-gray-600 mb-6 max-w-md transition-all duration-500 group-hover:translate-x-2">
                        Explore unique products from independent sellers.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="product.php" class="ripple bg-primary text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-all 
             duration-300 hover:shadow-lg hover:-translate-y-1 transform">
                            Shop Now <i
                                class="fas fa-arrow-right ml-2 transition-all duration-300 group-hover:translate-x-1"></i>
                        </a>
                        <a href="vendor_signup.php" class="ripple border-2 border-primary text-primary px-6 py-3 rounded-lg hover:bg-primary 
             hover:text-white transition-all duration-300 hover:shadow-lg hover:-translate-y-1 transform">
                            Become a Seller
                        </a>
                    </div>
                </div>

                <!-- Image with Hover Effects -->
                <div class="w-full md:w-1/2 order-1 md:order-2 mt-6 md:mt-0 group" data-aos="fade-left">
                    <div
                        class="relative overflow-hidden rounded-xl shadow-2xl transition-all duration-500 group-hover:shadow-primary/20">
                        <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixlib=rb-4.0.3&auto=format&fit=crop&w=1099&q=80"
                            alt="Shopping"
                            class="w-full max-w-md mx-auto object-cover transition-transform duration-700 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-dark/20 to-transparent opacity-0 
                     group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Advert Carousel -->
    <section class="container mx-auto px-4 py-12" id="featured-vendors">
        <div class="relative">
            <!-- Section Header -->
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 flex items-center group">
                    <i
                        class="fas fa-crown text-yellow-500 mr-3 transition-transform duration-300 group-hover:rotate-12"></i>
                    <span class="transition-all duration-300 group-hover:text-primary">Featured Vendors</span>
                </h2>
                <div class="flex space-x-2">
                    <button
                        class="ad-prev bg-white p-3 rounded-full shadow-md hover:bg-gray-50 transition-all duration-300 hover:shadow-lg group">
                        <i
                            class="fas fa-chevron-left text-gray-600 transition-transform duration-300 group-hover:-translate-x-1"></i>
                    </button>
                    <button
                        class="ad-next bg-white p-3 rounded-full shadow-md hover:bg-gray-50 transition-all duration-300 hover:shadow-lg group">
                        <i
                            class="fas fa-chevron-right text-gray-600 transition-transform duration-300 group-hover:translate-x-1"></i>
                    </button>
                </div>
            </div>

            <!-- Carousel Container -->
            <div class="relative overflow-hidden rounded-2xl">
                <div class="ad-carousel flex transition-transform duration-500 ease-[cubic-bezier(0.25,0.1,0.25,1)]">
                    <!-- Slide 1 -->
                    <div class="ad-slide flex-shrink-0 w-full px-2">
                        <!-- Added px-2 for spacing -->
                        <div
                            class="relative bg-white rounded-xl overflow-hidden shadow-lg border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 group">
                            <div
                                class="absolute top-4 right-4 bg-yellow-500 text-white text-xs font-medium px-3 py-1 rounded-full flex items-center transition-transform duration-300 group-hover:scale-105">
                                <i class="fas fa-crown mr-2"></i>Sponsored
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3">
                                <div class="md:col-span-2 p-8 flex flex-col justify-center">
                                    <div
                                        class="flex items-center mb-5 group-hover:translate-x-1 transition-transform duration-500">
                                        <div
                                            class="w-14 h-14 rounded-full overflow-hidden border-2 border-white shadow-md mr-4 transition-transform duration-300 group-hover:scale-110">
                                            <img src="https://images.unsplash.com/photo-1599305445671-ac291c95aaa9?auto=format&fit=crop&w=1169&q=80"
                                                alt="Vendor Logo"
                                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                                loading="lazy">
                                        </div>
                                        <div>
                                            <h3
                                                class="font-bold text-lg transition-colors duration-300 group-hover:text-primary">
                                                Luxury Homes</h3>
                                            <div class="flex text-yellow-400 text-xs">
                                                <i
                                                    class="fas fa-star hover:text-yellow-500 transition-transform duration-200 hover:scale-125"></i>
                                                <i
                                                    class="fas fa-star hover:text-yellow-500 transition-transform duration-200 hover:scale-125"></i>
                                                <i
                                                    class="fas fa-star hover:text-yellow-500 transition-transform duration-200 hover:scale-125"></i>
                                                <i
                                                    class="fas fa-star hover:text-yellow-500 transition-transform duration-200 hover:scale-125"></i>
                                                <i
                                                    class="fas fa-star hover:text-yellow-500 transition-transform duration-200 hover:scale-125"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <h2
                                        class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 transition-colors duration-300 group-hover:text-dark">
                                        Premium Furniture Collection</h2>
                                    <p
                                        class="text-gray-600 mb-6 transition-transform duration-500 group-hover:translate-x-1">
                                        Limited time offer - 25% off all items</p>
                                    <a href="#"
                                        class="inline-flex items-center bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-6 py-3 rounded-lg hover:shadow-md transition-all duration-300 w-fit group-hover:bg-gradient-to-r group-hover:from-yellow-600 group-hover:to-yellow-700">
                                        <span class="transition-transform duration-300 group-hover:translate-x-1">Shop
                                            Now</span>
                                        <i
                                            class="fas fa-arrow-right ml-2 transition-transform duration-300 group-hover:translate-x-2"></i>
                                    </a>
                                </div>
                                <div class="hidden md:block relative overflow-hidden">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-r from-white/10 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                    </div>
                                    <img src="https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?auto=format&fit=crop&w=1170&q=80"
                                        alt="Advert Product"
                                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 2 -->
                    <div class="ad-slide flex-shrink-0 w-full px-2">
                        <div
                            class="relative bg-white rounded-xl overflow-hidden shadow-lg border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 group">
                            <div
                                class="absolute top-4 right-4 bg-blue-500 text-white text-xs font-medium px-3 py-1 rounded-full flex items-center transition-transform duration-300 group-hover:scale-105">
                                <i class="fas fa-crown mr-2"></i>Sponsored
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3">
                                <div class="md:col-span-2 p-8 flex flex-col justify-center">
                                    <div
                                        class="flex items-center mb-5 group-hover:translate-x-1 transition-transform duration-500">
                                        <div
                                            class="w-14 h-14 rounded-full overflow-hidden border-2 border-white shadow-md mr-4 transition-transform duration-300 group-hover:scale-110">
                                            <img src="https://images.unsplash.com/photo-1556740738-b6a63e27c4df?auto=format&fit=crop&w=1170&q=80"
                                                alt="Vendor Logo"
                                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                                loading="lazy">
                                        </div>
                                        <div>
                                            <h3
                                                class="font-bold text-lg transition-colors duration-300 group-hover:text-primary">
                                                TechGadgets</h3>
                                            <div class="flex text-yellow-400 text-xs">
                                                <i
                                                    class="fas fa-star hover:text-yellow-500 transition-transform duration-200 hover:scale-125"></i>
                                                <i
                                                    class="fas fa-star hover:text-yellow-500 transition-transform duration-200 hover:scale-125"></i>
                                                <i
                                                    class="fas fa-star hover:text-yellow-500 transition-transform duration-200 hover:scale-125"></i>
                                                <i
                                                    class="fas fa-star hover:text-yellow-500 transition-transform duration-200 hover:scale-125"></i>
                                                <i
                                                    class="fas fa-star-half-alt hover:text-yellow-500 transition-transform duration-200 hover:scale-125"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <h2
                                        class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 transition-colors duration-300 group-hover:text-dark">
                                        New Tech Launch Event</h2>
                                    <p
                                        class="text-gray-600 mb-6 transition-transform duration-500 group-hover:translate-x-1">
                                        Be the first to get our latest gadgets</p>
                                    <a href="#"
                                        class="inline-flex items-center bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-3 rounded-lg hover:shadow-md transition-all duration-300 w-fit group-hover:bg-gradient-to-r group-hover:from-blue-600 group-hover:to-blue-700">
                                        <span
                                            class="transition-transform duration-300 group-hover:translate-x-1">Explore</span>
                                        <i
                                            class="fas fa-arrow-right ml-2 transition-transform duration-300 group-hover:translate-x-2"></i>
                                    </a>
                                </div>
                                <div class="hidden md:block relative overflow-hidden">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-r from-white/10 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                    </div>
                                    <img src="https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?auto=format&fit=crop&w=1170&q=80"
                                        alt="Advert Product"
                                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- slide 3 -->
                    <div class="ad-slide flex-shrink-0 w-full px-2">
                        <div
                            class="relative bg-white rounded-xl overflow-hidden shadow-lg border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 group">
                            <!-- Sponsored Badge -->
                            <div
                                class="absolute top-4 right-4 bg-purple-500 text-white text-xs font-medium px-3 py-1 rounded-full flex items-center transition-transform duration-300 group-hover:scale-105">
                                <i class="fas fa-crown mr-2"></i>Sponsored
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3">
                                <!-- Text Content -->
                                <div class="md:col-span-2 p-8 flex flex-col justify-center">
                                    <div
                                        class="flex items-center mb-5 group-hover:translate-x-1 transition-transform duration-500">
                                        <div
                                            class="w-14 h-14 rounded-full overflow-hidden border-2 border-white shadow-md mr-4 transition-transform duration-300 group-hover:scale-110">
                                            <img src="https://images.unsplash.com/photo-1576566588028-4147f3842f27?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1064&q=80"
                                                alt="Vendor Logo"
                                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                                loading="lazy">
                                        </div>
                                        <div>
                                            <h3
                                                class="font-bold text-lg transition-colors duration-300 group-hover:text-primary">
                                                FashionHub</h3>
                                            <div class="flex text-yellow-400 text-xs">
                                                <i
                                                    class="fas fa-star hover:text-yellow-500 transition-transform duration-200 hover:scale-125"></i>
                                                <i
                                                    class="fas fa-star hover:text-yellow-500 transition-transform duration-200 hover:scale-125"></i>
                                                <i
                                                    class="fas fa-star hover:text-yellow-500 transition-transform duration-200 hover:scale-125"></i>
                                                <i
                                                    class="fas fa-star hover:text-yellow-500 transition-transform duration-200 hover:scale-125"></i>
                                                <i
                                                    class="fas fa-star hover:text-yellow-500 transition-transform duration-200 hover:scale-125"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <h2
                                        class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 transition-colors duration-300 group-hover:text-dark">
                                        Summer Fashion Sale</h2>
                                    <p
                                        class="text-gray-600 mb-6 transition-transform duration-500 group-hover:translate-x-1">
                                        Up to 40% off on selected items</p>

                                    <a href="#"
                                        class="inline-flex items-center bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-3 rounded-lg hover:shadow-md transition-all duration-300 w-fit group-hover:bg-gradient-to-r group-hover:from-purple-600 group-hover:to-purple-700">
                                        <span class="transition-transform duration-300 group-hover:translate-x-1">View
                                            Collection</span>
                                        <i
                                            class="fas fa-arrow-right ml-2 transition-transform duration-300 group-hover:translate-x-2"></i>
                                    </a>
                                </div>

                                <!-- Image -->
                                <div class="hidden md:block relative overflow-hidden">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-r from-white/10 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                    </div>
                                    <img src="https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
                                        alt="Advert Product"
                                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Carousel Indicators -->
            <div class="flex justify-center mt-8 space-x-2">
                <button
                    class="ad-indicator w-3 h-3 rounded-full bg-gray-300 hover:bg-primary transition-all duration-300 hover:scale-125"></button>
                <button
                    class="ad-indicator w-3 h-3 rounded-full bg-gray-300 hover:bg-primary transition-all duration-300 hover:scale-125"></button>
                <button
                    class="ad-indicator w-3 h-3 rounded-full bg-gray-300 hover:bg-primary transition-all duration-300 hover:scale-125"></button>
            </div>
        </div>
    </section>

    <!-- Featured Categories -->
    <section class="container mx-auto px-4 py-12">
        <h2 class="text-4xl font-extrabold text-center mb-10 text-dark" data-aos="fade-up">Shop by Category</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <!-- Electronics -->
            <a href="#"
                class="category-card relative rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-2"
                data-aos="fade-up" data-aos-delay="50">
                <div class="relative h-44">
                    <img src="https://images.unsplash.com/photo-1555774698-0b77e0d5fac6?auto=format&fit=crop&w=1170&q=80"
                        alt="Electronics" class="w-full h-full object-cover">
                    <div
                        class="category-overlay absolute inset-0 bg-black bg-opacity-30 opacity-0 transition flex items-center justify-center">
                        <span class="text-white font-semibold">980 products</span>
                    </div>
                </div>
                <div class="p-5 bg-white border-t-2 border-primary">
                    <div class="flex items-center">
                        <i class="fas fa-laptop text-primary mr-3 text-xl"></i>
                        <h3 class="font-bold text-lg">Electronics</h3>
                    </div>
                </div>
            </a>

            <!-- Fashion -->
            <a href="#"
                class="category-card relative rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-2"
                data-aos="fade-up" data-aos-delay="100">
                <div class="relative h-44">
                    <img src="https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?auto=format&fit=crop&w=1170&q=80"
                        alt="Fashion" class="w-full h-full object-cover">
                    <div
                        class="category-overlay absolute inset-0 bg-black bg-opacity-30 opacity-0 transition flex items-center justify-center">
                        <span class="text-white font-semibold">980 products</span>
                    </div>
                </div>
                <div class="p-5 bg-white border-t-2 border-primary">
                    <div class="flex items-center">
                        <i class="fas fa-tshirt text-primary mr-3 text-xl"></i>
                        <h3 class="font-bold text-lg">Fashion</h3>
                    </div>
                </div>
            </a>

            <!-- Home & Garden -->
            <a href="#"
                class="category-card relative rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-2"
                data-aos="fade-up" data-aos-delay="150">
                <div class="relative h-44">
                    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1170&q=80"
                        alt="Home & Garden" class="w-full h-full object-cover">
                    <div
                        class="category-overlay absolute inset-0 bg-black bg-opacity-30 opacity-0 transition flex items-center justify-center">
                        <span class="text-white font-semibold">980 products</span>
                    </div>
                </div>
                <div class="p-5 bg-white border-t-2 border-primary">
                    <div class="flex items-center">
                        <i class="fas fa-home text-primary mr-3 text-xl"></i>
                        <h3 class="font-bold text-lg">Home & Garden</h3>
                    </div>
                </div>
            </a>

            <!-- Beauty -->
            <a href="#"
                class="category-card relative rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-2"
                data-aos="fade-up" data-aos-delay="200">
                <div class="relative h-44">
                    <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1170&q=80"
                        alt="Beauty" class="w-full h-full object-cover">
                    <div
                        class="category-overlay absolute inset-0 bg-black bg-opacity-30 opacity-0 transition flex items-center justify-center">
                        <span class="text-white font-semibold">980 products</span>
                    </div>
                </div>
                <div class="p-5 bg-white border-t-2 border-primary">
                    <div class="flex items-center">
                        <i class="fas fa-spa text-primary mr-3 text-xl"></i>
                        <h3 class="font-bold text-lg">Beauty</h3>
                    </div>
                </div>
            </a>
        </div>
    </section>

    <!-- Trending Products -->
    <section class="bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8" data-aos="fade-up">
                <h2 class="text-4xl font-extrabold text-dark">Trending This Week</h2>
                <a href="#" class="text-primary hover:underline font-semibold text-lg">View All</a>
            </div>
            <div class="relative">
                <div class="overflow-x-auto pb-4">
                    <div class="flex space-x-6">
                        <div class="product-card bg-white rounded-xl shadow-card overflow-hidden w-64 flex-shrink-0 transition product-entry hover-3d"
                            style="animation-delay: 0.1s">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=1170&q=80"
                                    alt="Product" class="w-full h-48 object-cover" loading="lazy">
                                <div
                                    class="absolute top-2 left-2 bg-secondary text-white text-xs px-3 py-1 rounded-full">
                                    <i class="fas fa-store mr-1"></i> TechHaven
                                </div>
                                <div
                                    class="absolute top-2 right-2 bg-red-500 text-white text-xs px-3 py-1 rounded-full">
                                    15% Off
                                </div>
                                <button
                                    class="absolute top-10 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                            <div class="p-5">
                                <h3 class="font-bold text-lg mb-2">Wireless Earbuds</h3>
                                <div class="flex items-center mb-3">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                            class="fas fa-star"></i><i class="fas fa-star"></i><i
                                            class="fas fa-star-half-alt"></i>
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
                        <div class="product-card bg-white rounded-xl shadow-card overflow-hidden w-64 flex-shrink-0 transition product-entry hover-3d"
                            style="animation-delay: 0.2s">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1546868871-7041f2a55e12?auto=format&fit=crop&w=1064&q=80"
                                    alt="Product" class="w-full h-48 object-cover" loading="lazy">
                                <div
                                    class="absolute top-2 left-2 bg-secondary text-white text-xs px-3 py-1 rounded-full">
                                    <i class="fas fa-store mr-1"></i> SmartGadgets
                                </div>
                                <button
                                    class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                            <div class="p-5">
                                <h3 class="font-bold text-lg mb-2">Smart Watch</h3>
                                <div class="flex items-center mb-3">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                            class="fas fa-star"></i><i class="fas fa-star"></i><i
                                            class="fas fa-star"></i>
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
                        <div class="product-card bg-white rounded-xl shadow-card overflow-hidden w-64 flex-shrink-0 transition product-entry hover-3d"
                            style="animation-delay: 0.3s">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=1099&q=80"
                                    alt="Product" class="w-full h-48 object-cover" loading="lazy">
                                <div
                                    class="absolute top-2 left-2 bg-secondary text-white text-xs px-3 py-1 rounded-full">
                                    <i class="fas fa-store mr-1"></i> CameraWorld
                                </div>
                                <div
                                    class="absolute top-2 right-2 bg-red-500 text-white text-xs px-3 py-1 rounded-full">
                                    20% Off
                                </div>
                                <button
                                    class="absolute top-10 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                            <div class="p-5">
                                <h3 class="font-bold text-lg mb-2">DSLR Camera</h3>
                                <div class="flex items-center mb-3">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                            class="fas fa-star"></i><i class="fas fa-star"></i><i
                                            class="fas fa-star"></i>
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
                        <div class="product-card bg-white rounded-xl shadow-card overflow-hidden w-64 flex-shrink-0 transition product-entry hover-3d"
                            style="animation-delay: 0.4s">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=1170&q=80"
                                    alt="Product" class="w-full h-48 object-cover" loading="lazy">
                                <div
                                    class="absolute top-2 left-2 bg-secondary text-white text-xs px-3 py-1 rounded-full">
                                    <i class="fas fa-store mr-1"></i> SportZone
                                </div>
                                <button
                                    class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                            <div class="p-5">
                                <h3 class="font-bold text-lg mb-2">Running Shoes</h3>
                                <div class="flex items-center mb-3">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                            class="fas fa-star"></i><i class="fas fa-star"></i><i
                                            class="fas fa-star-half-alt"></i>
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
                        <div class="product-card bg-white rounded-xl shadow-card overflow-hidden w-64 flex-shrink-0 transition product-entry hover-3d"
                            style="animation-delay: 0.5s">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1601784551446-20c9e07cdbdb?auto=format&fit=crop&w=1167&q=80"
                                    alt="Product" class="w-full h-48 object-cover" loading="lazy">
                                <div
                                    class="absolute top-2 left-2 bg-secondary text-white text-xs px-3 py-1 rounded-full">
                                    <i class="fas fa-store mr-1"></i> OutdoorGear
                                </div>
                                <button
                                    class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                            <div class="p-5">
                                <h3 class="font-bold text-lg mb-2">Backpack</h3>
                                <div class="flex items-center mb-3">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                            class="fas fa-star"></i><i class="fas fa-star"></i><i
                                            class="fas fa-star"></i>
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
                        <div class="product-card bg-white rounded-xl shadow-card overflow-hidden w-64 flex-shrink-0 transition product-entry hover-3d"
                            style="animation-delay: 0.6s">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1525904097878-94fb15835963?auto=format&fit=crop&w=1170&q=80"
                                    alt="Product" class="w-full h-48 object-cover" loading="lazy">
                                <div
                                    class="absolute top-2 left-2 bg-secondary text-white text-xs px-3 py-1 rounded-full">
                                    <i class="fas fa-store mr-1"></i> BeautySpot
                                </div>
                                <div
                                    class="absolute top-2 right-2 bg-red-500 text-white text-xs px-3 py-1 rounded-full">
                                    10% Off
                                </div>
                                <button
                                    class="absolute top-10 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                            <div class="p-5">
                                <h3 class="font-bold text-lg mb-2">Perfume Set</h3>
                                <div class="flex items-center mb-3">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                            class="fas fa-star"></i><i class="fas fa-star"></i><i
                                            class="fas fa-star-half-alt"></i>
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
                        <div class="product-card bg-white rounded-xl shadow-card overflow-hidden w-64 flex-shrink-0 transition product-entry hover-3d"
                            style="animation-delay: 0.7s">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1560343090-f0409e92791a?auto=format&fit=crop&w=1172&q=80"
                                    alt="Product" class="w-full h-48 object-cover" loading="lazy">
                                <div
                                    class="absolute top-2 left-2 bg-secondary text-white text-xs px-3 py-1 rounded-full">
                                    <i class="fas fa-store mr-1"></i> KitchenPro
                                </div>
                                <button
                                    class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                            <div class="p-5">
                                <h3 class="font-bold text-lg mb-2">Blender</h3>
                                <div class="flex items-center mb-3">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                            class="fas fa-star"></i><i class="fas fa-star"></i><i
                                            class="fas fa-star"></i>
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
                        <div class="product-card bg-white rounded-xl shadow-card overflow-hidden w-64 flex-shrink-0 transition product-entry hover-3d"
                            style="animation-delay: 0.8s">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1546054454-aa26e2b734c7?auto=format&fit=crop&w=1180&q=80"
                                    alt="Product" class="w-full h-48 object-cover" loading="lazy">
                                <div
                                    class="absolute top-2 left-2 bg-secondary text-white text-xs px-3 py-1 rounded-full">
                                    <i class="fas fa-store mr-1"></i> BookNook
                                </div>
                                <button
                                    class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                            <div class="p-5">
                                <h3 class="font-bold text-lg mb-2">Book Collection</h3>
                                <div class="flex items-center mb-3">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                            class="fas fa-star"></i><i class="fas fa-star"></i><i
                                            class="fas fa-star-half-alt"></i>
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
                    class="absolute left-0 top-1/2 transform -translate-y-1/2 -ml-5 bg-white p-3 rounded-full shadow-md hover:bg-gray-100 transition">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button
                    class="absolute right-0 top-1/2 transform -translate-y-1/2 -mr-5 bg-white p-3 rounded-full shadow-md hover:bg-gray-100 transition">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Vendor Showcase -->
    <section class="container mx-auto px-4 py-12 bg-subtle-pattern">
        <h2 class="text-4xl font-extrabold text-center mb-10 text-dark" data-aos="fade-up">Featured Vendors</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-card overflow-hidden text-center p-6 hover:shadow-glow transition"
                data-aos="zoom-in">
                <div class="w-24 h-24 mx-auto mb-4 rounded-full overflow-hidden border-4 border-primary">
                    <img src="https://images.unsplash.com/photo-1599305445671-ac291c95aaa9?auto=format&fit=crop&w=1169&q=80"
                        alt="Vendor" class="w-full h-full object-cover" loading="lazy">
                </div>
                <h3 class="font-bold text-xl mb-2">EcoFriendly Homes</h3>
                <div class="flex justify-center items-center mb-3">
                    <div class="flex text-yellow-400">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                            class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <span class="text-gray-500 text-sm ml-2">4.8</span>
                </div>
                <p class="text-gray-600 mb-4">126 products</p>
                <a href="#"
                    class="ripple inline-block bg-gradient-to-r from-primary to-indigo-700 text-white px-5 py-2 rounded-lg hover:shadow-lg transition relative overflow-hidden">
                    Visit Store
                </a>
            </div>
            <div class="bg-white rounded-xl shadow-card overflow-hidden text-center p-6 hover:shadow-glow transition"
                data-aos="zoom-in" data-aos-delay="100">
                <div class="w-24 h-24 mx-auto mb-4 rounded-full overflow-hidden border-4 border-primary">
                    <img src="https://images.unsplash.com/photo-1556740738-b6a63e27c4df?auto=format&fit=crop&w=1170&q=80"
                        alt="Vendor" class="w-full h-full object-cover" loading="lazy">
                </div>
                <h3 class="font-bold text-xl mb-2">TechHaven</h3>
                <div class="flex justify-center items-center mb-3">
                    <div class="flex text-yellow-400">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                            class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                    </div>
                    <span class="text-gray-500 text-sm ml-2">4.6</span>
                </div>
                <p class="text-gray-600 mb-4">89 products</p>
                <a href="#"
                    class="ripple inline-block bg-gradient-to-r from-primary to-indigo-700 text-white px-5 py-2 rounded-lg hover:shadow-lg transition relative overflow-hidden">
                    Visit Store
                </a>
            </div>
            <div class="bg-white rounded-xl shadow-card overflow-hidden text-center p-6 hover:shadow-glow transition"
                data-aos="zoom-in" data-aos-delay="200">
                <div class="w-24 h-24 mx-auto mb-4 rounded-full overflow-hidden border-4 border-primary">
                    <img src="https://images.unsplash.com/photo-1576566588028-4147f3842f27?auto=format&fit=crop&w=1064&q=80"
                        alt="Vendor" class="w-full h-full object-cover" loading="lazy">
                </div>
                <h3 class="font-bold text-xl mb-2">FashionHub</h3>
                <div class="flex justify-center items-center mb-3">
                    <div class="flex text-yellow-400">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                            class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <span class="text-gray-500 text-sm ml-2">4.9</span>
                </div>
                <p class="text-gray-600 mb-4">215 products</p>
                <a href="#"
                    class="ripple inline-block bg-gradient-to-r from-primary to-indigo-700 text-white px-5 py-2 rounded-lg hover:shadow-lg transition relative overflow-hidden">
                    Visit Store
                </a>
            </div>
            <div class="bg-white rounded-xl shadow-card overflow-hidden text-center p-6 hover:shadow-glow transition"
                data-aos="zoom-in" data-aos-delay="300">
                <div class="w-24 h-24 mx-auto mb-4 rounded-full overflow-hidden border-4 border-primary">
                    <img src="https://images.unsplash.com/photo-1518455027359-f3f8164ba6bd?auto=format&fit=crop&w=1170&q=80"
                        alt="Vendor" class="w-full h-full object-cover" loading="lazy">
                </div>
                <h3 class="font-bold text-xl mb-2">HomeDecor</h3>
                <div class="flex justify-center items-center mb-3">
                    <div class="flex text-yellow-400">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                            class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                    </div>
                    <span class="text-gray-500 text-sm ml-2">4.7</span>
                </div>
                <p class="text-gray-600 mb-4">178 products</p>
                <a href="#"
                    class="ripple inline-block bg-gradient-to-r from-primary to-indigo-700 text-white px-5 py-2 rounded-lg hover:shadow-lg transition relative overflow-hidden">
                    Visit Store
                </a>
            </div>
        </div>
    </section>

    <!-- Value Propositions -->
    <section class="bg-primary text-white py-12 parallax-bg"
        style="background-image: url('https://images.unsplash.com/photo-1556740738-b6a63e27c4df?auto=format&fit=crop&w=1170&q=80');">
        <div class="container mx-auto px-4 relative">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center" data-aos="fade-up">
                    <div
                        class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-primary to-secondary rounded-full flex items-center justify-center animate-pulse-slow">
                        <i class="fas fa-shield-alt text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-xl mb-2">Secure Payments</h3>
                    <p class="opacity-90">100% protected transactions with encrypted payment processing</p>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                    <div
                        class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-primary to-secondary rounded-full flex items-center justify-center animate-pulse-slow">
                        <i class="fas fa-truck text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-xl mb-2">Fast Shipping</h3>
                    <p class="opacity-90">Get your orders delivered quickly from vendors nationwide</p>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                    <div
                        class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-primary to-secondary rounded-full flex items-center justify-center animate-pulse-slow">
                        <i class="fas fa-exchange-alt text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-xl mb-2">Easy Returns</h3>
                    <p class="opacity-90">Hassle-free returns within 30 days for most items</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="container mx-auto px-4 py-12 bg-subtle-pattern">
        <div class="bg-white rounded-xl shadow-card p-8 text-center hover-3d" data-aos="fade-up">
            <h2 class="text-3xl font-extrabold mb-3 text-dark">Stay Updated</h2>
            <p class="text-gray-600 mb-6 max-w-2xl mx-auto">Subscribe to our newsletter for exclusive deals and new
                product alerts from our vendors.</p>
            <div class="flex max-w-md mx-auto">
                <input type="email" placeholder="Your email address"
                    class="flex-1 py-3 px-4 rounded-l-lg border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-300 transform focus:scale-105">
                <button
                    class="ripple bg-primary text-white px-6 py-3 rounded-r-lg hover:bg-indigo-700 transition transform hover:scale-105 relative overflow-hidden">
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
                    <img src="https://via.placeholder.com/150x50?text=MultiVendor" alt="Logo" class="h-10 mb-4">
                    <p class="text-gray-400">The marketplace for independent sellers and buyers.</p>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-4">Shop</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">All Products</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Featured</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">New Arrivals</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Deals</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-4">Sell</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Become a Vendor</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Vendor Dashboard</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Seller Resources</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Pricing</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-4">Contact</h4>
                    <ul class="space-y-3">
                        <li class="flex items-center text-gray-400"><i class="fas fa-map-marker-alt mr-3"></i> 123
                            Market St, City</li>
                        <li class="flex items-center text-gray-400"><i class="fas fa-phone mr-3"></i> (123) 456-7890
                        </li>
                        <li class="flex items-center text-gray-400"><i class="fas fa-envelope mr-3"></i>
                            support@multivendor.com</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 mb-4 md:mb-0">© 2023 MultiVendor. All rights reserved.</p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition p-2 rounded-full hover:bg-gray-700"><i
                            class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition p-2 rounded-full hover:bg-gray-700"><i
                            class="fab fa-twitter"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition p-2 rounded-full hover:bg-gray-700"><i
                            class="fab fa-instagram"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition p-2 rounded-full hover:bg-gray-700"><i
                            class="fab fa-pinterest"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating Cart Button -->
    <?php if($user_logged_in): ?>
    <a href="#"
        class="floating-cart-btn bg-primary text-white p-4 rounded-full shadow-xl hover:shadow-2xl transition-all duration-300">
        <i class="fas fa-shopping-cart text-xl"></i>
        <?php if($cartCount > 0): ?>
        <span class="cart-bubble"><?php echo $cartCount; ?></span>
        <?php endif; ?>
    </a>
    <?php endif; ?>

    <!-- Back to Top Button -->
    <button class="back-to-top hidden" id="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script>
    // Initialize AOS
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });

    // Initialize GSAP animations
    gsap.registerPlugin(ScrollTrigger);

    // Animate elements on scroll
    gsap.utils.toArray('[data-animate]').forEach(element => {
        gsap.from(element, {
            scrollTrigger: {
                trigger: element,
                start: "top 80%",
                toggleActions: "play none none none"
            },
            opacity: 0,
            y: 50,
            duration: 0.8,
            ease: "power2.out"
        });
    });

    // Typing effect for hero text
    const typingText = document.getElementById('typing-text');
    const words = ["Unique Finds", "Handcrafted Goods", "Local Products", "Exclusive Deals"];
    let wordIndex = 0;
    let charIndex = 0;
    let isDeleting = false;
    let isEnd = false;

    function typeEffect() {
        const currentWord = words[wordIndex];
        const currentChar = currentWord.substring(0, charIndex);
        typingText.textContent = currentWord.substring(0, charIndex);
        typingText.classList.add('typing-effect');

        if (!isDeleting && charIndex < currentWord.length) {
            charIndex++;
            setTimeout(typeEffect, 100);
        } else if (isDeleting && charIndex > 0) {
            charIndex--;
            setTimeout(typeEffect, 50);
        } else {
            isDeleting = !isDeleting;
            typingText.classList.remove('typing-effect');
            if (!isDeleting) {
                wordIndex = (wordIndex + 1) % words.length;
            }
            setTimeout(typeEffect, 1200);
        }
    }

    // Start typing effect
    setTimeout(typeEffect, 1000);
    particlesJS('particles-js', {
        "particles": {
            "number": {
                "value": 100,
                "density": {
                    "enable": true,
                    "value_area": 800
                }
            },
            "color": {
                "value": "#e0e0e0" // Changed to very light grey
            },
            "shape": {
                "type": "circle",
                "stroke": {
                    "width": 0,
                    "color": "#000000"
                },
                "polygon": {
                    "nb_sides": 5
                }
            },
            "opacity": {
                "value": 0.6,
                "random": false,
                "anim": {
                    "enable": false,
                    "speed": 1,
                    "opacity_min": 0.2,
                    "sync": false
                }
            },
            "size": {
                "value": 4.5,
                "random": true,
                "anim": {
                    "enable": false,
                    "speed": 40,
                    "size_min": 0.2,
                    "sync": false
                }
            },
            "line_linked": {
                "enable": true,
                "distance": 140,
                "color": "#B7B7B7",
                "opacity": 0.4,
                "width": 1.3
            },
            "move": {
                "enable": true,
                "speed": 2.5,
                "direction": "none",
                "random": false,
                "straight": false,
                "out_mode": "out",
                "bounce": false,
                "attract": {
                    "enable": false,
                    "rotateX": 600,
                    "rotateY": 1200
                }
            }
        },
        "interactivity": {
            "detect_on": "canvas",
            "events": {
                "onhover": {
                    "enable": true,
                    "mode": "grab"
                },
                "onclick": {
                    "enable": true,
                    "mode": "push"
                },
                "resize": true
            },
            "modes": {
                "grab": {
                    "distance": 140,
                    "line_linked": {
                        "opacity": 0.9
                    }
                },
                "bubble": {
                    "distance": 400,
                    "size": 40,
                    "duration": 2,
                    "opacity": 8,
                    "speed": 3
                },
                "repulse": {
                    "distance": 200,
                    "duration": 0.4
                },
                "push": {
                    "particles_nb": 4
                },
                "remove": {
                    "particles_nb": 2
                }
            }
        },
        "retina_detect": true
    });

    // Hero Slider
    const heroSlides = document.querySelectorAll('.hero-slide');
    const heroIndicators = document.querySelectorAll('.hero-banner button');
    let currentHeroSlide = 0;

    function showHeroSlide(index) {
        heroSlides.forEach(slide => slide.classList.remove('active'));
        heroIndicators.forEach(ind => ind.classList.remove('bg-primary'));
        heroSlides[index].classList.add('active');
        heroIndicators[index].classList.add('bg-primary');
        currentHeroSlide = index;
    }

    setInterval(() => {
        let nextSlide = (currentHeroSlide + 1) % heroSlides.length;
        showHeroSlide(nextSlide);
    }, 5000);

    heroIndicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => showHeroSlide(index));
    });

    // Advert Carousel
    const adCarousel = document.querySelector('.ad-carousel');
    const adSlides = document.querySelectorAll('.ad-slide');
    const adPrevBtn = document.querySelector('.ad-prev');
    const adNextBtn = document.querySelector('.ad-next');
    const adIndicators = document.querySelectorAll('.ad-indicator');
    let currentAdIndex = 0;
    const adSlideCount = adSlides.length;

    function updateAdCarousel() {
        adCarousel.style.transform = `translateX(-${currentAdIndex * 100}%)`;
        adIndicators.forEach((ind, i) => {
            ind.classList.toggle('bg-primary', i === currentAdIndex);
            ind.classList.toggle('bg-gray-300', i !== currentAdIndex);
        });
    }

    function nextAdSlide() {
        currentAdIndex = (currentAdIndex + 1) % adSlideCount;
        updateAdCarousel();
    }

    function prevAdSlide() {
        currentAdIndex = (currentAdIndex - 1 + adSlideCount) % adSlideCount;
        updateAdCarousel();
    }

    let adSlideInterval = setInterval(nextAdSlide, 5000);

    adCarousel.addEventListener('mouseenter', () => clearInterval(adSlideInterval));
    adCarousel.addEventListener('mouseleave', () => {
        adSlideInterval = setInterval(nextAdSlide, 5000);
    });

    adNextBtn.addEventListener('click', () => {
        clearInterval(adSlideInterval);
        nextAdSlide();
        adSlideInterval = setInterval(nextAdSlide, 5000);
    });

    adPrevBtn.addEventListener('click', () => {
        clearInterval(adSlideInterval);
        prevAdSlide();
        adSlideInterval = setInterval(nextAdSlide, 5000);
    });

    adIndicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            clearInterval(adSlideInterval);
            currentAdIndex = index;
            updateAdCarousel();
            adSlideInterval = setInterval(nextAdSlide, 5000);
        });
    });

    // Touch Support for Carousel
    let touchStartX = 0;
    let touchEndX = 0;

    adCarousel.addEventListener('touchstart', e => {
        touchStartX = e.changedTouches[0].screenX;
    });

    adCarousel.addEventListener('touchend', e => {
        touchEndX = e.changedTouches[0].screenX;
        if (touchStartX - touchEndX > 50) {
            clearInterval(adSlideInterval);
            nextAdSlide();
            adSlideInterval = setInterval(nextAdSlide, 5000);
        } else if (touchEndX - touchStartX > 50) {
            clearInterval(adSlideInterval);
            prevAdSlide();
            adSlideInterval = setInterval(nextAdSlide, 5000);
        }
    });

    updateAdCarousel();

    // Scroll Progress Bar
    window.addEventListener('scroll', () => {
        const scrollProgress = document.getElementById('scroll-progress');
        const scrollTop = document.documentElement.scrollTop;
        const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const progress = (scrollTop / scrollHeight) * 100;
        scrollProgress.style.width = progress + '%';
    });

    // Back to Top Button
    const backToTop = document.getElementById('back-to-top');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            backToTop.classList.add('visible');
        } else {
            backToTop.classList.remove('visible');
        }
    });

    backToTop.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Lazy Loading Observer
    const images = document.querySelectorAll('img[loading="lazy"]');
    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src || img.src;
                observer.unobserve(img);

            }
        });
    }, {
        rootMargin: '0px 0px 200px 0px'
    });

    images.forEach(img => observer.observe(img));

    // Loading Spinner
    const loadingSpinner = document.getElementById('loading-spinner');
    window.addEventListener('load', () => {
        loadingSpinner.style.display = 'none';
    });

    // Mobile Menu
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    mobileMenuButton?.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    const mobileCategoriesButton = document.getElementById('mobile-categories-button');
    const mobileCategoriesMenu = document.getElementById('mobile-categories-menu');
    mobileCategoriesButton?.addEventListener('click', () => {
        mobileCategoriesMenu.classList.toggle('hidden');
    });

    // Ripple Effect for Buttons
    const rippleButtons = document.querySelectorAll('.ripple');
    rippleButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const ripple = document.createElement('span');
            ripple.classList.add('ripple-effect');
            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;

            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Product Card Hover Effects
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const angleX = (y - centerY) / 20;
            const angleY = (centerX - x) / 20;

            card.style.transform =
                `perspective(1000px) rotateX(${angleX}deg) rotateY(${angleY}deg) translateY(-8px)`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(-8px)';
        });
    });

    // Floating Cart Button Animation
    const floatingCart = document.querySelector('.floating-cart-btn');
    if (floatingCart) {
        setInterval(() => {
            floatingCart.classList.toggle('animate-bounce-slow');
        }, 3000);
    }

    // Animate elements when they come into view
    const animateOnScroll = () => {
        const elements = document.querySelectorAll('[data-animate-on-scroll]');
        elements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.3;

            if (elementPosition < screenPosition) {
                element.classList.add('animate__animated', 'animate__fadeInUp');
            }
        });
    };

    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll(); // Run once on load

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();

            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Newsletter Form Submission
    const newsletterForm = document.querySelector('form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const emailInput = this.querySelector('input[type="email"]');

            if (emailInput.value) {
                // Show success animation
                const submitButton = this.querySelector('button[type="submit"]');
                submitButton.innerHTML = '<i class="fas fa-check"></i> Subscribed!';
                submitButton.classList.remove('bg-primary');
                submitButton.classList.add('bg-green-500');

                // Reset after 3 seconds
                setTimeout(() => {
                    submitButton.innerHTML = 'Subscribe';
                    submitButton.classList.remove('bg-green-500');
                    submitButton.classList.add('bg-primary');
                    emailInput.value = '';
                }, 3000);
            }
        });
    }

    // Add to Cart Animation
    const addToCartButtons = document.querySelectorAll('.product-card button:last-child');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            // Clone the button and animate it to the cart
            const clone = this.cloneNode(true);
            clone.style.position = 'absolute';
            clone.style.top = `${this.getBoundingClientRect().top}px`;
            clone.style.left = `${this.getBoundingClientRect().left}px`;
            clone.style.width = `${this.offsetWidth}px`;
            clone.style.height = `${this.offsetHeight}px`;
            clone.style.pointerEvents = 'none';
            clone.classList.add('animate__animated', 'animate__zoomOut');

            document.body.appendChild(clone);

            // Get cart position
            const cartPosition = floatingCart ?
                floatingCart.getBoundingClientRect() : {
                    top: 20,
                    right: 20
                };

            // Animate to cart
            gsap.to(clone, {
                x: cartPosition.right - this.getBoundingClientRect().right,
                y: cartPosition.top - this.getBoundingClientRect().top,
                scale: 0.3,
                opacity: 0,
                duration: 1,
                ease: "power2.out",
                onComplete: () => {
                    clone.remove();

                    // Animate cart bubble
                    if (floatingCart) {
                        const bubble = floatingCart.querySelector('.cart-bubble');
                        if (bubble) {
                            gsap.fromTo(bubble, {
                                scale: 1.5
                            }, {
                                scale: 1,
                                duration: 0.5,
                                ease: "elastic.out(1, 0.5)"
                            });
                        }
                    }
                }
            });

            // Show temporary notification
            const notification = document.createElement('div');
            notification.className =
                'fixed top-20 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg animate__animated animate__fadeInDown';
            notification.textContent = 'Added to cart!';
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.classList.add('animate__fadeOutUp');
                setTimeout(() => notification.remove(), 500);
            }, 2000);
        });
    });

    // Initialize tooltips
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    tooltipElements.forEach(element => {
        const tooltip = document.createElement('div');
        tooltip.className =
            'hidden absolute z-50 bg-gray-800 text-white text-xs rounded py-1 px-2 whitespace-nowrap';
        tooltip.textContent = element.getAttribute('data-tooltip');
        element.appendChild(tooltip);

        element.addEventListener('mouseenter', () => {
            tooltip.classList.remove('hidden');
            positionTooltip(element, tooltip);
        });

        element.addEventListener('mouseleave', () => {
            tooltip.classList.add('hidden');
        });
    });

    function positionTooltip(parent, tooltip) {
        const rect = parent.getBoundingClientRect();
        tooltip.style.top = `${rect.top - tooltip.offsetHeight - 10}px`;
        tooltip.style.left = `${rect.left + (rect.width - tooltip.offsetWidth) / 2}px`;
    }

    // Dark mode toggle (example implementation)
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
        });

        // Check for saved preference
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    }

    // Initialize any other plugins or custom functionality here
    console.log('Enhanced frontend initialized!');
    </script>