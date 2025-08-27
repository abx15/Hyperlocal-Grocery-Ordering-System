document.addEventListener('DOMContentLoaded', function() {
    // Tab Navigation
    const productsTab = document.getElementById('productsTab');
    const aboutTab = document.getElementById('aboutTab');
    const reviewsTab = document.getElementById('reviewsTab');
    const productsSection = document.getElementById('productsSection');
    const aboutSection = document.getElementById('aboutSection');
    const reviewsSection = document.getElementById('reviewsSection');
    
    function resetTabs() {
        [productsTab, aboutTab, reviewsTab].forEach(tab => {
            tab.classList.remove('border-green-500', 'text-green-600');
            tab.classList.add('border-transparent', 'text-gray-500');
        });
    }
    
    function showSection(section) {
        [productsSection, aboutSection, reviewsSection].forEach(sec => {
            sec.classList.add('hidden');
        });
        section.classList.remove('hidden');
    }
    
    productsTab.addEventListener('click', function() {
        resetTabs();
        this.classList.add('border-green-500', 'text-green-600');
        this.classList.remove('border-transparent', 'text-gray-500');
        showSection(productsSection);
    });
    
    aboutTab.addEventListener('click', function() {
        resetTabs();
        this.classList.add('border-green-500', 'text-green-600');
        this.classList.remove('border-transparent', 'text-gray-500');
        showSection(aboutSection);
    });
    
    reviewsTab.addEventListener('click', function() {
        resetTabs();
        this.classList.add('border-green-500', 'text-green-600');
        this.classList.remove('border-transparent', 'text-gray-500');
        showSection(reviewsSection);
    });
    
    // Product Sorting
    const productSort = document.getElementById('productSort');
    if (productSort) {
        productSort.addEventListener('change', function() {
            // In a real app, you would sort the products here
            console.log('Sorting by:', this.value);
        });
    }
    
    // Add to Cart
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            const storeId = this.getAttribute('data-store');
            
            // Simulate adding to cart
            this.innerHTML = '<i class="fas fa-check mr-1"></i> Added';
            this.classList.add('added');
            
            // Reset after 2 seconds
            setTimeout(() => {
                this.innerHTML = 'Add to Cart';
                this.classList.remove('added');
            }, 2000);
            
            console.log(`Added product ${productId} from store ${storeId} to cart`);
        });
    });
    
    // Star Rating for Reviews
    const stars = document.querySelectorAll('.rating-stars span');
    const ratingInput = document.getElementById('reviewRating');
    
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            ratingInput.value = rating;
            
            // Update star display
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.textContent = '★';
                    s.classList.add('active');
                } else {
                    s.textContent = '☆';
                    s.classList.remove('active');
                }
            });
        });
        
        // Hover effect
        star.addEventListener('mouseover', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.textContent = '★';
                } else {
                    s.textContent = '☆';
                }
            });
        });
        
        star.addEventListener('mouseout', function() {
            const currentRating = parseInt(ratingInput.value);
            
            stars.forEach((s, index) => {
                if (index < currentRating) {
                    s.textContent = '★';
                } else {
                    s.textContent = '☆';
                }
            });
        });
    });
    
    // Review Form Submission
    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const rating = ratingInput.value;
            const review = document.getElementById('reviewText').value;
            
            if (rating === '0') {
                alert('Please select a rating');
                return;
            }
            
            if (!review.trim()) {
                alert('Please write your review');
                return;
            }
            
            // In a real app, you would submit this to your backend
            console.log('Submitting review:', { rating, review });
            alert('Thank you for your review!');
            this.reset();
            
            // Reset stars
            stars.forEach(star => {
                star.textContent = '☆';
                star.classList.remove('active');
            });
            ratingInput.value = '0';
        });
    }
});