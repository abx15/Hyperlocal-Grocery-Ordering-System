<?php
// categories.php
require_once 'includes/db.php';

try {
    // Fetch all categories with product counts
    $stmt = $pdo->query("
        SELECT c.*, COUNT(p.id) as product_count 
        FROM categories c
        LEFT JOIN products p ON c.id = p.category_id
        GROUP BY c.id
    ");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if a specific category is requested
    $category_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $selected_category = null;
    $products = [];

    if ($category_id) {
        // Fetch the specific category
        $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$category_id]);
        $selected_category = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($selected_category) {
            // Fetch products for this category
            $stmt = $pdo->prepare("
                SELECT * FROM products 
                WHERE category_id = ? 
                AND is_active = 1
                ORDER BY name
            ");
            $stmt->execute([$category_id]);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    if (empty($categories)) {
        $error = "No categories found.";
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

$page_title = isset($selected_category) ? 
    htmlspecialchars($selected_category['name']) . ' - Locomart Grocery' : 
    'Categories - Locomart Grocery';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .header {
            background-color: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .main {
            max-width: 1280px;
            margin: auto;
            padding: 1.5rem;
        }
        .error {
            background-color: #fee2e2;
            border: 1px solid #fca5a5;
            color: #b91c1c;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
        }
        .category-card, .product-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .category-card:hover, .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .category-img, .product-img {
            height: 180px;
            object-fit: cover;
            width: 100%;
        }
        .default-img {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f1f5f9;
            color: #94a3b8;
            font-size: 3rem;
        }
        .back-btn {
            background-color: #e2e8f0;
            color: #334155;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
        }
        .back-btn:hover {
            background-color: #cbd5e1;
        }
        .product-price {
            color: #16a34a;
            font-weight: bold;
        }
        .category-badge {
            background-color: #3b82f6;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="main py-4">
            <h1 class="text-3xl font-bold text-gray-800">
                <?= isset($selected_category) ? htmlspecialchars($selected_category['name']) : 'Our Grocery Categories' ?>
            </h1>
            <p class="text-gray-600">
                <?= isset($selected_category) ? 
                    'Browse our selection of ' . htmlspecialchars($selected_category['name']) : 
                    'Shop by category' ?>
            </p>
        </div>
    </header>

    <main class="main">
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (isset($selected_category)): ?>
            <!-- Back to all categories button -->
            <a href="categories.php" class="back-btn inline-flex items-center mb-6">
                <i class="fas fa-arrow-left mr-2"></i>All Categories
            </a>

            <!-- Selected category details -->
            <div class="mb-8">
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    <?php if (!empty($selected_category['image_url'])): ?>
                        <img src="<?= htmlspecialchars($selected_category['image_url']) ?>"
                            alt="<?= htmlspecialchars($selected_category['name']) ?>" class="w-full h-64 object-cover"
                            onerror="this.onerror=null;this.src='https://via.placeholder.com/800x400?text=Category+Image';this.className='w-full h-64 object-cover'">
                    <?php else: ?>
                        <div class="w-full h-64 bg-blue-50 flex items-center justify-center">
                            <i class="fas fa-utensils text-6xl text-blue-200"></i>
                        </div>
                    <?php endif; ?>
                    <div class="p-6">
                        <h2 class="text-2xl font-semibold mb-3 text-gray-800"><?= htmlspecialchars($selected_category['name']) ?></h2>
                        <?php if (!empty($selected_category['description'])): ?>
                            <p class="text-gray-600 mb-4"><?= htmlspecialchars($selected_category['description']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Products in this category -->
                <h2 class="text-2xl font-bold mb-6 text-gray-800">Available Products</h2>
                <?php if (!empty($products)): ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        <?php foreach ($products as $product): ?>
                            <div class="product-card bg-white rounded-lg shadow-md overflow-hidden">
                                <?php if (!empty($product['image_url'])): ?>
                                    <img src="<?= htmlspecialchars($product['image_url']) ?>"
                                        alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-48 object-cover"
                                        onerror="this.onerror=null;this.src='https://via.placeholder.com/300x300?text=Product+Image';this.className='w-full h-48 object-cover'">
                                <?php else: ?>
                                    <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-shopping-basket text-4xl text-gray-400"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="p-4">
                                    <h3 class="font-semibold text-lg mb-1 text-gray-800"><?= htmlspecialchars($product['name']) ?></h3>
                                    <p class="text-gray-600 text-sm mb-2 truncate"><?= htmlspecialchars($product['description']) ?></p>
                                    <div class="flex justify-between items-center">
                                        <span class="product-price">₹<?= number_format($product['price'], 2) ?></span>
                                        <?php if ($product['stock_quantity'] > 0): ?>
                                            <span class="text-xs text-gray-500">In Stock: <?= $product['stock_quantity'] ?></span>
                                        <?php else: ?>
                                            <span class="text-xs text-red-500">Out of Stock</span>
                                        <?php endif; ?>
                                    </div>
                                    <button class="mt-3 w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-md transition">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                        <i class="fas fa-box-open text-4xl text-gray-400 mb-3"></i>
                        <p class="text-gray-600">No products available in this category yet.</p>
                    </div>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <!-- All categories view -->
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Browse Categories</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php foreach ($categories as $category): ?>
                    <a href="categories.php?id=<?= $category['id'] ?>"
                        class="category-card bg-white rounded-lg shadow-md overflow-hidden">
                        <?php if (!empty($category['image_url'])): ?>
                            <img src="<?= htmlspecialchars($category['image_url']) ?>"
                                alt="<?= htmlspecialchars($category['name']) ?>" class="w-full h-48 object-cover"
                                onerror="this.onerror=null;this.src='https://via.placeholder.com/300x300?text=Category+Image';this.className='w-full h-48 object-cover'">
                        <?php else: ?>
                            <div class="w-full h-48 bg-blue-50 flex items-center justify-center">
                                <i class="fas fa-tag text-4xl text-blue-200"></i>
                            </div>
                        <?php endif; ?>
                        <div class="p-4">
                            <div class="flex justify-between items-start">
                                <h2 class="text-xl font-semibold mb-2 text-gray-800"><?= htmlspecialchars($category['name']) ?></h2>
                                <span class="category-badge"><?= $category['product_count'] ?> items</span>
                            </div>
                            <?php if (!empty($category['description'])): ?>
                                <p class="text-gray-600 text-sm"><?= htmlspecialchars($category['description']) ?></p>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>