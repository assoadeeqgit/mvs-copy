<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vendor Dashboard - Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        :root {
            --primary: #0a2342;
            --secondary: #f8f9fa;
            --background: #ffffff;
            --accent: #d1d5db;
        }

        .dark {
            --primary: #1e40af;
            --secondary: #1f2937;
            --background: #111827;
            --accent: #374151;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: #1f2937;
        }

        .dark body {
            color: #f3f4f6;
        }

        #sidebar {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @media (max-width: 767px) {
            #sidebar {
                transform: translateX(-100%);
                position: fixed;
                height: 100vh;
                z-index: 50;
            }

            #sidebar.active {
                transform: translateX(0);
            }
        }

        .card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 40;
        }

        .toast {
            animation: slideIn 0.3s forwards, fadeOut 0.5s forwards 2.5s;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
            }

            to {
                transform: translateX(0);
            }
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
            }
        }
    </style>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#0a2342',
                            dark: '#1e40af'
                        },
                        secondary: {
                            DEFAULT: '#f8f9fa',
                            dark: '#1f2937'
                        },
                        background: {
                            DEFAULT: '#ffffff',
                            dark: '#111827'
                        },
                        accent: {
                            DEFAULT: '#d1d5db',
                            dark: '#374151'
                        },
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-background text-gray-800 dark:text-gray-200 transition-colors duration-200">
    <!-- Skip Navigation -->
    <a href="#main-content"
        class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:bg-white focus:dark:bg-gray-800 focus:px-4 focus:py-2 focus:rounded-lg focus:z-50">Skip
        to content</a>

    <!-- Mobile Header -->
    <header
        class="md:hidden bg-primary dark:bg-primary-dark p-4 flex justify-between items-center sticky top-0 z-40 shadow-md">
        <h2 class="text-xl font-semibold text-white">Vendor Dashboard</h2>
        <div class="flex items-center space-x-4">
            <button id="darkModeToggle"
                class="text-white p-2 rounded-full focus:outline-none focus:ring-2 focus:ring-white"
                aria-label="Toggle dark mode">
                <i class="fas fa-moon dark:hidden"></i>
                <i class="fas fa-sun hidden dark:inline"></i>
            </button>
            <button id="menuToggle" class="text-white text-2xl focus:outline-none" aria-label="Toggle menu">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    <!-- Overlay for mobile menu -->
    <div id="overlay" class="overlay"></div>

    <div class="flex flex-col md:flex-row min-h-screen">
        <!-- Sidebar -->
        <?php include('../include/aside.php'); ?>

        <!-- Main Content -->
        <main id="main-content"
            class="flex-1 bg-secondary dark:bg-secondary-dark p-6 md:p-8 transition-colors duration-200">
            <div id="content">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold">Vendor Profile</h1>
                    <div class="hidden md:flex items-center space-x-4">
                        <button id="darkModeToggleDesktop"
                            class="p-2 rounded-full focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark"
                            aria-label="Toggle dark mode">
                            <i class="fas fa-moon dark:hidden text-gray-700"></i>
                            <i class="fas fa-sun hidden dark:inline text-white"></i>
                        </button>
                        <div class="relative">
                            <button id="userMenuButton"
                                class="flex items-center text-sm rounded-full focus:outline-none"
                                aria-label="User menu">
                                <img class="h-8 w-8 rounded-full"
                                    src="https://ui-avatars.com/api/?name=Vendor&background=0a2342&color=fff"
                                    alt="User avatar">
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Profile Card -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                    <!-- Profile Overview -->
                    <div class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
                        <div class="bg-primary dark:bg-primary-dark h-32 relative">
                            <div class="absolute -bottom-16 left-1/2 transform -translate-x-1/2">
                                <img class="h-32 w-32 rounded-full border-4 border-white dark:border-gray-800"
                                    src="https://ui-avatars.com/api/?name=John+Doe&background=0a2342&color=fff"
                                    alt="Profile image">
                            </div>
                        </div>
                        <div class="pt-20 pb-6 px-6 text-center">
                            <h2 class="text-xl font-bold">John Doe</h2>
                            <p class="text-gray-500 dark:text-gray-400">Tech Gadgets Inc.</p>

                            <div class="mt-6 space-y-4 text-left">
                                <div class="flex items-center">
                                    <i class="fas fa-envelope text-gray-400 dark:text-gray-300 mr-3"></i>
                                    <span>john.doe@example.com</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-phone text-gray-400 dark:text-gray-300 mr-3"></i>
                                    <span>(555) 123-4567</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt text-gray-400 dark:text-gray-300 mr-3"></i>
                                    <span>Member since Jan 2023</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-clock text-gray-400 dark:text-gray-300 mr-3"></i>
                                    <span>Last active: Just now</span>
                                </div>
                            </div>

                            <button
                                class="mt-6 w-full bg-primary dark:bg-primary-dark hover:bg-opacity-90 text-white px-4 py-2 rounded-lg transition">
                                <i class="fas fa-camera mr-2"></i> Change Photo
                            </button>
                        </div>
                    </div>

                    <!-- Profile Details -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Personal Information -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-semibold">Personal Information</h2>
                                <button
                                    class="text-primary dark:text-primary-dark font-medium hover:underline">Edit</button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Full
                                        Name</label>
                                    <div class="p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">John Doe</div>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Email</label>
                                    <div class="p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">john.doe@example.com</div>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Phone</label>
                                    <div class="p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">(555) 123-4567</div>
                                </div>
                            </div>
                        </div>

                        <!-- Business Information -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-semibold">Business Information</h2>
                                <button
                                    class="text-primary dark:text-primary-dark font-medium hover:underline">Edit</button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Business
                                        Name</label>
                                    <div class="p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">Tech Gadgets Inc.</div>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Address</label>
                                    <div class="p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">123 Business Ave, Suite 456
                                    </div>
                                </div>
                                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">City</label>
                                        <div class="p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">San Francisco</div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">State/Region</label>
                                        <div class="p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">CA</div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">ZIP
                                            Code</label>
                                        <div class="p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">94105</div>
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Country</label>
                                    <div class="p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">United States</div>
                                </div>
                            </div>
                        </div>

                        <!-- Password Update -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-semibold">Password Update</h2>
                            </div>
                            <form class="space-y-6">
                                <div>
                                    <label for="current_password"
                                        class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Current
                                        Password</label>
                                    <div class="relative">
                                        <input type="password" id="current_password"
                                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark">
                                        <i class="fas fa-lock absolute left-3 top-3 text-gray-400"></i>
                                    </div>
                                </div>
                                <div>
                                    <label for="new_password"
                                        class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">New
                                        Password</label>
                                    <div class="relative">
                                        <input type="password" id="new_password"
                                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark">
                                        <i class="fas fa-key absolute left-3 top-3 text-gray-400"></i>
                                    </div>
                                </div>
                                <div>
                                    <label for="confirm_password"
                                        class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Confirm
                                        New Password</label>
                                    <div class="relative">
                                        <input type="password" id="confirm_password"
                                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark">
                                        <i class="fas fa-key absolute left-3 top-3 text-gray-400"></i>
                                    </div>
                                </div>
                                <button type="submit"
                                    class="w-full bg-primary dark:bg-primary-dark hover:bg-opacity-90 text-white px-4 py-2 rounded-lg transition">
                                    Update Password
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Account Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold mb-6">Account Actions</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="border border-red-200 dark:border-red-800 rounded-lg p-4">
                            <h3 class="text-lg font-medium text-red-600 dark:text-red-400 mb-2">Deactivate Account</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Temporarily disable your account.
                                You can reactivate it later.</p>
                            <button
                                class="text-sm text-red-600 dark:text-red-400 font-medium hover:underline">Deactivate
                                Account</button>
                        </div>
                        <div class="border border-red-200 dark:border-red-800 rounded-lg p-4">
                            <h3 class="text-lg font-medium text-red-600 dark:text-red-400 mb-2">Delete Account</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Permanently delete your account and
                                all associated data.</p>
                            <button class="text-sm text-red-600 dark:text-red-400 font-medium hover:underline">Delete
                                Account</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Toast Notification Container -->
    <div id="toastContainer" class="fixed bottom-4 right-4 space-y-3 z-50"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Mobile menu toggle
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');

            menuToggle.addEventListener('click', function () {
                sidebar.classList.toggle('active');
                overlay.style.display = sidebar.classList.contains('active') ? 'block' : 'none';
                menuToggle.querySelector('i').classList.toggle('fa-bars');
                menuToggle.querySelector('i').classList.toggle('fa-times');
            });

            overlay.addEventListener('click', function () {
                sidebar.classList.remove('active');
                overlay.style.display = 'none';
                menuToggle.querySelector('i').classList.remove('fa-times');
                menuToggle.querySelector('i').classList.add('fa-bars');
            });

            // Dark mode toggle
            const darkModeToggle = document.getElementById('darkModeToggle');
            const darkModeToggleDesktop = document.getElementById('darkModeToggleDesktop');

            function toggleDarkMode() {
                document.documentElement.classList.toggle('dark');
                localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
            }

            darkModeToggle.addEventListener('click', toggleDarkMode);
            darkModeToggleDesktop.addEventListener('click', toggleDarkMode);

            // Check for saved preference
            if (localStorage.getItem('darkMode') === 'true' ||
                (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('darkMode', 'true');
            }

            // Toast notification function
            function showToast(message, type = 'success') {
                const toastContainer = document.getElementById('toastContainer');
                const toast = document.createElement('div');
                toast.className =
                    `toast flex items-center p-4 rounded-lg shadow-lg ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
                toast.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                <span>${message}</span>
            `;
                toastContainer.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            }

            // Example toast
            setTimeout(() => showToast('Profile loaded successfully'), 1000);

            // Form submission example
            const passwordForm = document.querySelector('form');
            if (passwordForm) {
                passwordForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const currentPass = document.getElementById('current_password').value;
                    const newPass = document.getElementById('new_password').value;
                    const confirmPass = document.getElementById('confirm_password').value;

                    if (!currentPass || !newPass || !confirmPass) {
                        showToast('Please fill all password fields', 'error');
                        return;
                    }

                    if (newPass !== confirmPass) {
                        showToast('New passwords do not match', 'error');
                        return;
                    }

                    if (newPass.length < 8) {
                        showToast('Password must be at least 8 characters', 'error');
                        return;
                    }

                    showToast('Password updated successfully');
                    passwordForm.reset();
                });
            }

            // Edit buttons functionality
            const editButtons = document.querySelectorAll('[class*="text-primary"]');
            editButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const section = this.closest('.bg-white');
                    const fields = section.querySelectorAll('.rounded-lg');

                    fields.forEach(field => {
                        if (field.tagName === 'DIV') {
                            const currentValue = field.textContent;
                            field.innerHTML = `
                            <input type="text" value="${currentValue}" 
                                   class="w-full p-2 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800">
                        `;
                        }
                    });

                    this.textContent = 'Save';
                    this.classList.remove('text-primary', 'dark:text-primary-dark');
                    this.classList.add('text-green-600', 'dark:text-green-400');
                });
            });

            // Account action buttons
            document.querySelectorAll('.border-red-200 button').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const action = this.textContent;
                    if (confirm(
                        `Are you sure you want to ${action}? This action cannot be undone.`)) {
                        showToast(`${action} request received`, 'error');
                    }
                });
            });

            // Change photo button
            document.querySelector('.fa-camera').closest('button').addEventListener('click', function (e) {
                e.preventDefault();
                // In a real app, this would open a file dialog
                showToast('Photo change functionality would be implemented here');
            });
        });
    </script>
</body>

</html>