<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Preview | MultiVendor Marketplace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    .product-gallery {
        scroll-snap-type: x mandatory;
    }

    .product-gallery img {
        scroll-snap-align: start;
    }

    .choice-badge {
        background: linear-gradient(90deg, #4f46e5, #10b981);
    }

    .color-option.selected {
        box-shadow: 0 0 0 2px white, 0 0 0 4px #4f46e5;
    }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <!-- Header -->
    <?php include('./include/user_header.php'); ?>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Breadcrumbs -->
            <div class="px-6 pt-4 text-sm text-gray-500">
                <a href="#" class="hover:text-primary">Home</a> >
                <a href="#" class="hover:text-primary">Electronics</a> >
                <a href="#" class="hover:text-primary">Chargers</a> >
                <span class="text-dark">15W Wireless Charger</span>
            </div>

            <div class="md:flex">
                <!-- Product Gallery -->
                <div class="md:w-1/2 p-6">
                    <div class="relative h-96 mb-4 rounded-lg overflow-hidden bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1583394838336-acd977736f90?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                            alt="15W Wireless Charger" class="w-full h-full object-contain">
                    </div>
                    <div class="product-gallery flex space-x-2 overflow-x-auto pb-2">
                        <div
                            class="flex-shrink-0 w-20 h-20 rounded border border-gray-200 overflow-hidden cursor-pointer">
                            <img src="https://images.unsplash.com/photo-1583394838336-acd977736f90?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80"
                                alt="Charger angle 1" class="w-full h-full object-cover">
                        </div>
                        <div
                            class="flex-shrink-0 w-20 h-20 rounded border border-gray-200 overflow-hidden cursor-pointer">
                            <img src="https://images.unsplash.com/photo-1600324405947-4a5c2e0e245a?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80"
                                alt="Charger angle 2" class="w-full h-full object-cover">
                        </div>
                        <div
                            class="flex-shrink-0 w-20 h-20 rounded border border-gray-200 overflow-hidden cursor-pointer">
                            <img src="https://images.unsplash.com/photo-1600324405940-4896f1a79f5a?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80"
                                alt="Charger angle 3" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="md:w-1/2 p-6 border-l border-gray-100">
                    <div class="mb-4">
                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Folding design</span>
                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded ml-2">More convenient
                            storage</span>
                    </div>

                    <h1 class="text-2xl font-bold text-gray-800 mb-2">15W Wireless Charger For iPhone 16/15/14/13/12 Pro
                        Max Stand Fast Charging</h1>

                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400 text-sm">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="text-gray-500 text-sm ml-2">(128 reviews)</span>
                        <span class="text-gray-500 text-sm ml-4"><i class="fas fa-check-circle text-green-500"></i> 10
                            sold</span>
                    </div>

                    <div class="mb-6">
                        <div class="text-3xl font-bold text-primary">NGN12,959.16</div>
                        <div class="text-sm text-gray-500">Pay in NGN, Shop the World</div>
                        <div class="text-sm text-gray-500">Tax excluded, add at checkout if applicable</div>
                        <div class="text-sm text-green-600 mt-1">Extra 1% off with coins</div>
                    </div>

                    <!-- Color Selection -->
                    <div class="mb-6">
                        <h3 class="font-medium text-gray-700 mb-2">Color: <span class="text-dark">White</span></h3>
                        <div class="flex space-x-2">
                            <div
                                class="color-option selected w-10 h-10 rounded-full bg-white border-2 border-gray-200 cursor-pointer">
                            </div>
                            <div
                                class="color-option w-10 h-10 rounded-full bg-black border-2 border-gray-200 cursor-pointer">
                            </div>
                            <div
                                class="color-option w-10 h-10 rounded-full bg-blue-500 border-2 border-gray-200 cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <!-- Vendor Info -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="font-medium text-gray-700 mb-2">Sold by</h3>
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full overflow-hidden mr-3">
                                <img src="https://images.unsplash.com/photo-1599305445671-ac291c95aaa9?auto=format&fit=crop&w=100&q=80"
                                    alt="Vendor logo" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <div class="font-medium">TechGadgets Store</div>
                                <div class="text-sm text-gray-500">98% Positive Feedback</div>
                            </div>
                        </div>
                    </div>

                    <!-- Choice Badge -->
                    <div class="mb-6 p-3 rounded-lg choice-badge text-white text-sm flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>MultiVendor Marketplace commitment - Quality assured</span>
                    </div>

                    <!-- Shipping Info -->
                    <div class="mb-6 space-y-2 text-sm">
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Free shipping over NGN17,474.73</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-truck text-gray-500 mr-2"></i>
                            <span>Delivery: Jun 14 - 20</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-exchange-alt text-gray-500 mr-2"></i>
                            <span>7-day return policy</span>
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div class="mb-6">
                        <h3 class="font-medium text-gray-700 mb-2">Quantity</h3>
                        <div class="flex items-center">
                            <div class="flex border border-gray-300 rounded">
                                <button class="px-3 py-1 bg-gray-100 hover:bg-gray-200">-</button>
                                <div class="px-4 py-1">1</div>
                                <button class="px-3 py-1 bg-gray-100 hover:bg-gray-200">+</button>
                            </div>
                            <span class="text-sm text-gray-500 ml-3">Max. 1 pcs/shopper</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-3">
                        <button
                            class="flex-1 bg-primary hover:bg-indigo-700 text-white py-3 px-6 rounded-lg font-medium transition">
                            Add to Cart
                        </button>
                        <button
                            class="flex-1 border border-primary text-primary hover:bg-primary hover:text-white py-3 px-6 rounded-lg font-medium transition">
                            Buy Now
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details Tabs -->
        <div class="mt-8 bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <button class="border-b-2 border-primary text-primary px-4 py-3 font-medium">Description</button>
                    <button
                        class="border-b-2 border-transparent text-gray-500 px-4 py-3 font-medium hover:text-gray-700">Specifications</button>
                    <button
                        class="border-b-2 border-transparent text-gray-500 px-4 py-3 font-medium hover:text-gray-700">Reviews
                        (128)</button>
                    <button
                        class="border-b-2 border-transparent text-gray-500 px-4 py-3 font-medium hover:text-gray-700">Shipping
                        & Returns</button>
                </nav>
            </div>
            <div class="p-6">
                <h3 class="font-bold text-lg mb-3">Product Description</h3>
                <ul class="list-disc pl-5 space-y-2 text-gray-700">
                    <li>15W Fast Wireless Charging for iPhone 16/15/14/13/12 series</li>
                    <li>Foldable design for easy storage and portability</li>
                    <li>Universal compatibility with Samsung Galaxy devices</li>
                    <li>Built-in safety features including overcharge protection</li>
                    <li>LED charging indicator</li>
                    <li>Non-slip silicone surface to keep devices secure</li>
                </ul>

                <h3 class="font-bold text-lg mt-6 mb-3">Product Features</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start">
                        <i class="fas fa-bolt text-primary mt-1 mr-2"></i>
                        <div>
                            <h4 class="font-medium">Fast Charging</h4>
                            <p class="text-sm text-gray-600">Delivers up to 15W for compatible devices</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-shield-alt text-primary mt-1 mr-2"></i>
                        <div>
                            <h4 class="font-medium">Safety Protection</h4>
                            <p class="text-sm text-gray-600">Over-current, over-voltage, and temperature protection</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-compress-alt text-primary mt-1 mr-2"></i>
                        <div>
                            <h4 class="font-medium">Foldable Design</h4>
                            <p class="text-sm text-gray-600">Compact when folded for easy storage</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-mobile-alt text-primary mt-1 mr-2"></i>
                        <div>
                            <h4 class="font-medium">Universal Compatibility</h4>
                            <p class="text-sm text-gray-600">Works with most Qi-enabled devices</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4">You May Also Like</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Product Card 1 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="relative h-48 bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1600324405940-4896f1a79f5a?auto=format&fit=crop&w=400&q=80"
                            alt="Wireless Charger" class="w-full h-full object-contain p-4">
                        <button
                            class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="font-medium text-gray-800 mb-1 line-clamp-2">10W Fast Wireless Charger Pad for iPhone
                            Samsung</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400 text-xs">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-gray-500 text-xs ml-1">(87)</span>
                        </div>
                        <div class="text-lg font-bold text-primary">NGN8,499.00</div>
                    </div>
                </div>

                <!-- Product Card 2 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="relative h-48 bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1546054454-aa26e2b734c7?auto=format&fit=crop&w=400&q=80"
                            alt="USB C Cable" class="w-full h-full object-contain p-4">
                        <button
                            class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="font-medium text-gray-800 mb-1 line-clamp-2">3-in-1 Fast Charging Cable USB C to
                            Lightning/Micro</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400 text-xs">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-gray-500 text-xs ml-1">(142)</span>
                        </div>
                        <div class="text-lg font-bold text-primary">NGN3,299.00</div>
                    </div>
                </div>

                <!-- Product Card 3 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="relative h-48 bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1603732551681-2e91159b9dc2?auto=format&fit=crop&w=400&q=80"
                            alt="Power Bank" class="w-full h-full object-contain p-4">
                        <button
                            class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="font-medium text-gray-800 mb-1 line-clamp-2">20000mAh Power Bank with 18W PD Fast
                            Charging</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400 text-xs">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-gray-500 text-xs ml-1">(203)</span>
                        </div>
                        <div class="text-lg font-bold text-primary">NGN15,999.00</div>
                    </div>
                </div>

                <!-- Product Card 4 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="relative h-48 bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1605787020600-b9ebd5df1d07?auto=format&fit=crop&w=400&q=80"
                            alt="Car Charger" class="w-full h-full object-contain p-4">
                        <button
                            class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="font-medium text-gray-800 mb-1 line-clamp-2">36W Dual USB Car Charger with Quick
                            Charge 3.0</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400 text-xs">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-gray-500 text-xs ml-1">(56)</span>
                        </div>
                        <div class="text-lg font-bold text-primary">NGN6,750.00</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

    <script>
    // Image gallery interaction
    document.querySelectorAll('.product-gallery img').forEach(thumb => {
        thumb.addEventListener('click', () => {
            const mainImg = document.querySelector('.relative.h-96 img');
            mainImg.src = thumb.src.replace('/200/', '/800/');
        });
    });

    // Color selection
    document.querySelectorAll('.color-option').forEach(option => {
        option.addEventListener('click', () => {
            document.querySelector('.color-option.selected').classList.remove('selected');
            option.classList.add('selected');
            document.querySelector('h3 span.text-dark').textContent =
                option.classList.contains('bg-white') ? 'White' :
                option.classList.contains('bg-black') ? 'Black' : 'Blue';
        });
    });

    // Quantity adjustment
    const quantityDisplay = document.querySelector('.flex.items-center div:nth-child(2)');
    document.querySelector('.flex.items-center button:first-child').addEventListener('click', () => {
        let qty = parseInt(quantityDisplay.textContent);
        if (qty > 1) quantityDisplay.textContent = qty - 1;
    });
    document.querySelector('.flex.items-center button:last-child').addEventListener('click', () => {
        let qty = parseInt(quantityDisplay.textContent);
        if (qty < 10) quantityDisplay.textContent = qty + 1;
    });
    </script>
</body>

</html>