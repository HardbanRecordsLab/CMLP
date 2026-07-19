/**
 * HRL Form Validation
 * Client-side validation for contact and newsletter forms
 * 
 * @package HRL_Theme
 * @version 4.0.0
 */

(function() {
    'use strict';

    // Validation rules
    const validators = {
        required: (value) => value.trim() !== '',
        email: (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
        minLength: (value, min) => value.length >= min,
        maxLength: (value, max) => value.length <= max,
        phone: (value) => /^[\d\s\-\+\(\)]+$/.test(value) || value === ''
    };

    // Error messages
    const messages = {
        required: 'To pole jest wymagane',
        email: 'Podaj prawidłowy adres e-mail',
        minLength: (min) => `Minimalna długość to ${min} znaków`,
        maxLength: (max) => `Maksymalna długość to ${max} znaków`,
        phone: 'Podaj prawidłowy numer telefonu'
    };

    // Show error
    function showError(input, message) {
        clearError(input);
        input.classList.add('error');
        const errorDiv = document.createElement('div');
        errorDiv.className = 'form-error';
        errorDiv.textContent = message;
        errorDiv.setAttribute('role', 'alert');
        input.parentNode.appendChild(errorDiv);
    }

    // Clear error
    function clearError(input) {
        input.classList.remove('error');
        const errorDiv = input.parentNode.querySelector('.form-error');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    // Validate single field
    function validateField(input) {
        const value = input.value;
        const rules = input.getAttribute('data-validate');
        
        if (!rules) return true;

        const ruleList = rules.split(',');
        
        for (const rule of ruleList) {
            const [ruleName, param] = rule.split(':');
            
            if (ruleName === 'required' && !validators.required(value)) {
                showError(input, messages.required);
                return false;
            }
            
            if (ruleName === 'email' && value && !validators.email(value)) {
                showError(input, messages.email);
                return false;
            }
            
            if (ruleName === 'minLength' && value && !validators.minLength(value, parseInt(param))) {
                showError(input, messages.minLength(parseInt(param)));
                return false;
            }
            
            if (ruleName === 'maxLength' && value && !validators.maxLength(value, parseInt(param))) {
                showError(input, messages.maxLength(parseInt(param)));
                return false;
            }
            
            if (ruleName === 'phone' && value && !validators.phone(value)) {
                showError(input, messages.phone);
                return false;
            }
        }

        clearError(input);
        return true;
    }

    // Validate form
    function validateForm(form) {
        const inputs = form.querySelectorAll('input, textarea, select');
        let isValid = true;

        inputs.forEach(input => {
            if (!validateField(input)) {
                isValid = false;
            }
        });

        return isValid;
    }

    // Initialize validation
    function initValidation() {
        // Contact form
        const contactForm = document.querySelector('.contact-form');
        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                if (!validateForm(this)) {
                    e.preventDefault();
                    // Focus first invalid field
                    const firstError = this.querySelector('.error');
                    if (firstError) {
                        firstError.focus();
                    }
                }
            });

            // Real-time validation
            const inputs = contactForm.querySelectorAll('input, textarea');
            inputs.forEach(input => {
                input.addEventListener('blur', () => validateField(input));
                input.addEventListener('input', () => {
                    if (input.classList.contains('error')) {
                        validateField(input);
                    }
                });
            });
        }

        // Newsletter form
        const newsletterForm = document.querySelector('.newsletter-widget form');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', function(e) {
                if (!validateForm(this)) {
                    e.preventDefault();
                }
            });
        }

        // MKS order form
        const mksForm = document.querySelector('.formularz form');
        if (mksForm) {
            mksForm.addEventListener('submit', function(e) {
                if (!validateForm(this)) {
                    e.preventDefault();
                }
            });
        }
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initValidation);
    } else {
        initValidation();
    }
})();
