<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Hyperlocal Grocery'; ?></title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/styles.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
    <!-- icon -->
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/site-icon.png">
</head>

<body class="bg-gray-50 font-sans">
    <!-- Header Section -->
    <header class="bg-green-600 text-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <!-- Logo and Brand -->
                <div class="flex items-center space-x-2">
                    <a href="index.php" class="flex items-center">
                        <i class="fas fa-shopping-basket text-2xl"></i>
                        <span class="ml-2 text-xl font-bold">LocalMart</span>
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="hidden md:flex flex-1 mx-6">
                    <div class="relative w-full max-w-xl">
                        <input type="text" placeholder="Search for groceries..."
                            class="w-full px-4 py-2 rounded-full text-gray-800 focus:outline-none focus:ring-2 focus:ring-green-400">
                        <button class="absolute right-0 top-0 h-full px-4 text-gray-600 hover:text-green-600">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-4">
                    <a href="index.php" class="px-3 py-2 hover:bg-green-700 rounded-md transition">Home</a>

                    <!-- Stores Dropdown -->
                    <div class="relative group">
                        <button
                            class="dropdown-toggle px-3 py-2 hover:bg-green-700 rounded-md transition flex items-center">
                            Stores <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div
                            class="dropdown-menu absolute hidden group-hover:block bg-white text-gray-800 shadow-lg rounded-md mt-1 w-48 py-1 z-50">
                            <a href="/stores/nearby" class="block px-4 py-2 hover:bg-green-100">Nearby Stores</a>
                            <a href="stores.php" class="block px-4 py-2 hover:bg-green-100">All Stores</a>
                            <a href="categories.php" class="block px-4 py-2 hover:bg-green-100">By Category</a>
                        </div>
                    </div>

                    <a href="deals.php" class="px-3 py-2 hover:bg-green-700 rounded-md transition">Deals</a>

                    <!-- Account Dropdown -->
                    <!-- Account Dropdown - Updated with better hover handling -->
                    <div class="relative group" id="account-dropdown">
                        <button
                            class="dropdown-toggle flex items-center space-x-1 px-3 py-2 hover:bg-green-700 rounded-md transition">
                            <i class="fas fa-user-circle"></i>
                            <span>Account</span>
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div
                            class="dropdown-menu absolute right-0 hidden bg-white text-gray-800 shadow-lg rounded-md mt-1 w-48 py-1 z-50">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <a href="/account" class="block px-4 py-2 hover:bg-green-100">My Account</a>
                                <a href="/orders" class="block px-4 py-2 hover:bg-green-100">My Orders</a>
                                <a href="/wishlist" class="block px-4 py-2 hover:bg-green-100">Wishlist</a>
                                <div class="border-t border-gray-200 my-1"></div>
                                <a href="/logout" class="block px-4 py-2 hover:bg-green-100 text-red-600">Logout</a>
                            <?php else: ?>
                                <a href="login.php" class="block px-4 py-2 hover:bg-green-100">Login</a>
                                <a href="register.php" class="block px-4 py-2 hover:bg-green-100">Register</a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Cart -->
                    <a href="cart.php" class="relative px-3 py-2 hover:bg-green-700 rounded-md transition">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="ml-1">Cart</span>
                        <span
                            class="cart-count absolute -top-1 -right-1 bg-yellow-400 text-black text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">3</span>
                    </a>
                </nav>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-button" class="md:hidden p-2 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <!-- Mobile Search (hidden on desktop) -->
            <div class="mt-3 md:hidden">
                <div class="relative">
                    <input type="text" placeholder="Search for groceries..."
                        class="w-full px-4 py-2 rounded-full text-gray-800 focus:outline-none focus:ring-2 focus:ring-green-400">
                    <button class="absolute right-0 top-0 h-full px-4 text-gray-600 hover:text-green-600">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu (hidden by default) -->
        <div id="mobile-menu" class="mobile-menu hidden md:hidden bg-green-700 px-4 py-2">
            <a href="/" class="block px-3 py-2 hover:bg-green-600 rounded-md">Home</a>

            <div class="mobile-dropdown">
                <button
                    class="mobile-dropdown-toggle w-full text-left px-3 py-2 hover:bg-green-600 rounded-md flex justify-between items-center">
                    Stores <i class="fas fa-chevron-down"></i>
                </button>
                <div class="mobile-dropdown-menu hidden pl-4 mt-1">
                    <a href="/stores/nearby" class="block px-3 py-2 hover:bg-green-600 rounded-md">Nearby Stores</a>
                    <a href="/stores/all" class="block px-3 py-2 hover:bg-green-600 rounded-md">All Stores</a>
                    <a href="/stores/categories" class="block px-3 py-2 hover:bg-green-600 rounded-md">By Category</a>
                </div>
            </div>

            <a href="/deals" class="block px-3 py-2 hover:bg-green-600 rounded-md">Deals</a>

            <div class="mobile-dropdown">
                <button
                    class="mobile-dropdown-toggle w-full text-left px-3 py-2 hover:bg-green-600 rounded-md flex justify-between items-center">
                    Account <i class="fas fa-chevron-down"></i>
                </button>
                <div class="mobile-dropdown-menu hidden pl-4 mt-1">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="/account" class="block px-3 py-2 hover:bg-green-600 rounded-md">My Account</a>
                        <a href="/orders" class="block px-3 py-2 hover:bg-green-600 rounded-md">My Orders</a>
                        <a href="/wishlist" class="block px-3 py-2 hover:bg-green-600 rounded-md">Wishlist</a>
                        <a href="/logout" class="block px-3 py-2 hover:bg-green-600 rounded-md text-red-300">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="block px-3 py-2 hover:bg-green-600 rounded-md">Login</a>
                        <a href="register.php" class="block px-3 py-2 hover:bg-green-600 rounded-md">Register</a>
                    <?php endif; ?>
                </div>
            </div>

            <a href="cart.php" class="block px-3 py-2 hover:bg-green-600 rounded-md flex justify-between items-center">
                Cart <span
                    class="bg-yellow-400 text-black text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">3</span>
            </a>
        </div>
    </header>

    <script src="assets/js/header.js"></script>
</body>

</html>