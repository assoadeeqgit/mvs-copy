<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>Vendor Signup | MultiVendor Marketplace</title>
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
    <style>
    .form-input {
        @apply w-full px-4 py-3 border border-gray-300 rounded-lg focus: ring-2 focus:ring-primary focus:border-transparent transition;
    }

    .form-label {
        @apply block text-gray-700 font-medium mb-2 flex items-center;
    }

    .tooltip {
        @apply relative;
    }

    .tooltip:hover::after {
        @apply absolute z-10 bg-gray-800 text-white text-sm px-2 py-1 rounded shadow-lg;
        content: attr(data-tooltip);
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        margin-bottom: 8px;
    }

    .error {
        @apply border-red-500 focus: ring-red-500;
    }

    .error-message {
        @apply text-red-500 text-sm mt-1;
    }

    .progress-bar {
        @apply relative h-2 bg-gray-200 rounded-full overflow-hidden;
    }

    .progress-bar::before {
        @apply absolute h-full bg-primary transition-all duration-300;
        content: '';
    }

    .section-header {
        @apply flex justify-between items-center cursor-pointer py-3 px-4 bg-gray-100 rounded-lg hover: bg-gray-200 transition;
    }

    .animate-slide {
        @apply transition-all duration-300 ease-in-out;
    }

    .testimonial-card {
        @apply bg-white p-6 rounded-lg shadow-md text-center;
    }

    .hero-section {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
            url('https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80');
        background-size: cover;
        background-position: center;
    }

    @keyframes fadeInMenu {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeInMenu 0.3s ease-out;
    }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <!-- Header -->
    <header class="sticky top-0 z-50 bg-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center">
                <img src="https://via.placeholder.com/150x50?text=MultiVendor" alt="Logo" class="h-8">
            </div>
            <div class="hidden md:flex flex-1 mx-4">
                <form action="search-results.html" method="GET" class="relative w-full">
                    <input type="text" name="query" placeholder="Search for products..."
                        class="w-full py-3 px-6 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-base">
                    <button type="submit" class="absolute right-0 top-0 h-full px-4 text-gray-500">
                        <i class="fas fa-search text-lg"></i>
                    </button>
                </form>
            </div>
            <div class="flex items-center space-x-4">
                <a href="login.html" class="p-2 text-gray-700 hover:text-primary" title="Account">
                    <i class="fas fa-user text-lg"></i>
                </a>
                <a href="cart.html" class="p-2 text-gray-700 hover:text-primary relative" title="Cart">
                    <i class="fas fa-shopping-cart text-lg"></i>
                    <span
                        class="absolute -top-2 -right-2 bg-primary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                </a>
                <a href="wishlist.html" class="p-2 text-gray-700 hover:text-primary relative" title="Wishlist">
                    <i class="fas fa-heart text-lg"></i>
                    <span
                        class="absolute -top-2 -right-2 bg-primary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                </a>
                <button id="mobile-menu-button" class="md:hidden p-2 text-gray-700" aria-label="Toggle Menu"
                    aria-controls="mobile-menu" aria-expanded="false">
                    <i class="fas fa-bars text-lg"></i>
                </button>
            </div>
        </div>
        <!-- Desktop Navigation -->
        <nav class="hidden md:block border-t border-gray-100">
            <div class="container mx-auto px-4 py-3 flex justify-between items-center">
                <div class="flex space-x-6">
                    <a href="index.html" class="text-gray-700 hover:text-primary">Home</a>
                    <div class="relative group">
                        <button class="text-gray-700 hover:text-primary flex items-center focus:outline-none">
                            Categories <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div
                            class="absolute left-0 mt-2 w-full max-w-4xl bg-white shadow-lg rounded-md py-4 z-10 hidden group-hover:block md:animate-fade-in">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 px-6">
                                <a href="category-products.html?category=Electronics"
                                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">
                                    <i class="fas fa-laptop mr-2 text-primary"></i> Electronics
                                </a>
                                <a href="category-products.html?category=Clothing"
                                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">
                                    <i class="fas fa-tshirt mr-2 text-primary"></i> Clothing
                                </a>
                                <a href="category-products.html?category=Home & Kitchen"
                                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">
                                    <i class="fas fa-home mr-2 text-primary"></i> Home & Kitchen
                                </a>
                                <a href="category-products.html?category=Beauty"
                                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">
                                    <i class="fas fa-spa mr-2 text-primary"></i> Beauty
                                </a>
                            </div>
                        </div>
                    </div>
                    <a href="deals.html" class="text-gray-700 hover:text-primary">Deals</a>
                    <a href="vendors.html" class="text-gray-700 hover:text-primary">Vendors</a>
                </div>
                <a href="vendor-signup.html"
                    class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                    Sell With Us
                </a>
            </div>
        </nav>
        <!-- Mobile Navigation -->
        <nav id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100" aria-hidden="true">
            <div class="container mx-auto px-4 py-3 flex flex-col space-y-2">
                <a href="index.html" class="text-gray-700 hover:text-primary py-2">Home</a>
                <div class="relative">
                    <button id="mobile-categories-button"
                        class="text-gray-700 hover:text-primary flex items-center py-2 w-full text-left"
                        aria-expanded="false" aria-controls="mobile-categories-menu">
                        Categories <i class="fas fa-chevron-down ml-1 text-xs"></i>
                    </button>
                    <div id="mobile-categories-menu" class="hidden pl-4 space-y-2" aria-hidden="true">
                        <a href="category-products.html?category=Electronics"
                            class="block text-gray-700 hover:text-primary py-1">Electronics</a>
                        <a href="category-products.html?category=Clothing"
                            class="block text-gray-700 hover:text-primary py-1">Clothing</a>
                        <a href="category-products.html?category=Home & Kitchen"
                            class="block text-gray-700 hover:text-primary py1">Home & Kitchen</a>
                        <a href="category-products.html?category=Beauty"
                            class="block text-gray-700 hover:text-primary py-1">Beauty</a>
                    </div>
                </div>
                <a href="deals.html" class="text-gray-700 hover:text-primary py-2">Deals</a>
                <a href="vendors.html" class="text-gray-700 hover:text-primary py-2">Vendors</a>
                <a href="vendor-signup.html"
                    class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition inline-block">Sell
                    With Us</a>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero-section text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Become a Vendor Today</h1>
            <p class="text-xl max-w-2xl mx-auto opacity-90 mb-6">
                Unlock new markets and grow your business with MultiVendor Marketplace.
            </p>
            <a href="#signup-form"
                class="bg-white text-primary px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition">
                Start Your Application
            </a>
        </div>
    </section>

    <!-- Signup Form -->
    <section id="signup-form" class="py-12">
        <div class="container mx-auto px-4 max-w-6xl">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Form -->
                <div class="lg:w-2/3 bg-white rounded-xl shadow-md p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Vendor Application</h2>
                    <!-- Progress Bar -->
                    <div class="progress-bar mb-6" id="progress-bar" style="--progress: 25%;"></div>
                    <form id="vendor-form" class="space-y-6">
                        <!-- Business Information -->
                        <div class="form-section" data-section="1">
                            <div class="section-header" onclick="toggleSection(1)">
                                <h3 class="text-lg font-semibold text-gray-700">Business Information</h3>
                                <i class="fas fa-chevron-down transition-transform duration-300"></i>
                            </div>
                            <div class="section-content mt-4 space-y-6">
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="business-name" class="form-label">
                                            <i class="fas fa-building mr-2 text-primary"></i> Business Name*
                                        </label>
                                        <input type="text" id="business-name" class="form-input"
                                            placeholder="Your business name" required>
                                        <p class="error-message hidden">Business name is required.</p>
                                    </div>
                                    <div>
                                        <label for="business-email" class="form-label">
                                            <i class="fas fa-envelope mr-2 text-primary"></i> Business Email*
                                        </label>
                                        <input type="email" id="business-email" class="form-input"
                                            placeholder="contact@yourbusiness.com" required>
                                        <p class="error-message hidden">Valid email is required.</p>
                                    </div>
                                    <div>
                                        <label for="business-phone" class="form-label">
                                            <i class="fas fa-phone mr-2 text-primary"></i> Phone Number*
                                        </label>
                                        <input type="tel" id="business-phone" class="form-input"
                                            placeholder="+1 (555) 123-4567" required>
                                        <p class="error-message hidden">Phone number is required.</p>
                                    </div>
                                    <div class="tooltip" data-tooltip="Optional: Add your website for credibility">
                                        <label for="business-website" class="form-label">
                                            <i class="fas fa-globe mr-2 text-primary"></i> Website
                                        </label>
                                        <input type="url" id="business-website" class="form-input"
                                            placeholder="https://yourbusiness.com">
                                    </div>
                                </div>
                                <div>
                                    <label for="business-description" class="form-label">
                                        <i class="fas fa-file-alt mr-2 text-primary"></i> Business Description*
                                    </label>
                                    <textarea id="business-description" rows="3" class="form-input"
                                        placeholder="Tell us about your products/services" required></textarea>
                                    <p class="error-message hidden">Description is required.</p>
                                </div>
                            </div>
                        </div>
                        <!-- Business Address -->
                        <div class="form-section hidden" data-section="2">
                            <div class="section-header" onclick="toggleSection(2)">
                                <h3 class="text-lg font-semibold text-gray-700">Business Address</h3>
                                <i class="fas fa-chevron-down transition-transform duration-300"></i>
                            </div>
                            <div class="section-content mt-4 space-y-6 hidden">
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="address-line1" class="form-label">
                                            <i class="fas fa-map-marker-alt mr-2 text-primary"></i> Address Line 1*
                                        </label>
                                        <input type="text" id="address-line1" class="form-input"
                                            placeholder="Street address" required>
                                        <p class="error-message hidden">Address is required.</p>
                                    </div>
                                    <div class="tooltip" data-tooltip="Optional: Apartment, suite, etc.">
                                        <label for="address-line2" class="form-label">
                                            <i class="fas fa-map mr-2 text-primary"></i> Address Line 2
                                        </label>
                                        <input type="text" id="address-line2" class="form-input"
                                            placeholder="Apt, suite, etc.">
                                    </div>
                                    <div>
                                        <label for="city" class="form-label">
                                            <i class="fas fa-city mr-2 text-primary"></i> City*
                                        </label>
                                        <input type="text" id="city" class="form-input" placeholder="City" required>
                                        <p class="error-message hidden">City is required.</p>
                                    </div>
                                    <div>
                                        <label for="state" class="form-label">
                                            <i class="fas fa-map-pin mr-2 text-primary"></i> State/Province*
                                        </label>
                                        <input type="text" id="state" class="form-input" placeholder="State/Province"
                                            required>
                                        <p class="error-message hidden">State is required.</p>
                                    </div>
                                    <div>
                                        <label for="zip" class="form-label">
                                            <i class="fas fa-mail-bulk mr-2 text-primary"></i> ZIP/Postal Code*
                                        </label>
                                        <input type="text" id="zip" class="form-input" placeholder="ZIP/Postal Code"
                                            required>
                                        <p class="error-message hidden">ZIP code is required.</p>
                                    </div>
                                    <div>
                                        <label for="country" class="form-label">
                                            <i class="fas fa-globe-americas mr-2 text-primary"></i> Country*
                                        </label>
                                        <select id="country" class="form-input" required>
                                            <option value="">Select Country</option>
                                            <option value="US">United States</option>
                                            <option value="CA">Canada</option>
                                            <option value="UK">United Kingdom</option>
                                        </select>
                                        <p class="error-message hidden">Country is required.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Tax Information -->
                        <div class="form-section hidden" data-section="3">
                            <div class="section-header" onclick="toggleSection(3)">
                                <h3 class="text-lg font-semibold text-gray-700">Tax Information</h3>
                                <i class="fas fa-chevron-down transition-transform duration-300"></i>
                            </div>
                            <div class="section-content mt-4 space-y-6 hidden">
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="tax-id" class="form-label">
                                            <i class="fas fa-file-invoice-dollar mr-2 text-primary"></i> Tax ID/EIN*
                                        </label>
                                        <input type="text" id="tax-id" class="form-input"
                                            placeholder="Tax Identification Number" required>
                                        <p class="text-sm text-gray-500 mt-1">Required for payment processing</p>
                                        <p class="error-message hidden">Tax ID is required.</p>
                                    </div>
                                    <div>
                                        <label for="business-type" class="form-label">
                                            <i class="fas fa-briefcase mr-2 text-primary"></i> Business Type*
                                        </label>
                                        <select id="business-type" class="form-input" required>
                                            <option value="">Select Business Type</option>
                                            <option value="sole-proprietor">Sole Proprietor</option>
                                            <option value="llc">LLC</option>
                                            <option value="corporation">Corporation</option>
                                            <option value="partnership">Partnership</option>
                                        </select>
                                        <p class="error-message hidden">Business type is required.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Account Information -->
                        <div class="form-section hidden" data-section="4">
                            <div class="section-header" onclick="toggleSection(4)">
                                <h3 class="text-lg font-semibold text-gray-700">Account Information</h3>
                                <i class="fas fa-chevron-down transition-transform duration-300"></i>
                            </div>
                            <div class="section-content mt-4 space-y-6 hidden">
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="first-name" class="form-label">
                                            <i class="fas fa-user mr-2 text-primary"></i> First Name*
                                        </label>
                                        <input type="text" id="first-name" class="form-input"
                                            placeholder="Your first name" required>
                                        <p class="error-message hidden">First name is required.</p>
                                    </div>
                                    <div>
                                        <label for="last-name" class="form-label">
                                            <i class="fas fa-user mr-2 text-primary"></i> Last Name*
                                        </label>
                                        <input type="text" id="last-name" class="form-input"
                                            placeholder="Your last name" required>
                                        <p class="error-message hidden">Last name is required.</p>
                                    </div>
                                    <div>
                                        <label for="email" class="form-label">
                                            <i class="fas fa-envelope mr-2 text-primary"></i> Your Email*
                                        </label>
                                        <input type="email" id="email" class="form-input" placeholder="your@email.com"
                                            required>
                                        <p class="error-message hidden">Valid email is required.</p>
                                    </div>
                                    <div>
                                        <label for="phone" class="form-label">
                                            <i class="fas fa-phone mr-2 text-primary"></i> Your Phone*
                                        </label>
                                        <input type="tel" id="phone" class="form-input" placeholder="+1 (555) 123-4567"
                                            required>
                                        <p class="error-message hidden">Phone number is required.</p>
                                    </div>
                                    <div>
                                        <label for="password" class="form-label">
                                            <i class="fas fa-lock mr-2 text-primary"></i> Password*
                                        </label>
                                        <input type="password" id="password" class="form-input"
                                            placeholder="Create a password" required>
                                        <div id="password-strength" class="text-sm mt-1"></div>
                                        <p class="error-message hidden">Password is required.</p>
                                    </div>
                                    <div>
                                        <label for="confirm-password" class="form-label">
                                            <i class="fas fa-lock mr-2 text-primary"></i> Confirm Password*
                                        </label>
                                        <input type="password" id="confirm-password" class="form-input"
                                            placeholder="Confirm your password" required>
                                        <p class="error-message hidden">Passwords must match.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Terms and Submit -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="terms" name="terms" type="checkbox"
                                    class="focus:ring-primary h-4 w-4 text-primary border-gray-300 rounded" required>
                            </div>
                            <div class="ml-3">
                                <label for="terms" class="text-gray-700">
                                    I agree to the <a href="#" class="text-primary hover:underline">Terms of Service</a>
                                    and <a href="#" class="text-primary hover:underline">Privacy Policy</a>*
                                </label>
                                <p class="error-message hidden">You must agree to the terms.</p>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <button type="button" id="prev-section"
                                class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition hidden">Previous</button>
                            <button type="button" id="next-section"
                                class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Next</button>
                            <button type="submit" id="submit-form"
                                class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition hidden">Submit
                                Application</button>
                        </div>
                    </form>
                </div>
                <!-- Benefits Sidebar -->
                <div class="lg:w-1/3 lg:sticky lg:top-24">
                    <div class="bg-primary text-white p-6 rounded-xl shadow-md">
                        <h2 class="text-2xl font-bold mb-6">Why Sell With Us?</h2>
                        <ul class="space-y-5">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-xl mr-3 mt-1"></i>
                                <span>Reach thousands of active buyers</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-xl mr-3 mt-1"></i>
                                <span>Low 10% transaction fee</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-xl mr-3 mt-1"></i>
                                <span>Marketing and promotional support</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-xl mr-3 mt-1"></i>
                                <span>Secure payment processing</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-xl mr-3 mt-1"></i>
                                <span>Advanced seller analytics</span>
                            </li>
                        </ul>
                        <div class="mt-8 pt-6 border-t border-white border-opacity-30">
                            <p class="opacity-90">Already a vendor?</p>
                            <a href="vendor-login.html"
                                class="inline-block mt-2 bg-white text-primary px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition">
                                Vendor Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="bg-gray-100 py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Vendor Success Stories</h2>
            <div id="testimonial-slider" class="relative overflow-hidden">
                <div class="flex transition-transform duration-500" id="testimonial-track">
                    <!-- Testimonial 1 -->
                    <div class="testimonial-card flex-none w-full md:w-1/3 px-4">
                        <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Vendor"
                            class="w-16 h-16 rounded-full mx-auto mb-4">
                        <h3 class="font-bold text-lg mb-2">Emma Wilson</h3>
                        <div class="star-rating flex justify-center mb-2">
                            <i class="fas fa-star filled text-primary"></i>
                            <i class="fas fa-star filled text-primary"></i>
                            <i class="fas fa-star filled text-primary"></i>
                            <i class="fas fa-star filled text-primary"></i>
                            <i class="fas fa-star filled text-primary"></i>
                        </div>
                        <p class="text-gray-600">"Joining MultiVendor doubled my sales in just three months. The
                            platform is easy to use, and the support team is fantastic!"</p>
                    </div>
                    <!-- Testimonial 2 -->
                    <div class="testimonial-card flex-none w-full md:w-1/3 px-4">
                        <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="Vendor"
                            class="w-16 h-16 rounded-full mx-auto mb-4">
                        <h3 class="font-bold text-lg mb-2">James Patel</h3>
                        <div class="star-rating flex justify-center mb-2">
                            <i class="fas fa-star filled text-primary"></i>
                            <i class="fas fa-star filled text-primary"></i>
                            <i class="fas fa-star filled text-primary"></i>
                            <i class="fas fa-star filled text-primary"></i>
                            <i class="fas fa-star-half-alt filled text-primary"></i>
                        </div>
                        <p class="text-gray-600">"The analytics tools helped me optimize my listings, and the low fees
                            mean I keep more of my profits."</p>
                    </div>
                    <!-- Testimonial 3 -->
                    <div class="testimonial-card flex-none w-full md:w-1/3 px-4">
                        <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Vendor"
                            class="w-16 h-16 rounded-full mx-auto mb-4">
                        <h3 class="font-bold text-lg mb-2">Sophia Lee</h3>
                        <div class="star-rating flex justify-center mb-2">
                            <i class="fas fa-star filled text-primary"></i>
                            <i class="fas fa-star filled text-primary"></i>
                            <i class="fas fa-star filled text-primary"></i>
                            <i class="fas fa-star filled text-primary"></i>
                            <i class="fas fa-star filled text-primary"></i>
                        </div>
                        <p class="text-gray-600">"The marketing support boosted my visibility, and I love how seamless
                            the payment process is."</p>
                    </div>
                </div>
                <!-- Slider Controls -->
                <button id="prev-testimonial"
                    class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-primary text-white p-2 rounded-full hover:bg-indigo-700">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button id="next-testimonial"
                    class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-primary text-white p-2 rounded-full hover:bg-indigo-700">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="bg-primary text-white py-12">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-2xl font-bold mb-4">Stay Updated with Our Newsletter</h2>
            <p class="text-gray-200 mb-6">Get the latest deals and updates straight to your inbox.</p>
            <div class="flex justify-center">
                <input type="email" placeholder="Enter your email"
                    class="w-full max-w-md py-2 px-4 rounded-l-lg border-none focus:ring-2 focus:ring-white">
                <button class="bg-white text-primary py-2 px-6 rounded-r-lg hover:bg-gray-100">Subscribe</button>
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
                        <li><a href="index.html" class="text-gray-400 hover:text-white transition">All Products</a></li>
                        <li><a href="index.html#featured" class="text-gray-400 hover:text-white transition">Featured</a>
                        </li>
                        <li><a href="deals.html" class="text-gray-400 hover:text-white transition">Deals</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-4">Sell</h4>
                    <ul class="space-y-2">
                        <li><a href="vendor-signup.html" class="text-gray-400 hover:text-white transition">Become a
                                Vendor</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Vendor Dashboard</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Seller Resources</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-4">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="help-center.html" class="text-gray-400 hover:text-white transition">Help Center</a>
                        </li>
                        <li class="flex items-center text-gray-400"><i class="fas fa-phone mr-2"></i> (123) 456-7890
                        </li>
                        <li class="flex items-center text-gray-400"><i class="fas fa-envelope mr-2"></i> <a
                                href="mailto:support@multivendor.com"
                                class="hover:text-white transition">support@multivendor.com</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 mb-4 md:mb-0">© 2025 MultiVendor. All rights reserved.</p>
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
    <script>
    // Hamburger Menu
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileCategoriesButton = document.getElementById('mobile-categories-button');
    const mobileCategoriesMenu = document.getElementById('mobile-categories-menu');

    mobileMenuButton.addEventListener('click', () => {
        const isExpanded = mobileMenuButton.getAttribute('aria-expanded') === 'true';
        mobileMenu.classList.toggle('hidden');
        mobileMenuButton.setAttribute('aria-expanded', !isExpanded);
        mobileMenu.setAttribute('aria-hidden', isExpanded);
        mobileCategoriesMenu.classList.add('hidden');
        mobileCategoriesButton.setAttribute('aria-expanded', 'false');
        mobileCategoriesMenu.setAttribute('aria-hidden', 'true');
    });

    mobileCategoriesButton.addEventListener('click', () => {
        const isExpanded = mobileCategoriesButton.getAttribute('aria-expanded') === 'true';
        mobileCategoriesMenu.classList.toggle('hidden');
        mobileCategoriesButton.setAttribute('aria-expanded', !isExpanded);
        mobileCategoriesMenu.setAttribute('aria-hidden', isExpanded);
    });

    document.addEventListener('click', (e) => {
        if (!mobileMenuButton.contains(e.target) && !mobileMenu.contains(e.target)) {
            mobileMenu.classList.add('hidden');
            mobileMenuButton.setAttribute('aria-expanded', 'false');
            mobileMenu.setAttribute('aria-hidden', 'true');
            mobileCategoriesMenu.classList.add('hidden');
            mobileCategoriesButton.setAttribute('aria-expanded', 'false');
            mobileCategoriesMenu.setAttribute('aria-hidden', 'true');
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            mobileMenu.classList.add('hidden');
            mobileMenuButton.setAttribute('aria-expanded', 'false');
            mobileMenu.setAttribute('aria-hidden', 'true');
            mobileCategoriesMenu.classList.add('hidden');
            mobileCategoriesButton.setAttribute('aria-expanded', 'false');
            mobileCategoriesMenu.setAttribute('aria-hidden', 'true');
        }
    });

    // Form Section Toggling
    let currentSection = 1;
    const totalSections = 4;
    const progressBar = document.getElementById('progress-bar');
    const prevButton = document.getElementById('prev-section');
    const nextButton = document.getElementById('next-section');
    const submitButton = document.getElementById('submit-form');

    function toggleSection(section) {
        document.querySelectorAll('.form-section').forEach(s => {
            const content = s.querySelector('.section-content');
            const chevron = s.querySelector('.section-header i');
            if (parseInt(s.dataset.section) === section) {
                content.classList.toggle('hidden');
                chevron.classList.toggle('rotate-180');
            } else {
                content.classList.add('hidden');
                chevron.classList.remove('rotate-180');
            }
        });
        currentSection = section;
        updateProgress();
        updateButtons();
    }

    function updateProgress() {
        const progress = (currentSection / totalSections) * 100;
        progressBar.style.setProperty('--progress', `${progress}%`);
        progressBar.style.setProperty('width', `${progress}%`);
    }

    function updateButtons() {
        prevButton.classList.toggle('hidden', currentSection === 1);
        nextButton.classList.toggle('hidden', currentSection === totalSections);
        submitButton.classList.toggle('hidden', currentSection !== totalSections);
    }

    prevButton.addEventListener('click', () => {
        if (currentSection > 1) toggleSection(currentSection - 1);
    });

    nextButton.addEventListener('click', () => {
        if (validateSection(currentSection) && currentSection < totalSections) {
            toggleSection(currentSection + 1);
        }
    });

    // Form Validation
    function validateSection(section) {
        const inputs = document.querySelector(`.form-section[data-section="${section}"]`).querySelectorAll(
            'input[required], select[required], textarea[required]');
        let isValid = true;
        inputs.forEach(input => {
            const errorMessage = input.nextElementSibling?.classList.contains('error-message') ? input
                .nextElementSibling : null;
            if (!input.value.trim()) {
                input.classList.add('error');
                if (errorMessage) errorMessage.classList.remove('hidden');
                isValid = false;
            } else {
                input.classList.remove('error');
                if (errorMessage) errorMessage.classList.add('hidden');
            }
            if (input.type === 'email' && input.value && !/\S+@\S+\.\S+/.test(input.value)) {
                input.classList.add('error');
                if (errorMessage) errorMessage.textContent = 'Valid email is required.';
                errorMessage?.classList.remove('hidden');
                isValid = false;
            }
            if (input.id === 'confirm-password' && input.value !== document.getElementById('password').value) {
                input.classList.add('error');
                if (errorMessage) errorMessage.textContent = 'Passwords must match.';
                errorMessage?.classList.remove('hidden');
                isValid = false;
            }
        });
        const terms = document.getElementById('terms');
        if (section === 4 && !terms.checked) {
            terms.nextElementSibling.querySelector('.error-message').classList.remove('hidden');
            isValid = false;
        } else {
            terms.nextElementSibling.querySelector('.error-message').classList.add('hidden');
        }
        return isValid;
    }

    document.getElementById('vendor-form').addEventListener('submit', (e) => {
        e.preventDefault();
        if (validateSection(currentSection)) {
            alert('Application submitted successfully! (Simulated)');
        }
    });

    // Password Strength Meter
    document.getElementById('password').addEventListener('input', () => {
        const password = document.getElementById('password').value;
        const strengthDiv = document.getElementById('password-strength');
        let strength = 0;
        if (password.length >= 8) strength += 1;
        if (/[A-Z]/.test(password)) strength += 1;
        if (/[0-9]/.test(password)) strength += 1;
        if (/[^A-Za-z0-9]/.test(password)) strength += 1;
        switch (strength) {
            case 0:
            case 1:
                strengthDiv.textContent = 'Weak';
                strengthDiv.className = 'text-sm mt-1 text-red-500';
                break;
            case 2:
                strengthDiv.textContent = 'Moderate';
                strengthDiv.className = 'text-sm mt-1 text-yellow-500';
                break;
            case 3:
                strengthDiv.textContent = 'Strong';
                strengthDiv.className = 'text-sm mt-1 text-green-500';
                break;
            case 4:
                strengthDiv.textContent = 'Very Strong';
                strengthDiv.className = 'text-sm mt-1 text-green-700';
                break;
        }
    });

    // Testimonial Slider
    let currentTestimonial = 0;
    const testimonials = document.querySelectorAll('.testimonial-card');
    const track = document.getElementById('testimonial-track');
    const totalTestimonials = testimonials.length;

    function updateSlider() {
        const offset = currentTestimonial * -100;
        track.style.transform = `translateX(${offset}%)`;
    }

    document.getElementById('next-testimonial').addEventListener('click', () => {
        currentTestimonial = (currentTestimonial + 1) % totalTestimonials;
        updateSlider();
    });

    document.getElementById('prev-testimonial').addEventListener('click', () => {
        currentTestimonial = (currentTestimonial - 1 + totalTestimonials) % totalTestimonials;
        updateSlider();
    });

    setInterval(() => {
        currentTestimonial = (currentTestimonial + 1) % totalTestimonials;
        updateSlider();
    }, 5000);
    </script>
</body>

</html>