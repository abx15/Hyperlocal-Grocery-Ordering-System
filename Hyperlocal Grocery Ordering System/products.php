<?php
require 'includes/db.php';

try {
    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($products)) {
        $error = "No products found.";
    }
} catch (PDOException $e) {
    $products = [];
    $error = "Error fetching products: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body class="bg-gray-100">
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">Product Catalog</h1>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <div class="flex justify-between items-center mb-6">
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Search products..."
                    class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <div class="absolute left-3 top-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <button id="filterBtn"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filters
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($products as $product): ?>
                <div
                    class="product-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="relative">
                        <img src="<?php echo htmlspecialchars($product['image_url'] ?? 'https://via.placeholder.com/300'); ?>"
                            alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-48 object-cover">
                        <?php if (isset($product['discount']) && $product['discount'] > 0): ?>
                            <span class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                -<?php echo htmlspecialchars($product['discount']); ?>%
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">
                            <?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="text-gray-600 text-sm mb-2"><?php echo htmlspecialchars($product['description'] ?? ''); ?>
                        </p>
                        <div class="flex items-center justify-between mt-3">
                            <div>
                                <?php if (isset($product['discount']) && $product['discount'] > 0): ?>
                                    <span
                                        class="text-gray-400 line-through mr-2">₹<?php echo htmlspecialchars(number_format($product['price'], 2)); ?></span>
                                    <span
                                        class="text-blue-600 font-bold">₹<?php echo htmlspecialchars(number_format($product['price'] * (1 - $product['discount'] / 100), 2)); ?></span>
                                <?php else: ?>
                                    <span
                                        class="text-blue-600 font-bold">₹<?php echo htmlspecialchars(number_format($product['price'] ?? 0, 2)); ?></span>
                                <?php endif; ?>
                            </div>
                            <button class="add-to-cart bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm"
                                data-id="<?php echo htmlspecialchars($product['id']); ?>">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Empty state -->
        <?php if (empty($products)): ?>
            <div class="text-center py-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No products found</h3>
                <p class="mt-1 text-gray-500">We couldn't find any products matching your criteria.</p>
                <?php if (isset($error)): ?>
                    <p class="mt-2 text-sm text-red-500">Error: <?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- Filter Modal (hidden by default) -->
    <div id="filterModal" class="fixed inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Filter Products</h3>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Price Range</label>
                        <div class="mt-1 flex items-center space-x-4">
                            <input type="number" id="minPrice" placeholder="Min"
                                class="w-1/2 border rounded-md px-3 py-2">
                            <input type="number" id="maxPrice" placeholder="Max"
                                class="w-1/2 border rounded-md px-3 py-2">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <select id="categoryFilter"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">All Categories</option>
                            <option value="electronics">Electronics</option>
                            <option value="clothing">Clothing</option>
                            <option value="home">Home & Garden</option>
                        </select>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="applyFilters"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Apply Filters
                    </button>
                    <button type="button" id="cancelFilters"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="assets/js/products.js"></script>
</body>

</html>