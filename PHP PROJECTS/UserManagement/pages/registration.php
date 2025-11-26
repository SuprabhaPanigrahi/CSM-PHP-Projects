<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>user-registration</title>
    <?php include '../includes/styles.php'; ?>
    <style>
        .form-label {
            font-weight: 500;
            margin-bottom: 5px;
        }
        .text-danger {
            font-size: 0.875em;
        }
        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        .form-control.is-valid {
            border-color: #28a745;
        }
        .required-fields-note {
            color: #6c757d;
            font-size: 0.875em;
            margin-bottom: 1rem;
        }
        .password-requirements {
            font-size: 0.8em;
            color: #6c757d;
            margin-top: 5px;
        }
        .form-check {
            margin-bottom: 0.5rem;
        }
        .form-check-input {
            margin-top: 0.25rem;
        }
        .form-check-label {
            margin-left: 0.25rem;
        }
    </style>
</head>

<body>
    <div class="containert m-5 p-5">
        <h1 class="text-center">User Registration</h1>
        <div class="container m-5 p-5 border rounded shadow w-75 mx-auto">
            <div class="mb-4">
                <h6>Form Completion Progress</h6>
                <div class="progress">
                    <div class="progress-bar" id="form-progress" role="progressbar" style="width: 0%">0%</div>
                </div>
            </div>
            <p class="required-fields-note"><span class="text-danger">*</span> indicates required fields</p>
            <form action="../process/register-user.php" method="POST" class="p-4"
                onsubmit="return validate_registration_form()">>
                <fieldset class="p-5">
                    <legend>Personal Information</legend>
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Full Name: <span class="text-danger">*</span></label>
                        <div class="form-field-wrapper">
                            <input type="text" id="fullname" name="fullname" class="form-control"
                                onkeyup="clearErrorMessage('fullname_error')" onblur="validateFullName()" 
                                placeholder="Enter your full name" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email: <span class="text-danger">*</span></label>
                        <div class="form-field-wrapper">
                            <input type="email" id="email" name="email" class="form-control" 
                                onkeyup="clearErrorMessage('email_error')" onblur="validateEmail()"
                                placeholder="Enter your email address" required />
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password: <span class="text-danger">*</span></label>
                        <div class="form-field-wrapper">
                            <input type="password" id="password" name="password" class="form-control"
                                onkeyup="showPasswordStrength('password', 'password_strength'); clearErrorMessage('password_error')" 
                                onblur="validatePassword()" placeholder="Enter a strong password" required>
                        </div>
                        <div id="password_strength" class="small mt-1"></div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password: <span class="text-danger">*</span></label>
                        <div class="form-field-wrapper">
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                                onkeyup="clearErrorMessage('confirm_password_error')" onblur="validateConfirmPassword()"
                                placeholder="Confirm your password" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number: <span class="text-danger">*</span></label>
                        <div class="form-field-wrapper">
                            <input type="tel" id="phone" name="phone" class="form-control"
                                onkeyup="clearErrorMessage('phone_error')" onblur="validatePhone()"
                                placeholder="Enter your phone number" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="dob" class="form-label">Date of Birth: <span class="text-danger">*</span></label>
                        <div class="form-field-wrapper">
                            <input type="date" id="dob" name="dob" class="form-control"
                                onchange="clearErrorMessage('dob_error')" onblur="validateDateOfBirth()" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender: <span class="text-danger">*</span></label>
                        <div class="form-check">
                            <div class="form-field-wrapper">
                                <input type="radio" id="gender_male" name="gender" value="male" class="form-check-input"
                                    onchange="clearErrorMessage('gender_error')">
                            </div>
                            <label for="gender_male" class="form-check-label">Male</label>
                        </div>
                        <div class="form-check">
                            <div class="form-field-wrapper">
                                <input type="radio" id="gender_female" name="gender" value="female" class="form-check-input"
                                    onchange="clearErrorMessage('gender_error')">
                            </div>
                            <label for="gender_female" class="form-check-label">Female</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Qualification: <span class="text-danger">*</span></label>
                        <div class="form-check">
                            <div class="form-field-wrapper">
                                <input type="checkbox" id="qualification_bachelor" name="qualification[]" value="bachelor" class="form-check-input"
                                    onchange="clearErrorMessage('qualification_error')">
                            </div>
                            <label for="qualification_bachelor" class="form-check-label">Bachelor's Degree</label>
                        </div>
                        <div class="form-check">
                            <div class="form-field-wrapper">
                                <input type="checkbox" id="qualification_master" name="qualification[]" value="master" class="form-check-input"
                                    onchange="clearErrorMessage('qualification_error')">
                            </div>
                            <label for="qualification_master" class="form-check-label">Master's Degree</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address: <span class="text-danger">*</span></label>
                        <div class="form-field-wrapper">
                            <textarea id="address" name="address" class="form-control" rows="3"
                                onkeyup="clearErrorMessage('address_error')" onblur="validateAddress()"
                                placeholder="Enter your complete address" required></textarea>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="country" class="form-label">Country: <span class="text-danger">*</span></label>
                        <div class="form-field-wrapper">
                            <select id="country" name="country" class="form-control" 
                                onchange="clearErrorMessage('country_error')" onblur="validateCountry()" required>
                                <option value="">-- Select Country --</option>
                                <option value="usa">United States of America</option>
                                <option value="canada">Canada</option>
                                <option value="uk">United Kingdom</option>
                                <option value="australia">Australia</option>
                                <option value="india">India</option>
                                <option value="germany">Germany</option>
                                <option value="france">France</option>
                                <option value="japan">Japan</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="zip" class="form-label">Zip Code: <span class="text-danger">*</span></label>
                        <div class="form-field-wrapper">
                            <input type="text" id="zip" name="zip" class="form-control"
                                onkeyup="clearErrorMessage('zip_error')" onblur="validateZipCode()"
                                placeholder="Enter your zip/postal code" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Photo:</label>
                        <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Optional: Upload a profile picture (JPG, PNG, GIF)</small>
                    </div>
                    <div class="mb-3">
                        <div class="form-field-wrapper">
                            <button type="submit" id="submit-button" class="btn btn-primary w-100">Register</button>
                        </div>
                        <button type="reset" class="btn btn-secondary w-100 mt-2" onclick="hideAllValidationTooltips()">Reset</button>
                        <a href="view-users.php" class="btn btn-info w-100 mt-2">View All Users</a>
                    </div>
                </fieldset>
            </form>
        </div>
       
    </div>
    <?php include '../includes/scripts.php'; ?>

</body>

</html>