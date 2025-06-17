<?php
session_start();
include "../setup/dbconnection.php";

$hospital_id = $_SESSION["hospital_id"] ?? null;

if (!$hospital_id) {
  die("Unauthorized access. Please log in.");
}

$sql = "SELECT * FROM birth_records WHERE hospital_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
  die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$result = $stmt->get_result();
$firstrecord = $result->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Birth Records Management</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #2c3e50;
      --secondary-color: #2c3e50;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8f9fa;
    }

    .header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 20px 0;
      margin-bottom: 30px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }

    .card-header {
      background-color: var(--primary-color);
      color: white;
      border-radius: 10px 10px 0 0 !important;
      font-weight: 600;
    }

    .btn-view {
      background-color: var(--primary-color);
      color: white;
    }

    .hospital-logo {
      height: 40px;
      width: auto;
    }

    /* Certificate Style for View Modal */
    .certificate-container {
      background-color: white;
      border: 15px solid #f5f5f5;
      padding: 30px;
      position: relative;
    }

    .certificate-border {
      border: 2px solid #3498db;
      padding: 30px;
      position: relative;
    }

    .certificate-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .certificate-title {
      color: #3498db;
      font-size: 28px;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .certificate-subtitle {
      color: #555;
      font-size: 18px;
    }

    .certificate-seal {
      position: absolute;
      right: 30px;
      top: 30px;
      width: 80px;
      opacity: 0.8;
    }

    .certificate-watermark {
      position: absolute;
      opacity: 0.1;
      font-size: 120px;
      color: #3498db;
      transform: rotate(-30deg);
      left: 25%;
      top: 30%;
      z-index: 0;
    }

    .certificate-body {
      position: relative;
      z-index: 1;
    }

    .section-title {
      color: #3498db;
      border-bottom: 2px solid #eee;
      padding-bottom: 5px;
      margin-bottom: 15px;
      font-size: 20px;
    }

    .detail-row {
      display: flex;
      margin-bottom: 10px;
    }

    .detail-label {
      font-weight: 600;
      color: #555;
      min-width: 180px;
    }

    .detail-value {
      flex-grow: 1;
    }

    .signature-area {
      margin-top: 50px;
      display: flex;
      justify-content: space-between;
    }

    .signature-box {
      text-align: center;
      width: 200px;
      border-top: 1px solid #333;
      padding-top: 10px;
      margin-top: 60px;
    }

    .flex {
      display: flex;
      justify-content: space-between;
    }

    .margin_left {
      margin-left: 12rem;
    }

    @media print {
      body * {
        visibility: hidden;
      }

      .certificate-container,
      .certificate-container * {
        visibility: visible;
      }

      .certificate-container {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        border: none;
        box-shadow: none;
      }

      .no-print {
        display: none !important;
      }
    }
  </style>
</head>







<body>
  <div class="header">
    <div class="container">
      <div class="row align-items-left mx-auto flex">
        <div class="col-md-6 me-3 margin_left">
          <h1><i class="fas fa-baby " style="text-align: right;"></i> Records Management</h1>
        </div>
        <div class="col-md-6 text-end">
          <img src="https://via.placeholder.com/150x40?text=Hospital+Logo" alt="Hospital Logo" class="hospital-logo">
        </div>
      </div>
    </div>
  </div>


  <div class="container me-4" style="max-width: 1200px;">
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
          <span><i class="fas fa-list me-2"></i> Birth Records List</span>
          <div>
            <a href="birthrecord.html">
              <button class="btn btn-sm btn-light">
                <i class="fas fa-plus me-1"></i> Add Record
              </button>
            </a>
          </div>
        </div>
      </div>
      <?php if ($result->num_rows > 0) { ?>
        <div class="card-body">
          <!-- Add search input here -->
          <div class="mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by child name..."
              onkeyup="searchTable()">
          </div>

          <div class="table-responsive">
            <table class="table table-hover" id="recordsTable">
              <thead>
                <tr>
                  <th>Record ID</th>
                  <th>Full Name</th>
                  <th>Date of Birth</th>
                  <th>Father Name</th>
                  <th>Mother Name</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($record = $result->fetch_assoc()) { ?>
                  <tr>
                    <td><?= $record["record_id"] ?></td>
                    <td><?= $record["child_name"] ?></td>
                    <td><?= $record["dob"] ?></td>
                    <td><?= $record["father_name"] ?></td>
                    <td><?= $record["mother_name"] ?></td>
                    <td>
                      <button class="btn btn-sm btn-view" data-bs-toggle="modal" data-bs-target="#viewRecordModal"
                        data-child_name="<?= htmlspecialchars($record['child_name']) ?>"
                        data-dob="<?= htmlspecialchars($record['dob']) ?>"
                        data-gender="<?= htmlspecialchars($record['gender']) ?>"
                        data-weight="<?= htmlspecialchars($record['weight']) ?>"
                        data-father_name="<?= htmlspecialchars($record['father_name']) ?>"
                        data-mother_name="<?= htmlspecialchars($record['mother_name']) ?>"
                        data-pob="<?= htmlspecialchars($record['pob']) ?>">
                        <i class="fas fa-eye"></i> View/Print
                      </button>
                      <!-- <button class="btn btn-sm btn-view" onclick="viewRecord(<?php echo $record['record_id'] ?>)">
                                                <i class="fas fa-eye"></i> View/Print
                                            </button> -->
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php } else {
        echo '<div class="alert alert-info">No birth records found</div>';
      } ?>
    </div>
  </div>


  <!-- View/Print Record Modal -->
  <?php

  ?>
  <div class="modal fade" id="viewRecordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Birth Certificate</h5>
          <div>
            <button class="btn btn-light btn-sm me-2" onclick="window.print()">
              <i class="fas fa-print"></i> Print
            </button>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
        </div>
        <div class="modal-body">
          <div class="certificate-container">
            <div class="certificate-border">
              <div class="certificate-watermark">OFFICIAL RECORD</div>
              <img src="https://via.placeholder.com/100x100?text=SEAL" alt="Official Seal" class="certificate-seal">

              <div class="certificate-header">
                <div class="certificate-title">BIRTH CERTIFICATE</div>
                <div class="certificate-subtitle">City General Hospital - Department of Vital Records</div>
              </div>

              <div class="certificate-body">
                <div class="section-title">Child Information</div>
                <div class="detail-row">
                  <div class="detail-label">Full Name:</div>
                  <div class="detail-value"><span id="modalChildName"></span></div>
                </div>
                <div class="detail-row">
                  <div class="detail-label">Date of Birth:</div>
                  <div class="detail-value"><span id="modalDOB"></span></div>
                </div>
                <div class="detail-row">
                  <div class="detail-label">Gender:</div>
                  <div class="detail-value"><span id="modalGender"></span></div>
                </div>
                <div class="detail-row">
                  <div class="detail-label">Birth Weight:</div>
                  <div class="detail-value"><span id="modalWeight"></span></div>
                </div>
                <div class="detail-row">
                  <div class="detail-label">Father's Name:</div>
                  <div class="detail-value"><span id="modalFatherName"></span></div>
                </div>
                <div class="detail-row">
                  <div class="detail-label">Mother's Name:</div>
                  <div class="detail-value"><span id="modalMotherName"></span></div>
                </div>
                <div class="detail-row">
                  <div class="detail-label">Place of Birth:</div>
                  <div class="detail-value"><span id="modalPOB"></span></div>
                </div>

                <div class="detail-row">
                  <div class="detail-label">Parents' Address:</div>
                  <div class="detail-value"><?= $record["address"] ?></div>
                </div>
                <div class="detail-row">
                  <div class="detail-label">Parents' Contact:</div>
                  <div class="detail-value"><?= $record["phone"] ?></div>
                </div>

                <div class="section-title mt-4">Birth Details</div>
                <div class="detail-row">
                  <div class="detail-label">Attending Physician:</div>
                  <div class="detail-value">Dr. Sarah Johnson</div>
                </div>
                <div class="detail-row">
                  <div class="detail-label">Type of Delivery:</div>
                  <div class="detail-value">Vaginal (Normal)</div>
                </div>
                <div class="detail-row">
                  <div class="detail-label">Additional Notes:</div>
                  <div class="detail-value">Healthy baby boy delivered without complications. APGAR scores: 9 at 1
                    minute, 10 at 5 minutes.</div>
                </div>

                <div class="section-title mt-4">Registration Information</div>
                <div class="detail-row">
                  <div class="detail-label">Certificate Number:</div>
                  <div class="detail-value"><?= $record["HBR"] ?></div>
                </div>
                <div class="detail-row">
                  <div class="detail-label">Date Registered:</div>
                  <div class="detail-value"><?= $record["created_at"] ?></div>
                </div>

                <div class="signature-area">
                  <div class="signature-box">
                    <div>Attending Physician</div>
                    <div class="mt-3">Dr. Sarah Johnson</div>
                  </div>
                  <div class="signature-box">
                    <div>Hospital Administrator</div>
                    <div class="mt-3">Robert Williams</div>
                  </div>
                  <div class="signature-box">
                    <div>Official Seal</div>
                    <div class="mt-3">City General Hospital</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer no-print">
          <button class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-print"></i> Print Certificate
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Initialize the modal
    const viewRecordModal = new bootstrap.Modal(document.getElementById('viewRecordModal'));

    //View record details function
    function viewRecord(id) {
      <?php $viewid = $id ?>
      viewRecordModal.show();
    }

    const viewModal = document.getElementById('viewRecordModal');
    viewModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;

      document.getElementById('modalChildName').innerText = button.getAttribute('data-child_name');
      document.getElementById('modalDOB').innerText = button.getAttribute('data-dob');
      document.getElementById('modalGender').innerText = button.getAttribute('data-gender');
      document.getElementById('modalWeight').innerText = button.getAttribute('data-weight');
      document.getElementById('modalFatherName').innerText = button.getAttribute('data-father_name');
      document.getElementById('modalMotherName').innerText = button.getAttribute('data-mother_name');
      document.getElementById('modalPOB').innerText = button.getAttribute('data-pob');
      document.getElementById('modalAddress').innerText = button.getAttribute('data-address');
    });





    function searchTable() {
      // Get input value and convert to lowercase
      const input = document.getElementById("searchInput");
      const filter = input.value.toLowerCase();

      // Get table and rows
      const table = document.getElementById("recordsTable");
      const rows = table.getElementsByTagName("tr");

      // Loop through all table rows (skip header row)
      for (let i = 1; i < rows.length; i++) {
        // Get the name column (second column, index 1)
        const nameCell = rows[i].getElementsByTagName("td")[1];
        if (nameCell) {
          const nameText = nameCell.textContent || nameCell.innerText;

          // Show/hide row based on search term
          if (nameText.toLowerCase().indexOf(filter) > -1) {
            rows[i].style.display = "";
          } else {
            rows[i].style.display = "none";
          }
        }
      }
    }
  </script>
</body>

</html>