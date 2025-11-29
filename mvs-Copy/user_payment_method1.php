<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Methods - MultiVendor Marketplace</title>
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
                    'float': 'float 6s ease-in-out infinite',
                },
                keyframes: {
                    float: {
                        '0%, 100%': {
                            transform: 'translateY(0)'
                        },
                        '50%': {
                            transform: 'translateY(-10px)'
                        }
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

    .form-input {
        border: 1px solid #e5e7eb;
        padding: 0.5rem;
        border-radius: 0.375rem;
        width: 100%;
    }

    .form-input:focus {
        outline: none;
        box-shadow: 0 0 0 2px #4f46e5;
        border-color: transparent;
    }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <!-- Header Section -->
    <header class="bg-white shadow sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <img src="https://via.placeholder.com/150x50?text=MultiVendor" alt="Logo" class="h-8">
            </div>
            <nav class="hidden md:flex space-x-8">
                <a href="home.html" class="text-gray-600 hover:text-primary relative header-link">Home</a>
                <a href="home.html#categories"
                    class="text-gray-600 hover:text-primary relative header-link">Categories</a>
                <a href="deals.html" class="text-gray-600 hover:text-primary relative header-link">Deals</a>
                <a href="cart.html" class="text-gray-600 hover:text-primary relative header-link">Cart</a>
            </nav>
            <div class="flex items-center space-x-4">
                <a href="user_profile.html" class="text-gray-600 hover:text-primary"><i class="fas fa-user"></i></a>
                <a href="logout.html" class="text-gray-600 hover:text-red-500"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </div>
    </header>

    <!-- Payment Methods Section -->
    <section class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Profile Sidebar -->
            <div class="md:w-1/4">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4" data-aos="fade-up">
                    <div class="flex flex-col items-center mb-6">
                        <div class="relative mb-4">
                            <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80"
                                alt="Profile" class="w-24 h-24 rounded-full object-cover">
                            <button
                                class="absolute bottom-0 right-0 bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition">
                                <i class="fas fa-camera text-sm"></i>
                            </button>
                        </div>
                        <h2 class="font-bold text-xl">John Doe</h2>
                        <p class="text-gray-600 text-sm">Member since June 2022</p>
                    </div>

                    <nav class="space-y-2">
                        <a href="user_profile.html"
                            class="profile-nav-item block px-4 py-2 text-gray-700 hover:text-primary transition flex items-center">
                            <i class="fas fa-user-circle mr-3"></i> My Profile
                        </a>
                        <a href="orders.html"
                            class="profile-nav-item block px-4 py-2 text-gray-700 hover:text-primary transition flex items-center">
                            <i class="fas fa-shopping-bag mr-3"></i> My Orders
                        </a>
                        <a href="wishlist.html"
                            class="profile-nav-item block px-4 py-2 text-gray-700 hover:text-primary transition flex items-center">
                            <i class="fas fa-heart mr-3"></i> Wishlist
                        </a>
                        <a href="user_address.html"
                            class="profile-nav-item block px-4 py-2 text-gray-700 hover:text-primary transition flex items-center">
                            <i class="fas fa-map-marker-alt mr-3"></i> Addresses
                        </a>
                        <a href="user_payment.html"
                            class="profile-nav-item active block px-4 py-2 text-gray-700 hover:text-primary transition flex items-center">
                            <i class="fas fa-credit-card mr-3"></i> Payment Methods
                        </a>
                        <a href="notifications.html"
                            class="profile-nav-item block px-4 py-2 text-gray-700 hover:text-primary transition flex items-center">
                            <i class="fas fa-bell mr-3"></i> Notifications
                        </a>
                        <a href="user_setting.html"
                            class="profile-nav-item block px-4 py-2 text-gray-700 hover:text-primary transition flex items-center">
                            <i class="fas fa-cog mr-3"></i> Account Settings
                        </a>
                        <a href="logout.html"
                            class="block px-4 py-2 text-red-500 hover:text-red-700 transition flex items-center">
                            <i class="fas fa-sign-out-alt mr-3"></i> Logout
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Payment Methods Content -->
            <div class="md:w-3/4">
                <div class="bg-white rounded-lg shadow-md p-6" data-aos="fade-up">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Payment Methods</h2>
                        <button id="add-payment-btn"
                            class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                            <i class="fas fa-plus mr-1"></i> Add New
                        </button>
                    </div>

                    <div id="message" class="hidden mb-6 px-4 py-3 rounded"></div>

                    <!-- Saved Payment Methods -->
                    <div id="payment-methods" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Populated by JavaScript -->
                    </div>

                    <!-- Add/Edit Payment Form -->
                    <div id="payment-form" class="hidden mt-6 p-6 bg-gray-50 rounded-lg">
                        <h3 class="text-xl font-bold text-gray-800 mb-4" id="form-title">Add Payment Method</h3>
                        <form id="payment-form-inner">
                            <input type="hidden" id="payment_method_id">
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-1">Payment Type</label>
                                <select id="payment_type" class="form-input" required>
                                    <option value="card">Credit/Debit Card</option>
                                    <option value="paypal">PayPal</option>
                                </select>
                            </div>

                            <!-- Card Fields -->
                            <div id="card-fields" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="card_type" class="block text-gray-700 mb-1">Card Type</label>
                                    <select id="card_type" class="form-input">
                                        <option value="Visa">Visa</option>
                                        <option value="Mastercard">Mastercard</option>
                                        <option value="Amex">American Express</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="card_number" class="block text-gray-700 mb-1">Card Number</label>
                                    <input type="text" id="card_number" class="form-input"
                                        placeholder="1234 5678 9012 3456">
                                </div>
                                <div>
                                    <label for="cardholder_name" class="block text-gray-700 mb-1">Cardholder
                                        Name</label>
                                    <input type="text" id="cardholder_name" class="form-input">
                                </div>
                                <div>
                                    <label for="expiry" class="block text-gray-700 mb-1">Expiry (MM/YY)</label>
                                    <input type="text" id="expiry" class="form-input" placeholder="MM/YY">
                                </div>
                                <div>
                                    <label for="cvc" class="block text-gray-700 mb-1">CVC</label>
                                    <input type="text" id="cvc" class="form-input" placeholder="123">
                                </div>
                            </div>

                            <!-- PayPal Fields -->
                            <div id="paypal-fields" class="hidden">
                                <div class="mb-4">
                                    <label for="paypal_email" class="block text-gray-700 mb-1">PayPal Email</label>
                                    <input type="email" id="paypal_email" class="form-input"
                                        placeholder="example@paypal.com">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" id="is_default" class="mr-2">
                                    <span>Set as default payment method</span>
                                </label>
                            </div>

                            <div class="flex space-x-4">
                                <button type="submit"
                                    class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                                    Save Payment Method
                                </button>
                                <button type="button" id="cancel-form"
                                    class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
                                    Cancel
                                </button>
                            </div>
                        </form>
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
                        <li><a href="home.html" class="text-gray-400 hover:text-white transition">All Products</a></li>
                        <li><a href="home.html#featured" class="text-gray-400 hover:text-white transition">Featured</a>
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
                        <li class="flex items-center text-gray-400"><i class="fas fa-envelope mr-2"></i>
                            <a href="mailto:support@multivendor.com"
                                class="hover:text-white transition">support@multivendor.com</a>
                        </li>
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
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });

    // Mock payment methods storage in localStorage
    const initialMethods = [{
            id: 1,
            type: 'card',
            details: {
                card_type: 'Visa',
                last_four: '1234',
                expiry: '12/26',
                cardholder_name: 'John Doe'
            },
            is_default: true
        },
        {
            id: 2,
            type: 'card',
            details: {
                card_type: 'Mastercard',
                last_four: '5678',
                expiry: '06/25',
                cardholder_name: 'John Doe'
            },
            is_default: false
        },
        {
            id: 3,
            type: 'paypal',
            details: {
                email: 'john.doe@example.com'
            },
            is_default: false
        }
    ];

    // Initialize localStorage if empty
    if (!localStorage.getItem('payment_methods')) {
        localStorage.setItem('payment_methods', JSON.stringify(initialMethods));
    }

    document.addEventListener('DOMContentLoaded', function() {
        const paymentForm = document.getElementById('payment-form');
        const paymentFormInner = document.getElementById('payment-form-inner');
        const paymentType = document.getElementById('payment_type');
        const cardFields = document.getElementById('card-fields');
        const paypalFields = document.getElementById('paypal-fields');
        const addPaymentBtn = document.getElementById('add-payment-btn');
        const cancelFormBtn = document.getElementById('cancel-form');
        const formTitle = document.getElementById('form-title');
        const paymentMethodIdInput = document.getElementById('payment_method_id');
        const paymentMethodsContainer = document.getElementById('payment-methods');
        const messageDiv = document.getElementById('message');

        // Load payment methods
        function loadPaymentMethods() {
            const methods = JSON.parse(localStorage.getItem('payment_methods') || '[]');
            paymentMethodsContainer.innerHTML = methods.length === 0 ?
                '<p class="text-gray-600 col-span-2">No payment methods saved.</p>' :
                methods.map(method => `
                        <div class="border border-gray-200 rounded-lg p-4 relative hover:border-primary transition">
                            <div class="absolute top-2 right-2 flex space-x-2">
                                <button class="text-gray-400 hover:text-primary edit-payment-btn" data-id="${method.id}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-gray-400 hover:text-red-500 delete-payment-btn" data-id="${method.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="flex items-center mb-2">
                                ${method.type === 'card'
                                    ? `<img src="https://via.placeholder.com/40x25?text=${method.details.card_type}" alt="${method.details.card_type}" class="h-6 mr-2">
                                       <h3 class="font-semibold">${method.details.card_type} ending in ${method.details.last_four}</h3>`
                                    : `<img src="https://via.placeholder.com/40x25?text=PayPal" alt="PayPal" class="h-6 mr-2">
                                       <h3 class="font-semibold">PayPal</h3>`}
                            </div>
                            ${method.type === 'card'
                                ? `<p class="text-gray-600">Cardholder: ${method.details.cardholder_name}</p>
                                   <p class="text-gray-600">Expires: ${method.details.expiry}</p>`
                                : `<p class="text-gray-600">Email: ${method.details.email}</p>`}
                            <div class="mt-2">
                                ${method.is_default
                                    ? '<span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Default</span>'
                                    : `<button class="text-primary hover:text-indigo-700 text-sm set-default-btn" data-id="${method.id}">Set as Default</button>`}
                            </div>
                        </div>
                    `).join('');

            // Attach event listeners
            document.querySelectorAll('.edit-payment-btn').forEach(btn => {
                btn.addEventListener('click', () => editPaymentMethod(btn.dataset.id));
            });
            document.querySelectorAll('.delete-payment-btn').forEach(btn => {
                btn.addEventListener('click', () => deletePaymentMethod(btn.dataset.id));
            });
            document.querySelectorAll('.set-default-btn').forEach(btn => {
                btn.addEventListener('click', () => setDefaultPaymentMethod(btn.dataset.id));
            });
        }

        // Toggle payment fields
        function togglePaymentFields() {
            if (paymentType.value === 'card') {
                cardFields.classList.remove('hidden');
                paypalFields.classList.add('hidden');
                cardFields.querySelectorAll('input, select').forEach(input => input.required = true);
                paypalFields.querySelectorAll('input').forEach(input => input.required = false);
            } else {
                cardFields.classList.add('hidden');
                paypalFields.classList.remove('hidden');
                cardFields.querySelectorAll('input, select').forEach(input => input.required = false);
                paypalFields.querySelectorAll('input').forEach(input => input.required = true);
            }
        }

        paymentType.addEventListener('change', togglePaymentFields);

        // Show/hide form
        addPaymentBtn.addEventListener('click', () => {
            paymentForm.classList.remove('hidden');
            formTitle.textContent = 'Add Payment Method';
            paymentMethodIdInput.value = '';
            paymentFormInner.reset();
            togglePaymentFields();
        });

        cancelFormBtn.addEventListener('click', () => {
            paymentForm.classList.add('hidden');
            paymentFormInner.reset();
            messageDiv.classList.add('hidden');
        });

        // Form submission
        paymentFormInner.addEventListener('submit', (e) => {
            e.preventDefault();
            let isValid = true;
            const requiredFields = paymentFormInner.querySelectorAll(
                'input[required], select[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            if (paymentType.value === 'card') {
                const cardNumber = document.getElementById('card_number').value;
                const expiry = document.getElementById('expiry').value;
                const cvc = document.getElementById('cvc').value;
                if (!/^\d{4}$/.test(cardNumber.replace(/\D/g, '').slice(-4))) {
                    isValid = false;
                    document.getElementById('card_number').classList.add('border-red-500');
                }
                if (!/^(0[1-9]|1[0-2])\/[0-9]{2}$/.test(expiry)) {
                    isValid = false;
                    document.getElementById('expiry').ClassList.add('border-red-500');
                }
                if (!/^\d{3,4}$/.test(cvc)) {
                    isValid = false;
                    document.getElementById('cvc').classList.add('border-red-500');
                }
            } else {
                const paypalEmail = document.getElementById('paypal_email').value;
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(paypalEmail)) {
                    isValid = false;
                    document.getElementById('paypal_email').classList.add('border-red-500');
                }
            }

            if (!isValid) {
                showMessage('Please fill in all required fields correctly.', 'error');
                return;
            }

            const methods = JSON.parse(localStorage.getItem('payment_methods') || '[]');
            const isDefault = document.getElementById('is_default').checked;
            const methodId = paymentMethodIdInput.value || Date.now().toString();

            const newMethod = {
                id: methodId,
                type: paymentType.value,
                details: paymentType.value === 'card' ? {
                    card_type: document.getElementById('card_type').value,
                    last_four: document.getElementById('card_number').value.replace(/\D/g, '')
                        .slice(-4),
                    expiry: document.getElementById('expiry').value,
                    cardholder_name: document.getElementById('cardholder_name').value
                } : {
                    email: document.getElementById('paypal_email').value
                },
                is_default: isDefault
            };

            if (isDefault) {
                methods.forEach(m => m.is_default = false);
            }

            if (paymentMethodIdInput.value) {
                const index = methods.findIndex(m => m.id === methodId);
                methods[index] = newMethod;
            } else {
                methods.push(newMethod);
            }

            localStorage.setItem('payment_methods', JSON.stringify(methods));
            paymentForm.classList.add('hidden');
            paymentFormInner.reset();
            showMessage('Payment method saved successfully.', 'success');
            loadPaymentMethods();
        });

        // Edit payment method
        function editPaymentMethod(id) {
            const methods = JSON.parse(localStorage.getItem('payment_methods') || '[]');
            const method = methods.find(m => m.id === id);
            if (!method) return;

            paymentForm.classList.remove('hidden');
            formTitle.textContent = 'Edit Payment Method';
            paymentMethodIdInput.value = id;
            paymentType.value = method.type;
            togglePaymentFields();

            if (method.type === 'card') {
                document.getElementById('card_type').value = method.details.card_type;
                document.getElementById('cardholder_name').value = method.details.cardholder_name;
                document.getElementById('expiry').value = method.details.expiry;
                document.getElementById('card_number').value = '**** **** **** ' + method.details.last_four;
                document.getElementById('cvc').value = '';
            } else {
                document.getElementById('paypal_email').value = method.details.email;
            }
            document.getElementById('is_default').checked = method.is_default;
        }

        // Delete payment method
        function deletePaymentMethod(id) {
            if (!confirm('Are you sure you want to delete this payment method?')) return;
            let methods = JSON.parse(localStorage.getItem('payment_methods') || '[]');
            methods = methods.filter(m => m.id !== id);
            localStorage.setItem('payment_methods', JSON.stringify(methods));
            showMessage('Payment method deleted successfully.', 'success');
            loadPaymentMethods();
        }

        // Set default payment method
        function setDefaultPaymentMethod(id) {
            let methods = JSON.parse(localStorage.getItem('payment_methods') || '[]');
            methods.forEach(m => m.is_default = m.id === id);
            localStorage.setItem('payment_methods', JSON.stringify(methods));
            showMessage('Default payment method updated.', 'success');
            loadPaymentMethods();
        }

        // Show message
        function showMessage(text, type) {
            messageDiv.textContent = text;
            messageDiv.className =
                `mb-6 px-4 py-3 rounded ${type === 'error' ? 'bg-red-100 border border-red-400 text-red-700' : 'bg-green-100 border border-green-400 text-green-700'}`;
            messageDiv.classList.remove('hidden');
            setTimeout(() => messageDiv.classList.add('hidden'), 3000);
        }

        // Initial load
        loadPaymentMethods();
    });
    </script>
</body>

</html>