<?php
// require "../userdashboard/init.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Birth Certificate Application</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    :root {
      --primary-color: #2c3e50;
      --secondary-color: #0d924f;
      --accent-color: #e74c3c;
      --light-bg: #f8f9fa;
      --dark-text: #212529;
      --light-text: #6c757d;
    }

    html,
    body {
      display: flex;
      flex-direction: column;
      height: 100%;
      margin: 0;
      padding: 0;
      background-color: var(--light-bg);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: var(--dark-text);
    }

    .hospital-header {
      background-color: var(--primary-color);
      color: white;
      padding: 1.5rem 0;
      border-bottom: 5px solid var(--accent-color);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .hospital-header h1 {
      font-weight: 700;
      letter-spacing: 0.5px;
    }

    .form-container {
      background-color: white;
      border-radius: 0.5rem;
      box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
      padding: 2rem;
      margin-bottom: 2rem;
      border-top: 4px solid var(--secondary-color);
      transition: transform 0.3s ease;
    }

    .form-container:hover {
      transform: translateY(-2px);
      box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.1);
    }

    .form-title {
      color: var(--primary-color);
      border-bottom: 2px solid var(--secondary-color);
      padding-bottom: 0.75rem;
      margin-bottom: 1.5rem;
      font-weight: 600;
    }

    .btn-submit {
      background-color: var(--secondary-color);
      border-color: var(--secondary-color);
      padding: 0.75rem 1.5rem;
      font-weight: 600;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
    }

    .btn-submit:hover {
      background-color: #0b7a41;
      border-color: #0b7a41;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(13, 146, 79, 0.3);
    }

    .btn-outline-secondary {
      transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
      transform: translateY(-2px);
    }

    fieldset {
      border: 1px solid #dee2e6;
      border-radius: 0.5rem;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      background-color: rgba(248, 249, 250, 0.5);
    }

    legend {
      width: auto;
      padding: 0 1rem;
      font-size: 1.1rem;
      color: var(--primary-color);
      font-weight: 600;
      background-color: white;
      border-radius: 0.25rem;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--secondary-color);
      box-shadow: 0 0 0 0.25rem rgba(13, 146, 79, 0.2);
    }

    .required-field::after {
      content: " *";
      color: var(--accent-color);
    }

    .input-group-text {
      background-color: rgba(13, 146, 79, 0.1);
      color: var(--secondary-color);
      border-color: #dee2e6;
    }

    .floating-alert {
      position: fixed;
      top: 1rem;
      right: 1rem;
      z-index: 9999;
      animation: slideIn 0.5s forwards, fadeOut 0.5s 4.5s forwards;
    }

    @keyframes slideIn {
      from {
        transform: translateX(100%);
        opacity: 0;
      }

      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes fadeOut {
      from {
        opacity: 1;
      }

      to {
        opacity: 0;
      }
    }

    @media (max-width: 768px) {
      .form-container {
        padding: 1.5rem;
      }

      .hospital-header {
        padding: 1rem 0;
      }

      .hospital-header h1 {
        font-size: 1.75rem;
      }
    }
  </style>
</head>

<body>
  <div class="hospital-header text-center">
    <div class="container">
      <h1><i class="fas fa-hospital me-2"></i>CITY GENERAL HOSPITAL</h1>
      <p class="mb-0">Birth Registration Department</p>
    </div>
  </div>

  <div class="container my-4 me-1 ">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="form-container">
          <h2 class="form-title"><i class="fas fa-certificate me-2"></i>Birth Certificate Application</h2>

          <!-- Success/Error Alert Placeholder -->
          <div id="formAlert" class="alert alert-dismissible fade" role="alert">
            <span id="alertMessage"></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>

          <form id="birthRecordForm" action="" method="POST">
            <!-- Newborn Information Section -->
            <fieldset class="mb-4">
              <legend>Newborn Information</legend>
              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label for="newbornName" class="form-label required-field">Full Name of Newborn</label>
                  <input type="text" class="form-control" name="child_full_name" id="newbornName"
                    placeholder="e.g. Hana Dinku" required />
                </div>
                <div class="col-md-3">
                  <label for="dob" class="form-label required-field">Date of Birth</label>
                  <input type="date" class="form-control" name="dob" id="dob" required />
                </div>
                <div class="col-md-3">
                  <label for="timeOfBirth" class="form-label">Time of Birth</label>
                  <input type="time" class="form-control" name="tob" id="timeOfBirth" />
                </div>
              </div>

              <div class="row g-3">
                <div class="col-md-4">
                  <label for="gender" class="form-label required-field">Gender</label>
                  <select class="form-select" name="gender" id="gender" required>
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label for="weight" class="form-label">Birth Weight</label>
                  <div class="input-group">
                    <input type="number" class="form-control" name="weight" id="weight" min="0.5" max="8" step="0.1" />
                    <span class="input-group-text">kg</span>
                  </div>
                </div>
              </div>
            </fieldset>

            <!-- Parent Information Section -->
            <fieldset class="mb-4">
              <legend>Parent Information</legend>
              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label for="fatherName" class="form-label required-field">Father's Full Name</label>
                  <input type="text" class="form-control" name="father_name" id="fatherName"
                    placeholder="e.g. Dinku Guta" required />
                </div>
                <div class="col-md-6">
                  <label for="motherName" class="form-label required-field">Mother's Full Name</label>
                  <input type="text" class="form-control" name="mother_name" id="motherName"
                    placeholder="e.g. Aster Geleta" required />
                </div>
              </div>

              <div class="row g-3">
                <div class="col-md-6">
                  <label for="parentContact" class="form-label required-field">Contact Number</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input type="tel" class="form-control" name="phone" id="parentContact" placeholder="0953935589"
                      required />
                  </div>
                  <small class="text-muted">Format: 09XXXXXXXX or +2519XXXXXXXX</small>
                </div>
                <div class="col-md-6">
                  <label for="parentAddress" class="form-label required-field">Residential Address</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    <input type="text" class="form-control" name="address" id="parentAddress" required />
                  </div>
                </div>
              </div>
            </fieldset>

            <!-- Birth Details Section -->
            <fieldset class="mb-4">
              <legend>Birth Details</legend>
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="placeOfBirth" class="form-label required-field">Place of Birth</label>
                  <select class="form-select" name="pob" id="placeOfBirth" required>
                    <option value="">Select Place of Birth</option>
                    <option value="hospital">This Hospital</option>
                    <option value="other_hospital">Other Hospital</option>
                    <option value="home">Home</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label for="attendantName" class="form-label">Name of Attendant</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user-md"></i></span>
                    <input type="text" class="form-control" name="name_of_doctor" id="attendantName"
                      placeholder="Doctor/Nurse name" />
                  </div>
                </div>
              </div>
            </fieldset>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
              <button type="reset" class="btn btn-outline-secondary me-md-2">
                <i class="fas fa-undo me-2"></i>Reset Form
              </button>
              <button type="submit" name="submit" class="btn btn-submit">
                <i class="fas fa-paper-plane me-2"></i>Submit Application
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- PHP Processing Code -->
  <?php
  include "../setup/dbconnection.php";
  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

  if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
  }

  function generateId()
  {
    global $conn;
    $year = date('y');
    $sql = "SELECT MAX(record_id) AS max_id FROM birth_records";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $id = $result->fetch_assoc();
    $next_id = $id["max_id"] + 1;
    return "HBR-" . str_pad($next_id, 4, "0", STR_PAD_LEFT) . "/" . $year;
  }

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $hospital_id = $_SESSION["hospital_id"];
    $HBR = generateId();
    $childname = htmlspecialchars($_POST["child_full_name"]);
    $dob = $_POST["dob"];
    $pob = htmlspecialchars($_POST["pob"]);
    $tob = $_POST["tob"];
    $gender = htmlspecialchars($_POST["gender"]);
    $weight = $_POST["weight"] ? floatval($_POST["weight"]) : 0.0;
    $fatherName = htmlspecialchars($_POST["father_name"]);
    $motherName = htmlspecialchars($_POST["mother_name"]);
    $phone = htmlspecialchars($_POST["phone"]);
    $address = htmlspecialchars($_POST["address"]);
    $nameofdoctor = htmlspecialchars($_POST["name_of_doctor"]);

    $sql = "INSERT INTO birth_records(record_id,hospital_id,child_name, dob, pob, tob, gender, weight, father_name, mother_name, phone, address, nameOfDoctor, HBR)
              VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisssssdssssss", $record_id, $hospital_id, $childname, $dob, $pob, $tob, $gender, $weight, $fatherName, $motherName, $phone, $address, $nameofdoctor, $HBR);

    try {
      if ($stmt->execute()) {
        echo '<div id="successAlert" class="floating-alert alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>Birth record successfully registered!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';

        // Clear form after successful submission
        echo '<script>
                document.getElementById("birthRecordForm").reset();
                setTimeout(() => {
                  document.getElementById("successAlert").remove();
                }, 5000);
              </script>';
      }
    } catch (Exception $e) {
      echo '<div id="errorAlert" class="floating-alert alert alert-danger alert-dismissible fade show" role="alert">
              <i class="fas fa-exclamation-circle me-2"></i>Error: ' . $e->getMessage() . '
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }
  }
  ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Enhanced Client-side Validation
    document.getElementById('birthRecordForm').addEventListener('submit', function (e) {
      let isValid = true;
      const alertBox = document.getElementById('formAlert');
      const alertMessage = document.getElementById('alertMessage');

      // Clear previous alerts
      alertBox.classList.remove('alert-success', 'alert-danger', 'show');

      // Validate Newborn Name
      const newbornName = document.getElementById('newbornName').value.trim();
      if (!/^[A-Za-z\s\-']+$/.test(newbornName)) {
        showAlert('Newborn name must contain only letters, spaces, hyphens, and apostrophes.', 'danger');
        isValid = false;
      }

      // Validate Parent Names
      const fatherName = document.getElementById('fatherName').value.trim();
      const motherName = document.getElementById('motherName').value.trim();
      if (!/^[A-Za-z\s\-']+$/.test(fatherName) || !/^[A-Za-z\s\-']+$/.test(motherName)) {
        showAlert('Parent names must contain only letters, spaces, hyphens, and apostrophes.', 'danger');
        isValid = false;
      }

      // Validate Phone Number (Ethiopian format)
      const phone = document.getElementById('parentContact').value.trim();
      if (!/^(\+2519|09)\d{8}$/.test(phone)) {
        showAlert('Please enter a valid Ethiopian phone number (09XXXXXXXX or +2519XXXXXXXX).', 'danger');
        isValid = false;
      }

      // Validate Date of Birth (not in future)
      const dob = document.getElementById('dob').value;
      if (new Date(dob) > new Date()) {
        showAlert('Date of birth cannot be in the future.', 'danger');
        isValid = false;
      }

      // Validate Weight if provided
      const weight = document.getElementById('weight').value;
      if (weight && (weight < 0.5 || weight > 8)) {
        showAlert('Birth weight must be between 0.5kg and 8kg.', 'danger');
        isValid = false;
      }

      if (!isValid) {
        e.preventDefault();
      }

      function showAlert(message, type) {
        alertMessage.textContent = message;
        alertBox.classList.add('alert-' + type, 'show');

        // Scroll to alert
        alertBox.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });

    // Set max date for date of birth field to today
    document.getElementById('dob').max = new Date().toISOString().split('T')[0];

    // Add input masking for phone number
    document.getElementById('parentContact').addEventListener('input', function (e) {
      let value = e.target.value.replace(/\D/g, '');
      if (value.startsWith('251')) {
        value = '+' + value;
      } else if (value.length > 0 && !value.startsWith('0') && !value.startsWith('9')) {
        value = '0' + value;
      }
      e.target.value = value;
    });
  </script>
</body>

</html>