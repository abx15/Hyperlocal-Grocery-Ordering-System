<?php
require_once 'includes/db.php';

// Fetch categories
$categoriesQuery = "SELECT * FROM categories LIMIT 6";
$categoriesStmt = $pdo->query($categoriesQuery);
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch popular products
$productsQuery = "SELECT p.*, c.name as category_name 
                 FROM products p 
                 JOIN categories c ON p.category_id = c.id 
                 ORDER BY p.created_at DESC LIMIT 4";
$productsStmt = $pdo->query($productsQuery);
$products = $productsStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch active stores from database
try {
    $stmt = $pdo->query("SELECT * FROM stores WHERE is_active = 1 ORDER BY distance ASC LIMIT 6");
    $stores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $stores = [];
    $error = "Error fetching stores: " . $e->getMessage();
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LocalMart - Hyperlocal Grocery Delivery</title>
    <meta name="description"
        content="LocalMart - Your go-to platform for hyperlocal grocery delivery. Fresh produce, local stores, and quick delivery.">
    <meta name="keywords" content="grocery delivery, hyperlocal, fresh produce, local stores, quick delivery">
    <meta name="author" content="LocalMart Team">
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/index.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/site-icon.png">

</head>

<body>
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero bg-green-50 py-16">
        <div class="container mx-auto px-4 flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-8 md:mb-0">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Fresh Groceries Delivered to Your Doorstep
                </h1>
                <p class="text-lg text-gray-600 mb-6">Support local stores and get your groceries delivered in under 2
                    hours.</p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="stores.php"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-full text-center transition duration-300">
                        Shop Now <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    <a href="deals.php"
                        class="border-2 border-green-600 text-green-600 hover:bg-green-600 hover:text-white font-bold py-3 px-6 rounded-full text-center transition duration-300">
                        Today's Deals
                    </a>
                </div>
            </div>
            <div class="md:w-1/2">
                <img src="assets/images/hero-image.webp" alt="Grocery Delivery"
                    class="w-full h-auto rounded-lg shadow-xl">
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Why Choose LocalMart?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="feature-card p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300 text-center">
                    <div class="bg-green-100 w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-bolt text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Fast Delivery</h3>
                    <p class="text-gray-600">Get your groceries delivered in as little as 60 minutes from local stores
                        near you.</p>
                </div>
                <div class="feature-card p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300 text-center">
                    <div class="bg-green-100 w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-leaf text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Fresh Products</h3>
                    <p class="text-gray-600">Direct from local farmers and producers, ensuring the freshest quality.</p>
                </div>
                <div class="feature-card p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300 text-center">
                    <div class="bg-green-100 w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-hand-holding-heart text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Support Local</h3>
                    <p class="text-gray-600">Help your community thrive by supporting neighborhood businesses.</p>
                </div>
            </div>
        </div>
    </section>



    <!-- Categories Section -->
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Shop by Category</h2>
                <a href="categories.php" class="text-green-600 hover:text-green-700 font-semibold">View All <i
                        class="fas fa-arrow-right ml-1"></i></a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                 <?php foreach ($categories as $category): ?>
    <a href="products.php?category_id=<?= htmlspecialchars($category['id']) ?>"
        class="category-card bg-white rounded-lg shadow-sm hover:shadow-md transition duration-300 p-4 text-center">
        <div class="bg-green-50 p-4 rounded-full mb-3">
            <?php if (isset($category['image_url']) && $category['image_url']): ?>
                <img src="<?= htmlspecialchars($category['image_url']) ?>"
                     alt="<?= htmlspecialchars($category['name']) ?>" class="w-full h-auto"
                     onerror="this.src='assets/images/default-category.png'">
            <?php else: ?>
                <img src="assets/images/default-category.png"
                     alt="Default Category Image" class="w-full h-auto">
            <?php endif; ?>
        </div>
        <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($category['name']) ?></h3>
    </a>
<?php endforeach; ?>

            </div>
        </div>
    </section>

    <!-- Popular Products Section -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Popular Products</h2>
                <a href="products.php" class="text-green-600 hover:text-green-700 font-semibold">View All <i
                        class="fas fa-arrow-right ml-1"></i></a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php foreach ($products as $product): ?>
                    <div
                        class="product-card bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 overflow-hidden">
                        <div class="relative">
                            <!-- Product Image -->
                            <img src="<?= isset($product['image_url']) ? htmlspecialchars($product['image_url']) : 'default-product.png' ?>"
                                alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-48 object-cover">
                            <!-- Discount Badge -->
                            <?php if (isset($product['discount']) && $product['discount'] > 0): ?>
                                <div class="absolute top-2 right-2 bg-yellow-400 text-xs font-bold px-2 py-1 rounded-full">
                                    <?= htmlspecialchars($product['discount']) ?>% OFF
                                </div>
                            <?php endif; ?>

                            <!-- Bestseller Badge -->
                            <?php if (!empty($product['is_bestseller'])): ?>
                                <div
                                    class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                    BESTSELLER
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-1"><?= $product['name'] ?></h3>
                            <p class="text-gray-600 text-sm mb-2"><?= $product['category_name'] ?></p>
                            <div class="flex justify-between items-center">
                                <!-- In the product card section -->
                                <div class="flex justify-between items-center">
                                    <!-- Price Section -->
                                    <div>
                                        <span class="text-green-600 font-bold">
                                            ₹<?= htmlspecialchars($product['price']) ?>
                                        </span>

                                        <?php if (isset($product['original_price']) && $product['original_price'] > $product['price']): ?>
                                            <span class="text-gray-400 text-sm line-through ml-2">
                                                ₹<?= htmlspecialchars($product['original_price']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex gap-2">
                                        <button
                                            class="add-to-cart bg-green-100 text-green-600 hover:bg-green-600 hover:text-white p-2 rounded-full transition duration-300"
                                            data-product-id="<?= $product['id'] ?>" aria-label="Add to cart">
                                            <i class="fas fa-cart-plus"></i>
                                        </button>
                                        <button
                                            class="add-to-wishlist bg-gray-100 text-gray-600 hover:bg-red-500 hover:text-white p-2 rounded-full transition duration-300"
                                            data-product-id="<?= $product['id'] ?>" aria-label="Add to wishlist">
                                            <i class="far fa-heart"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

<!-- Local Stores Section -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <h2 class="text-3xl font-bold text-gray-800">Stores Near You</h2>
            <a href="stores.php" class="text-green-600 hover:text-green-700 font-semibold flex items-center">
                View All <i class="fas fa-arrow-right ml-2 text-sm"></i>
            </a>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($stores)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($stores as $store): ?>
                    <div class="store-card bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 overflow-hidden h-full flex flex-col">
                        <div class="relative aspect-video overflow-hidden">
                            <img 
                                src="<?php echo htmlspecialchars($store['image']); ?>" 
                                alt="<?php echo htmlspecialchars($store['name']); ?>" 
                                class="w-full h-full object-cover"
                                loading="lazy"
                                
                            >
                        </div>
                        <div class="p-4 flex-grow flex flex-col">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-semibold text-lg text-gray-800">
                                    <?php echo htmlspecialchars($store['name']); ?>
                                </h3>
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded whitespace-nowrap">
                                    <?php echo number_format($store['distance'], 1); ?> km away
                                </span>
                            </div>
                            
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                <?php echo htmlspecialchars($store['description']); ?>
                            </p>
                            
                            <div class="mt-auto">
                                <div class="flex items-center text-sm text-gray-500 mb-4 flex-wrap gap-x-2">
                                    <div class="flex items-center">
                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                        <span class="font-medium"><?php echo number_format($store['rating'], 1); ?></span>
                                        <span class="mx-1 hidden sm:inline">(<?php echo $store['review_count']; ?>)</span>
                                    </div>
                                    <span class="hidden sm:inline">•</span>
                                    <span><?php echo $store['delivery_time']; ?>-<?php echo $store['delivery_time'] + 10; ?> min</span>
                                    <span class="hidden sm:inline">•</span>
                                    <span>
                                        <?php echo ($store['delivery_fee'] > 0) ? 
                                            '₹' . number_format($store['delivery_fee'], 2) . ' delivery' : 
                                            'Free delivery'; 
                                        ?>
                                    </span>
                                </div>
                                
                                <a 
                                    href="store.php?id=<?php echo $store['id']; ?>" 
                                    class="block text-center bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded transition duration-300"
                                    aria-label="Visit <?php echo htmlspecialchars($store['name']); ?>"
                                >
                                    Visit Store
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <i class="fas fa-store-slash text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-medium text-gray-800 mb-2">No Stores Available</h3>
                <p class="text-gray-600 mb-4">We couldn't find any stores near your location.</p>
                <a href="stores.php" class="inline-block bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded transition duration-300">
                    Browse All Stores
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

    <!-- Testimonials Section -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">What Our Customers Say</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="testimonial-card bg-gray-50 p-6 rounded-lg shadow-sm">
                    <div class="flex items-center mb-4">
                        <img src="assets/images/testimonials/user1.webp" alt="Rahul Sharma"
                            class="w-12 h-12 rounded-full object-cover mr-4">
                        <div>
                            <h4 class="font-semibold">Rahul Sharma</h4>
                            <div class="flex">
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600">"LocalMart has been a game changer for me. The delivery is always on time
                        and the produce is fresher than what I get at the supermarket. Supporting local businesses is
                        just a bonus!"</p>
                </div>

                <!-- Testimonial 2 -->
                <div class="testimonial-card bg-gray-50 p-6 rounded-lg shadow-sm">
                    <div class="flex items-center mb-4">
                        <img src="assets/images/testimonials/user2.jpg" alt="Priya Patel"
                            class="w-12 h-12 rounded-full object-cover mr-4">
                        <div>
                            <h4 class="font-semibold">Priya Patel</h4>
                            <div class="flex">
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600">"I love how easy it is to order from multiple local stores in one go. The
                        quality is consistently excellent and it's so convenient when I'm busy with work and kids."</p>
                </div>

                <!-- Testimonial 3 -->
                <div class="testimonial-card bg-gray-50 p-6 rounded-lg shadow-sm">
                    <div class="flex items-center mb-4">
                        <img src="assets/images/testimonials/user3.webp" alt="Ankit Verma"
                            class="w-12 h-12 rounded-full object-cover mr-4">
                        <div>
                            <h4 class="font-semibold">Ankit Verma</h4>
                            <div class="flex">
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star-half-alt text-yellow-400"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600">"As someone who cares about where my food comes from, LocalMart is perfect.
                        I can see exactly which farm my vegetables came from and the meat is always fresh."</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-12 bg-green-600 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">Stay Updated</h2>
            <p class="text-lg mb-6 max-w-2xl mx-auto">Subscribe to our newsletter for exclusive deals, new store
                announcements, and grocery tips.</p>
            <form id="newsletter-form" class="max-w-md mx-auto flex">
                <input type="email" placeholder="Your email address" required
                    class="flex-1 px-4 py-3 rounded-l-full focus:outline-none text-gray-800">
                <button type="submit"
                    class="bg-green-800 hover:bg-green-900 px-6 py-3 rounded-r-full font-semibold transition duration-300">
                    Subscribe
                </button>
            </form>
        </div>
    </section>

    <script src="assets/js/index.js"></script>

    <?php include 'includes/footer.php'; ?>
</body>

</html>