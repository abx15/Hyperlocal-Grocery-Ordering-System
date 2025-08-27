<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <link rel="stylesheet" href="assets/css/orders.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="assets/js/orders.js"></script>
</head>
<body>
    <div class="bg-gray-50 min-h-screen">
        <!-- Hero Section -->
        <div class="bg-green-600 text-white py-12 px-4">
            <div class="container mx-auto text-center">
                <h1 class="text-4xl font-bold mb-4">Your Orders</h1>
                <p class="text-xl mb-8">Manage your orders and track their status</p>
                <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg p-4">
                    <div class="flex flex-col md:flex-row gap-4">
                        <input type="text" id="orderSearch" placeholder="Search orders..." 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <select id="statusFilter" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main Content -->
        <div class="container mx-auto px-4 py-12">
            <!-- Sorting and Results Info -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-8">
                <div class="mb-4 md:mb-0">
                    <span class="text-gray-600" id="resultsCount">Loading orders...</span>
                </div>
                <div class="flex items-center">
                    <span class="mr-2 text-gray-600">Sort by:</span>
                    <select id="sortBy" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="date">Date</option>
                        <option value="status">Status</option>
                        <option value="total">Total Amount</option>
                    </select>
                </div>
            </div>

            <!-- Orders List -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="ordersContainer">
                <!-- Orders will be loaded here dynamically -->
                <div class="text-center py-12">
                    <i class="fas fa-spinner fa-spin text-4xl text-green-600 mb-4"></i>
                    <p class="text-gray-600">Loading orders...</p>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-12 flex justify-center" id="pagination">
                <!-- Pagination will be loaded here dynamically -->
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Load orders on page load
            loadOrders();

            // Search functionality
            $('#orderSearch').on('input', function() {
                loadOrders();
            });

            // Filter by status
            $('#statusFilter').on('change', function() {
                loadOrders();
            });

            // Sort orders
            $('#sortBy').on('change', function() {
                loadOrders();
            });
        });

        function loadOrders() {
            // Fetch and display orders based on search, filter, and sort criteria
            // This function will make an AJAX call to the server to get the orders data
        }
    </script>
</div>

        
</body>
</html>