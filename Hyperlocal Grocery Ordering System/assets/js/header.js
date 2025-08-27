document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle - keep existing code
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function(e) {
            e.stopPropagation();
            mobileMenu.classList.toggle('hidden');
            mobileMenu.classList.toggle('active');
            
            const icon = this.querySelector('i');
            if (mobileMenu.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    }
    
    // Improved desktop dropdown handling
    const accountDropdown = document.getElementById('account-dropdown');
    if (accountDropdown) {
        const dropdownToggle = accountDropdown.querySelector('.dropdown-toggle');
        const dropdownMenu = accountDropdown.querySelector('.dropdown-menu');
        let dropdownTimeout;
        
        // Show dropdown on hover
        accountDropdown.addEventListener('mouseenter', function() {
            clearTimeout(dropdownTimeout);
            dropdownMenu.classList.remove('hidden');
        });
        
        // Hide dropdown after delay when leaving
        accountDropdown.addEventListener('mouseleave', function() {
            dropdownTimeout = setTimeout(() => {
                dropdownMenu.classList.add('hidden');
            }, 300); // 300ms delay before hiding
        });
        
        // Cancel hide if re-entering dropdown
        dropdownMenu.addEventListener('mouseenter', function() {
            clearTimeout(dropdownTimeout);
        });
        
        // Hide when leaving dropdown menu
        dropdownMenu.addEventListener('mouseleave', function() {
            dropdownTimeout = setTimeout(() => {
                dropdownMenu.classList.add('hidden');
            }, 500);
        });
    }
    
    // Mobile dropdown toggles - keep existing code
    document.querySelectorAll('.mobile-dropdown-toggle').forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = this.nextElementSibling;
            const icon = this.querySelector('i');
            
            document.querySelectorAll('.mobile-dropdown-menu').forEach(menu => {
                if (menu !== dropdown && menu.classList.contains('active')) {
                    menu.classList.remove('active');
                    const otherIcon = menu.previousElementSibling.querySelector('i');
                    otherIcon.classList.remove('active');
                }
            });
            
            dropdown.classList.toggle('active');
            this.classList.toggle('active');
            icon.classList.toggle('active');
        });
    });
    
    // Close mobile menu when clicking outside - keep existing code
    document.addEventListener('click', function(e) {
        if (!mobileMenu.contains(e.target) && e.target !== mobileMenuButton) {
            mobileMenu.classList.remove('active');
            mobileMenu.classList.add('hidden');
            
            if (mobileMenuButton) {
                const icon = mobileMenuButton.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
            
            document.querySelectorAll('.mobile-dropdown-menu').forEach(menu => {
                menu.classList.remove('active');
                const toggle = menu.previousElementSibling;
                toggle.classList.remove('active');
                const icon = toggle.querySelector('i');
                icon.classList.remove('active');
            });
        }
    });
    
    // Prevent dropdown menus from closing - keep existing code
    document.querySelectorAll('.dropdown-menu, .mobile-dropdown-menu').forEach(menu => {
        menu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
    
    // Cart count animation - keep existing code
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        cartCount.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
        });
        
        cartCount.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    }
});