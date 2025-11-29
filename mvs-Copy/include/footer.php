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
                    <li><a href="index.php" class="text-gray-400 hover:text-white transition">All Products</a></li>
                    <li><a href="index.php#featured" class="text-gray-400 hover:text-white transition">Featured</a>
                    </li>
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
                <h4 class="font-bold text-lg mb-4">Support</h4>
                <ul class="space-y-2">
                    <li><a href="help-center.php" class="text-gray-400 hover:text-white transition">Help Center</a>
                    </li>
                    <li class="flex items-center text-gray-400"><i class="fas fa-phone mr-2"></i> (123) 456-7890
                    </li>
                    <li class="flex items-center text-gray-400"><i class="fas fa-envelope mr-2"></i> <a
                            href="mailto:<?php echo htmlspecialchars($supportEmail); ?>"
                            class="hover:text-white transition"><?php echo htmlspecialchars($supportEmail); ?></a>
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