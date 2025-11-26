function validate_registration_form() {
    let isValid = true;
    
    // Hide all existing tooltips
    hideAllValidationTooltips();
    
    // Validate all fields and collect errors
    const validationResults = [];
    
    validationResults.push({ valid: validateFullName(), field: 'fullname' });
    validationResults.push({ valid: validateEmail(), field: 'email' });
    validationResults.push({ valid: validatePassword(), field: 'password' });
    validationResults.push({ valid: validateConfirmPassword(), field: 'confirm_password' });
    validationResults.push({ valid: validatePhone(), field: 'phone' });
    validationResults.push({ valid: validateDateOfBirth(), field: 'dob' });
    validationResults.push({ valid: validateGender(), field: 'gender_male' });
    validationResults.push({ valid: validateQualification(), field: 'qualification_bachelor' });
    validationResults.push({ valid: validateAddress(), field: 'address' });
    validationResults.push({ valid: validateCountry(), field: 'country' });
    validationResults.push({ valid: validateZipCode(), field: 'zip' });
    
    // Check if any validation failed
    const failedValidations = validationResults.filter(result => !result.valid);
    
    if (failedValidations.length > 0) {
        isValid = false;
        
        // Focus on first failed field
        const firstFailedField = document.getElementById(failedValidations[0].field);
        if (firstFailedField) {
            firstFailedField.focus();
            firstFailedField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        // Show summary tooltip on submit button
        const submitButton = document.querySelector('button[type="submit"]');
        if (submitButton && validationTooltip) {
            validationTooltip.showError('submit-button', `Please fix ${failedValidations.length} error(s) in the form`);
            
            // Create temporary ID for submit button if it doesn't exist
            if (!submitButton.id) {
                submitButton.id = 'submit-button';
                submitButton.parentNode.classList.add('form-field-wrapper');
            }
        }
    } else {
        // All validations passed - show success message
        const submitButton = document.querySelector('button[type="submit"]');
        if (submitButton && validationTooltip) {
            if (!submitButton.id) {
                submitButton.id = 'submit-button';
                submitButton.parentNode.classList.add('form-field-wrapper');
            }
            validationTooltip.showSuccess('submit-button', '✓ All fields are valid! Submitting...');
        }
    }
    
    return isValid;
}


function clearErrorMessage(elementId) {
    document.getElementById(elementId).innerText = "";
}

function clearAllErrors() {
    const errorElements = [
        'fullname_error', 'email_error', 'password_error', 'confirm_password_error',
        'phone_error', 'dob_error', 'gender_error', 'qualification_error',
        'address_error', 'country_error', 'zip_error'
    ];
    
    errorElements.forEach(elementId => {
        const element = document.getElementById(elementId);
        if (element) {
            element.innerText = "";
        }
    });
}

 

function validateFullName() {
    if (!blankValidationTooltip('fullname', 'Full Name')) {
        return false;
    }
    if (!allowOnlyAlphabetsTooltip('fullname', 'Full Name')) {
        return false;
    }
    if (!allowSpecificRangeLengthTooltip('fullname', 'Full Name', 3, 30)) {
        return false;
    }
    
    // Show success tooltip
    showValidationTooltip('fullname', '✓ Valid full name', 'success');
    return true;
}

function validateEmail() {
    if (!blankValidationTooltip('email', 'Email')) {
        return false;
    }
    if (!validateEmailFormatTooltip('email', 'Email')) {
        return false;
    }
    
    // Show success tooltip
    showValidationTooltip('email', '✓ Valid email address', 'success');
    return true;
}

function validatePassword() {
    if (!blankValidationTooltip('password', 'Password')) {
        return false;
    } 
    if (!validatePasswordStrengthTooltip('password', 'Password')) {
        return false;
    }
    
    // Show success tooltip
    showValidationTooltip('password', '✓ Strong password', 'success');
    return true;
}

function validateConfirmPassword() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (!blankValidationTooltip('confirm_password', 'Confirm Password')) {
        return false;
    }
    
    if (password !== confirmPassword) {
        showValidationTooltip('confirm_password', 'Passwords do not match');
        document.getElementById('confirm_password').focus();
        return false;
    }
    
    // Show success tooltip
    showValidationTooltip('confirm_password', '✓ Passwords match', 'success');
    return true;
}

function validatePhone() {
    if (!blankValidationTooltip('phone', 'Phone Number')) {
        return false;
    }
    if (!validatePhoneFormatTooltip('phone', 'Phone Number')) {
        return false;
    }
    
    // Show success tooltip
    showValidationTooltip('phone', '✓ Valid phone number', 'success');
    return true;
}

function validateDateOfBirth() {
    if (!blankValidationTooltip('dob', 'Date of Birth')) {
        return false;
    }
    if (!validateAgeTooltip('dob')) {
        return false;
    }
    
    // Show success tooltip
    showValidationTooltip('dob', '✓ Valid date of birth', 'success');
    return true;
}

function validateGender() {
    const maleRadio = document.getElementById('gender_male');
    const femaleRadio = document.getElementById('gender_female');
    
    if (!maleRadio.checked && !femaleRadio.checked) {
        showValidationTooltip('gender_male', 'Please select your gender');
        return false;
    }
    
    // Show success tooltip on the checked radio
    const checkedGender = maleRadio.checked ? 'gender_male' : 'gender_female';
    showValidationTooltip(checkedGender, '✓ Gender selected', 'success');
    return true;
}

function validateQualification() {
    const bachelorCheckbox = document.getElementById('qualification_bachelor');
    const masterCheckbox = document.getElementById('qualification_master');
    
    if (!bachelorCheckbox.checked && !masterCheckbox.checked) {
        showValidationTooltip('qualification_bachelor', 'Please select at least one qualification');
        return false;
    }
    
    // Show success tooltip on first checked checkbox
    const checkedQualification = bachelorCheckbox.checked ? 'qualification_bachelor' : 'qualification_master';
    showValidationTooltip(checkedQualification, '✓ Qualification selected', 'success');
    return true;
}

function validateAddress() {
    if (!blankValidationTooltip('address', 'Address')) {
        return false;
    }
    if (!allowSpecificRangeLengthTooltip('address', 'Address', 10, 200)) {
        return false;
    }
    
    // Show success tooltip
    showValidationTooltip('address', '✓ Valid address', 'success');
    return true;
}

function validateCountry() {
    if (!blankValidationTooltip('country', 'Country')) {
        return false;
    }
    
    // Show success tooltip
    showValidationTooltip('country', '✓ Country selected', 'success');
    return true;
}

function validateZipCode() {
    if (!blankValidationTooltip('zip', 'Zip Code')) {
        return false;
    }
    if (!validateZipFormatTooltip('zip', 'Zip Code')) {
        return false;
    }
    
    // Show success tooltip
    showValidationTooltip('zip', '✓ Valid zip code', 'success');
    return true;
}