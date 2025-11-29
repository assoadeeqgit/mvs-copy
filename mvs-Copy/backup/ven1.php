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
    <header class="sticky top-0 z-50 bg-white shadow-md">
        <!-- Top Bar -->
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center">
                <img src="https://via.placeholder.com/150x50?text=MultiVendor" alt="Logo" class="h-8">
            </div>

            <div class="hidden md:flex flex-1 mx-8">
                <div class="relative w-full">
                    <input type="text" placeholder="Search for products..."
                        class="w-full py-2 px-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <button class="absolute right-0 top-0 h-full px-4 text-gray-500">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <a href="user_profile.php" class="p-2 text-gray-700 hover:text-primary">
                    <i class="fas fa-user text-lg"></i>
                </a>
                <a href="cart.php" class="p-2 text-gray-700 hover:text-primary relative">
                    <i class="fas fa-shopping-cart text-lg"></i>
                    <span
                        class="absolute -top-1 -right-1 bg-primary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                </a>
                <button class="md:hidden p-2 text-gray-700">
                    <i class="fas fa-bars text-lg">
                    </i>
                </button>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="hidden md:block border-t border-gray-100">
            <div class="container mx-auto px-4 py-3 flex justify-between items-center">
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-700 hover:text-primary">Home</a>
                    <div class="relative group">
                        <button class="text-gray-700 hover:text-primary flex items-center">
                            Categories <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div
                            class="absolute left-0 mt-2 w-48 bg-white shadow-lg rounded-md py-1 z-10 hidden group-hover:block">
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Electronics</a>
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Fashion</a>
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Home & Garden</a>
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Beauty</a>
                        </div>
                    </div>
                    <a href="#" class="text-gray-700 hover:text-primary">Deals</a>
                    <a href="#" class="text-gray-700 hover:text-primary">Vendors</a>
                </div>
                <a href="#" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                    Sell With Us
                </a>
            </div>
        </nav>
    </header>

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
                        <div class="product-card bg-white rounded-lg shadow-md overflow-hidden ierrorgn w-64 flex-shrink-0 transition product-entry"
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

    <!-- Explore More Products -->
    <section class="bg-white py-12">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-6" data-aos="fade-up">
                <h2 class="text-3xl font-bold">Explore More Products</h2>
                <a href="#" class="text-primary hover:underline">Browse All</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <!-- Product Card 1 -->
                <div class="product-card bg-white rounded-lg shadow-md overflow-hidden transition product-entry"
                    style="animation-delay: 0.1s">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80"
                            alt="Product" class="w-full h-48 object-cover">
                        <div class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                            <i class="fas fa-store mr-1"></i> FashionTrend
                        </div>
                        <button
                            class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-1">Summer Dress</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-gray-500 text-sm ml-2">(210)</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg">$39.99</span>
                            <button class="bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 2 -->
                <div class="product-card bg-white rounded-lg shadow-md overflow-hidden transition product-entry"
                    style="animation-delay: 0.2s">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1605733513597-a8f834bd6621?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80"
                            alt="Product" class="w-full h-48 object-cover">
                        <div class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                            <i class="fas fa-store mr-1"></i> TechTrend
                        </div>
                        <button
                            class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-1">Gaming Mouse</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-gray-500 text-sm ml-2">(320)</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg">$29.99</span>
                            <button class="bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 3 -->
                <div class="product-card bg-white rounded-lg shadow-md overflow-hidden transition product-entry"
                    style="animation-delay: 0.3s">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80"
                            alt="Product" class="w-full h-48 object-cover">
                        <div class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                            <i class="fas fa-store mr-1"></i> HomeHaven
                        </div>
                        <button
                            class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-1">Coffee Table</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-gray-500 text-sm ml-2">(180)</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg">$199.99</span>
                            <button class="bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 4 -->
                <div class="product-card bg-white rounded-lg shadow-md overflow-hidden transition product-entry"
                    style="animation-delay: 0.4s">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1578500494198-27d3c37c3508?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80"
                            alt="Product" class="w-full h-48 object-cover">
                        <div class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                            <i class="fas fa-store mr-1"></i> BeautyBliss
                        </div>
                        <button
                            class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-1">Skincare Set</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-gray-500 text-sm ml-2">(250)</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg">$69.99</span>
                            <button class="bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 5 -->
                <div class="product-card bg-white rounded-lg shadow-md overflow-hidden transition product-entry"
                    style="animation-delay: 0.5s">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1516321497487-e288fb19713f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80"
                            alt="Product" class="w-full h-48 object-cover">
                        <div class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                            <i class="fas fa-store mr-1"></i> FitnessZone
                        </div>
                        <button
                            class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-1">Yoga Mat</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-gray-500 text-sm ml-2">(150)</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg">$24.99</span>
                            <button class="bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 6 -->
                <div class="product-card bg-white rounded-lg shadow-md overflow-hidden transition product-entry"
                    style="animation-delay: 0.6s">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80"
                            alt="Product" class="w-full h-48 object-cover">
                        <div class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                            <i class="fas fa-store mr-1"></i> HomeLux
                        </div>
                        <button
                            class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-1">Decorative Lamp</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-gray-500 text-sm ml-2">(200)</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg">$89.99</span>
                            <button class="bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 7 -->
                <div class="product-card bg-white rounded-lg shadow-md overflow-hidden transition product-entry"
                    style="animation-delay: 0.7s">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1512428559087-560fa5ceab6c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80"
                            alt="Product" class="w-full h-48 object-cover">
                        <div class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                            <i class="fas fa-store mr-1"></i> GadgetZone
                        </div>
                        <button
                            class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-1">Wireless Charger</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-gray-500 text-sm ml-2">(175)</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg">$19.99</span>
                            <button class="bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 8 -->
                <div class="product-card bg-white rounded-lg shadow-md overflow-hidden transition product-entry"
                    style="animation-delay: 0.8s">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1580870069867-74c57ee1bb07?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80"
                            alt="Product" class="w-full h-48 object-cover">
                        <div class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                            <i class="fas fa-store mr-1"></i> PetShop
                        </div>
                        <button
                            class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-1">Pet Bed</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-gray-500 text-sm ml-2">(230)</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg">$49.99</span>
                            <button class="bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 9 -->
                <div class="product-card bg-white rounded-lg shadow-md overflow-hidden transition product-entry"
                    style="animation-delay: 0.9s">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1516321497487-e288fb19713f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80"
                            alt="Product" class="w-full h-48 object-cover">
                        <div class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                            <i class="fas fa-store mr-1"></i> OutdoorVibes
                        </div>
                        <button
                            class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-1">Camping Tent</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-gray-500 text-sm ml-2">(190)</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg">$129.99</span>
                            <button class="bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 10 -->
                <div class="product-card bg-white rounded-lg shadow-md overflow-hidden transition product-entry"
                    style="animation-delay: 1.0s">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80"
                            alt="Product" class="w-full h-48 object-cover">
                        <div class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                            <i class="fas fa-store mr-1"></i> AudioWorld
                        </div>
                        <button
                            class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg Parsonsmb-1">Headphones</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-gray-500 text-sm ml-2">(300)</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg">$99.99</span>
                            <button class="bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 11 -->
                <div class="product-card bg-white rounded-lg shadow-md overflow-hidden transition product-entry"
                    style="animation-delay: 1.1s">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1491553895911-0055eca6402d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80"
                            alt="Product" class="w-full h-48 object-cover">
                        <div class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                            <i class="fas fa-store mr-1"></i> SneakerHub
                        </div>
                        <button
                            class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-1">Sneakers</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-gray-500 text-sm ml-2">(280)</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg">$89.99</span>
                            <button class="bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 12 -->
                <div class="product-card bg-white rounded-lg shadow-md overflow-hidden transition product-entry"
                    style="animation-delay: 1.2s">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1605733513597-a8f834bd6621?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80"
                            alt="Product" class="w-full h-48 object-cover">
                        <div class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                            <i class="fas fa-store mr-1"></i> TechGadgets
                        </div>
                        <button
                            class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-1">Smart Speaker</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-gray-500 text-sm ml-2">(260)</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg">$79.99</span>
                            <button class="bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
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
                    <h3 class="font-bold text