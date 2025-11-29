<?php
require('./include/config.php');

$sql = 'SELECT * FROM products';
$query = $dbh->prepare($sql);
$query->execute();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MarketHub - Home</title>
    <link rel="icon" href="favicon.ico">
    <meta name="description"
        content="MarketHub - Your favorite online marketplace for electronics, fashion, home, and more.">
    <meta name="author" content="Your Name">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#0a2342', // Navy
                    secondary: '#f8f9fa', // Soft white
                    background: '#ffffff', // Pure White
                    accent: '#d1d5db', // Light Gray for borders
                }
            }
        }
    }
    </script>
</head>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('carousel');
    const totalSlides = carousel.children.length;
    let index = 0;

    setInterval(() => {
        index++;
        if (index >= totalSlides) {
            index = 0;
        }
        carousel.style.transform = `translateX(-${index * 100}%)`;
    }, 10000);
});
</script>

<body class="text-primary bg-gradient-to-r from-blue-100 to-purple-100">

    <!-- Header -->
    <nav
        class="bg-secondary shadow-lg rounded-2xl mx-4 mt-2 p-4 flex flex-wrap items-center justify-between sticky top-0 z-50 gap-4">
        <div class="text-2xl font-bold text-primary">
            MarketHub
        </div>

        <div class="flex flex-1 w-full sm:w-auto sm:max-w-md">
            <input type="text" placeholder="Search products..."
                class="w-full p-3 rounded-full border border-accent focus:ring-2 focus:ring-primary outline-none text-sm">
        </div>

        <div class="flex items-center space-x-4">
            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile"
                class="w-8 h-8 rounded-full">
            <a href="login.php"
                class="bg-primary text-white px-4 py-2 rounded-full hover:bg-[#081a34] hover:shadow-lg transition text-sm">
                Login
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="px-6 mt-8 space-y-12 mb-10">

        <!-- Banner -->
        <section class="mt-2 mx-2 sm:mx-6 overflow-hidden relative rounded-2xl shadow-lg">
            <div id="carousel" class="flex transition-all duration-700 ease-in-out">
                <div class="min-w-full">
                    <img src="./images/ban2.png" alt="" class="w-full h-64 sm:h-[27rem] object-cover rounded-2xl">
                </div>
                <div class="min-w-full">
                    <img src="./images/ban1.jpg" alt="" class="w-full h-64 sm:h-[27rem] object-cover rounded-2xl">
                </div>
                <div class="min-w-full">
                    <img src="./images/b.jpg" alt="" class="w-full h-64 sm:h-[27rem] object-cover rounded-2xl">
                </div>
            </div>
        </section>

        <!-- Categories -->
        <section>
            <h2 class="text-xl font-bold mb-4">Categories</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div
                    class="bg-secondary rounded-2xl shadow-lg p-4 text-center transform hover:scale-105 transition duration-300">
                    <img src="https://cdn-icons-png.flaticon.com/512/1040/1040221.png" class="w-16 mx-auto mb-2"
                        alt="Electronics">
                    <p class="text-sm font-semibold">Electronics</p>
                </div>
                <div
                    class="bg-secondary rounded-2xl shadow-lg p-4 text-center transform hover:scale-105 transition duration-300">
                    <img src="https://cdn-icons-png.flaticon.com/512/1995/1995527.png" class="w-16 mx-auto mb-2"
                        alt="Fashion">
                    <p class="text-sm font-semibold">Fashion</p>
                </div>
                <div
                    class="bg-secondary rounded-2xl shadow-lg p-4 text-center transform hover:scale-105 transition duration-300">
                    <img src="https://cdn-icons-png.flaticon.com/512/883/883407.png" class="w-16 mx-auto mb-2"
                        alt="Home">
                    <p class="text-sm font-semibold">Home</p>
                </div>
                <div
                    class="bg-secondary rounded-2xl shadow-lg p-4 text-center transform hover:scale-105 transition duration-300">
                    <img src="https://cdn-icons-png.flaticon.com/512/3039/3039414.png" class="w-16 mx-auto mb-2"
                        alt="Beauty">
                    <p class="text-sm font-semibold">Beauty</p>
                </div>
            </div>
        </section>

        <!-- Top Stores -->
        <section>
            <h2 class="text-xl font-bold mb-4">Top Stores</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div
                    class="bg-secondary rounded-2xl shadow-lg p-6 text-center transform hover:scale-105 transition duration-300">
                    <img src="https://cdn-icons-png.flaticon.com/512/1674/1674291.png" class="w-20 mx-auto mb-4"
                        alt="TechWorld">
                    <h3 class="font-semibold">TechWorld</h3>
                    <p class="text-sm text-primary/70">Best gadgets & electronics</p>
                </div>
                <div
                    class="bg-secondary rounded-2xl shadow-lg p-6 text-center transform hover:scale-105 transition duration-300">
                    <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" class="w-20 mx-auto mb-4"
                        alt="FashionFreak">
                    <h3 class="font-semibold">FashionFreak</h3>
                    <p class="text-sm text-primary/70">Trendy clothes & shoes</p>
                </div>
                <div
                    class="bg-secondary rounded-2xl shadow-lg p-6 text-center transform hover:scale-105 transition duration-300">
                    <img src="https://cdn-icons-png.flaticon.com/512/2922/2922510.png" class="w-20 mx-auto mb-4"
                        alt="HomePlus">
                    <h3 class="font-semibold">HomePlus</h3>
                    <p class="text-sm text-primary/70">Furniture & kitchen</p>
                </div>
            </div>
        </section>

        <!-- Featured Products -->
        <section>
            <h2 class="text-xl font-bold mb-4">Featured Products</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">

                <?php while ($item = $query->fetch(PDO::FETCH_ASSOC)): ?>
                <div
                    class="bg-secondary rounded-2xl shadow-lg overflow-hidden transform hover:scale-105 transition duration-300 product-item">
                    <img src="<?= $item['image']; ?>" class="w-full h-48 object-contain p-2 bg-white"
                        alt="<?= $item['name']; ?>">
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2 product-name"><?= $item['name']; ?></h3>
                        <p class="text-sm text-primary/70 mb-2"><?= $item['price']; ?></p>
                        <span
                            class="inline-block bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">
                            In Stock: <?= $item['stock']; ?>
                        </span>
                    </div>
                </div>
                <?php endwhile; ?>

            </div>
        </section>

    </main>

    <script>
    document.querySelector('input[type="text"]').addEventListener('input', function(e) {
        let searchTerm = e.target.value.toLowerCase();
        const products = document.querySelectorAll('.product-item');

        products.forEach(function(product) {
            const productName = product.querySelector('.product-name').textContent.toLowerCase();
            if (productName.includes(searchTerm)) {
                product.style.display = 'block';
            } else {
                product.style.display = 'none';
            }
        });
    });
    </script>

</body>

</html>