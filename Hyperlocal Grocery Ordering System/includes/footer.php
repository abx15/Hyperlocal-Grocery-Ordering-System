    </main>

    <!-- Footer Section -->
    <footer class="bg-gray-800 text-white pt-10 pb-6">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About Section -->
                <div class="col-span-1">
                    <h3 class="text-xl font-bold mb-4">LocalMart</h3>
                    <p class="text-gray-400 mb-4">
                        Your hyperlocal grocery solution connecting you with nearby stores for fast, fresh deliveries.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="col-span-1">
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="/" class="text-gray-400 hover:text-white transition">Home</a></li>
                        <li><a href="stores.php" class="text-gray-400 hover:text-white transition">Stores</a></li>
                        <li><a href="deals.php" class="text-gray-400 hover:text-white transition">Deals</a></li>
                        <li><a href="about.php" class="text-gray-400 hover:text-white transition">About Us</a></li>
                        <li><a href="register.php" class="text-gray-400 hover:text-white transition">Contact</a></li>
                    </ul>
                </div>
                
                <!-- Customer Service -->
                <div class="col-span-1">
                    <h4 class="text-lg font-semibold mb-4">Customer Service</h4>
                    <ul class="space-y-2">
                        <li><a href="/faq" class="text-gray-400 hover:text-white transition">FAQ</a></li>
                        <li><a href="/shipping" class="text-gray-400 hover:text-white transition">Shipping Policy</a></li>
                        <li><a href="/returns" class="text-gray-400 hover:text-white transition">Return Policy</a></li>
                        <li><a href="/privacy" class="text-gray-400 hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="/terms" class="text-gray-400 hover:text-white transition">Terms & Conditions</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div class="col-span-1">
                    <h4 class="text-lg font-semibold mb-4">Contact Us</h4>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-2 text-green-400"></i>
                            <span class="text-gray-400">123 Local Street, Your City, Country</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt mr-2 text-green-400"></i>
                            <span class="text-gray-400">+1 (555) 123-4567</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-2 text-green-400"></i>
                            <span class="text-gray-400">support@localmart.com</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-clock mr-2 text-green-400"></i>
                            <span class="text-gray-400">Mon-Sun: 8:00 AM - 10:00 PM</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-6 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm mb-4 md:mb-0">
                    &copy; <?php echo date('Y'); ?> LocalMart. All rights reserved.
                </p>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition">Privacy Policy</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition">Terms of Service</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="back-to-top" class="fixed bottom-6 right-6 bg-green-600 text-white p-3 rounded-full shadow-lg hidden hover:bg-green-700 transition">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script src="assets/js/header.js"></script>
</body>
</html>