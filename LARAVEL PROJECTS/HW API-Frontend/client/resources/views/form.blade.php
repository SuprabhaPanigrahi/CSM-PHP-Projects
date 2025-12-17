@extends('layouts.app')

@section('title', 'Submit Form')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <h2 class="mb-4"><i class="fas fa-edit me-2"></i>Multi-Field Form Submission</h2>
        <p class="text-muted mb-4">Fill out all fields below. All data will be sent to the backend API.</p>

        <form id="multiForm" enctype="multipart/form-data">
            <!-- Personal Information -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-user me-2"></i>Personal Information
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback">Please enter your name.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback">Please enter a valid email.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dropdown Selection -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-globe me-2"></i>Location
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="country" class="form-label">Country *</label>
                        <select class="form-select" id="country" name="country" required>
                            <option value="">Select a country</option>
                            <option value="USA">United States</option>
                            <option value="UK">United Kingdom</option>
                            <option value="Canada">Canada</option>
                            <option value="Australia">Australia</option>
                            <option value="India">India</option>
                            <option value="Germany">Germany</option>
                            <option value="Japan">Japan</option>
                        </select>
                        <div class="invalid-feedback">Please select a country.</div>
                    </div>
                </div>
            </div>

            <!-- File Upload -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-image me-2"></i>Image Upload
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Profile Image (Optional)</label>
                        <div class="file-upload-area">
                            <input type="file" class="file-upload-input" id="image" name="image" accept="image/*">
                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                            <h5>Click to upload or drag and drop</h5>
                            <p class="text-muted">PNG, JPG, GIF up to 2MB</p>
                            <img id="imagePreview" class="image-preview" alt="Preview">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Radio Buttons -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-venus-mars me-2"></i>Gender *
                </div>
                <div class="card-body">
                    <div class="radio-group">
                        <div class="radio-item">
                            <div class="custom-radio">
                                <input class="form-check-input" type="radio" name="gender" id="male" value="male" required>
                                <label class="form-check-label" for="male">
                                    <i class="fas fa-male me-2"></i>Male
                                </label>
                            </div>
                        </div>
                        <div class="radio-item">
                            <div class="custom-radio">
                                <input class="form-check-input" type="radio" name="gender" id="female" value="female">
                                <label class="form-check-label" for="female">
                                    <i class="fas fa-female me-2"></i>Female
                                </label>
                            </div>
                        </div>
                        <div class="radio-item">
                            <div class="custom-radio">
                                <input class="form-check-input" type="radio" name="gender" id="other" value="other">
                                <label class="form-check-label" for="other">
                                    <i class="fas fa-user me-2"></i>Other
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="invalid-feedback d-block" id="genderError" style="display: none;">Please select a gender.</div>
                </div>
            </div>

            <!-- Checkboxes -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-heart me-2"></i>Interests
                </div>
                <div class="card-body">
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <div class="custom-checkbox">
                                <input class="form-check-input" type="checkbox" name="interests[]" id="sports" value="sports">
                                <label class="form-check-label" for="sports">
                                    <i class="fas fa-football-ball me-2"></i>Sports
                                </label>
                            </div>
                        </div>
                        <div class="checkbox-item">
                            <div class="custom-checkbox">
                                <input class="form-check-input" type="checkbox" name="interests[]" id="music" value="music">
                                <label class="form-check-label" for="music">
                                    <i class="fas fa-music me-2"></i>Music
                                </label>
                            </div>
                        </div>
                        <div class="checkbox-item">
                            <div class="custom-checkbox">
                                <input class="form-check-input" type="checkbox" name="interests[]" id="reading" value="reading">
                                <label class="form-check-label" for="reading">
                                    <i class="fas fa-book me-2"></i>Reading
                                </label>
                            </div>
                        </div>
                        <div class="checkbox-item">
                            <div class="custom-checkbox">
                                <input class="form-check-input" type="checkbox" name="interests[]" id="travel" value="travel">
                                <label class="form-check-label" for="travel">
                                    <i class="fas fa-plane me-2"></i>Travel
                                </label>
                            </div>
                        </div>
                        <div class="checkbox-item">
                            <div class="custom-checkbox">
                                <input class="form-check-input" type="checkbox" name="interests[]" id="coding" value="coding">
                                <label class="form-check-label" for="coding">
                                    <i class="fas fa-code me-2"></i>Coding
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Textarea -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-comment me-2"></i>Additional Message
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="message" class="form-label">Your Message</label>
                        <textarea class="form-control" id="message" name="message" rows="4" placeholder="Enter any additional information..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Terms Checkbox -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="terms_accepted" name="terms_accepted" required>
                        <label class="form-check-label" for="terms_accepted">
                            I agree to the <a href="#" class="text-decoration-none">Terms and Conditions</a> *
                        </label>
                        <div class="invalid-feedback">You must agree to the terms.</div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="button" class="btn btn-secondary me-md-2" onclick="resetForm()">
                    <i class="fas fa-redo me-2"></i>Reset Form
                </button>
                <button type="submit" class="btn btn-primary-custom" id="submitBtn">
                    <i class="fas fa-paper-plane me-2"></i>Submit Form
                </button>
            </div>
        </form>

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="text-center mt-4" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Submitting form to API...</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Image preview
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagePreview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });

    // Form submission
    // Form submission
    document.getElementById('multiForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        // Validate form
        if (!validateForm()) {
            return;
        }

        // Show loading
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending to backend (port 5000)...';
        submitBtn.disabled = true;
        document.getElementById('loadingSpinner').style.display = 'block';

        try {
            // Collect form data
            const formData = new FormData();

            // Add text fields
            formData.append('name', document.getElementById('name').value.trim());
            formData.append('email', document.getElementById('email').value.trim());
            formData.append('phone', document.getElementById('phone').value.trim() || '');
            formData.append('country', document.getElementById('country').value);
            formData.append('gender', document.querySelector('input[name="gender"]:checked').value);
            formData.append('message', document.getElementById('message').value.trim() || '');
            formData.append('terms_accepted', document.getElementById('terms_accepted').checked ? '1' : '0');

            // Add image
            const imageFile = document.getElementById('image').files[0];
            if (imageFile) {
                formData.append('image', imageFile);
            }

            // Add interests
            const interests = [];
            document.querySelectorAll('input[name="interests[]"]:checked').forEach(checkbox => {
                interests.push(checkbox.value);
            });
            if (interests.length > 0) {
                formData.append('interests', JSON.stringify(interests));
            }

            // Send to backend API on port 5000
            console.log('Sending to backend port 5000:', API_BASE_URL + '/form/submit');

            const response = await fetch(API_BASE_URL + '/form/submit', {
                method: 'POST',
                body: formData
                // Note: Don't set Content-Type header for FormData
            });

            const data = await response.json();

            console.log('API Response from port 5000:', data);

            if (response.ok && data.success) {
                showToast('Form submitted successfully to backend (port 5000)!', 'success');
                setTimeout(() => {
                    resetForm();
                    // Redirect to submissions page after successful submission
                    window.location.href = '/submissions';
                }, 2000);
            } else {
                throw new Error(data.message || `Submission failed with status ${response.status}`);
            }
        } catch (error) {
            console.error('Error:', error);
            showToast(`Error connecting to backend (port 5000): ${error.message}`, 'error');
        } finally {
            // Reset button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            document.getElementById('loadingSpinner').style.display = 'none';
        }
    });

    // Form validation
    function validateForm() {
        let isValid = true;

        // Reset validation styles
        document.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });

        // Check required fields
        const requiredFields = ['name', 'email', 'country'];
        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            }
        });

        // Check gender
        const genderSelected = document.querySelector('input[name="gender"]:checked');
        if (!genderSelected) {
            document.getElementById('genderError').style.display = 'block';
            isValid = false;
        } else {
            document.getElementById('genderError').style.display = 'none';
        }

        // Check terms
        const termsAccepted = document.getElementById('terms_accepted');
        if (!termsAccepted.checked) {
            termsAccepted.classList.add('is-invalid');
            isValid = false;
        }

        return isValid;
    }

    // Reset form
    function resetForm() {
        document.getElementById('multiForm').reset();
        document.getElementById('imagePreview').style.display = 'none';

        // Reset validation styles
        document.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
        document.getElementById('genderError').style.display = 'none';
    }
</script>
@endpush