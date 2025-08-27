<?php 
$pageTitle = "Register - LocalMart";
include 'includes/header.php'; 
?>

<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl">
        <div class="md:flex">
            <!-- Registration Form Side -->
            <div class="p-8 w-full">
                <div class="text-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-800">Create Your Account</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Join LocalMart to start ordering from local stores near you
                    </p>
                </div>

                <form id="registrationForm" class="mt-8 space-y-6" action="./auth/register.php" method="POST">
                    <!-- Name Field -->
                    <div class="form-group">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <div class="relative">
                            <input id="name" name="name" type="text" autocomplete="name" required
                                class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="Your Name">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-red-600 hidden" id="name-error"></p>
                    </div>

                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <div class="relative">
                            <input id="email" name="email" type="email" autocomplete="email" required
                                class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="you@example.com">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-red-600 hidden" id="email-error"></p>
                    </div>

                    <!-- Phone Field -->
                    <div class="form-group">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <div class="relative">
                            <input id="phone" name="phone" type="tel" autocomplete="tel" required
                                class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="+91 9876543210">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-phone text-gray-400"></i>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-red-600 hidden" id="phone-error"></p>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" autocomplete="new-password" required
                                class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="••••••••">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer toggle-password">
                                <i class="fas fa-eye text-gray-400"></i>
                            </div>
                        </div>
                        <div class="mt-1 text-xs text-gray-500" id="password-strength"></div>
                        <p class="mt-1 text-sm text-red-600 hidden" id="password-error"></p>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="form-group">
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <div class="relative">
                            <input id="confirm_password" name="confirm_password" type="password" autocomplete="new-password" required
                                class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="••••••••">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer toggle-password">
                                <i class="fas fa-eye text-gray-400"></i>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-red-600 hidden" id="confirm_password-error"></p>
                    </div>

                    <!-- Address Field -->
                    <div class="form-group">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Delivery Address</label>
                        <textarea id="address" name="address" rows="3" required
                            class="form-input block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="Your complete delivery address"></textarea>
                        <p class="mt-1 text-sm text-red-600 hidden" id="address-error"></p>
                    </div>

                    <!-- Terms Checkbox -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="terms" name="terms" type="checkbox" required
                                class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="font-medium text-gray-700">I agree to the <a href="/terms" class="text-green-600 hover:text-green-500">Terms of Service</a> and <a href="/privacy" class="text-green-600 hover:text-green-500">Privacy Policy</a></label>
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-red-600 hidden" id="terms-error"></p>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" id="registerButton"
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <i class="fas fa-user-plus text-green-300 group-hover:text-green-200"></i>
                            </span>
                            Register Now
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account? <a href="login.php" class="font-medium text-green-600 hover:text-green-500">Sign in</a>
                    </p>
                </div>
            </div>

            <!-- Promo Side (Visible on larger screens) -->
            <div class="hidden md:block md:w-1/2 bg-green-600 text-white p-8 flex flex-col justify-center">
                <div class="text-center">
                    <i class="fas fa-shopping-basket text-5xl mb-4"></i>
                    <h3 class="text-2xl font-bold mb-2">Welcome to LocalMart</h3>
                    <p class="mb-6">Join our community and get access to:</p>
                    <ul class="space-y-3 text-left">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mt-1 mr-2"></i>
                            <span>Fast delivery from local stores</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mt-1 mr-2"></i>
                            <span>Exclusive deals and discounts</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mt-1 mr-2"></i>
                            <span>Personalized recommendations</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mt-1 mr-2"></i>
                            <span>Order tracking and history</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
<script src="assets/js/register.js"></script>