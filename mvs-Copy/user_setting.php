<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings - MultiVendor Marketplace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    .settings-tab {
        transition: all 0.3s;
    }

    .settings-tab.active {
        border-bottom: 3px solid #4f46e5;
        color: #4f46e5;
    }

    .settings-content {
        display: none;
    }

    .settings-content.active {
        display: block;
        animation: fadeIn 0.5s;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .toggle-checkbox:checked {
        right: 0;
        border-color: #4f46e5;
    }

    .toggle-checkbox:checked+.toggle-label {
        background-color: #4f46e5;
    }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <!-- Header Section -->
    <?php include('./include/user_profile_header.php');?>

    <!-- Account Settings Section -->
    <section class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar Navigation -->
            <?php include('./include/user_profile_section.php'); ?>
            <!-- Main Content -->
            <div class="md:w-3/4">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Settings Tabs -->
                    <div class="border-b border-gray-200">
                        <nav class="flex overflow-x-auto">
                            <button class="settings-tab active px-6 py-4 font-medium text-sm whitespace-nowrap"
                                data-tab="general">
                                <i class="fas fa-cog mr-2"></i> General
                            </button>
                            <button class="settings-tab px-6 py-4 font-medium text-sm whitespace-nowrap"
                                data-tab="privacy">
                                <i class="fas fa-shield-alt mr-2"></i> Privacy
                            </button>
                            <button class="settings-tab px-6 py-4 font-medium text-sm whitespace-nowrap"
                                data-tab="notifications">
                                <i class="fas fa-bell mr-2"></i> Notifications
                            </button>
                            <button class="settings-tab px-6 py-4 font-medium text-sm whitespace-nowrap"
                                data-tab="security">
                                <i class="fas fa-lock mr-2"></i> Security
                            </button>
                            <button class="settings-tab px-6 py-4 font-medium text-sm whitespace-nowrap"
                                data-tab="payments">
                                <i class="fas fa-credit-card mr-2"></i> Payments
                            </button>
                        </nav>
                    </div>

                    <!-- Settings Content -->
                    <div class="p-6">
                        <!-- General Settings -->
                        <div id="general-settings" class="settings-content active">
                            <h3 class="text-xl font-bold text-gray-800 mb-6">General Account Settings</h3>

                            <div class="space-y-6">
                                <!-- Language Preference -->
                                <div
                                    class="flex flex-col md:flex-row md:items-center justify-between border-b border-gray-100 pb-6">
                                    <div class="mb-3 md:mb-0">
                                        <h4 class="font-medium text-gray-800">Language Preference</h4>
                                        <p class="text-gray-500 text-sm">Change your preferred language</p>
                                    </div>
                                    <select
                                        class="form-select w-full md:w-48 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                                        <option>English</option>
                                        <option>Spanish</option>
                                        <option>French</option>
                                        <option>German</option>
                                    </select>
                                </div>

                                <!-- Theme Preference -->
                                <div
                                    class="flex flex-col md:flex-row md:items-center justify-between border-b border-gray-100 pb-6">
                                    <div class="mb-3 md:mb-0">
                                        <h4 class="font-medium text-gray-800">Theme</h4>
                                        <p class="text-gray-500 text-sm">Light or Dark mode</p>
                                    </div>
                                    <div class="flex space-x-4">
                                        <button class="px-4 py-2 border border-gray-300 rounded-lg flex items-center">
                                            <i class="far fa-sun mr-2"></i> Light
                                        </button>
                                        <button class="px-4 py-2 bg-gray-800 text-white rounded-lg flex items-center">
                                            <i class="far fa-moon mr-2"></i> Dark
                                        </button>
                                    </div>
                                </div>

                                <!-- Account Visibility -->
                                <!-- <div
                                    class="flex flex-col md:flex-row md:items-center justify-between border-b border-gray-100 pb-6">
                                    <div class="mb-3 md:mb-0">
                                        <h4 class="font-medium text-gray-800">Account Visibility</h4>
                                        <p class="text-gray-500 text-sm">Make your profile public or private</p>
                                    </div>
                                    <div class="relative inline-block w-12 mr-2 align-middle select-none">
                                        <input type="checkbox" id="visibility-toggle"
                                            class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                                        <label for="visibility-toggle"
                                            class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                    </div>
                                    <span class="text-sm text-gray-700">Private</span>
                                </div> -->

                                <!-- Delete Account -->
                                <div class="pt-4">
                                    <button class="text-red-500 hover:text-red-700 font-medium flex items-center">
                                        <i class="fas fa-trash-alt mr-2"></i> Delete Account
                                    </button>
                                    <p class="text-gray-500 text-sm mt-1">Permanently remove your account and all
                                        data
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Privacy Settings -->
                        <div id="privacy-settings" class="settings-content">
                            <h3 class="text-xl font-bold text-gray-800 mb-6">Privacy Settings</h3>

                            <div class="space-y-6">
                                <!-- Data Sharing -->
                                <div class="border-b border-gray-100 pb-6">
                                    <h4 class="font-medium text-gray-800 mb-3">Data Sharing Preferences</h4>
                                    <div class="space-y-3">
                                        <div class="flex items-center">
                                            <input type="checkbox" id="share-analytics"
                                                class="form-checkbox h-5 w-5 text-primary rounded focus:ring-primary">
                                            <label for="share-analytics" class="ml-2 text-gray-700">Share analytics
                                                data
                                                to improve services</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" id="share-marketing"
                                                class="form-checkbox h-5 w-5 text-primary rounded focus:ring-primary"
                                                checked>
                                            <label for="share-marketing" class="ml-2 text-gray-700">Receive
                                                marketing
                                                communications</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" id="share-vendors"
                                                class="form-checkbox h-5 w-5 text-primary rounded focus:ring-primary">
                                            <label for="share-vendors" class="ml-2 text-gray-700">Allow vendors to
                                                contact me about purchases</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Search Visibility -->
                                <div class="border-b border-gray-100 pb-6">
                                    <h4 class="font-medium text-gray-800 mb-3">Search Visibility</h4>
                                    <div class="space-y-3">
                                        <div class="flex items-center">
                                            <input type="checkbox" id="search-index"
                                                class="form-checkbox h-5 w-5 text-primary rounded focus:ring-primary"
                                                checked>
                                            <label for="search-index" class="ml-2 text-gray-700">Include my profile
                                                in
                                                search results</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" id="search-engines"
                                                class="form-checkbox h-5 w-5 text-primary rounded focus:ring-primary">
                                            <label for="search-engines" class="ml-2 text-gray-700">Allow search
                                                engines
                                                to index my profile</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Activity Privacy -->
                                <div class="pt-2">
                                    <h4 class="font-medium text-gray-800 mb-3">Activity Privacy</h4>
                                    <div class="space-y-3">
                                        <div class="flex items-center">
                                            <input type="radio" id="activity-public" name="activity-privacy"
                                                class="form-radio h-5 w-5 text-primary" checked>
                                            <label for="activity-public" class="ml-2 text-gray-700">Show my activity
                                                publicly</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="activity-followers" name="activity-privacy"
                                                class="form-radio h-5 w-5 text-primary">
                                            <label for="activity-followers" class="ml-2 text-gray-700">Show activity
                                                to
                                                followers only</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="activity-private" name="activity-privacy"
                                                class="form-radio h-5 w-5 text-primary">
                                            <label for="activity-private" class="ml-2 text-gray-700">Keep all
                                                activity
                                                private</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notification Settings -->
                        <div id="notification-settings" class="settings-content">
                            <h3 class="text-xl font-bold text-gray-800 mb-6">Notification Preferences</h3>

                            <div class="space-y-6">
                                <!-- Email Notifications -->
                                <div class="border-b border-gray-100 pb-6">
                                    <h4 class="font-medium text-gray-800 mb-3">Email Notifications</h4>
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <label class="text-gray-700">Order updates</label>
                                                <p class="text-gray-500 text-sm">Shipping notifications, order
                                                    confirmations</p>
                                            </div>
                                            <div class="relative inline-block w-12 align-middle select-none">
                                                <input type="checkbox" checked
                                                    class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                                                <label
                                                    class="toggle-label block overflow-hidden h-6 rounded-full bg-primary cursor-pointer"></label>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <label class="text-gray-700">Promotional offers</label>
                                                <p class="text-gray-500 text-sm">Discounts, special deals from
                                                    vendors
                                                </p>
                                            </div>
                                            <div class="relative inline-block w-12 align-middle select-none">
                                                <input type="checkbox"
                                                    class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                                                <label
                                                    class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <label class="text-gray-700">Account security</label>
                                                <p class="text-gray-500 text-sm">Login alerts, password changes</p>
                                            </div>
                                            <div class="relative inline-block w-12 align-middle select-none">
                                                <input type="checkbox" checked
                                                    class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                                                <label
                                                    class="toggle-label block overflow-hidden h-6 rounded-full bg-primary cursor-pointer"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Push Notifications -->
                                <div class="border-b border-gray-100 pb-6">
                                    <h4 class="font-medium text-gray-800 mb-3">Push Notifications</h4>
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between">
                                            <label class="text-gray-700">Order updates</label>
                                            <div class="relative inline-block w-12 align-middle select-none">
                                                <input type="checkbox" checked
                                                    class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                                                <label
                                                    class="toggle-label block overflow-hidden h-6 rounded-full bg-primary cursor-pointer"></label>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <label class="text-gray-700">Messages from vendors</label>
                                            <div class="relative inline-block w-12 align-middle select-none">
                                                <input type="checkbox"
                                                    class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                                                <label
                                                    class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <label class="text-gray-700">Price drop alerts</label>
                                            <div class="relative inline-block w-12 align-middle select-none">
                                                <input type="checkbox" checked
                                                    class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                                                <label
                                                    class="toggle-label block overflow-hidden h-6 rounded-full bg-primary cursor-pointer"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- SMS Notifications -->
                                <div class="pt-2">
                                    <h4 class="font-medium text-gray-800 mb-3">SMS Notifications</h4>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <label class="text-gray-700">Enable SMS alerts</label>
                                            <p class="text-gray-500 text-sm">Standard messaging rates may apply</p>
                                        </div>
                                        <div class="relative inline-block w-12 align-middle select-none">
                                            <input type="checkbox"
                                                class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                                            <label
                                                class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Settings -->
                        <div id="security-settings" class="settings-content">
                            <h3 class="text-xl font-bold text-gray-800 mb-6">Security Settings</h3>

                            <div class="space-y-6">
                                <!-- Two-Factor Authentication -->
                                <div class="border-b border-gray-100 pb-6">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="font-medium text-gray-800">Two-Factor Authentication</h4>
                                            <p class="text-gray-500 text-sm">Add an extra layer of security to your
                                                account</p>
                                        </div>
                                        <button
                                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-indigo-700 transition">
                                            Enable 2FA
                                        </button>
                                    </div>
                                </div>

                                <!-- Active Sessions -->
                                <div class="border-b border-gray-100 pb-6">
                                    <h4 class="font-medium text-gray-800 mb-3">Active Sessions</h4>
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div class="flex items-center">
                                                <i class="fas fa-desktop text-gray-500 mr-3 text-xl"></i>
                                                <div>
                                                    <p class="font-medium">Windows 10 • Chrome</p>
                                                    <p class="text-gray-500 text-sm">New York, US • Current session
                                                    </p>
                                                </div>
                                            </div>
                                            <button class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-sign-out-alt"></i>
                                            </button>
                                        </div>
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div class="flex items-center">
                                                <i class="fas fa-mobile-alt text-gray-500 mr-3 text-xl"></i>
                                                <div>
                                                    <p class="font-medium">iPhone 13 • Safari</p>
                                                    <p class="text-gray-500 text-sm">Los Angeles, US • 2 days ago
                                                    </p>
                                                </div>
                                            </div>
                                            <button class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-sign-out-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Password Update -->
                                <div class="pt-2">
                                    <h4 class="font-medium text-gray-800 mb-3">Password</h4>
                                    <p class="text-gray-500 text-sm mb-4">Last changed 3 months ago</p>
                                    <button
                                        class="px-4 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition">
                                        Change Password
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Settings -->
                        <div id="payment-settings" class="settings-content">
                            <h3 class="text-xl font-bold text-gray-800 mb-6">Payment Methods</h3>

                            <div class="space-y-6">
                                <!-- Saved Cards -->
                                <div class="border-b border-gray-100 pb-6">
                                    <h4 class="font-medium text-gray-800 mb-3">Saved Payment Methods</h4>
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div class="flex items-center">
                                                <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/visa/visa-original.svg"
                                                    class="w-8 h-8 mr-3" alt="Visa">
                                                <div>
                                                    <p class="font-medium">Visa •••• 4242</p>
                                                    <p class="text-gray-500 text-sm">Expires 05/2025</p>
                                                </div>
                                            </div>
                                            <button class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div class="flex items-center">
                                                <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mastercard/mastercard-original.svg"
                                                    class="w-8 h-8 mr-3" alt="Mastercard">
                                                <div>
                                                    <p class="font-medium">Mastercard •••• 5555</p>
                                                    <p class="text-gray-500 text-sm">Expires 11/2024</p>
                                                </div>
                                            </div>
                                            <button class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <button
                                        class="mt-4 px-4 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition">
                                        <i class="fas fa-plus mr-2"></i> Add New Card
                                    </button>
                                </div>

                                <!-- Payment Preferences -->
                                <div class="border-b border-gray-100 pb-6">
                                    <h4 class="font-medium text-gray-800 mb-3">Payment Preferences</h4>
                                    <div class="space-y-3">
                                        <div class="flex items-center">
                                            <input type="checkbox" id="save-cards"
                                                class="form-checkbox h-5 w-5 text-primary rounded focus:ring-primary"
                                                checked>
                                            <label for="save-cards" class="ml-2 text-gray-700">Save payment methods
                                                for
                                                future purchases</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" id="auto-reload"
                                                class="form-checkbox h-5 w-5 text-primary rounded focus:ring-primary">
                                            <label for="auto-reload" class="ml-2 text-gray-700">Enable auto-reload
                                                for
                                                wallet balance</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Billing History -->
                                <div class="pt-2">
                                    <h4 class="font-medium text-gray-800 mb-3">Billing History</h4>
                                    <p class="text-gray-500 text-sm mb-4">View and download your past invoices</p>
                                    <button
                                        class="px-4 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition">
                                        View Billing History
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-12">
        <div class="container mx-auto px-4">
            <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 mb-4 md:mb-0">© 2023 MultiVendor. All rights reserved.</p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script>
    // Tab switching functionality
    document.querySelectorAll('.settings-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            // Remove active class from all tabs and content
            document.querySelectorAll('.settings-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.settings-content').forEach(c => c.classList.remove('active'));

            // Add active class to clicked tab
            tab.classList.add('active');

            // Show corresponding content
            const tabId = tab.getAttribute('data-tab');
            document.getElementById(`${tabId}-settings`).classList.add('active');
        });
    });

    // Toggle switch functionality
    document.querySelectorAll('.toggle-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const label = this.nextElementSibling;
            if (this.checked) {
                label.classList.remove('bg-gray-300');
                label.classList.add('bg-primary');
            } else {
                label.classList.remove('bg-primary');
                label.classList.add('bg-gray-300');
            }
        });
    });
    </script>
</body>

</html>