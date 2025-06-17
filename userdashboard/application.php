 <?php
require 'init.php';

include "../setup/dbconnection.php";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $target_dir = "../assets/uploads/";
    function uploadfile($inputname, $target_dir)
    {
        if (!empty($_FILES[$inputname]["name"])) {
            $filename = basename($_FILES[$inputname]["name"]);
            $filetype = strtolower(pathinfo($_FILES[$inputname]["name"], PATHINFO_EXTENSION));
            $allowedTypes = array("jpg", "jpeg", "png", "pdf");
            if (in_array($filetype, $allowedTypes)) {
                $filepath = $target_dir . time() . "_" . $filename ;
                if (move_uploaded_file($_FILES[$inputname]["tmp_name"], $filepath)) {
                    return $filepath;
                } else {
                    echo " error ";
                }
            } else {
                echo "invalid type";
            }
        } else {
            return null;
        }
    }

    if (isset($_SESSION["id"])) {
        $user_id = $_SESSION["id"];

        $father_id_path = uploadfile("father_id", $target_dir);
        $mother_id_path = uploadfile("mother_id", $target_dir);
        $applicant_id_path = uploadfile("applicant_id", $target_dir);
        $birth_record_path = uploadfile("birth_record", $target_dir);
        $firstname = $_POST["first_name"];
        $middlename = $_POST["middle_name"];
        $lastname = $_POST["last_name"];
        $dob = $_POST["dob"];
        $gender = $_POST["gender"];
        $birth_place = $_POST["birth_place"];
        $email = $_POST["email"];
        $address = $_POST["address"];
        $father_name = $_POST["father_name"];
        $mother_name = $_POST["mother_name"];

        $sql = "INSERT INTO applications ( user_id,first_name ,middle_name ,last_name ,dob,email,gender,place_of_birth,father_name,mother_name ,current_address,father_id,mother_id,applicant_id,birth_record) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssssssssssss", $user_id, $firstname, $middlename, $lastname, $dob, $email, $gender, $birth_place, $father_name, $mother_name, $address, $father_id_path, $mother_id_path, $applicant_id_path, $birth_record_path);

        echo $father_id_path;
        echo $mother_id_path;
        if ($stmt->execute()) {
            header("location: user.php");
        } else {
            header("location: status.php");
        }
    } else {
        header("location: ../public/login.php");
    }
}

?> 
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birth Certificate Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #0d924f;
            --accent-color: #e74c3c;
            --light-bg: #f8f9fa;
            --error-color: #e74c3c;
            --success-color: #0d924f;
            --focus-color: #86b7fe;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-container {
            max-width: 800px;
            margin-left: auto;
            margin-right: 2rem;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.5rem;
            border-bottom: none;
        }

        .card-header h4 {
            margin-bottom: 0;
            font-weight: 600;
        }

        .card-body {
            padding: 2rem;
        }

        .form-section {
            margin-bottom: 2.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #e9ecef;
        }

        .form-section h5 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--secondary-color);
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }

        .required-field::after {
            content: " *";
            color: var(--error-color);
        }

        .error-message {
            color: var(--error-color);
            font-size: 0.8rem;
            margin-top: 0.25rem;
            display: none;
        }

        .is-invalid {
            border-color: var(--error-color) !important;
            background-image: none;
        }

        .is-valid {
            border-color: var(--success-color) !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--focus-color);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }

        .file-upload-info {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }

        .submit-btn {
            background-color: var(--secondary-color);
            border: none;
            padding: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #0b7a41;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-check-input:checked {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        @media (max-width: 768px) {
            .form-container {
                margin-right: auto;
                padding: 0 15px;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid me-5">
        <div class="row justify-content-end">
            <div class="col-lg-8 col-xl-7">
                <div class="form-container">
                    <div class="card shadow">
                        <div class="card-header text-center">
                            <h4>Birth Certificate Application</h4>
                        </div>
                        <div class="card-body">
                            <form id="applicationForm" method="post" action="application.php" enctype="multipart/form-data" novalidate>
                                <!-- Personal Information Section -->
                                <div class="form-section">
                                    <h5>Personal Information</h5>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="first_name" class="form-label required-field">First Name</label>
                                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                                            <div id="first_name_error" class="error-message">Please enter a valid first name (letters only)</div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="middle_name" class="form-label">Middle Name</label>
                                            <input type="text" class="form-control" id="middle_name" name="middle_name">
                                            <div id="middle_name_error" class="error-message">Only letters, spaces, hyphens and apostrophes allowed</div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="last_name" class="form-label required-field">Last Name</label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                                            <div id="last_name_error" class="error-message">Please enter a valid last name (letters only)</div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="dob" class="form-label required-field">Date of Birth</label>
                                            <input type="date" class="form-control" id="dob" name="dob" required>
                                            <div id="dob_error" class="error-message">Please select a valid date in the past</div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="gender" class="form-label required-field">Gender</label>
                                            <select class="form-select" id="gender" name="gender" required>
                                                <option value="" disabled selected>Select Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                            <div id="gender_error" class="error-message">Please select a gender</div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="birth_place" class="form-label required-field">Place of Birth</label>
                                        <input type="text" class="form-control" id="birth_place" name="birth_place" required>
                                        <div id="birth_place_error" class="error-message">Please enter a valid place of birth</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label required-field">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                        <div id="email_error" class="error-message">Please enter a valid email address</div>
                                    </div>
                                </div>

                                <!-- Contact Details Section -->
                                <div class="form-section">
                                    <h5>Contact Details</h5>

                                    <div class="mb-3">
                                        <label for="address" class="form-label required-field">Address</label>
                                        <input type="text" class="form-control" id="address" name="address" required>
                                        <div id="address_error" class="error-message">Please enter your address</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="father_name" class="form-label required-field">Father's Full Name</label>
                                            <input type="text" class="form-control" id="father_name" name="father_name" required>
                                            <div id="father_name_error" class="error-message">Please enter father's full name</div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="mother_name" class="form-label required-field">Mother's Full Name</label>
                                            <input type="text" class="form-control" id="mother_name" name="mother_name" required>
                                            <div id="mother_name_error" class="error-message">Please enter mother's full name</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Supporting Documents Section -->
                                <div class="form-section">
                                    <h5>Supporting Documents</h5>

                                    <div class="mb-3">
                                        <label for="father_id_proof" class="form-label required-field">Father's ID Proof</label>
                                        <input type="file" class="form-control" id="father_id_proof" name="father_id" accept=".pdf,.jpg,.jpeg,.png" required>
                                        <div class="file-upload-info"></div>
                                        <div id="father_id_proof_error" class="error-message"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="mother_id_proof" class="form-label required-field">Mother's ID Proof</label>
                                        <input type="file" class="form-control" id="mother_id_proof" name="mother_id" accept=".pdf,.jpg,.jpeg,.png" required>
                                        <div class="file-upload-info"></div>
                                        <div id="mother_id_proof_error" class="error-message">Please upload a valid ID proof (JPG, PNG or PDF)</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="applicant_id_proof" class="form-label">Applicant's ID Proof (if 18+)</label>
                                        <input type="file" class="form-control" id="applicant_id_proof" name="applicant_id" accept=".pdf,.jpg,.jpeg,.png">
                                        <div class="file-upload-info"></div>
                                        <div id="applicant_id_proof_error" class="error-message">Please upload a valid ID proof (JPG, PNG or PDF)</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="birth_record" class="form-label">Hospital Birth Record</label>
                                        <input type="file" class="form-control" id="birth_record" name="birth_record" accept=".pdf,.jpg,.jpeg,.png">
                                        <div class="file-upload-info"></div>
                                        <div id="birth_record_error" class="error-message"></div>
                                    </div>
                                </div>

                                <!-- Agreement and Submission -->
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="agree" required>
                                        <label class="form-check-label" for="agree">
                                            I confirm that all information provided is accurate and complete
                                        </label>
                                        <div id="agree_error" class="error-message">You must agree to proceed</div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary submit-btn w-100" name="submit">
                                    Submit Application
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize form validation
            initFormValidation();
        });

        function initFormValidation() {
            const form = document.getElementById('applicationForm');

            // Name fields validation (real-time)
            const nameFields = ['first_name', 'middle_name', 'last_name', 'father_name', 'mother_name'];
            nameFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                field.addEventListener('input', () => validateNameField(fieldId));
                field.addEventListener('blur', () => validateNameField(fieldId));
            });

            // Date of birth validation
            const dobField = document.getElementById('dob');
            dobField.addEventListener('change', validateDOB);
            dobField.addEventListener('blur', validateDOB);

            // Email validation
            const emailField = document.getElementById('email');
            emailField.addEventListener('input', validateEmail);
            emailField.addEventListener('blur', validateEmail);

            // File upload validation
            const fileFields = ['father_id_proof', 'mother_id_proof', 'applicant_id_proof', 'birth_record'];
            fileFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                field.addEventListener('change', () => validateFileUpload(fieldId));
            });

            // Required fields validation
            const requiredFields = ['gender', 'birth_place', 'address', 'agree'];
            requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    if (field.type === 'checkbox') {
                        field.addEventListener('change', () => validateRequiredField(fieldId));
                    } else {
                        field.addEventListener('input', () => validateRequiredField(fieldId));
                        field.addEventListener('blur', () => validateRequiredField(fieldId));
                    }
                }
            });

            // Form submission handler
            form.addEventListener('submit', function(event) {
                // event.preventDefault();

                // Validate all fields
                let isValid = true;

                // Validate name fields
                nameFields.forEach(fieldId => {
                    if (!validateNameField(fieldId)) isValid = false;
                });

                // Validate date of birth
                if (!validateDOB()) isValid = false;

                // Validate email
                if (!validateEmail()) isValid = false;

                // Validate required fields
                requiredFields.forEach(fieldId => {
                    if (!validateRequiredField(fieldId)) isValid = false;
                });

                // Validate file uploads
                fileFields.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field.required && !validateFileUpload(fieldId)) {
                        isValid = false;
                    }

                });

                // Submit if valid
                if (isValid) {
                    form.submit();
                } else if (!isValid) {
                    // Scroll to first error


                    event.preventDefault();
                    const firstError = document.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }

                    // const firstError = document.querySelector('.is-invalid');
                    // if (firstError) {
                    //     firstError.scrollIntoView({
                    //         behavior: 'smooth',
                    //         block: 'center'
                    //     });
                    // }
                }
            });
        }

        function validateNameField(fieldId) {
            const field = document.getElementById(fieldId);
            const errorElement = document.getElementById(`${fieldId}_error`);
            const value = field.value.trim();

            // Middle name is optional
            if (fieldId === 'middle_name' && value === '') {
                hideError(field, errorElement);
                return true;
            }

            const nameRegex = /^[A-Za-z\s\-']+$/;

            if (field.required && value === '') {
                showError(field, errorElement, 'This field is required');
                return false;
            }

            if (value !== '' && !nameRegex.test(value)) {
                showError(field, errorElement, 'Only letters, spaces, hyphens and apostrophes allowed');
                return false;
            }

            if (value.length > 50) {
                showError(field, errorElement, 'Name is too long (max 50 characters)');
                return false;
            }

            showSuccess(field, errorElement);
            return true;
        }

        function validateDOB() {
            const field = document.getElementById('dob');
            const errorElement = document.getElementById('dob_error');
            const dob = new Date(field.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Compare dates only

            if (!field.value) {
                showError(field, errorElement, 'Date of birth is required');
                return false;
            }

            if (dob >= today) {
                showError(field, errorElement, 'Date must be in the past');
                return false;
            }

            // Validate reasonable age (between 0 and 120 years)
            const minDate = new Date();
            minDate.setFullYear(minDate.getFullYear() - 120);

            if (dob < minDate) {
                showError(field, errorElement, 'Please enter a valid date of birth');
                return false;
            }

            showSuccess(field, errorElement);
            return true;
        }

        function validateEmail() {
            const field = document.getElementById('email');
            const errorElement = document.getElementById('email_error');
            const email = field.value.trim();
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            if (!email) {
                showError(field, errorElement, 'Email is required');
                return false;
            }

            if (!emailRegex.test(email)) {
                showError(field, errorElement, 'Please enter a valid email address');
                return false;
            }

            if (email.length > 100) {
                showError(field, errorElement, 'Email is too long (max 100 characters)');
                return false;
            }

            showSuccess(field, errorElement);
            return true;
        }

        function validateFileUpload(fieldId) {
            const field = document.getElementById(fieldId);
            const errorElement = document.getElementById(`${fieldId}_error`);

            // Skip validation if field is not required and empty
            if (!field.required && field.files.length === 0) {
                hideError(field, errorElement);
                return true;
            }

            if (field.required && field.files.length === 0) {
                showError(field, errorElement, 'This file is required');
                return false;
            }

            const file = field.files[0];
            const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            const maxSize = 5 * 1024 * 1024; // 5MB

            if (!allowedTypes.includes(file.type)) {
                showError(field, errorElement, 'Only JPG, PNG or PDF files are allowed');
                return false;
            }

            if (file.size > maxSize) {
                showError(field, errorElement, 'File size exceeds 5MB limit');
                return false;
            }

            showSuccess(field, errorElement);
            return true;
        }

        function validateRequiredField(fieldId) {
            const field = document.getElementById(fieldId);
            const errorElement = document.getElementById(`${fieldId}_error`);

            if (field.type === 'checkbox') {
                if (!field.checked) {
                    showError(field, errorElement, 'You must agree to proceed');
                    return false;
                }
            } else if (field.value.trim() === '') {
                showError(field, errorElement, 'This field is required');
                return false;
            }

            showSuccess(field, errorElement);
            return true;
        }

        // Helper functions
        function showError(field, errorElement, message) {
            errorElement.textContent = message;
            errorElement.style.display = 'block';
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
        }

        function showSuccess(field, errorElement) {
            errorElement.style.display = 'none';
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        }

        function hideError(field, errorElement) {
            errorElement.style.display = 'none';
            field.classList.remove('is-invalid');
            field.classList.remove('is-valid');
        }
    </script>
</body>
</html>