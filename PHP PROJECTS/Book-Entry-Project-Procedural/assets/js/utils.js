function isBlank(value) {
  return !value || value.trim() === '';
}

function isLengthValid(value, min, max) {
  const len = value.trim().length;
  return len >= min && len <= max;
}

function isPositiveNumber(value) {
  const num = Number(value);
  return !isNaN(num) && num > 0;
}

function isAnyChecked(checkboxes) {
  return Array.from(checkboxes).some(cb => cb.checked);
}

function isValidFile(fileInput, allowedTypes) {
  if (!fileInput.files || fileInput.files.length === 0) return false;
  const ext = fileInput.files[0].name.split('.').pop().toLowerCase();
  return allowedTypes.includes(ext);
}

function showError(errorId, message) {
  const el = document.getElementById(errorId);
  el.innerText = message;
  el.style.display = 'block';
}

function hideError(errorId) {
  const el = document.getElementById(errorId);
  el.innerText = '';
  el.style.display = 'none';
}
