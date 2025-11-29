<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Signup | Your Marketplace</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    navy: '#001f3f',
                    primary: '#2563eb', // blue-600
                    'primary-dark': '#1d4ed8', // blue-700
                    'primary-light': '#3b82f6', // blue-500
                    'navy-light': '#0a2a4a',
                },
                fontFamily: {
                    sans: ['Inter', 'sans-serif'],
                },
                boxShadow: {
                    'soft': '0 4px 20px rgba(0, 0, 0, 0.08)',
                    'card': '0 2px 10px rgba(0, 31, 63, 0.1)',
                }
            }
        }
    }
    </script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    .form-input {
        @apply w-full px-4 py-3 border border-gray-300 rounded-lg focus: ring-2 focus:ring-primary focus:border-transparent transition text-gray-700;
    }

    .form-label {
        @apply block text-gray-700 font-medium mb-2 text-sm;
    }

    .btn-primary {
        @apply bg-primary hover: bg-primary-dark text-white font-medium py-3 px-6 rounded-lg transition shadow-md hover:shadow-lg;
    }

    .btn-outline {
        @apply border border-primary text-primary hover: bg-primary hover:text-white font-medium py-3 px-6 rounded-lg transition;
    }

    .section-title {
        @apply text-2xl font-bold text-gray-800 mb-1;
    }

    .section-subtitle {
        @apply text-gray-600 text-sm mb-6;
    }

    .form-section-title {
        @apply text-lg font-semibold mb-4 text-gray-700 border-b pb-2;
    }

    .benefit-card {
        @apply flex items-start p-4 bg-navy-light rounded-lg transition hover: bg-opacity-80;
    }

    .benefit-icon {
        @apply bg-primary-light bg-opacity-20 p-2 rounded-full mr-4 flex-shrink-0;
    }

    .testimonial-card {
        @apply bg-blue-900 bg-opacity-50 p-6 rounded-lg h-full flex flex-col;
    }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <!-- Navigation -->
    <nav class="bg-navy text-white py-4 shadow-md">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a href="home.php" class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-store mr-2"></i>YourMarketplace
            </a>
            <div class="space-x-6 hidden md:flex">
                <a href="home.php" class="hover:text-blue-200 transition">Home</a>
                <a href="vendors.html" class="hover:text-blue-200 transition">Vendors</a>
                <a href="#" class="hover:text-blue-200 transition">Contact</a>
                <a href="vendor-login.html" class="hover:text-blue-200 transition">Vendor Login</a>
            </div>
            <button class="md:hidden text-white">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-navy to-primary text-white py-24">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-4">Join Our Vendor Network</h1>
            <p class="text-xl max-w-2xl mx-auto opacity-90 leading-relaxed">
                Grow your business with our marketplace of thousands of active buyers
            </p>
            <div class="mt-8">
                <a href="#application"
                    class="btn-outline inline-flex items-center bg-white bg-opacity-10 hover:bg-opacity-20">
                    <i class="fas fa-arrow-down mr-2"></i> Apply Now
                </a>
            </div>
        </div>
    </section>

    <!-- Signup Form -->
    <section class="py-16" id="application">
        <div class="container mx-auto px-4 max-w-6xl">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                <div class="md:flex">
                    <!-- Benefits Column -->
                    <div class="md:w-2/5 bg-navy text-white p-10">
                        <div class="sticky top-8">
                            <h2 class="text-2xl font-bold mb-6">Why Sell With Us?</h2>
                            <ul class="space-y-4">
                                <li class="benefit-card">
                                    <div class="benefit-icon">
                                        <i class="fas fa-users text-primary text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold">Large Customer Base</h3>
                                        <p class="text-blue-100 text-sm mt-1">Access thousands of active buyers</p>
                                    </div>
                                </li>
                                <li class="benefit-card">
                                    <div class="benefit-icon">
                                        <i class="fas fa-chart-line text-primary text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold">Growth Tools</h3>
                                        <p class="text-blue-100 text-sm mt-1">Powerful analytics dashboard</p>
                                    </div>
                                </li>
                                <li class="benefit-card">
                                    <div class="benefit-icon">
                                        <i class="fas fa-truck text-primary text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold">Fulfillment Support</h3>
                                        <p class="text-blue-100 text-sm mt-1">Optional logistics solutions</p>
                                    </div>
                                </li>
                                <li class="benefit-card">
                                    <div class="benefit-icon">
                                        <i class="fas fa-shield-alt text-primary text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold">Secure Payments</h3>
                                        <p class="text-blue-100 text-sm mt-1">Guaranteed on-time payments</p>
                                    </div>
                                </li>
                            </ul>

                            <div class="mt-12 pt-6 border-t border-blue-800">
                                <h3 class="font-semibold mb-3">Already a vendor?</h3>
                                <a href="vendor-login.html"
                                    class="btn-outline inline-flex items-center w-full justify-center bg-navy-light hover:bg-opacity-80">
                                    <i class="fas fa-sign-in-alt mr-2"></i> Sign In to Your Account
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Form Column -->
                    <div class="md:w-3/5 p-10">
                        <div class="flex items-center mb-6">
                            <div class="bg-primary-light bg-opacity-20 p-3 rounded-full mr-4">
                                <i class="fas fa-store text-primary text-xl"></i>
                            </div>
                            <div>
                                <h2 class="section-title">Vendor Application</h2>
                                <p class="section-subtitle">Please complete all required fields (<span
                                        class="text-primary">*</span>)</p>
                            </div>
                        </div>

                        <form class="space-y-8">
                            <!-- Progress Steps -->
                            <div class="border-b border-gray-200">
                                <nav class="flex space-x-8">
                                    <button type="button"
                                        class="border-b-2 border-primary py-4 text-sm font-medium text-primary">
                                        Business Info
                                    </button>
                                    <button type="button"
                                        class="border-b-2 border-transparent py-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                        Products
                                    </button>
                                    <button type="button"
                                        class="border-b-2 border-transparent py-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                        Account
                                    </button>
                                </nav>
                            </div>

                            <!-- Business Information -->
                            <div>
                                <h3 class="form-section-title">Business Information</h3>
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="business-name" class="form-label">Business Name <span
                                                class="text-primary">*</span></label>
                                        <input type="text" id="business-name" class="form-input"
                                            placeholder="Your business name" required>
                                    </div>
                                    <div>
                                        <label for="business-type" class="form-label">Business Type <span
                                                class="text-primary">*</span></label>
                                        <select id="business-type" class="form-input" required>
                                            <option value="">Select Business Type</option>
                                            <option value="sole-proprietor">Sole Proprietor</option>
                                            <option value="llc">LLC</option>
                                            <option value="corporation">Corporation</option>
                                            <option value="partnership">Partnership</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="business-email" class="form-label">Business Email <span
                                                class="text-primary">*</span></label>
                                        <input type="email" id="business-email" class="form-input"
                                            placeholder="contact@yourbusiness.com" required>
                                    </div>
                                    <div>
                                        <label for="business-phone" class="form-label">Phone Number <span
                                                class="text-primary">*</span></label>
                                        <input type="tel" id="business-phone" class="form-input"
                                            placeholder="+1 (555) 123-4567" required>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <label for="business-description" class="form-label">Business Description <span
                                            class="text-primary">*</span></label>
                                    <textarea id="business-description" rows="4" class="form-input"
                                        placeholder="Tell us about your products/services (50-200 words)"
                                        required></textarea>
                                    <p class="text-xs text-gray-500 mt-1">This will appear on your public vendor profile
                                    </p>
                                </div>
                            </div>

                            <!-- Product Information -->
                            <div>
                                <h3 class="form-section-title">Product Information</h3>
                                <div>
                                    <label class="form-label">What types of products do you sell? <span
                                            class="text-primary">*</span></label>
                                    <div class="grid md:grid-cols-2 gap-4 mt-3">
                                        <label class="inline-flex items-center space-x-3">
                                            <span class="flex items-center h-5">
                                                <input type="checkbox"
                                                    class="form-checkbox h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                                            </span>
                                            <span class="text-gray-700">Physical Goods</span>
                                        </label>
                                        <label class="inline-flex items-center space-x-3">
                                            <span class="flex items-center h-5">
                                                <input type="checkbox"
                                                    class="form-checkbox h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                                            </span>
                                            <span class="text-gray-700">Digital Products</span>
                                        </label>
                                        <label class="inline-flex items-center space-x-3">
                                            <span class="flex items-center h-5">
                                                <input type="checkbox"
                                                    class="form-checkbox h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                                            </span>
                                            <span class="text-gray-700">Services</span>
                                        </label>
                                        <label class="inline-flex items-center space-x-3">
                                            <span class="flex items-center h-5">
                                                <input type="checkbox"
                                                    class="form-checkbox h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                                            </span>
                                            <span class="text-gray-700">Subscription</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <label for="product-categories" class="form-label">Main Product Category <span
                                            class="text-primary">*</span></label>
                                    <select id="product-categories" class="form-input" required>
                                        <option value="">Select Category</option>
                                        <option value="electronics">Electronics</option>
                                        <option value="fashion">Fashion</option>
                                        <option value="home-garden">Home & Garden</option>
                                        <option value="beauty">Beauty</option>
                                        <option value="sports">Sports & Outdoors</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Account Information -->
                            <div>
                                <h3 class="form-section-title">Account Information</h3>
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="first-name" class="form-label">First Name <span
                                                class="text-primary">*</span></label>
                                        <input type="text" id="first-name" class="form-input"
                                            placeholder="Your first name" required>
                                    </div>
                                    <div>
                                        <label for="last-name" class="form-label">Last Name <span
                                                class="text-primary">*</span></label>
                                        <input type="text" id="last-name" class="form-input"
                                            placeholder="Your last name" required>
                                    </div>
                                    <div>
                                        <label for="email" class="form-label">Your Email <span
                                                class="text-primary">*</span></label>
                                        <input type="email" id="email" class="form-input" placeholder="your@email.com"
                                            required>
                                    </div>
                                    <div>
                                        <label for="phone" class="form-label">Your Phone <span
                                                class="text-primary">*</span></label>
                                        <input type="tel" id="phone" class="form-input" placeholder="+1 (555) 123-4567"
                                            required>
                                    </div>
                                    <div>
                                        <label for="password" class="form-label">Password <span
                                                class="text-primary">*</span></label>
                                        <div class="relative">
                                            <input type="password" id="password" class="form-input pr-10"
                                                placeholder="Create a password" required>
                                            <button type="button"
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700">
                                                <i class="far fa-eye"></i>
                                            </button>
                                        </div>
                                        <div class="mt-2 text-xs text-gray-500">
                                            <p>• At least 8 characters</p>
                                            <p>• One uppercase letter</p>
                                            <p>• One number</p>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="confirm-password" class="form-label">Confirm Password <span
                                                class="text-primary">*</span></label>
                                        <div class="relative">
                                            <input type="password" id="confirm-password" class="form-input pr-10"
                                                placeholder="Confirm your password" required>
                                            <button type="button"
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700">
                                                <i class="far fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms and Submit -->
                            <div class="border-t border-gray-200 pt-6">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="terms" name="terms" type="checkbox"
                                            class="focus:ring-primary h-4 w-4 text-primary border-gray-300 rounded"
                                            required>
                                    </div>
                                    <div class="ml-3">
                                        <label for="terms" class="text-gray-700 text-sm">
                                            I agree to the <a href="#"
                                                class="text-primary hover:underline font-medium">Terms of
                                                Service</a> and <a href="#"
                                                class="text-primary hover:underline font-medium">Privacy
                                                Policy</a> <span class="text-primary">*</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="mt-8">
                                    <button type="submit" class="btn-primary w-full flex items-center justify-center">
                                        <i class="fas fa-paper-plane mr-2"></i> Submit Application
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="bg-gray-50 py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-3">Why Vendors Choose Us</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Join thousands of successful businesses growing with our
                    platform</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8 text-center">
                <div class="bg-white p-8 rounded-lg shadow-sm hover:shadow-md transition">
                    <div class="text-navy text-4xl font-bold mb-3 flex justify-center">
                        <span
                            class="bg-blue-100 bg-opacity-50 p-4 rounded-full w-20 h-20 flex items-center justify-center">10K+</span>
                    </div>
                    <h3 class="font-semibold text-gray-800 mt-4">Active Buyers</h3>
                    <p class="text-gray-600 mt-2 text-sm">Ready to discover your products</p>
                </div>
                <div class="bg-white p-8 rounded-lg shadow-sm hover:shadow-md transition">
                    <div class="text-primary text-4xl font-bold mb-3 flex justify-center">
                        <span
                            class="bg-blue-100 bg-opacity-50 p-4 rounded-full w-20 h-20 flex items-center justify-center">15%</span>
                    </div>
                    <h3 class="font-semibold text-gray-800 mt-4">Average Growth</h3>
                    <p class="text-gray-600 mt-2 text-sm">For new vendors in first year</p>
                </div>
                <div class="bg-white p-8 rounded-lg shadow-sm hover:shadow-md transition">
                    <div class="text-primary-dark text-4xl font-bold mb-3 flex justify-center">
                        <span
                            class="bg-blue-100 bg-opacity-50 p-4 rounded-full w-20 h-20 flex items-center justify-center">24/7</span>
                    </div>
                    <h3 class="font-semibold text-gray-800 mt-4">Vendor Support</h3>
                    <p class="text-gray-600 mt-2 text-sm">Dedicated team to help you succeed</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-16 bg-navy text-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-3">Trusted by Thousands of Vendors</h2>
            <p class="text-blue-200 text-center mb-12 max-w-2xl mx-auto">Don't just take our word for it - hear what our
                partners say about selling on our platform</p>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="testimonial-card">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                            <img src="https://randomuser.me/api/portraits/women/43.jpg" alt="Sarah J."
                                class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-semibold">Sarah J.</h4>
                            <div class="flex text-yellow-400 text-sm">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-blue-100 flex-grow">"Our sales increased by 200% in the first 6 months. The vendor
                        dashboard makes inventory management so simple."</p>
                    <div class="mt-4 pt-4 border-t border-blue-800 text-sm text-blue-200">
                        <i class="fas fa-store mr-1"></i> Home Decor Store
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Michael T."
                                class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-semibold">Michael T.</h4>
                            <div class="flex text-yellow-400 text-sm">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-blue-100 flex-grow">"The marketing tools helped us reach new customers we couldn't
                        access before. Payments always arrive on time."</p>
                    <div class="mt-4 pt-4 border-t border-blue-800 text-sm text-blue-200">
                        <i class="fas fa-store mr-1"></i> Electronics Retailer
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                            <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Priya K."
                                class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-semibold">Priya K.</h4>
                            <div class="flex text-yellow-400 text-sm">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-blue-100 flex-grow">"Customer support helped us navigate international shipping.
                        We've expanded to 3 new countries thanks to this platform."</p>
                    <div class="mt-4 pt-4 border-t border-blue-800 text-sm text-blue-200">
                        <i class="fas fa-store mr-1"></i> Fashion Boutique
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gradient-to-r from-primary to-primary-dark text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-6">Ready to Grow Your Business?</h2>
            <p class="text-xl max-w-2xl mx-auto mb-8">Join hundreds of vendors already benefiting from our marketplace
            </p>
            <a href="#application"
                class="btn-outline bg-white text-primary hover:bg-navy hover:text-white inline-flex items-center">
                <i class="fas fa-edit mr-2"></i> Start Your Application
            </a>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 max-w-4xl">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Frequently Asked Questions</h2>
            <div class="space-y-4">
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <button class="w-full flex justify-between items-center p-6 text-left hover:bg-gray-50 transition">
                        <h3 class="font-medium text-gray-800">How long does the approval process take?</h3>
                        <i class="fas fa-chevron-down text-primary transition-transform"></i>
                    </button>
                    <div class="px-6 pb-6 hidden">
                        <p class="text-gray-600">Our team typically reviews applications within 2-3 business days.
                            During peak times, it may take up to 5 business days. You'll receive an email notification
                            once your application has been reviewed.</p>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <button class="w-full flex justify-between items-center p-6 text-left hover:bg-gray-50 transition">
                        <h3 class="font-medium text-gray-800">What are the selling fees?</h3>
                        <i class="fas fa-chevron-down text-primary transition-transform"></i>
                    </button>
                    <div class="px-6 pb-6 hidden">
                        <p class="text-gray-600">We charge a competitive 10% commission on each sale. There are no
                            monthly subscription fees or hidden charges. Payment processing fees may apply depending on
                            your chosen payment method.</p>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <button class="w-full flex justify-between items-center p-6 text-left hover:bg-gray-50 transition">
                        <h3 class="font-medium text-gray-800">Can I sell internationally?</h3>
                        <i class="fas fa-chevron-down text-primary transition-transform"></i>
                    </button>
                    <div class="px-6 pb-6 hidden">
                        <p class="text-gray-600">Yes! We support international sales. You can specify which countries
                            you're willing to ship to in your vendor dashboard. We also offer optional international
                            fulfillment services.</p>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <button class="w-full flex justify-between items-center p-6 text-left hover:bg-gray-50 transition">
                        <h3 class="font-medium text-gray-800">How do I get paid?</h3>
                        <i class="fas fa-chevron-down text-primary transition-transform"></i>
                    </button>
                    <div class="px-6 pb-6 hidden">
                        <p class="text-gray-600">Payments are processed weekly via direct deposit to your bank account
                            or through PayPal. You can set your preferred payment method in your vendor account
                            settings.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-navy text-white pt-16 pb-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="font-bold text-lg mb-4 text-white flex items-center">
                        <i class="fas fa-store mr-2"></i>YourMarketplace
                    </h3>
                    <p class="text-blue-200 mb-4">Connecting quality vendors with customers worldwide.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-blue-200 hover:text-white transition"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-blue-200 hover:text-white transition"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-blue-200 hover:text-white transition"><i
                                class="fab fa-instagram"></i></a>
                        <a href="#" class="text-blue-200 hover:text-white transition"><i
                                class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-4 text-white">For Vendors</h3>
                    <ul class="space-y-2">
                        <li><a href="vendor-signup.html" class="text-blue-200 hover:text-white transition">Apply to
                                Sell</a></li>
                        <li><a href="vendor-login.html" class="text-blue-200 hover:text-white transition">Vendor
                                Login</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white transition">Seller Resources</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white transition">Fee Structure</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-4 text-white">Support</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-blue-200 hover:text-white transition">Help Center</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white transition">Contact Us</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white transition">Shipping Policy</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white transition">Returns</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-4 text-white">Legal</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-blue-200 hover:text-white transition">Terms of Service</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white transition">Vendor Agreement</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white transition">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-blue-800 mt-12 pt-8 text-center text-blue-300 text-sm">
                <p>&copy; 2023 YourMarketplace. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
    // Simple FAQ toggle functionality
    document.querySelectorAll('.border-gray-200 button').forEach(button => {
        button.addEventListener('click', () => {
            const content = button.nextElementSibling;
            const icon = button.querySelector('i');

            content.classList.toggle('hidden');
            icon.classList.toggle('transform');
            icon.classList.toggle('rotate-180');
        });
    });
    </script>
</body>

</html>