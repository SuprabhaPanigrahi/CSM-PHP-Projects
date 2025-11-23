function blankValidation(fieldId, errorElementId, fieldName) {
  let field = document.getElementById(fieldId);
  if (field.value.trim() === "") {
    document.getElementById(errorElementId).innerText = fieldName + " cannot be blank";
    field.focus();
    return false;
  }
  return true;
}

function allowOnlyAlphabets(fieldId, errorElementId, fieldName) {
  let regex = /^[A-Za-z\s]+$/;
  let field = document.getElementById(fieldId);
  if (!regex.test(field.value)) {
    document.getElementById(errorElementId).innerText = fieldName + " must be alphabetic";
    field.focus();
    return false;
  }
  return true;
}

function allowSpecificRangeLength(fieldId, errorElementId, fieldName, minLength, maxLength) {
  let field = document.getElementById(fieldId); 
  if (field.value.length < minLength || field.value.length > maxLength) {
    document.getElementById(errorElementId).innerText = fieldName + " must be at least " + minLength + " characters and at most " + maxLength + " characters";
    field.focus();
    return false;
  }
  return true;
}

function validateEmailFormat(fieldId, errorElementId, fieldName) {
  // alert("validateEmailFormat called");
  let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  let field = document.getElementById(fieldId);
  // alert(field.value);
  // alert(regex.test(field.value));
  if (!regex.test(field.value)) {
    document.getElementById(errorElementId).innerText = "Invalid " + fieldName + " format";
    field.focus();
    return false;
  }
  return true;
}

function validatePasswordStrength(fieldId, errorElementId, fieldName) {
  let regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
  let field = document.getElementById(fieldId); 
  if (!regex.test(field.value)) {
    document.getElementById(errorElementId).innerText = fieldName + " must be at least 8 characters long and include uppercase, lowercase, number, and special character";
    field.focus();
    return false;
  }
  return true; 
}

function validatePhoneFormat(fieldId, errorElementId, fieldName) {
  // Supports formats: +1234567890, 123-456-7890, (123) 456-7890, 123.456.7890, 1234567890
  let regex = /^[\+]?[1-9][\d]{0,15}$|^[\+]?[(]?[\d\s\-\(\)\.]{10,}$/;
  let field = document.getElementById(fieldId);
  let cleanPhone = field.value.replace(/[\s\-\(\)\.]/g, '');
  
  if (cleanPhone.length < 10 || cleanPhone.length > 15) {
    document.getElementById(errorElementId).innerText = fieldName + " must be between 10-15 digits";
    field.focus();
    return false;
  }
  
  if (!/^\+?[\d\s\-\(\)\.]+$/.test(field.value)) {
    document.getElementById(errorElementId).innerText = fieldName + " contains invalid characters";
    field.focus();
    return false;
  }
  return true;
}

function validateAge(fieldId, errorElementId) {
  let field = document.getElementById(fieldId);
  let birthDate = new Date(field.value);
  let today = new Date();
  let age = today.getFullYear() - birthDate.getFullYear();
  let monthDiff = today.getMonth() - birthDate.getMonth();
  
  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }
  
  if (age < 13) {
    document.getElementById(errorElementId).innerText = "You must be at least 13 years old";
    field.focus();
    return false;
  }
  
  if (age > 120) {
    document.getElementById(errorElementId).innerText = "Please enter a valid birth date";
    field.focus();
    return false;
  }
  
  if (birthDate > today) {
    document.getElementById(errorElementId).innerText = "Birth date cannot be in the future";
    field.focus();
    return false;
  }
  
  return true;
}

function validateZipFormat(fieldId, errorElementId, fieldName) {
  let field = document.getElementById(fieldId);
  let value = field.value.trim();
  
  // US ZIP: 12345 or 12345-6789
  // UK/Canada: A1A 1A1
  // General: 3-10 alphanumeric characters
  let regex = /^[A-Za-z0-9\s\-]{3,10}$/;
  
  if (!regex.test(value)) {
    document.getElementById(errorElementId).innerText = fieldName + " must be 3-10 characters (letters, numbers, spaces, hyphens only)";
    field.focus();
    return false;
  }
  return true;
}

function validateAlphanumeric(fieldId, errorElementId, fieldName) {
  let regex = /^[A-Za-z0-9\s]+$/;
  let field = document.getElementById(fieldId);
  if (!regex.test(field.value)) {
    document.getElementById(errorElementId).innerText = fieldName + " must contain only letters, numbers, and spaces";
    field.focus();
    return false;
  }
  return true;
}

function showPasswordStrength(fieldId, strengthElementId) {
  let field = document.getElementById(fieldId);
  let strengthElement = document.getElementById(strengthElementId);
  let password = field.value;
  
  if (password.length === 0) {
    strengthElement.innerHTML = '';
    return;
  }
  
  let strength = 0;
  let feedback = [];
  
  // Length check
  if (password.length >= 8) strength++;
  else feedback.push('At least 8 characters');
  
  // Lowercase check
  if (/[a-z]/.test(password)) strength++;
  else feedback.push('Lowercase letter');
  
  // Uppercase check
  if (/[A-Z]/.test(password)) strength++;
  else feedback.push('Uppercase letter');
  
  // Number check
  if (/\d/.test(password)) strength++;
  else feedback.push('Number');
  
  // Special character check
  if (/[@$!%*?&]/.test(password)) strength++;
  else feedback.push('Special character');
  
  let strengthText = '';
  let strengthClass = '';
  
  switch (strength) {
    case 0:
    case 1:
      strengthText = 'Very Weak';
      strengthClass = 'text-danger';
      break;
    case 2:
      strengthText = 'Weak';
      strengthClass = 'text-warning';
      break;
    case 3:
      strengthText = 'Fair';
      strengthClass = 'text-info';
      break;
    case 4:
      strengthText = 'Good';
      strengthClass = 'text-primary';
      break;
    case 5:
      strengthText = 'Strong';
      strengthClass = 'text-success';
      break;
  }
  
  let feedbackText = feedback.length > 0 ? '<br><small>Missing: ' + feedback.join(', ') + '</small>' : '';
  strengthElement.innerHTML = `<span class="${strengthClass}">Password Strength: ${strengthText}</span>${feedbackText}`;
}

// Real-time validation feedback
function setFieldValidation(fieldId, isValid) {
  let field = document.getElementById(fieldId);
  field.classList.remove('is-valid', 'is-invalid');
  
  if (isValid) {
    field.classList.add('is-valid');
  } else {
    field.classList.add('is-invalid');
  }
}

// Enhanced error display with styling
function showError(errorElementId, message) {
  let errorElement = document.getElementById(errorElementId);
  errorElement.innerText = message;
  errorElement.style.display = 'block';
}

function hideError(errorElementId) {
  let errorElement = document.getElementById(errorElementId);
  errorElement.innerText = '';
  errorElement.style.display = 'none';
}

