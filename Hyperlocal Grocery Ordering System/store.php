<?php
require 'includes/db.php';

// Get store ID from URL
$id = $_GET['id'] ?? null;

// Initialize variables
$store = null;
$products = [];
$error = null;

try {
    // Fetch store details
    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM stores WHERE id = ?");
        $stmt->execute([$id]);
        $store = $stmt->fetch();

        if ($store) {
            // Fetch products for this store
            $product_stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $product_stmt->execute([$id]);
            $products = $product_stmt->fetchAll();
        } else {
            $error = "Store not found";
        }
    } else {
        $error = "No store specified";
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

$pageTitle = $store ? htmlspecialchars($store['name']) . " - LocalMart" : "Store - LocalMart";
include 'includes/header.php';
?>

<div class="bg-gray-50 min-h-screen">
    <?php if ($error): ?>
        <div class="container mx-auto px-4 py-8">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
            </div>
        </div>
    <?php elseif ($store): ?>
        <!-- Store Header -->
        <div class="bg-white shadow">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-1/3 lg:w-1/4 p-4">
                        <img src="<?php echo htmlspecialchars($store['image'] ?? 'https://via.placeholder.com/300'); ?>"
                            alt="<?php echo htmlspecialchars($store['name']); ?>"
                            class="w-full h-48 md:h-64 object-cover rounded-lg shadow-md">
                    </div>
                    <div class="md:w-2/3 lg:w-3/4 p-4 md:p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                                    <?php echo htmlspecialchars($store['name']); ?>
                                </h1>
                                <div class="flex items-center mt-2">
                                    <div class="flex text-yellow-400">
                                        <?php
                                        $rating = $store['rating'] ?? 0;
                                        $fullStars = floor($rating);
                                        $hasHalfStar = $rating - $fullStars >= 0.5;

                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $fullStars) {
                                                echo '<i class="fas fa-star"></i>';
                                            } elseif ($i == $fullStars + 1 && $hasHalfStar) {
                                                echo '<i class="fas fa-star-half-alt"></i>';
                                            } else {
                                                echo '<i class="far fa-star"></i>';
                                            }
                                        }
                                        ?>
                                    </div>
                                    <span class="ml-2 text-gray-600"><?php echo number_format($rating, 1); ?>
                                        (<?php echo $store['review_count'] ?? 0; ?> reviews)</span>
                                </div>
                            </div>
                            <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                <?php echo htmlspecialchars($store['distance'] ?? '0'); ?> km away
                            </div>
                        </div>

                        <p class="mt-4 text-gray-600"><?php echo htmlspecialchars($store['description'] ?? ''); ?></p>

                        <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="flex items-center">
                                <i class="fas fa-clock text-green-600 mr-2"></i>
                                <div>
                                    <p class="text-xs text-gray-500">Delivery Time</p>
                                    <p class="font-medium">
                                        <?php
                                        $delivery_time = (int) filter_var($store['delivery_time'] ?? '30', FILTER_SANITIZE_NUMBER_INT);
                                        echo $delivery_time . '-' . ($delivery_time + 10) . ' min';
                                        ?>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-tag text-green-600 mr-2"></i>
                                <div>
                                    <p class="text-xs text-gray-500">Delivery Fee</p>
                                    <p class="font-medium">₹<?php echo $store['delivery_fee'] ?? '40'; ?></p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-green-600 mr-2"></i>
                                <div>
                                    <p class="text-xs text-gray-500">Location</p>
                                    <p class="font-medium"><?php echo htmlspecialchars($store['location'] ?? ''); ?></p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-phone-alt text-green-600 mr-2"></i>
                                <div>
                                    <p class="text-xs text-gray-500">Contact</p>
                                    <p class="font-medium"><?php echo htmlspecialchars($store['phone'] ?? ''); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Store Navigation -->
        <div class="container mx-auto px-4 border-b">
            <nav class="flex space-x-8">
                <button id="productsTab"
                    class="store-tab py-4 px-1 border-b-2 font-medium text-sm border-green-500 text-green-600">
                    Products
                </button>
                <button id="aboutTab"
                    class="store-tab py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    About
                </button>
                <button id="reviewsTab"
                    class="store-tab py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Reviews
                </button>
            </nav>
        </div>

        <!-- Store Content -->
        <div class="container mx-auto px-4 py-8">
            <!-- Products Section -->
            <div id="productsSection" class="store-section">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Products</h2>
                    <div class="relative">
                        <select id="productSort"
                            class="appearance-none bg-white border border-gray-300 rounded-md pl-3 pr-8 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="popular">Popular</option>
                            <option value="price_asc">Price: Low to High</option>
                            <option value="price_desc">Price: High to Low</option>
                            <option value="name">Name (A-Z)</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </div>
                    </div>
                </div>

                <?php if (count($products) > 0): ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        <?php foreach ($products as $product): ?>
                            <div
                                class="product-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                <div class="relative">
                                    <img src="<?php echo htmlspecialchars($product['image'] ?? 'https://via.placeholder.com/300'); ?>"
                                        alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-48 object-cover">
                                    <?php if (isset($product['discount']) && $product['discount'] > 0): ?>
                                        <span
                                            class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                            -<?php echo htmlspecialchars($product['discount']); ?>%
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-1">
                                        <?php echo htmlspecialchars($product['name']); ?>
                                    </h3>
                                    <p class="text-gray-600 text-sm mb-2">
                                        <?php echo htmlspecialchars($product['description'] ?? ''); ?>
                                    </p>
                                    <div class="flex items-center justify-between mt-3">
                                        <div>
                                            <?php if (isset($product['discount']) && $product['discount'] > 0): ?>
                                                <span
                                                    class="text-gray-400 line-through mr-2">₹<?php echo htmlspecialchars(number_format($product['price'], 2)); ?></span>
                                                <span
                                                    class="text-green-600 font-bold">₹<?php echo htmlspecialchars(number_format($product['price'] * (1 - $product['discount'] / 100), 2)); ?></span>
                                            <?php else: ?>
                                                <span
                                                    class="text-green-600 font-bold">₹<?php echo htmlspecialchars(number_format($product['price'] ?? 0, 2)); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <button
                                            class="add-to-cart bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm"
                                            data-id="<?php echo htmlspecialchars($product['id']); ?>"
                                            data-store="<?php echo htmlspecialchars($store['id']); ?>">
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12 bg-white rounded-lg shadow">
                        <i class="fas fa-box-open text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900">No products available</h3>
                        <p class="mt-1 text-gray-500">This store hasn't added any products yet.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- About Section -->
            <div id="aboutSection" class="store-section hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">About <?php echo htmlspecialchars($store['name']); ?>
                    </h2>
                    <div class="prose max-w-none">
                        <p><?php echo htmlspecialchars($store['description'] ?? ''); ?></p>

                        <h3 class="text-lg font-semibold mt-6">Store Details</h3>
                        <ul class="mt-2 space-y-2">
                            <li class="flex">
                                <i class="fas fa-map-marker-alt text-green-600 mt-1 mr-3"></i>
                                <span><?php echo htmlspecialchars($store['address'] ?? ''); ?></span>
                            </li>
                            <li class="flex">
                                <i class="fas fa-clock text-green-600 mt-1 mr-3"></i>
                                <span><?php echo htmlspecialchars($store['hours'] ?? '9:00 AM - 8:00 PM (Daily)'); ?></span>
                            </li>
                            <li class="flex">
                                <i class="fas fa-phone-alt text-green-600 mt-1 mr-3"></i>
                                <span><?php echo htmlspecialchars($store['phone'] ?? ''); ?></span>
                            </li>
                            <li class="flex">
                                <i class="fas fa-envelope text-green-600 mt-1 mr-3"></i>
                                <span><?php echo htmlspecialchars($store['email'] ?? ''); ?></span>
                            </li>
                        </ul>

                        <h3 class="text-lg font-semibold mt-6">Categories</h3>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <?php
                            $categories = explode(',', $store['categories'] ?? '');
                            foreach ($categories as $category):
                                if (trim($category)): ?>
                                    <span
                                        class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm"><?php echo htmlspecialchars(trim($category)); ?></span>
                                <?php endif;
                            endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Section -->
            <div id="reviewsSection" class="store-section hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Customer Reviews</h2>

                    <div class="flex flex-col md:flex-row gap-8 mb-8">
                        <div class="md:w-1/3 text-center">
                            <div class="text-5xl font-bold text-gray-900 mb-2">
                                <?php echo number_format($store['rating'] ?? 0, 1); ?>
                            </div>
                            <div class="flex justify-center text-yellow-400 mb-2">
                                <?php
                                $rating = $store['rating'] ?? 0;
                                $fullStars = floor($rating);
                                $hasHalfStar = $rating - $fullStars >= 0.5;

                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $fullStars) {
                                        echo '<i class="fas fa-star"></i>';
                                    } elseif ($i == $fullStars + 1 && $hasHalfStar) {
                                        echo '<i class="fas fa-star-half-alt"></i>';
                                    } else {
                                        echo '<i class="far fa-star"></i>';
                                    }
                                }
                                ?>
                            </div>
                            <p class="text-gray-600">Based on <?php echo $store['review_count'] ?? 0; ?> reviews</p>
                        </div>

                        <div class="md:w-2/3">
                            <div class="space-y-4">
                                <!-- Review items would be loaded here -->
                                <div class="text-center py-8">
                                    <i class="fas fa-comment-alt text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500">No reviews yet. Be the first to review!</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Write a Review</h3>
                        <form id="reviewForm" class="space-y-4">
                            <div>
                                <label for="reviewRating" class="block text-sm font-medium text-gray-700">Rating</label>
                                <div class="rating-stars mt-1">
                                    <span class="text-2xl" data-rating="1">☆</span>
                                    <span class="text-2xl" data-rating="2">☆</span>
                                    <span class="text-2xl" data-rating="3">☆</span>
                                    <span class="text-2xl" data-rating="4">☆</span>
                                    <span class="text-2xl" data-rating="5">☆</span>
                                    <input type="hidden" id="reviewRating" name="rating" value="0">
                                </div>
                            </div>
                            <div>
                                <label for="reviewText" class="block text-sm font-medium text-gray-700">Review</label>
                                <textarea id="reviewText" name="review" rows="4"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500"></textarea>
                            </div>
                            <div>
                                <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md">
                                    Submit Review
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

<!-- Custom CSS -->
<link rel="stylesheet" href="assets/css/store.css">

<!-- JavaScript -->
<script src="assets/js/store.js"></script>