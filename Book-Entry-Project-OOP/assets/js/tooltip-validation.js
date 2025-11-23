// Custom Validation Tooltip System
class ValidationTooltip {
    constructor() {
        this.activeTooltips = new Map();
        this.init();
    }

    init() {
        // Disable browser default validation
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.noValidate = true;
        });

        // Wrap form fields in containers for positioning
        this.wrapFormFields();
    }

    wrapFormFields() {
        const formControls = document.querySelectorAll('.form-control, .form-check-input');
        formControls.forEach(field => {
            if (!field.closest('.form-field-wrapper')) {
                const wrapper = document.createElement('div');
                wrapper.className = 'form-field-wrapper';
                field.parentNode.insertBefore(wrapper, field);
                wrapper.appendChild(field);
            }
        });
    }

    showTooltip(fieldId, message, type = 'error') {
        this.hideTooltip(fieldId);

        const field = document.getElementById(fieldId);
        if (!field) return;

        const wrapper = field.closest('.form-field-wrapper');
        if (!wrapper) return;

        const tooltip = document.createElement('div');
        tooltip.className = `validation-tooltip ${type === 'success' ? 'success' : type === 'warning' ? 'warning' : ''}`;
        tooltip.textContent = message;
        tooltip.id = `tooltip-${fieldId}`;

        // Position tooltip
        wrapper.appendChild(tooltip);
        this.positionTooltip(field, tooltip);

        // Show tooltip with animation
        setTimeout(() => {
            tooltip.classList.add('show');
        }, 10);

        // Store reference
        this.activeTooltips.set(fieldId, tooltip);

        // Auto-hide after 5 seconds for non-error tooltips
        if (type !== 'error') {
            setTimeout(() => {
                this.hideTooltip(fieldId);
            }, 5000);
        }

        // Update field styling
        this.updateFieldStyling(field, type);
    }

    hideTooltip(fieldId) {
        const existingTooltip = this.activeTooltips.get(fieldId);
        if (existingTooltip) {
            existingTooltip.classList.remove('show');
            setTimeout(() => {
                if (existingTooltip.parentNode) {
                    existingTooltip.parentNode.removeChild(existingTooltip);
                }
            }, 300);
            this.activeTooltips.delete(fieldId);
        }

        // Reset field styling
        const field = document.getElementById(fieldId);
        if (field) {
            field.classList.remove('invalid', 'valid');
        }
    }

    hideAllTooltips() {
        this.activeTooltips.forEach((tooltip, fieldId) => {
            this.hideTooltip(fieldId);
        });
    }

    positionTooltip(field, tooltip) {
        const fieldRect = field.getBoundingClientRect();
        const wrapperRect = field.closest('.form-field-wrapper').getBoundingClientRect();
        
        // Position below the field
        tooltip.style.top = (fieldRect.height + 5) + 'px';
        tooltip.style.left = '0px';

        // Check if tooltip would be cut off at bottom of viewport
        const tooltipRect = tooltip.getBoundingClientRect();
        if (tooltipRect.bottom > window.innerHeight - 20) {
            // Position above the field
            tooltip.style.top = '-' + (tooltip.offsetHeight + 5) + 'px';
            tooltip.classList.add('bottom');
        }
    }

    updateFieldStyling(field, type) {
        field.classList.remove('invalid', 'valid');
        if (type === 'error') {
            field.classList.add('invalid');
        } else if (type === 'success') {
            field.classList.add('valid');
        }
    }

    // Method to show success tooltip
    showSuccess(fieldId, message = 'Valid!') {
        this.showTooltip(fieldId, message, 'success');
    }

    // Method to show warning tooltip
    showWarning(fieldId, message) {
        this.showTooltip(fieldId, message, 'warning');
    }

    // Method to show error tooltip
    showError(fieldId, message) {
        this.showTooltip(fieldId, message, 'error');
    }
}

// Initialize tooltip system
let validationTooltip;

document.addEventListener('DOMContentLoaded', function() {
    validationTooltip = new ValidationTooltip();
});

// Enhanced validation functions with tooltip integration
function showValidationTooltip(fieldId, message, type = 'error') {
    if (validationTooltip) {
        if (type === 'success') {
            validationTooltip.showSuccess(fieldId, message);
        } else if (type === 'warning') {
            validationTooltip.showWarning(fieldId, message);
        } else {
            validationTooltip.showError(fieldId, message);
        }
    }
}

function hideValidationTooltip(fieldId) {
    if (validationTooltip) {
        validationTooltip.hideTooltip(fieldId);
    }
}

function hideAllValidationTooltips() {
    if (validationTooltip) {
        validationTooltip.hideAllTooltips();
    }
}

// Hide tooltips when user starts typing
function clearErrorMessage(elementId) {
    const fieldId = elementId.replace('_error', '');
    hideValidationTooltip(fieldId);
    
    // Also clear any existing error spans
    const errorElement = document.getElementById(elementId);
    if (errorElement) {
        errorElement.innerText = "";
    }
}

// Enhanced validation functions that show tooltips instead of inline errors
function blankValidationTooltip(fieldId, fieldName) {
    const field = document.getElementById(fieldId);
    if (field.value.trim() === "") {
        showValidationTooltip(fieldId, `${fieldName} cannot be blank`);
        field.focus();
        return false;
    }
    return true;
}

function allowOnlyAlphabetsTooltip(fieldId, fieldName) {
    const regex = /^[A-Za-z\s]+$/;
    const field = document.getElementById(fieldId);
    if (!regex.test(field.value)) {
        showValidationTooltip(fieldId, `${fieldName} must contain only letters and spaces`);
        field.focus();
        return false;
    }
    return true;
}

function allowSpecificRangeLengthTooltip(fieldId, fieldName, minLength, maxLength) {
    const field = document.getElementById(fieldId);
    if (field.value.length < minLength || field.value.length > maxLength) {
        showValidationTooltip(fieldId, `${fieldName} must be between ${minLength} and ${maxLength} characters`);
        field.focus();
        return false;
    }
    return true;
}

function validateEmailFormatTooltip(fieldId, fieldName) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const field = document.getElementById(fieldId);
    if (!regex.test(field.value)) {
        showValidationTooltip(fieldId, `Please enter a valid ${fieldName.toLowerCase()} address`);
        field.focus();
        return false;
    }
    return true;
}

function validatePasswordStrengthTooltip(fieldId, fieldName) {
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    const field = document.getElementById(fieldId);
    if (!regex.test(field.value)) {
        showValidationTooltip(fieldId, `${fieldName} must be at least 8 characters with uppercase, lowercase, number, and special character`);
        field.focus();
        return false;
    }
    return true;
}

function validatePhoneFormatTooltip(fieldId, fieldName) {
    const field = document.getElementById(fieldId);
    const cleanPhone = field.value.replace(/[\s\-\(\)\.]/g, '');
    
    if (cleanPhone.length < 10 || cleanPhone.length > 15) {
        showValidationTooltip(fieldId, `${fieldName} must be between 10-15 digits`);
        field.focus();
        return false;
    }
    
    if (!/^\+?[\d\s\-\(\)\.]+$/.test(field.value)) {
        showValidationTooltip(fieldId, `${fieldName} contains invalid characters`);
        field.focus();
        return false;
    }
    return true;
}

function validateAgeTooltip(fieldId) {
    const field = document.getElementById(fieldId);
    const birthDate = new Date(field.value);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    
    if (age < 13) {
        showValidationTooltip(fieldId, "You must be at least 13 years old to register");
        field.focus();
        return false;
    }
    
    if (age > 120) {
        showValidationTooltip(fieldId, "Please enter a valid birth date");
        field.focus();
        return false;
    }
    
    if (birthDate > today) {
        showValidationTooltip(fieldId, "Birth date cannot be in the future");
        field.focus();
        return false;
    }
    
    return true;
}

function validateZipFormatTooltip(fieldId, fieldName) {
    const field = document.getElementById(fieldId);
    const value = field.value.trim();
    const regex = /^[A-Za-z0-9\s\-]{3,10}$/;
    
    if (!regex.test(value)) {
        showValidationTooltip(fieldId, `${fieldName} must be 3-10 characters (letters, numbers, spaces, hyphens only)`);
        field.focus();
        return false;
    }
    return true;
}