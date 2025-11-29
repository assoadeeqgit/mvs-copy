<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - MultiVendor Marketplace</title>
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
    .profile-nav-item.active {
        border-bottom: 2px solid #4f46e5;
        color: #4f46e5;
    }

    .order-status {
        position: relative;
        padding-left: 25px;
    }

    .order-status:before {
        content: "";
        position: absolute;
        left: 8px;
        top: 0;
        height: 100%;
        width: 2px;
        background-color: #e5e7eb;
    }

    .order-status-step {
        position: relative;
    }

    .order-status-step:before {
        content: "";
        position: absolute;
        left: -25px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: #e5e7eb;
        z-index: 1;
    }

    .order-status-step.active:before {
        background-color: #10b981;
    }

    .order-status-step.completed:before {
        background-color: #10b981;
    }

    .order-status-step.completed:after {
        content: "\f00c";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        position: absolute;
        left: -24px;
        top: 4px;
        font-size: 8px;
        color: white;
        z-index: 2;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .header-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background-color: #4f46e5;
        transition: width 0.3s ease;
    }

    .header-link:hover::after {
        width: 100%;
    }

    .breadcrumb-arrow {
        transition: transform 0.3s ease;
    }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <!-- Header Section -->
    <?php include('./include/user_profile_header.php'); ?>

    <!-- Profile Section -->
    <section class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Profile Sidebar -->
            <?php include("./include/user_profile_section.php")?>
            <!-- Profile Content -->
            <div class="md:w-3/4">
                <!-- Profile Overview -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">My Profile</h2>
                        <button class="text-primary hover:text-indigo-700"><a href="edit_user_profile.php">
                                <i class="fas fa-edit mr-1"></i> Edit Profile</a>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="font-semibold text-gray-700 mb-2">Personal Information</h3>
                            <div class="space-y-2">
                                <p><span class="text-gray-600 w-24 inline-block">Name:</span> Sarah Johnson</p>
                                <p><span class="text-gray-600 w-24 inline-block">Email:</span>
                                    sarah.johnson@example.com
                                </p>
                                <p><span class="text-gray-600 w-24 inline-block">Phone:</span> (555) 123-4567</p>
                                <p><span class="text-gray-600 w-24 inline-block">Birthday:</span> June 15, 1990</p>
                            </div>
                        </div>

                        <div>
                            <h3 class="font-semibold text-gray-700 mb-2">Account Security</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span>Password</span>
                                    <button class="text-primary text-sm hover:text-indigo-700">
                                        Change Password
                                    </button>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span>Two-Factor Authentication</span>
                                    <button class="text-primary text-sm hover:text-indigo-700">
                                        Enable
                                    </button>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span>Connected Devices</span>
                                    <button class="text-primary text-sm hover:text-indigo-700">
                                        Manage
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Recent Orders</h2>
                        <a href="#" class="text-primary hover:text-indigo-700 hover:underline">View All Orders</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-3 px-4 text-gray-600 font-semibold">Order #</th>
                                    <th class="text-left py-3 px-4 text-gray-600 font-semibold">Date</th>
                                    <th class="text-left py-3 px-4 text-gray-600 font-semibold">Items</th>
                                    <th class="text-left py-3 px-4 text-gray-600 font-semibold">Total</th>
                                    <th class="text-left py-3 px-4 text-gray-600 font-semibold">Status</th>
                                    <th class="text-left py-3 px-4 text-gray-600 font-semibold">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Order 1 -->
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="py-4 px-4">#MV-78945</td>
                                    <td class="py-4 px-4">Oct 12, 2023</td>
                                    <td class="py-4 px-4">3</td>
                                    <td class="py-4 px-4">$273.76</td>
                                    <td class="py-4 px-4">
                                        <span
                                            class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Delivered</span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <a href="#" class="text-primary hover:text-indigo-700">View</a>
                                    </td>
                                </tr>

                                <!-- Order 2 -->
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="py-4 px-4">#MV-78123</td>
                                    <td class="py-4 px-4">Oct 5, 2023</td>
                                    <td class="py-4 px-4">1</td>
                                    <td class="py-4 px-4">$129.99</td>
                                    <td class="py-4 px-4">
                                        <span
                                            class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Shipped</span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <a href="#" class="text-primary hover:text-indigo-700">View</a>
                                    </td>
                                </tr>

                                <!-- Order 3 -->
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="py-4 px-4">#MV-77654</td>
                                    <td class="py-4 px-4">Sep 28, 2023</td>
                                    <td class="py-4 px-4">2</td>
                                    <td class="py-4 px-4">$89.98</td>
                                    <td class="py-4 px-4">
                                        <span
                                            class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Processing</span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <a href="#" class="text-primary hover:text-indigo-700">View</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Order Tracking -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Track Order #MV-77654</h2>

                    <div class="order-status pl-8 space-y-8">
                        <!-- Step 1 -->
                        <div class="order-status-step completed">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex justify-between items-center mb-1">
                                    <h3 class="font-semibold">Order Placed</h3>
                                    <span class="text-sm text-gray-500">Sep 28, 2023</span>
                                </div>
                                <p class="text-gray-600 text-sm">Your order has been received</p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="order-status-step completed">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex justify-between items-center mb-1">
                                    <h3 class="font-semibold">Order Processed</h3>
                                    <span class="text-sm text-gray-500">Sep 29, 2023</span>
                                </div>
                                <p class="text-gray-600 text-sm">Seller is preparing your order</p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="order-status-step active">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex justify-between items-center mb-1">
                                    <h3 class="font-semibold">Shipped</h3>
                                    <span class="text-sm text-gray-500">Oct 2, 2023</span>
                                </div>
                                <p class="text-gray-600 text-sm">Your order is on the way</p>
                                <div class="mt-2 text-sm">
                                    <p class="font-medium">Tracking Number: <span
                                            class="text-primary">SH123456789</span></p>
                                    <p class="text-gray-600">Carrier: Standard Shipping</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="order-status-step">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex justify-between items-center mb-1">
                                    <h3 class="font-semibold">Out for Delivery</h3>
                                    <span class="text-sm text-gray-500">Estimated Oct 5, 2023</span>
                                </div>
                                <p class="text-gray-600 text-sm">Your order will arrive soon</p>
                            </div>
                        </div>

                        <!-- Step 5 -->
                        <div class="order-status-step">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex justify-between items-center mb-1">
                                    <h3 class="font-semibold">Delivered</h3>
                                    <span class="text-sm text-gray-500">Estimated Oct 5, 2023</span>
                                </div>
                                <p class="text-gray-600 text-sm">Your order will be delivered</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Saved Addresses -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Saved Addresses</h2>
                        <button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                            <i class="fas fa-plus mr-1"></i> Add New
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Address 1 -->
                        <div class="border border-gray-200 rounded-lg p-4 relative hover:border-primary transition">
                            <div class="absolute top-2 right-2 flex space-x-2">
                                <button class="text-gray-400 hover:text-primary">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-gray-400 hover:text-red-500">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <h3 class="font-semibold mb-2">Home</h3>
                            <p class="text-gray-600">Sarah Johnson</p>
                            <p class="text-gray-600">123 Main Street</p>
                            <p class="text-gray-600">Apartment 4B</p>
                            <p class="text-gray-600">New York, NY 10001</p>
                            <p class="text-gray-600">United States</p>
                            <p class="text-gray-600">Phone: (555) 123-4567</p>
                            <div class="mt-2">
                                <span
                                    class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Default</span>
                            </div>
                        </div>

                        <!-- Address 2 -->
                        <div class="border border-gray-200 rounded-lg p-4 relative hover:border-primary transition">
                            <div class="absolute top-2 right-2 flex space-x-2">
                                <button class="text-gray-400 hover:text-primary">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-gray-400 hover:text-red-500">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <h3 class="font-semibold mb-2">Work</h3>
                            <p class="text-gray-600">Sarah Johnson</p>
                            <p class="text-gray-600">456 Business Ave</p>
                            <p class="text-gray-600">Floor 10, Suite 1002</p>
                            <p class="text-gray-600">New York, NY 10005</p>
                            <p class="text-gray-600">United States</p>
                            <p class="text-gray-600">Phone: (555) 987-6543</p>
                        </div>
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

    // Profile navigation tabs
    document.addEventListener('DOMContentLoaded', function() {
        // Navigation tabs
        const navItems = document.querySelectorAll('.profile-nav-item');
        navItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                navItems.forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');

                // In a real app, you would load the appropriate content here
                // For this demo, we're just showing the first tab's content
            });
        });

        // Edit profile button
        const editProfileBtn = document.querySelector('button:contains("Edit Profile")');
        if (editProfileBtn) {
            editProfileBtn.addEventListener('click', function() {
                alert('Edit profile functionality would open a form in a real application');
            });
        }

        // Change password button
        const changePasswordBtn = document.querySelector('button:contains("Change Password")');
        if (changePasswordBtn) {
            changePasswordBtn.addEventListener('click', function() {
                alert('Change password form would appear here');
            });
        }

        // Add new address button
        const addAddressBtn = document.querySelector('button:contains("Add New")');
        if (addAddressBtn) {
            addAddressBtn.addEventListener('click', function() {
                alert('Add new address form would appear here');
            });
        }

        // Edit/delete address buttons
        document.querySelectorAll('.fa-edit').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                alert('Edit address form would appear here');
            });
        });

        document.querySelectorAll('.fa-trash').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                if (confirm('Are you sure you want to delete this address?')) {
                    this.closest('.border').remove();
                }
            });
        });

        // Set default address
        document.querySelectorAll('.border').forEach(address => {
            address.addEventListener('click', function() {
                if (!this.querySelector('.bg-green-100')) {
                    document.querySelectorAll('.bg-green-100').forEach(def => def.remove());
                    const defaultBadge = document.createElement('div');
                    defaultBadge.className = 'mt-2';
                    defaultBadge.innerHTML =
                        '<span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Default</span>';
                    this.appendChild(defaultBadge);
                }
            });
        });
    });
    </script>
</body>

</html>