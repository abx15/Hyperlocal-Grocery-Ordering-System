document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function(e) {
            e.stopPropagation();
            mobileMenu.classList.toggle('hidden');
            mobileMenu.classList.toggle('show');
        });
    }
    
    // Mobile stores dropdown
    const mobileStoresButton = document.getElementById('mobile-stores-button');
    const mobileStoresMenu = document.getElementById('mobile-stores-menu');
    
    if (mobileStoresButton && mobileStoresMenu) {
        mobileStoresButton.addEventListener('click', function(e) {
            e.stopPropagation();
            mobileStoresMenu.classList.toggle('show');
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-chevron-down');
            icon.classList.toggle('fa-chevron-up');
        });
    }
    
    // Mobile account dropdown
    const mobileAccountButton = document.getElementById('mobile-account-button');
    const mobileAccountMenu = document.getElementById('mobile-account-menu');
    
    if (mobileAccountButton && mobileAccountMenu) {
        mobileAccountButton.addEventListener('click', function(e) {
            e.stopPropagation();
            mobileAccountMenu.classList.toggle('show');
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-chevron-down');
            icon.classList.toggle('fa-chevron-up');
        });
    }
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        if (mobileMenu && !mobileMenu.contains(event.target)) {
            if (mobileMenuButton && event.target !== mobileMenuButton) {
                mobileMenu.classList.add('hidden');
                mobileMenu.classList.remove('show');
                
                // Close any open mobile dropdowns
                if (mobileStoresMenu) {
                    mobileStoresMenu.classList.remove('show');
                    const storeIcon = mobileStoresButton.querySelector('i');
                    if (storeIcon) {
                        storeIcon.classList.add('fa-chevron-down');
                        storeIcon.classList.remove('fa-chevron-up');
                    }
                }
                
                if (mobileAccountMenu) {
                    mobileAccountMenu.classList.remove('show');
                    const accountIcon = mobileAccountButton.querySelector('i');
                    if (accountIcon) {
                        accountIcon.classList.add('fa-chevron-down');
                        accountIcon.classList.remove('fa-chevron-up');
                    }
                }
            }
        }
    });
});