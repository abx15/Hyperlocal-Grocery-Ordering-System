<?php 
$pageTitle = "Local Stores - LocalMart";
include 'includes/header.php';

// Database connection
require_once 'includes/db.php';

// Get filters from URL if present
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
$distanceFilter = isset($_GET['distance']) ? (int)$_GET['distance'] : 0;
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'distance';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 9; // Stores per page

// Build base query
$query = "SELECT * FROM stores WHERE 1=1";
$params = [];

// Apply filters
if (!empty($categoryFilter)) {
    $query .= " AND categories LIKE :category";
    $params[':category'] = "%$categoryFilter%";
}

if ($distanceFilter > 0) {
    $query .= " AND distance <= :distance";
    $params[':distance'] = $distanceFilter;
}

if (!empty($searchQuery)) {
    $query .= " AND (name LIKE :search OR description LIKE :search OR categories LIKE :search)";
    $params[':search'] = "%$searchQuery%";
}

// Apply sorting
switch ($sortBy) {
    case 'rating':
        $query .= " ORDER BY rating DESC";
        break;
    case 'delivery_time':
        $query .= " ORDER BY delivery_time ASC";
        break;
    case 'name':
        $query .= " ORDER BY name ASC";
        break;
    default:
        $query .= " ORDER BY distance ASC";
}

// Get total count for pagination
$countStmt = $pdo->prepare(str_replace('*', 'COUNT(*) as total', $query));
$countStmt->execute($params);
$totalStores = $countStmt->fetchColumn();

// Calculate pagination
$totalPages = ceil($totalStores / $perPage);
$offset = ($page - 1) * $perPage;
$query .= " LIMIT $offset, $perPage";

// Fetch stores
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$stores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="bg-gray-50 min-h-screen">
    <!-- Hero Section -->
    <div class="bg-green-600 text-white py-12 px-4">
        <div class="container mx-auto text-center">
            <h1 class="text-4xl font-bold mb-4">Local Stores Near You</h1>
            <p class="text-xl mb-8">Discover and shop from stores in your neighborhood</p>
            
            <!-- Search and Filter Bar -->
            <form method="GET" action="stores.php" class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg p-4">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <input type="text" name="search" id="storeSearch" placeholder="Search stores..." 
                            value="<?= htmlspecialchars($searchQuery) ?>"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <div class="absolute right-3 top-3 text-gray-400">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                    <select name="category" id="categoryFilter" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">All Categories</option>
                        <option value="grocery" <?= $categoryFilter === 'grocery' ? 'selected' : '' ?>>Grocery</option>
                        <option value="vegetables" <?= $categoryFilter === 'vegetables' ? 'selected' : '' ?>>Fruits & Vegetables</option>
                        <option value="dairy" <?= $categoryFilter === 'dairy' ? 'selected' : '' ?>>Dairy</option>
                        <option value="meat" <?= $categoryFilter === 'meat' ? 'selected' : '' ?>>Meat & Seafood</option>
                        <option value="bakery" <?= $categoryFilter === 'bakery' ? 'selected' : '' ?>>Bakery</option>
                        <option value="organic" <?= $categoryFilter === 'organic' ? 'selected' : '' ?>>Organic</option>
                    </select>
                    <select name="distance" id="distanceFilter" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="5" <?= $distanceFilter === 5 ? 'selected' : '' ?>>Within 5 km</option>
                        <option value="10" <?= $distanceFilter === 10 ? 'selected' : '' ?>>Within 10 km</option>
                        <option value="15" <?= $distanceFilter === 15 ? 'selected' : '' ?>>Within 15 km</option>
                        <option value="0" <?= $distanceFilter === 0 ? 'selected' : '' ?>>Any distance</option>
                    </select>
                    <input type="hidden" name="sort" id="sortValue" value="<?= htmlspecialchars($sortBy) ?>">
                </div>
                <button type="submit" class="hidden">Apply Filters</button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-12">
        <!-- Sorting and Results Info -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8">
            <div class="mb-4 md:mb-0">
                <span class="text-gray-600">
                    Showing <?= ($offset + 1) ?>-<?= min($offset + $perPage, $totalStores) ?> of <?= $totalStores ?> stores
                </span>
            </div>
            <div class="flex items-center">
                <span class="mr-2 text-gray-600">Sort by:</span>
                <select id="sortBy" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="distance" <?= $sortBy === 'distance' ? 'selected' : '' ?>>Distance</option>
                    <option value="rating" <?= $sortBy === 'rating' ? 'selected' : '' ?>>Rating</option>
                    <option value="delivery_time" <?= $sortBy === 'delivery_time' ? 'selected' : '' ?>>Delivery Time</option>
                    <option value="name" <?= $sortBy === 'name' ? 'selected' : '' ?>>Name (A-Z)</option>
                </select>
            </div>
        </div>

        <!-- Stores Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="storesContainer">
            <?php if (count($stores) > 0): ?>
                <?php foreach ($stores as $store): ?>
                    <div class="store-card bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 overflow-hidden">
                        <img src="<?= htmlspecialchars($store['image']) ?>" alt="<?= htmlspecialchars($store['name']) ?>" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-semibold text-lg"><?= htmlspecialchars($store['name']) ?></h3>
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded"><?= $store['distance'] ?> km away</span>
                            </div>
                            <p class="text-gray-600 text-sm mb-3"><?= htmlspecialchars($store['description']) ?></p>
                            <div class="flex items-center text-sm text-gray-500 mb-3">
                                <i class="fas fa-star text-yellow-400 mr-1"></i>
                                <span class="font-medium"><?= $store['rating'] ?></span>
                                <span class="mx-1">•</span>
                                <span><?= $store['delivery_time'] ?> min</span>
                                <span class="mx-1">•</span>
                                <span><?= $store['delivery_fee'] > 0 ? '₹'.$store['delivery_fee'].' delivery' : 'Free delivery' ?></span>
                            </div>
                            <!-- <button class="view-store-details w-full text-center bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded transition duration-300"
                                    data-store-id="<?= $store['id'] ?>"
                                    data-store-name="<?= htmlspecialchars($store['name']) ?>"
                                    data-store-description="<?= htmlspecialchars($store['description']) ?>"
                                    data-store-image="<?= htmlspecialchars($store['image']) ?>"
                                    data-store-rating="<?= $store['rating'] ?>"
                                    data-store-distance="<?= $store['distance'] ?> km away"
                                    data-store-address="<?= htmlspecialchars($store['address']) ?>"
                                    data-store-hours="<?= htmlspecialchars($store['opening_hours']) ?>"
                                    data-store-delivery="<?= $store['delivery_fee'] > 0 ? '₹'.$store['delivery_fee'].' delivery' : 'Free delivery' ?>"
                                    data-store-categories="<?= htmlspecialchars($store['categories']) ?>">
                                View Details
                            </button> -->
                            <a href="store.php?id=<?= $store['id'] ?>" class="mt-2 block text-center border-2 border-green-600 text-green-600 hover:bg-green-600 hover:text-white font-medium py-2 px-4 rounded transition duration-300">
                                Visit Store
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-store-slash text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">No stores found</h3>
                    <p class="text-gray-600">Try adjusting your search filters</p>
                    <a href="stores.php" class="mt-4 inline-block bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded transition duration-300">
                        Reset Filters
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="mt-12 flex justify-center" id="pagination">
            <nav class="flex items-center space-x-2">
                <?php if ($page > 1): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>" class="px-3 py-1 rounded-lg border border-gray-300 hover:bg-gray-100">
                        <i class="fas fa-angle-double-left"></i>
                    </a>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>" class="px-3 py-1 rounded-lg border border-gray-300 hover:bg-gray-100">
                        <i class="fas fa-angle-left"></i>
                    </a>
                <?php endif; ?>

                <?php 
                $start = max(1, $page - 2);
                $end = min($totalPages, $page + 2);
                
                if ($start > 1) {
                    echo '<span class="px-3 py-1">...</span>';
                }
                
                for ($i = $start; $i <= $end; $i++): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" class="px-3 py-1 rounded-lg border <?= $i === $page ? 'bg-green-600 text-white border-green-600' : 'border-gray-300 hover:bg-gray-100' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor;
                
                if ($end < $totalPages) {
                    echo '<span class="px-3 py-1">...</span>';
                }
                ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>" class="px-3 py-1 rounded-lg border border-gray-300 hover:bg-gray-100">
                        <i class="fas fa-angle-right"></i>
                    </a>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $totalPages])) ?>" class="px-3 py-1 rounded-lg border border-gray-300 hover:bg-gray-100">
                        <i class="fas fa-angle-double-right"></i>
                    </a>
                <?php endif; ?>
            </nav>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Store Modal Template (Hidden by default) -->
<div id="storeModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 id="modalStoreName" class="text-2xl font-bold"></h3>
                    <div class="flex items-center mt-1">
                        <div id="modalStoreStars" class="flex text-yellow-400"></div>
                        <span id="modalStoreRating" class="ml-2 text-gray-600"></span>
                        <span class="mx-2">•</span>
                        <span id="modalStoreDistance" class="text-gray-600"></span>
                    </div>
                </div>
                <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <img id="modalStoreImage" src="" alt="Store" class="w-full h-48 object-cover rounded-lg">
                    <div class="mt-4">
                        <h4 class="font-semibold mb-2">About</h4>
                        <p id="modalStoreDescription" class="text-gray-600"></p>
                    </div>
                </div>
                <div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold mb-3">Store Details</h4>
                        <ul class="space-y-3">
                            <li class="flex">
                                <i class="fas fa-map-marker-alt text-green-600 mt-1 mr-3"></i>
                                <span id="modalStoreAddress" class="text-gray-600"></span>
                            </li>
                            <li class="flex">
                                <i class="fas fa-clock text-green-600 mt-1 mr-3"></i>
                                <span id="modalStoreHours" class="text-gray-600"></span>
                            </li>
                            <li class="flex">
                                <i class="fas fa-motorcycle text-green-600 mt-1 mr-3"></i>
                                <span id="modalStoreDelivery" class="text-gray-600"></span>
                            </li>
                            <li class="flex">
                                <i class="fas fa-tag text-green-600 mt-1 mr-3"></i>
                                <span id="modalStoreCategories" class="text-gray-600"></span>
                            </li>
                        </ul>
                    </div>
                    <a id="modalStoreLink" href="#" class="mt-4 block text-center bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-lg transition duration-300">
                        Visit Store <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>