document.addEventListener('DOMContentLoaded', function() {
    // Handle sort by change
    document.getElementById('sortBy').addEventListener('change', function() {
        document.getElementById('sortValue').value = this.value;
        document.querySelector('form').submit();
    });

    // Store modal functionality
    const modal = document.getElementById('storeModal');
    const closeModal = document.getElementById('closeModal');
    const viewStoreButtons = document.querySelectorAll('.view-store-details');

    viewStoreButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Get store data from data attributes
            const storeId = this.getAttribute('data-store-id');
            const storeName = this.getAttribute('data-store-name');
            const storeDescription = this.getAttribute('data-store-description');
            const storeImage = this.getAttribute('data-store-image');
            const storeRating = parseFloat(this.getAttribute('data-store-rating'));
            const storeDistance = this.getAttribute('data-store-distance');
            const storeAddress = this.getAttribute('data-store-address');
            const storeHours = this.getAttribute('data-store-hours');
            const storeDelivery = this.getAttribute('data-store-delivery');
            const storeCategories = this.getAttribute('data-store-categories');

            // Populate modal
            document.getElementById('modalStoreName').textContent = storeName;
            document.getElementById('modalStoreDescription').textContent = storeDescription;
            document.getElementById('modalStoreImage').src = 'assets/images/stores/' + storeImage;
            document.getElementById('modalStoreRating').textContent = storeRating.toFixed(1);
            document.getElementById('modalStoreDistance').textContent = storeDistance;
            document.getElementById('modalStoreAddress').textContent = storeAddress;
            document.getElementById('modalStoreHours').textContent = storeHours;
            document.getElementById('modalStoreDelivery').textContent = storeDelivery;
            document.getElementById('modalStoreCategories').textContent = storeCategories;
            document.getElementById('modalStoreLink').href = 'store.php?id=' + storeId;

            // Generate star rating
            const starsContainer = document.getElementById('modalStoreStars');
            starsContainer.innerHTML = '';
            const fullStars = Math.floor(storeRating);
            const hasHalfStar = storeRating % 1 >= 0.5;

            for (let i = 0; i < 5; i++) {
                const star = document.createElement('i');
                if (i < fullStars) {
                    star.className = 'fas fa-star';
                } else if (i === fullStars && hasHalfStar) {
                    star.className = 'fas fa-star-half-alt';
                } else {
                    star.className = 'far fa-star';
                }
                starsContainer.appendChild(star);
            }

            // Show modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    });

    // Close modal
    closeModal.addEventListener('click', function() {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    });

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    });

    // Auto-submit filters when changed (except search)
    document.getElementById('categoryFilter').addEventListener('change', function() {
        document.querySelector('form').submit();
    });

    document.getElementById('distanceFilter').addEventListener('change', function() {
        document.querySelector('form').submit();
    });

    // Debounce search input
    const searchInput = document.getElementById('storeSearch');
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.querySelector('form').submit();
        }, 500);
    });
});