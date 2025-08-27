document.addEventListener('DOMContentLoaded', function() {
    // Cart state
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
    
    // Initialize cart count display
    updateCartCount();
    updateWishlistCount();

    // Add to cart functionality
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            const productCard = this.closest('.product-card');
            const productName = productCard.querySelector('h3').textContent;
            const productPrice = productCard.querySelector('.text-green-600').textContent;
            
            // Check if product is already in cart
            const existingItem = cart.find(item => item.id === productId);
            
            if (existingItem) {
                // Remove from cart
                cart = cart.filter(item => item.id !== productId);
                this.innerHTML = '<i class="fas fa-cart-plus"></i>';
                this.classList.remove('bg-green-600', 'text-white');
                this.classList.add('bg-green-100', 'text-green-600');
                showToast(`${productName} removed from cart`);
            } else {
                // Add to cart
                cart.push({
                    id: productId,
                    name: productName,
                    price: productPrice,
                    quantity: 1
                });
                this.innerHTML = '<i class="fas fa-check"></i>';
                this.classList.remove('bg-green-100', 'text-green-600');
                this.classList.add('bg-green-600', 'text-white');
                showToast(`${productName} added to cart`);
            }
            
            // Save to localStorage
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartCount();
            
            // Reset button after 1.5 seconds if added
            if (!existingItem) {
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-minus"></i>';
                }, 1500);
            }
        });
    });
    
    // Add to wishlist functionality
    document.querySelectorAll('.add-to-wishlist').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            const productCard = this.closest('.product-card');
            const productName = productCard.querySelector('h3').textContent;
            
            // Check if product is already in wishlist
            const existingItem = wishlist.find(item => item.id === productId);
            
            if (existingItem) {
                // Remove from wishlist
                wishlist = wishlist.filter(item => item.id !== productId);
                this.innerHTML = '<i class="far fa-heart"></i>';
                this.classList.remove('bg-red-500', 'text-white');
                this.classList.add('bg-gray-100', 'text-gray-600');
                showToast(`${productName} removed from wishlist`);
            } else {
                // Add to wishlist
                wishlist.push({
                    id: productId,
                    name: productName
                });
                this.innerHTML = '<i class="fas fa-heart"></i>';
                this.classList.remove('bg-gray-100', 'text-gray-600');
                this.classList.add('bg-red-500', 'text-white');
                showToast(`${productName} added to wishlist`);
            }
            
            // Save to localStorage
            localStorage.setItem('wishlist', JSON.stringify(wishlist));
            updateWishlistCount();
        });
    });
    
    // Newsletter form submission
    const newsletterForm = document.getElementById('newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const emailInput = this.querySelector('input[type="email"]');
            const email = emailInput.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!emailRegex.test(email)) {
                showToast('Please enter a valid email address', 'error');
                emailInput.focus();
                return;
            }
            
            // Simulate API call
            setTimeout(() => {
                showToast('Thank you for subscribing!');
                emailInput.value = '';
                
                // In a real app, you would send this to your backend
                console.log(`Subscribed email: ${email}`);
            }, 500);
        });
    }
    
    // Update cart count display
    function updateCartCount() {
        const cartCount = document.querySelector('.cart-count');
        if (cartCount) {
            const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
            cartCount.textContent = totalItems;
            
            if (totalItems > 0) {
                cartCount.classList.remove('hidden');
                // Add animation
                cartCount.classList.add('animate-bounce');
                setTimeout(() => {
                    cartCount.classList.remove('animate-bounce');
                }, 1000);
            } else {
                cartCount.classList.add('hidden');
            }
        }
    }
    
    // Update wishlist count (if you have a wishlist counter)
    function updateWishlistCount() {
        const wishlistCount = document.querySelector('.wishlist-count');
        if (wishlistCount) {
            wishlistCount.textContent = wishlist.length;
            
            if (wishlist.length > 0) {
                wishlistCount.classList.remove('hidden');
            } else {
                wishlistCount.classList.add('hidden');
            }
        }
    }
    
    // Show toast notification
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded-md shadow-lg text-white ${
            type === 'error' ? 'bg-red-500' : 'bg-green-500'
        }`;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.add('opacity-0', 'transition-opacity', 'duration-300');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    // Initialize any carousels or sliders if needed
    console.log('Index page JavaScript loaded');
});