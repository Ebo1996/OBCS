<?php
// DB connection
include "../setup/dbconnection.php";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  function generate_id()
  {
    global $conn;
    $year = date('y');
    $sql = "SELECT MAX(id) AS max_id FROM kebele_ids";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $next_id = $row["max_id"] + 1;
    return "KIN-" . str_pad($next_id, 4, '0', STR_PAD_LEFT) . "/" . $year;
  }

  // Check if POST variables exist
  $firstName = isset($_POST['firstName']) ? $_POST['firstName'] : '';
  $middleName = isset($_POST['middleName']) ? $_POST['middleName'] : '';
  $lastName = isset($_POST['lastName']) ? $_POST['lastName'] : '';
  $dob = isset($_POST['dob']) ? $_POST['dob'] : '';
  $age = isset($_POST['age']) ? $_POST['age'] : '';
  $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
  $address = isset($_POST['address']) ? $_POST['address'] : '';
  $kebele_id = generate_id();

  // Handle file upload
  $photo = "";
  if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
    $uploadDir = "uploads/";
    if (!file_exists($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }
    $photoName = basename($_FILES["photo"]["name"]);
    $photoPath = $uploadDir . time() . "_" . $photoName;

    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $photoPath)) {
      $photo = $photoPath;
    }
  }

  // Insert into DB - corrected to match the number of parameters
  $stmt = $conn->prepare("INSERT INTO kebele_ids (kebele_id_number, first_name, middle_name, last_name, date_of_birth, gender, address, photo_path, age) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssssssssi", $kebele_id, $firstName, $middleName, $lastName, $dob, $gender, $address, $photo, $age);

  if ($stmt->execute()) {
    echo "<div class='alert alert-success'>Registration successful!</div>";
  } else {
    echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
  }

  $stmt->close();
  $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ID REGISTRATION FOR EFA BULA KEBELE</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <!-- Custom CSS -->
  <style>
    /* :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-color: #f72585;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        } */
    :root {
      --primary-color: #2c3e50;
      --secondary-color: #0d924f;
      --accent-color: #e74c3c;
      --light-bg: #f8f9fa;
      --accent-color: #f72585;
      --light-color: #f8f9fa;
      --dark-color: #212529;
    }

    body {
      /* background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); */
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
      min-height: 100vh;
    }

    .registration-container {
      max-width: 900px;
      margin: 40px auto;
      padding: 40px;
      background-color: white;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.3);
      backdrop-filter: blur(10px);
      animation: fadeIn 0.8s ease-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .form-header {
      text-align: center;
      margin-bottom: 40px;
      position: relative;
      padding-bottom: 20px;
    }

    .form-header h2 {
      font-weight: 800;
      color: var(--secondary-color);
      text-transform: uppercase;
      letter-spacing: 1px;
      font-size: 2rem;
      margin-bottom: 10px;
    }

    .form-header::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 25%;
      width: 50%;
      height: 4px;
      background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
      border-radius: 2px;
    }

    .form-label {
      font-weight: 600;
      color: var(--dark-color);
      margin-bottom: 8px;
    }

    .required-field::after {
      content: " *";
      color: var(--accent-color);
    }

    .form-control,
    .form-select {
      padding: 12px 15px;
      border-radius: 8px;
      border: 2px solid #e9ecef;
      transition: all 0.3s;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }

    .btn-submit {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      padding: 12px 30px;
      font-weight: 600;
      letter-spacing: 0.5px;
      border-radius: 8px;
      color: white;
      transition: all 0.3s;
      box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }

    .btn-submit:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
      color: white;
    }

    .btn-reset {
      border: 2px solid var(--primary-color);
      color: var(--primary-color);
      font-weight: 600;
      padding: 12px 30px;
      border-radius: 8px;
      transition: all 0.3s;
    }

    .btn-reset:hover {
      background-color: var(--primary-color);
      color: white;
    }

    .photo-preview {
      width: 150px;
      height: 150px;
      object-fit: cover;
      border-radius: 10px;
      border: 3px solid white;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      display: none;
      margin: 15px auto;
    }

    .upload-area {
      border: 2px dashed #ced4da;
      border-radius: 10px;
      padding: 30px;
      text-align: center;
      cursor: pointer;
      transition: all 0.3s;
      background-color: rgba(248, 249, 250, 0.5);
      position: relative;
      overflow: hidden;
    }

    .upload-area:hover {
      border-color: var(--primary-color);
      background-color: rgba(67, 97, 238, 0.05);
      transform: translateY(-3px);
    }

    .upload-area.active {
      border-color: var(--primary-color);
      background-color: rgba(67, 97, 238, 0.05);
    }

    .upload-icon {
      font-size: 2.5rem;
      color: var(--primary-color);
      margin-bottom: 10px;
    }

    .form-section {
      margin-bottom: 30px;
      padding: 20px;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
      border: 1px solid rgba(0, 0, 0, 0.05);
      transition: all 0.3s;
    }

    .form-section:hover {
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
      transform: translateY(-2px);
    }

    .form-section-title {
      color: var(--secondary-color);
      font-weight: 700;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 2px solid rgba(67, 97, 238, 0.2);
      display: flex;
      align-items: center;
    }

    .form-section-title i {
      margin-right: 10px;
      font-size: 1.2rem;
    }

    .invalid-feedback {
      font-weight: 500;
    }

    .form-check-input:checked {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }

    .form-check-input:focus {
      box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }

    @media (max-width: 768px) {
      .registration-container {
        padding: 25px;
        margin: 20px;
      }

      .form-header h2 {
        font-size: 1.5rem;
      }

      .upload-area {
        padding: 20px;
      }
    }
  </style>
</head>

<body>
  <div class="container py-4">
    <div class="registration-container animate__animated animate__fadeInUp">
      <div class="form-header">
        <h2><i class="bi bi-person-badge"></i> ID REGISTRATION</h2>
        <p class="text-muted">EFA BULA KEBELE ADMINISTRATION</p>
      </div>

      <form id="registrationForm" method="post" action="idregistration.php" enctype="multipart/form-data" novalidate>
        <!-- Personal Information Section -->
        <div class="form-section">
          <h5 class="form-section-title"><i class="bi bi-person-lines-fill"></i> Personal Information</h5>
          <div class="row g-3">
            <div class="col-md-4">
              <label for="firstName" class="form-label required-field">First Name</label>
              <input type="text" class="form-control" id="firstName" name="firstName" required>
              <div class="invalid-feedback">
                Please provide your first name.
              </div>
            </div>
            <div class="col-md-4">
              <label for="middleName" class="form-label">Middle Name</label>
              <input type="text" class="form-control" name="middleName" id="middleName">
            </div>
            <div class="col-md-4">
              <label for="lastName" class="form-label required-field">Last Name</label>
              <input type="text" class="form-control" name="lastName" id="lastName" required>
              <div class="invalid-feedback">
                Please provide your last name.
              </div>
            </div>
          </div>
        </div>

        <!-- Demographic Information Section -->
        <div class="form-section">
          <h5 class="form-section-title"><i class="bi bi-calendar2-heart"></i> Demographic Information</h5>
          <div class="row g-3">
            <div class="col-md-4">
              <label for="dob" class="form-label required-field">Date of Birth</label>
              <input type="date" class="form-control" name="dob" id="dob" required>
              <div class="invalid-feedback">
                Please select your date of birth.
              </div>
            </div>
            <div class="col-md-4">
              <label for="age" class="form-label required-field">Age</label>
              <div class="input-group">
                <input type="number" class="form-control" name="age" id="age" required>
                <span class="input-group-text">years</span>
              </div>
              <div class="invalid-feedback">
                You must be at least 18 years old.
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label required-field">Gender</label>
              <div class="d-flex gap-3">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="gender" id="male" value="male" required>
                  <label class="form-check-label" for="male">
                    <i class="bi bi-gender-male"></i> Male
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="gender" id="female" value="female">
                  <label class="form-check-label" for="female">
                    <i class="bi bi-gender-female"></i> Female
                  </label>
                </div>
              </div>
              <div class="invalid-feedback">
                Please select your gender.
              </div>
            </div>
          </div>
        </div>

        <!-- Contact Information Section -->
        <div class="form-section">
          <h5 class="form-section-title"><i class="bi bi-geo-alt"></i> Contact Information</h5>
          <div class="mb-3">
            <label for="address" class="form-label required-field">Full Address</label>
            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
            <div class="invalid-feedback">
              Please provide your complete address.
            </div>
          </div>
        </div>

        <!-- Additional Information Section -->
        <div class="form-section">
          <h5 class="form-section-title"><i class="bi bi-file-earmark-text"></i> Additional Information</h5>
          <div class="row g-3">
            <div class="col-md-6">
              <label for="nationality" class="form-label required-field">Nationality</label>
              <input type="text" class="form-control" name="nationality" id="nationality" required>
              <div class="invalid-feedback">
                Please provide your nationality.
              </div>
            </div>
            <div class="col-md-6">
              <label for="currentJob" class="form-label required-field">Current Occupation</label>
              <input type="text" class="form-control" name="currentJob" id="currentJob" required>
              <div class="invalid-feedback">
                Please provide your current occupation.
              </div>
            </div>
          </div>
        </div>

        <!-- Photo Upload Section -->
        <div class="form-section">
          <h5 class="form-section-title"><i class="bi bi-camera"></i> Photo Identification</h5>
          <label for="photo" class="form-label required-field">Upload Your Photo</label>
          <div class="upload-area" id="uploadArea">
            <div id="uploadContent">
              <i class="bi bi-cloud-arrow-up upload-icon"></i>
              <h5 class="mb-2">Drag & Drop or Click to Upload</h5>
              <p class="small text-muted">JPG or PNG (Max 2MB, 150x150px minimum)</p>
            </div>
            <input type="file" class="d-none" id="photo" name="photo" accept="image/*" required>
            <img id="photoPreview" class="photo-preview" alt="Preview">
          </div>
          <div class="invalid-feedback">
            Please upload a clear photo of yourself.
          </div>
        </div>

        <!-- Terms and Submission -->
        <div class="form-section">
          <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="chackBox" id="agreeTerms" required>
            <label class="form-check-label" for="agreeTerms">
              I certify that all information provided is accurate and complete to the best of my knowledge.
            </label>
            <div class="invalid-feedback">
              You must agree to the terms before submitting.
            </div>
          </div>

          <div class="d-flex justify-content-between">
            <button type="reset" class="btn btn-reset">
              <i class="bi bi-arrow-counterclockwise"></i> Reset Form
            </button>
            <button type="submit" name="submit" class="btn btn-submit">
              <i class="bi bi-send-check"></i> Submit Registration
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Bootstrap 5 JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Custom JavaScript -->
  <script>
    (function () {
      'use strict';

      // Form elements
      const form = document.getElementById('registrationForm');
      const dobInput = document.getElementById('dob');
      const ageInput = document.getElementById('age');
      const uploadArea = document.getElementById('uploadArea');
      const photoInput = document.getElementById('photo');
      const photoPreview = document.getElementById('photoPreview');
      const uploadContent = document.getElementById('uploadContent');

      // Calculate age from date of birth
      function calculateAge(dob) {
        const today = new Date();
        const birthDate = new Date(dob);
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();

        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
          age--;
        }

        return age;
      }

      // Update age when date of birth changes
      dobInput.addEventListener('change', function () {
        const dob = new Date(this.value);
        const today = new Date();
        const minAgeDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());

        if (dob > minAgeDate) {
          this.setCustomValidity('You must be at least 18 years old');
          ageInput.value = '';
        } else {
          this.setCustomValidity('');
          ageInput.value = calculateAge(this.value);
        }
      });

      // Photo upload handling
      uploadArea.addEventListener('click', function () {
        photoInput.click();
      });

      // Drag and drop for photo upload
      ['dragover', 'dragenter'].forEach(event => {
        uploadArea.addEventListener(event, function (e) {
          e.preventDefault();
          this.classList.add('active');
        });
      });

      ['dragleave', 'dragend', 'drop'].forEach(event => {
        uploadArea.addEventListener(event, function () {
          this.classList.remove('active');
        });
      });

      uploadArea.addEventListener('drop', function (e) {
        e.preventDefault();
        if (e.dataTransfer.files.length) {
          photoInput.files = e.dataTransfer.files;
          displayPhotoPreview(e.dataTransfer.files[0]);
        }
      });

      photoInput.addEventListener('change', function () {
        if (this.files && this.files[0]) {
          displayPhotoPreview(this.files[0]);

          // Validate file size (max 2MB)
          if (this.files[0].size > 2 * 1024 * 1024) {
            this.setCustomValidity('File size must be less than 2MB');
            photoPreview.style.display = 'none';
            uploadContent.style.display = 'block';
          } else {
            this.setCustomValidity('');
          }
        }
      });

      function displayPhotoPreview(file) {
        const reader = new FileReader();

        reader.onload = function (e) {
          photoPreview.src = e.target.result;
          photoPreview.style.display = 'block';
          uploadContent.style.display = 'none';
        }

        reader.readAsDataURL(file);
      }

      // Form submission
      // form.addEventListener('submit', function(event) {
      //     event.preventDefault();

      //     if (!form.checkValidity()) {
      //         event.stopPropagation();
      //     } else {
      //         // Form is valid - show success animation
      //         const submitBtn = form.querySelector('button[type="submit"]');
      //         submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
      //         submitBtn.disabled = true;

      //         // Simulate form processing
      //         setTimeout(() => {
      //             // Show success message
      //             const successAlert = document.createElement('div');
      //             successAlert.className = 'alert alert-success alert-dismissible fade show mt-4 animate__animated animate__fadeIn';
      //             successAlert.innerHTML = `
      //                 <i class="bi bi-check-circle-fill"></i> 
      //                 <strong>Registration successful!</strong> Your ID registration has been submitted.
      //                 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      //             `;
      //             form.insertBefore(successAlert, form.lastElementChild);

      //             // Reset form
      //             submitBtn.innerHTML = '<i class="bi bi-send-check"></i> Submit Registration';
      //             submitBtn.disabled = false;

      //             // In a real application, you would submit to a server here
      //             console.log('Form would be submitted here');
      //         }, 2000);
      //     }

      //     form.classList.add('was-validated');
      // }, false);

      // Reset form handler
      form.addEventListener('reset', function () {
        form.classList.remove('was-validated');
        photoPreview.style.display = 'none';
        uploadContent.style.display = 'block';

        // Reset custom validity
        dobInput.setCustomValidity('');
        photoInput.setCustomValidity('');
      });
    })();
  </script>
</body>

</html>