<!-- Recently Viewed -->
<section class="container mx-auto px-4 py-12">
    <h2 class="text-2xl font-bold mb-6">Recently Viewed</h2>
    <?php if (empty($recentlyViewed)): ?>
    <div class="text-center py-8">
        <i class="fas fa-eye text-4xl text-gray-300 mb-4"></i>
        <p class="text-gray-500">No recently viewed products.</p>
        <a href="home.php" class="text-primary hover:underline mt-2 inline-block">Browse Products</a>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php foreach ($recentlyViewed as $product): 
                $price = $product['discounted_price'] !== null ? $product['discounted_price'] : $product['price'];
                $image = $product['image_url'] ?: 'https://via.placeholder.com/300x200?text=' . urlencode($product['product_name']);
            ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden product-card transition">
            <div class="relative">
                <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>"
                    class="w-full h-48 object-cover">
                <div class="absolute top-2 left-2 bg-secondary text-white text-xs px-2 py-1 rounded">
                    <i class="fas fa-store mr-1"></i> <?= htmlspecialchars($product['vendor_name']) ?>
                </div>
                <button
                    class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-primary hover:text-white transition add-to-wishlist"
                    data-product-id="<?= $product['product_id'] ?>">
                    <i class="fas fa-heart"></i>
                </button>
            </div>
            <div class="p-4">
                <h3 class="font-semibold text-lg mb-1"><?= htmlspecialchars($product['product_name']) ?></h3>
                <div class="flex items-center mb-2">
                    <div class="flex text-yellow-400">
                        <?php
                                $rating = floatval($product['avg_rating']);
                                $fullStars = floor($rating);
                                $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                for ($i = 0; $i < $fullStars; $i++):
                                ?>
                        <i class="fas fa-star"></i>
                        <?php endfor; ?>
                        <?php if ($hasHalfStar): ?>
                        <i class="fas fa-star-half-alt"></i>
                        <?php endif; ?>
                        <?php for ($i = 0; $i < $emptyStars; $i++): ?>
                        <i class="far fa-star"></i>
                        <?php endfor; ?>
                    </div>
                    <span class="text-gray-500 text-sm ml-2">(<?= $product['review_count'] ?>)</span>
                </div>
                <div class="flex justify-between items-center">
                    <div>
                        <?php if ($product['discounted_price'] !== null && $product['discounted_price'] < $product['price']): ?>
                        <span class="text-gray-500 line-through">$<?= number_format($product['price'], 2) ?></span>
                        <span
                            class="font-bold text-lg ml-2">$<?= number_format($product['discounted_price'], 2) ?></span>
                        <?php else: ?>
                        <span class="font-bold text-lg">$<?= number_format($price, 2) ?></span>
                        <?php endif; ?>
                    </div>
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit"
                            class="bg-primary text-white p-2 rounded-full hover:bg-indigo-700 transition">
                            <i class="fas fa-plus"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>