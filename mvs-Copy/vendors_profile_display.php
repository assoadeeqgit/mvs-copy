<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechHaven - Vendor Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .vendor-header {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                url('https://images.unsplash.com/photo-1521791136064-7986c2920216?ixlib=rb-4.0.3&auto=format&fit=crop&w=1169&q=80');
            background-size: cover;
            background-position: center;
        }

        .star-rating .star {
            color: #e2e8f0;
        }

        .star-rating .star.filled {
            color: #f59e0b;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <!-- Header -->
    <?php include('./include/user_header.php'); ?>

    <!-- Vendor Header Section -->
    <section class="vendor-header text-white py-16">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center">
                <!-- Vendor Logo -->
                <div
                    class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-white bg-white shadow-lg overflow-hidden mb-6 md:mb-0 md:mr-8">
                    <img src="https://images.unsplash.com/photo-1556740738-b6a63e27c4df?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80"
                        alt="TechHaven" class="w-full h-full object-cover">
                </div>

                <!-- Vendor Info -->
                <div class="flex-1">
                    <div class="flex items-center mb-2">
                        <h1 class="text-3xl md:text-4xl font-bold mr-3">TechHaven</h1>
                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full flex items-center">
                            <i class="fas fa-check-circle mr-1"></i> Verified
                        </span>
                    </div>

                    <!-- Rating -->
                    <div class="flex items-center mb-4">
                        <div class="star-rating flex mr-2">
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star-half-alt filled"></i>
                        </div>
                        <span class="text-white text-sm">(4.7 rating from 128 reviews)</span>
                    </div>

                    <!-- Stats -->
                    <div class="flex flex-wrap gap-4 mb-4">
                        <div class="bg-white bg-opacity-20 px-4 py-2 rounded-lg">
                            <div class="text-xs">Products</div>
                            <div class="font-bold">89</div>
                        </div>
                        <div class="bg-white bg-opacity-20 px-4 py-2 rounded-lg">
                            <div class="text-xs">Joined</div>
                            <div class="font-bold">Jan 2020</div>
                        </div>
                        <div class="bg-white bg-opacity-20 px-4 py-2 rounded-lg">
                            <div class="text-xs">Orders</div>
                            <div class="font-bold">2.4K+</div>
                        </div>
                    </div>

                    <!-- Social Links -->
                    <div class="flex space-x-3">
                        <a href="#" class="text-white hover:text-blue-300">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-white hover:text-pink-300">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-white hover:text-blue-400">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-1 rounded-lg text-sm ml-2">
                            <i class="fas fa-store mr-1"></i> Follow Store
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="container mx-auto px-4 py-8">
        <!-- Navigation Tabs -->
        <div class="flex border-b mb-6 overflow-x-auto">
            <button onclick="switchTab('products')"
                class="tab-btn px-6 py-3 font-medium text-indigo-600 border-b-2 border-indigo-600 whitespace-nowrap">
                <i class="fas fa-box-open mr-2"></i> Products
            </button>
            <button onclick="switchTab('about')"
                class="tab-btn px-6 py-3 font-medium text-gray-600 hover:text-indigo-600 whitespace-nowrap">
                <i class="fas fa-info-circle mr-2"></i> About
            </button>
            <button onclick="switchTab('reviews')"
                class="tab-btn px-6 py-3 font-medium text-gray-600 hover:text-indigo-600 whitespace-nowrap">
                <i class="fas fa-star mr-2"></i> Reviews
            </button>
            <button onclick="switchTab('policies')"
                class="tab-btn px-6 py-3 font-medium text-gray-600 hover:text-indigo-600 whitespace-nowrap">
                <i class="fas fa-file-contract mr-2"></i> Policies
            </button>
        </div>

        <!-- Products Tab -->
        <div id="products-tab" class="tab-content active">
            <!-- Sorting Options -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold">89 Products</h2>
                <div class="flex items-center">
                    <label class="mr-2 text-gray-600">Sort by:</label>
                    <select class="border rounded p-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="newest">Newest</option>
                        <option value="price_asc">Price: Low to High</option>
                        <option value="price_desc">Price: High to Low</option>
                        <option value="rating">Top Rated</option>
                    </select>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <!-- Product 1 -->
                <div class="product-card bg-white rounded-lg shadow-md overflow-hidden transition duration-300">
                    <div class="relative">
                        <a href="product.html">
                            <img src="https://images.unsplash.com/photo-1546868871-7041f2a55e12?ixlib=rb-4.0.3&auto=format&fit=crop&w=1064&q=80"
                                alt="Smart Watch" class="w-full h-48 object-cover">
                        </a>
                        <button
                            class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-indigo-600 hover:text-white transition">
                            <i class="fas fa-heart"></i>
                        </button>
                        <span
                            class="absolute top-2 left-2 bg-green-100 text-green-800 text-xs px-2 py-1 rounded">New</span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-1 truncate">
                            <a href="product.html" class="hover:text-indigo-600">Smart Watch Pro X3</a>
                        </h3>
                        <div class="flex items-center mb-2">
                            <div class="star-rating flex mr-1">
                                <i class="fas fa-star filled"></i>
                                <i class="fas fa-star filled"></i>
                                <i class="fas fa-star filled"></i>
                                <i class="fas fa-star filled"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-gray-500 text-sm">(128)</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg">$129.99</span>
                            <button class="bg-indigo-600 text-white p-2 rounded-full hover:bg-indigo-700 transition">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product 2 -->
                <div class="product-card bg-white rounded-lg shadow-md overflow-hidden transition duration-300">
                    <div class="relative">
                        <a href="product.html">
                            <img src="https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80"
                                alt="Wireless Earbuds" class="w-full h-48 object-cover">
                        </a>
                        <button
                            class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-indigo-600 hover:text-white transition">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-1 truncate">
                            <a href="product.html" class="hover:text-indigo-600">Wireless Noise-Canceling
                                Earbuds</a>
                        </h3>
                        <div class="flex items-center mb-2">
                            <div class="star-rating flex mr-1">
                                <i class="fas fa-star filled"></i>
                                <i class="fas fa-star filled"></i>
                                <i class="fas fa-star filled"></i>
                                <i class="fas fa-star filled"></i>
                                <i class="fas fa-star-half-alt filled"></i>
                            </div>
                            <span class="text-gray-500 text-sm">(256)</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg">$79.99</span>
                            <button class="bg-indigo-600 text-white p-2 rounded-full hover:bg-indigo-700 transition">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- More products... -->
            </div>

            <!-- Pagination -->
            <div class="flex justify-center mt-8">
                <nav class="flex items-center gap-1">
                    <button class="px-3 py-1 rounded border hover:bg-gray-100">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="px-3 py-1 rounded border bg-indigo-600 text-white">1</button>
                    <button class="px-3 py-1 rounded border hover:bg-gray-100">2</button>
                    <button class="px-3 py-1 rounded border hover:bg-gray-100">3</button>
                    <button class="px-3 py-1 rounded border hover:bg-gray-100">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </nav>
            </div>
        </div>

        <!-- About Tab -->
        <div id="about-tab" class="tab-content">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">About TechHaven</h2>
                <p class="text-gray-600 mb-6">
                    TechHaven is your premier destination for cutting-edge technology and innovative gadgets.
                    Founded in 2020, we specialize in bringing you the latest in smart devices, wearables,
                    and home automation technology from top brands around the world.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold mb-2 text-lg">Our Story</h3>
                        <p class="text-gray-600">
                            What started as a small tech blog has grown into one of the most trusted online
                            retailers
                            for technology enthusiasts. Our team of experts tests every product to ensure we only
                            offer
                            the best to our customers.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold mb-2 text-lg">Why Choose Us?</h3>
                        <ul class="list-disc list-inside text-gray-600 space-y-1">
                            <li>Authentic products with manufacturer warranties</li>
                            <li>Free shipping on orders over $50</li>
                            <li>30-day return policy</li>
                            <li>24/7 customer support</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="font-semibold mb-2 text-lg">Contact Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt text-indigo-600 mt-1 mr-2"></i>
                            <div>
                                <h4 class="font-medium">Address</h4>
                                <p class="text-gray-600 text-sm">123 Tech Street, Silicon Valley, CA 94025</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-phone-alt text-indigo-600 mt-1 mr-2"></i>
                            <div>
                                <h4 class="font-medium">Phone</h4>
                                <p class="text-gray-600 text-sm">(555) 123-4567</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-envelope text-indigo-600 mt-1 mr-2"></i>
                            <div>
                                <h4 class="font-medium">Email</h4>
                                <p class="text-gray-600 text-sm">support@techhaven.com</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Tab -->
        <div id="reviews-tab" class="tab-content">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <h2 class="text-xl font-bold">Customer Reviews</h2>
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg mt-2 md:mt-0">
                        <i class="fas fa-pen mr-1"></i> Write a Review
                    </button>
                </div>

                <!-- Overall Rating -->
                <div class="flex items-center mb-8 bg-gray-50 p-4 rounded-lg">
                    <div class="text-center mr-6">
                        <div class="text-4xl font-bold text-indigo-600">4.7</div>
                        <div class="star-rating flex justify-center mt-1">
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star-half-alt filled"></i>
                        </div>
                        <div class="text-sm text-gray-500 mt-1">128 reviews</div>
                    </div>

                    <!-- Rating Breakdown -->
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <span class="w-8 text-sm font-medium">5 star</span>
                            <div class="flex-1 bg-gray-200 rounded-full h-2 mx-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: 75%"></div>
                            </div>
                            <span class="w-8 text-sm text-gray-500">96</span>
                        </div>
                        <div class="flex items-center mb-2">
                            <span class="w-8 text-sm font-medium">4 star</span>
                            <div class="flex-1 bg-gray-200 rounded-full h-2 mx-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: 15%"></div>
                            </div>
                            <span class="w-8 text-sm text-gray-500">19</span>
                        </div>
                        <div class="flex items-center mb-2">
                            <span class="w-8 text-sm font-medium">3 star</span>
                            <div class="flex-1 bg-gray-200 rounded-full h-2 mx-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: 7%"></div>
                            </div>
                            <span class="w-8 text-sm text-gray-500">9</span>
                        </div>
                        <div class="flex items-center mb-2">
                            <span class="w-8 text-sm font-medium">2 star</span>
                            <div class="flex-1 bg-gray-200 rounded-full h-2 mx-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: 2%"></div>
                            </div>
                            <span class="w-8 text-sm text-gray-500">3</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-8 text-sm font-medium">1 star</span>
                            <div class="flex-1 bg-gray-200 rounded-full h-2 mx-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: 1%"></div>
                            </div>
                            <span class="w-8 text-sm text-gray-500">1</span>
                        </div>
                    </div>
                </div>

                <!-- Reviews List -->
                <div class="space-y-6">
                    <!-- Review 1 -->
                    <div class="border-b pb-6">
                        <div class="flex justify-between mb-2">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gray-300 mr-3 overflow-hidden">
                                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User"
                                        class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <div class="font-medium">Sarah Johnson</div>
                                    <div class="star-rating flex">
                                        <i class="fas fa-star filled"></i>
                                        <i class="fas fa-star filled"></i>
                                        <i class="fas fa-star filled"></i>
                                        <i class="fas fa-star filled"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <span class="text-sm text-gray-500">2 weeks ago</span>
                        </div>
                        <h3 class="font-semibold mb-1">Great quality products</h3>
                        <p class="text-gray-600">I've purchased several items from TechHaven and they've all
                            exceeded my
                            expectations. The shipping was fast and the products were well-packaged.</p>
                        <div class="flex mt-3">
                            <img src="https://via.placeholder.com/80x80" alt="Review photo"
                                class="w-16 h-16 rounded mr-2 border">
                            <img src="https://via.placeholder.com/80x80" alt="Review photo"
                                class="w-16 h-16 rounded border">
                        </div>
                    </div>

                    <!-- Review 2 -->
                    <div class="border-b pb-6">
                        <div class="flex justify-between mb-2">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gray-300 mr-3 overflow-hidden">
                                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User"
                                        class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <div class="font-medium">Michael Chen</div>
                                    <div class="star-rating flex">
                                        <i class="fas fa-star filled"></i>
                                        <i class="fas fa-star filled"></i>
                                        <i class="fas fa-star filled"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <span class="text-sm text-gray-500">1 month ago</span>
                        </div>
                        <h3 class="font-semibold mb-1">Good but slow shipping</h3>
                        <p class="text-gray-600">The product quality is excellent, but it took longer to arrive than
                            expected. Customer service was responsive though.</p>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="flex justify-center mt-8">
                    <nav class="flex items-center gap-1">
                        <button class="px-3 py-1 rounded border hover:bg-gray-100">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="px-3 py-1 rounded border bg-indigo-600 text-white">1</button>
                        <button class="px-3 py-1 rounded border hover:bg-gray-100">2</button>
                        <button class="px-3 py-1 rounded border hover:bg-gray-100">3</button>
                        <button class="px-3 py-1 rounded border hover:bg-gray-100">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Policies Tab -->
        <div id="policies-tab" class="tab-content">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-6">Store Policies</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Shipping Policy -->
                    <div class="border rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <div class="bg-indigo-100 p-2 rounded-full mr-3">
                                <i class="fas fa-truck text-indigo-600"></i>
                            </div>
                            <h3 class="font-semibold">Shipping Policy</h3>
                        </div>
                        <ul class="list-disc list-inside text-gray-600 space-y-1 text-sm">
                            <li>Free shipping on orders over $50</li>
                            <li>Standard shipping: 3-5 business days</li>
                            <li>Express shipping available</li>
                            <li>International shipping rates apply</li>
                        </ul>
                    </div>

                    <!-- Return Policy -->
                    <div class="border rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <div class="bg-indigo-100 p-2 rounded-full mr-3">
                                <i class="fas fa-exchange-alt text-indigo-600"></i>
                            </div>
                            <h3 class="font-semibold">Return Policy</h3>
                        </div>
                        <ul class="list-disc list-inside text-gray-600 space-y-1 text-sm">
                            <li>30-day return policy</li>
                            <li>Items must be unused and in original packaging</li>
                            <li>Customer pays return shipping</li>
                            <li>Refund processed within 5 business days</li>
                        </ul>
                    </div>

                    <!-- Payment Methods -->
                    <div class="border rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <div class="bg-indigo-100 p-2 rounded-full mr-3">
                                <i class="fas fa-credit-card text-indigo-600"></i>
                            </div>
                            <h3 class="font-semibold">Payment Methods</h3>
                        </div>
                        <div class="flex flex-wrap gap-2 mb-3">
                            <span class="bg-gray-100 px-2 py-1 rounded text-xs">Visa</span>
                            <span class="bg-gray-100 px-2 py-1 rounded text-xs">MasterCard</span>
                            <span class="bg-gray-100 px-2 py-1 rounded text-xs">PayPal</span>
                            <span class="bg-gray-100 px-2 py-1 rounded text-xs">Apple Pay</span>
                        </div>
                        <p class="text-gray-600 text-sm">All transactions are secure and encrypted.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="bg-gray-100 py-12">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-2xl font-bold mb-2">Stay Updated</h2>
            <p class="text-gray-600 mb-6 max-w-2xl mx-auto">Subscribe to receive news and special offers from
                TechHaven
            </p>
            <div class="flex max-w-md mx-auto">
                <input type="email" placeholder="Your email address"
                    class="flex-1 py-3 px-4 rounded-l-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <button class="bg-indigo-600 text-white px-6 py-3 rounded-r-lg hover:bg-indigo-700 transition">
                    Subscribe
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
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
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-4">Help</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Contact Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">FAQs</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Shipping</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-4">Follow Us</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i
                                class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2023 MultiVendor Marketplace. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Tab switching
        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });

            // Show selected tab
            document.getElementById(tabName + '-tab').classList.add('active');

            // Update active tab button
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('text-indigo-600', 'border-indigo-600');
                btn.classList.add('text-gray-600');
            });

            event.currentTarget.classList.add('text-indigo-600', 'border-indigo-600');
            event.currentTarget.classList.remove('text-gray-600');
        }
    </script>
</body>

</html>