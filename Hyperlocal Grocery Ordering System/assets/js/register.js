document.addEventListener('DOMContentLoaded', function() {
    const registrationForm = document.getElementById('registrationForm');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const passwordStrength = document.getElementById('password-strength');
    const togglePasswordButtons = document.querySelectorAll('.toggle-password');
    const registerButton = document.getElementById('registerButton');

    // Toggle password visibility
    togglePasswordButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Password strength indicator
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        
        // Check password length
        if (password.length >= 8) strength++;
        
        // Check for mixed case
        if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength++;
        
        // Check for numbers
        if (password.match(/([0-9])/)) strength++;
        
        // Check for special chars
        if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) strength++;
        
        // Update strength meter
        passwordStrength.className = '';
        
        if (password.length === 0) {
            passwordStrength.textContent = '';
        } else {
            passwordStrength.textContent = '';
            
            if (strength <= 2) {
                passwordStrength.classList.add('text-red-500');
                passwordStrength.textContent = 'Weak password';
            } else if (strength === 3) {
                passwordStrength.classList.add('text-yellow-500');
                passwordStrength.textContent = 'Medium strength';
            } else {
                passwordStrength.classList.add('text-green-500');
                passwordStrength.textContent = 'Strong password';
            }
        }
    });

    // Form validation and submission
    registrationForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        let isValid = true;
        
        // Reset all errors
        document.querySelectorAll('[id$="-error"]').forEach(el => {
            el.classList.add('hidden');
        });
        document.querySelectorAll('.form-input').forEach(el => {
            el.classList.remove('border-red-500');
        });
        
        // Validate name
        const nameInput = document.getElementById('name');
        if (nameInput.value.trim().length < 2) {
            showError('name', 'Please enter your full name (at least 2 characters)');
            isValid = false;
        }
        
        // Validate email
        const emailInput = document.getElementById('email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailInput.value)) {
            showError('email', 'Please enter a valid email address');
            isValid = false;
        }
        
        // Validate phone
        const phoneInput = document.getElementById('phone');
        const phoneRegex = /^[+]?[(]?[0-9]{1,4}[)]?[-\s.]?[0-9]{1,3}[-\s.]?[0-9]{3,6}$/im;
        if (!phoneRegex.test(phoneInput.value)) {
            showError('phone', 'Please enter a valid phone number');
            isValid = false;
        }
        
        // Validate password
        if (passwordInput.value.length < 8) {
            showError('password', 'Password must be at least 8 characters');
            isValid = false;
        }
        
        // Validate password match
        if (passwordInput.value !== confirmPasswordInput.value) {
            showError('confirm_password', 'Passwords do not match');
            isValid = false;
        }
        
        // Validate address
        const addressInput = document.getElementById('address');
        if (addressInput.value.trim().length < 10) {
            showError('address', 'Please enter a complete address (at least 10 characters)');
            isValid = false;
        }
        
        // Validate terms
        const termsInput = document.getElementById('terms');
        if (!termsInput.checked) {
            showError('terms', 'You must agree to the terms and conditions');
            isValid = false;
        }
        
        if (isValid) {
            registerButton.disabled = true;
            registerButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Registering...';
            
            try {
                const formData = new FormData(registrationForm);
                const response = await fetch(registrationForm.action, {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Show success message
                    alert(result.message);
                    registrationForm.reset();
                    
                    // Redirect to login page
                    window.location.href = 'login.php';
                } else {
                    // Show error message
                    alert(result.message || 'Registration failed. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again later.');
            } finally {
                registerButton.disabled = false;
                registerButton.innerHTML = '<span class="absolute left-0 inset-y-0 flex items-center pl-3">' +
                    '<i class="fas fa-user-plus text-green-300 group-hover:text-green-200"></i>' +
                    '</span>Register Now';
            }
        }
    });
    
    // Helper function to show errors
    function showError(field, message) {
        const input = document.getElementById(field);
        const errorElement = document.getElementById(`${field}-error`);
        
        input.classList.add('border-red-500');
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
    }
});