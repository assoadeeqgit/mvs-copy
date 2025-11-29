<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - MultiVendor Marketplace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    .profile-pic-upload {
        position: relative;
        display: inline-block;
    }

    .profile-pic-upload:hover .profile-pic-overlay {
        opacity: 1;
    }

    .profile-pic-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .form-input:focus {
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
    }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <!-- Header Section -->
    <?php include('./include/user_profile_header.php');?>

    <!-- Edit Profile Section -->
    <section class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Profile Sidebar -->
            <?php include('./include/user_profile_section.php'); ?>

            <!-- Edit Profile Content -->
            <div class="md:w-3/4">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Edit Profile</h2>
                        <a href="user_profile.php" class="text-gray-500 hover:text-primary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>

                    <form>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <!-- Personal Information -->
                            <div>
                                <h3 class="font-semibold text-lg text-gray-800 mb-4">Personal Information</h3>

                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-medium mb-2" for="first-name">
                                        First Name
                                    </label>
                                    <input
                                        class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                                        id="first-name" type="text" value="Sarah">
                                </div>

                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-medium mb-2" for="last-name">
                                        Last Name
                                    </label>
                                    <input
                                        class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                                        id="last-name" type="text" value="Johnson">
                                </div>

                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-medium mb-2" for="email">
                                        Email Address
                                    </label>
                                    <input
                                        class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                                        id="email" type="email" value="sarah.johnson@example.com">
                                </div>

                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-medium mb-2" for="phone">
                                        Phone Number
                                    </label>
                                    <input
                                        class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                                        id="phone" type="tel" value="(555) 123-4567">
                                </div>

                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-medium mb-2" for="birthday">
                                        Date of Birth
                                    </label>
                                    <input
                                        class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                                        id="birthday" type="date" value="1990-06-15">
                                </div>
                            </div>

                            <!-- Profile Picture & Bio -->
                            <div>
                                <h3 class="font-semibold text-lg text-gray-800 mb-4">Profile Details</h3>

                                <div class="mb-6 flex flex-col items-center">
                                    <div class="profile-pic-upload mb-3">
                                        <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
                                            alt="Profile" class="w-32 h-32 rounded-full object-cover">
                                        <div class="profile-pic-overlay">
                                            <span class="text-white text-sm font-medium">
                                                <i class="fas fa-camera mr-1"></i> Change Photo
                                            </span>
                                        </div>
                                    </div>
                                    <input type="file" id="profile-pic" class="hidden" accept="image/*">
                                    <label for="profile-pic"
                                        class="text-primary text-sm font-medium cursor-pointer hover:underline">
                                        Upload new photo
                                    </label>
                                    <p class="text-gray-500 text-xs mt-1">JPG, GIF or PNG. Max size 2MB</p>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-medium mb-2" for="bio">
                                        About Me
                                    </label>
                                    <textarea
                                        class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                                        id="bio" rows="4"
                                        placeholder="Tell us about yourself">Online shopper and tech enthusiast. Love finding unique products from independent sellers.</textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-medium mb-2">
                                        Gender
                                    </label>
                                    <div class="flex space-x-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" class="form-radio text-primary" name="gender" checked>
                                            <span class="ml-2">Female</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" class="form-radio text-primary" name="gender">
                                            <span class="ml-2">Male</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" class="form-radio text-primary" name="gender">
                                            <span class="ml-2">Other</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div class="mb-8">
                            <h3 class="font-semibold text-lg text-gray-800 mb-4">Social Media</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center">
                                    <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                        <i class="fab fa-facebook-f text-blue-600"></i>
                                    </div>
                                    <input
                                        class="form-input flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                                        type="text" placeholder="Facebook profile">
                                </div>
                                <div class="flex items-center">
                                    <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                        <i class="fab fa-twitter text-blue-400"></i>
                                    </div>
                                    <input
                                        class="form-input flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                                        type="text" placeholder="Twitter handle">
                                </div>
                                <div class="flex items-center">
                                    <div class="bg-pink-100 p-2 rounded-lg mr-3">
                                        <i class="fab fa-instagram text-pink-600"></i>
                                    </div>
                                    <input
                                        class="form-input flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                                        type="text" placeholder="Instagram username">
                                </div>
                                <div class="flex items-center">
                                    <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                        <i class="fab fa-linkedin-in text-blue-700"></i>
                                    </div>
                                    <input
                                        class="form-input flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                                        type="text" placeholder="LinkedIn profile">
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-200">
                            <button type="button"
                                class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-indigo-700 transition">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password Section -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-semibold text-lg text-gray-800 mb-4">Change Password</h3>

                    <form>
                        <div class="space-y-4 mb-6">
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-2" for="current-password">
                                    Current Password
                                </label>
                                <div class="relative">
                                    <input
                                        class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                                        id="current-password" type="password">
                                    <button type="button"
                                        class="absolute right-3 top-3 text-gray-500 hover:text-primary">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-2" for="new-password">
                                    New Password
                                </label>
                                <div class="relative">
                                    <input
                                        class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                                        id="new-password" type="password">
                                    <button type="button"
                                        class="absolute right-3 top-3 text-gray-500 hover:text-primary">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Minimum 8 characters with at least one number</p>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-2" for="confirm-password">
                                    Confirm New Password
                                </label>
                                <div class="relative">
                                    <input
                                        class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary"
                                        id="confirm-password" type="password">
                                    <button type="button"
                                        class="absolute right-3 top-3 text-gray-500 hover:text-primary">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-indigo-700 transition">
                                Update Password
                            </button>
                        </div>
                    </form>
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

    <script>
    // Toggle password visibility
    document.querySelectorAll('.fa-eye').forEach(icon => {
        icon.addEventListener('click', function() {
            const input = this.closest('.relative').querySelector('input');
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    });

    // Profile picture upload preview
    document.getElementById('profile-pic').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.querySelector('.profile-pic-upload img').src = event.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
    </script>
</body>

</html>