<div class="md:w-1/4">
    <div class="bg-white rounded-lg shadow-md p-6 sticky top-4" data-aos="fade-up">
        <div class="flex flex-col items-center mb-6">
            <div class="relative mb-4">
                <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80"
                    alt="Profile" class="w-24 h-24 rounded-full object-cover">
                <button id="change-profile-pic"
                    class="absolute bottom-0 right-0 bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition"
                    title="Change Profile Picture">
                    <i class="fas fa-camera text-sm"></i>
                </button>
            </div>
            <h2 class="font-bold text-xl">John Doe</h2>
            <p class="text-gray-600 text-sm">Member since June 2022</p>
        </div>

        <nav class="space-y-2">
            <a href="./user_profile.php"
                class="profile-nav-item block px-4 py-2 text-gray-700 hover:text-primary transition flex items-center">
                <i class="fas fa-user-circle mr-3"></i> My Profile
            </a>
            <a href="orders.php"
                class="profile-nav-item block px-4 py-2 text-gray-700 hover:text-primary transition flex items-center">
                <i class="fas fa-shopping-bag mr-3"></i> My Orders
            </a>
            <a href="wishlist.php"
                class="profile-nav-item block px-4 py-2 text-gray-700 hover:text-primary transition flex items-center">
                <i class="fas fa-heart mr-3"></i> Wishlist
            </a>
            <a href="user_address.php"
                class="profile-nav-item block px-4 py-2 text-gray-700 hover:text-primary transition flex items-center">
                <i class="fas fa-map-marker-alt mr-3"></i> Addresses
            </a>
            <a href="user_payment.php"
                class="profile-nav-item block px-4 py-2 text-gray-700 hover:text-primary transition flex items-center">
                <i class="fas fa-credit-card mr-3"></i> Payment Methods
            </a>
            <a href="notifications.php"
                class="profile-nav-item block px-4 py-2 text-gray-700 hover:text-primary transition flex items-center">
                <i class="fas fa-bell mr-3"></i> Notifications
            </a>
            <a href="user_setting.php"
                class="profile-nav-item block px-4 py-2 text-gray-700 hover:text-primary transition flex items-center">
                <i class="fas fa-cog mr-3"></i> Account Settings
            </a>
            <a href="logout.php" class="block px-4 py-2 text-red-500 hover:text-red-700 transition flex items-center">
                <i class="fas fa-sign-out-alt mr-3"></i> Logout
            </a>
        </nav>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dynamically set active navigation item
    const navItems = document.querySelectorAll('.profile-nav-item');
    const currentPage = window.location.pathname.split('/').pop() || 'user_profile.php';

    navItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href === currentPage) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
    });

    // Mock profile picture change
    const changePicBtn = document.getElementById('change-profile-pic');
    changePicBtn.addEventListener('click', function() {
        alert('Profile picture change functionality would open a file picker in a real application.');
    });
});
</script>