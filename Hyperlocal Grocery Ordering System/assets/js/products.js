// products.js
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const searchInput = document.getElementById('searchInput');
    const filterBtn = document.getElementById('filterBtn');
    const filterModal = document.getElementById('filterModal');
    const applyFilters = document.getElementById('applyFilters');
    const cancelFilters = document.getElementById('cancelFilters');
    const productCards = document.querySelectorAll('.product-card');
    const addToCartButtons = document.querySelectorAll('.add-to-cart');

    // Toggle filter modal
    filterBtn.addEventListener('click', () => {
        filterModal.classList.remove('hidden');
    });

    // Close modal on cancel
    cancelFilters.addEventListener('click', () => {
        filterModal.classList.add('hidden');
    });

    // Apply filters
    applyFilters.addEventListener('click', () => {
        const minPrice = parseFloat(document.getElementById('minPrice').value) || 0;
        const maxPrice = parseFloat(document.getElementById('maxPrice').value) || Infinity;
        const category = document.getElementById('categoryFilter').value.toLowerCase();
        
        productCards.forEach(card => {
            const priceText = card.querySelector('.text-blue-600').textContent;
            const price = parseFloat(priceText.replace('$', ''));
            const productCategory = card.dataset.category || '';
            
            const priceMatch = price >= minPrice && price <= maxPrice;
            const categoryMatch = !category || productCategory.toLowerCase().includes(category);
            
            if (priceMatch && categoryMatch) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
        
        filterModal.classList.add('hidden');
    });

    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        productCards.forEach(card => {
            const title = card.querySelector('h3').textContent.toLowerCase();
            const description = card.querySelector('p').textContent.toLowerCase();
            
            if (title.includes(searchTerm) || description.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Add to cart functionality
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.id;
            addToCart(productId);
        });
    });

    // Add to cart function
    function addToCart(productId) {
        // In a real app, you would make an AJAX request here
        console.log(`Added product ${productId} to cart`);
        
        // Show feedback to user
        const button = document.querySelector(`.add-to-cart[data-id="${productId}"]`);
        const originalText = button.textContent;
        button.textContent = 'Added!';
        button.classList.remove('bg-blue-500', 'hover:bg-blue-600');
        button.classList.add('bg-green-500', 'hover:bg-green-600');
        
        setTimeout(() => {
            button.textContent = originalText;
            button.classList.remove('bg-green-500', 'hover:bg-green-600');
            button.classList.add('bg-blue-500', 'hover:bg-blue-600');
        }, 2000);
    }

    // Close modal when clicking outside
    window.addEventListener('click', (event) => {
        if (event.target === filterModal) {
            filterModal.classList.add('hidden');
        }
    });
});