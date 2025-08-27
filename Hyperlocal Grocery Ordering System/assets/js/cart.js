document.addEventListener('DOMContentLoaded', function() {
    // Quantity input validation
    const quantityInputs = document.querySelectorAll('.quantity-input');
    
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.value < 1) {
                this.value = 1;
            }
        });
    });
    
    // Confirm before removing item
    const removeButtons = document.querySelectorAll('.remove-btn');
    
    removeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                e.preventDefault();
            }
        });
    });
    
    // Update cart button click handler
    const updateBtn = document.querySelector('.update-btn');
    if (updateBtn) {
        updateBtn.addEventListener('click', function() {
            // You could add additional validation here if needed
            console.log('Cart updated');
        });
    }
    
    // Calculate and update totals dynamically (client-side)
    function updateTotals() {
        let total = 0;
        
        document.querySelectorAll('tbody tr').forEach(row => {
            const price = parseFloat(row.querySelector('td:nth-child(2)').textContent.replace('$', ''));
            const quantity = parseInt(row.querySelector('.quantity-input').value);
            const subtotal = price * quantity;
            
            row.querySelector('td:nth-child(4)').textContent = '$' + subtotal.toFixed(2);
            total += subtotal;
        });
        
        document.querySelector('.total-amount').textContent = '$' + total.toFixed(2);
    }
    
    // Attach event listeners for quantity changes
    quantityInputs.forEach(input => {
        input.addEventListener('change', updateTotals);
        input.addEventListener('keyup', updateTotals);
    });
    
    // Initialize totals on page load
    updateTotals();
});