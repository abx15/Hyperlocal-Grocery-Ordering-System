<?php
$pageTitle = "Login - LocalMart";
include 'includes/header.php';
?>
<div class="min-h-screen bg-gray-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white p-8 rounded-xl shadow-md">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-extrabold text-gray-800">Sign in to LocalMart</h2>
            <p class="mt-2 text-sm text-gray-600">Welcome back! Please login to continue.</p>
        </div>

        <?php
        if (isset($_GET['error'])) {
            echo '<div class="mb-4 text-red-600 text-sm text-center">' . htmlspecialchars($_GET['error']) . '</div>';
        }
        if (isset($_GET['registered'])) {
            echo '<div class="mb-4 text-green-600 text-sm text-center">Registration successful! Please login.</div>';
        }
        ?>

        <form action="/auth/login.php" method="POST" class="space-y-6">
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                <input type="email" name="email" id="email" required
                    class="form-input mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                    placeholder="you@example.com">
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" required
                    class="form-input mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                    placeholder="••••••••">
            </div>

            <!-- Submit -->
            <div>
                <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300">
                    Login
                </button>
            </div>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600">
            Don't have an account? 
            <a href="register.php" class="text-green-600 hover:text-green-500 font-medium">Register here</a>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
