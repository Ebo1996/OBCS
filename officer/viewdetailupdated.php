<?php
session_start();
include '../setup/dbconnection.php';

$doc = [
    'applicant_id' => 'photo is not uploaded',
    'mother_id' => '',
    'father_id' => '',
    'birth_record' => ''
];

if (isset($_GET["app_id"]) && !empty($_GET["app_id"])) {
    $app_id = $_GET["app_id"];

    $sql = "SELECT father_id, mother_id, applicant_id, birth_record FROM applications WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $app_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Application is not found");
    } else {
        $doc = $result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birth Certificate Management - View Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .photo-container {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
        }

        .thumbnail {
            cursor: pointer;
            transition: all 0.3s;
        }

        .thumbnail:hover {
            transform: scale(1.05);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .main-photo {
            height: 300px;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .main-photo img {
            max-width: 100%;
            max-height: 100%;
        }

        .search-section {
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        /* Modern ID Card Design */
        .modern-id-card {
            width: 100%;
            max-width: 600px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            border: 1px solid #d1d9e6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            margin-bottom: 30px;
        }

        .id-card-header {
            background: linear-gradient(to right, #3498db, #2c3e50);
            color: white;
            padding: 15px 20px;
            text-align: center;
        }

        .id-card-header h4 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .id-card-body {
            display: flex;
            padding: 20px;
            position: relative;
        }

        .id-photo-section {
            width: 150px;
            margin-right: 20px;
            position: relative;
        }

        .id-main-photo {
            width: 100%;
            height: 180px;
            border-radius: 8px;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .id-details-section {
            flex: 1;
        }

        .id-detail-row {
            display: flex;
            margin-bottom: 10px;
            align-items: center;
        }

        .id-detail-label {
            width: 120px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.9rem;
        }

        .id-detail-value {
            flex: 1;
            padding: 6px 10px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 4px;
            font-size: 0.95rem;
            color: #34495e;
            border: 1px solid #e0e4e8;
        }

        .id-card-footer {
            background: #f8f9fa;
            padding: 12px 20px;
            text-align: center;
            border-top: 1px solid #e0e4e8;
            font-size: 0.8rem;
            color: #7f8c8d;
        }

        .id-card-qr {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 80px;
            height: 80px;
            background: white;
            padding: 5px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .id-card-qr img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .id-card-watermark {
            position: absolute;
            opacity: 0.05;
            font-size: 120px;
            font-weight: bold;
            color: #2c3e50;
            transform: rotate(-30deg);
            pointer-events: none;
            z-index: 0;
        }

        /* Certificate Styles */
        .certificate-modern {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            border: 1px solid #e1e5ee;
            position: relative;
            overflow: hidden;
        }

        .certificate-modern::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 10px;
            background: linear-gradient(90deg, #3498db, #2ecc71);
        }

        .certificate-header-modern {
            text-align: center;
            margin-bottom: 30px;
        }

        .certificate-header-modern h5 {
            color: #2c3e50;
            font-size: 22px;
            margin-bottom: 5px;
        }

        .certificate-header-modern h6 {
            color: #4a5568;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .certificate-header-modern h4 {
            color: #3498db;
            font-size: 24px;
            margin: 20px 0;
            position: relative;
        }

        .certificate-header-modern h4::after {
            content: "";
            display: block;
            width: 100px;
            height: 3px;
            background: #3498db;
            margin: 10px auto;
        }

        .certificate-content-modern {
            display: flex;
            gap: 30px;
        }

        .certificate-photo-modern {
            width: 150px;
            height: 180px;
            border: 3px solid #f1f5f9;
            border-radius: 5px;
            object-fit: cover;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .certificate-details-modern {
            flex: 1;
        }

        .certificate-table-modern {
            width: 100%;
            border-collapse: collapse;
        }

        .certificate-table-modern th {
            text-align: left;
            padding: 10px 15px;
            background-color: #f8fafc;
            color: #4a5568;
            width: 35%;
            border: 1px solid #e2e8f0;
        }

        .certificate-table-modern td {
            padding: 10px 15px;
            border: 1px solid #e2e8f0;
            color: #2d3748;
        }

        .certificate-footer-modern {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px dashed #cbd5e0;
        }

        .signature-box-modern {
            text-align: center;
            flex: 1;
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-show {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>
</head>

<body>
    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Left Side - Photo Display -->
            <div class="col-md-6">
                <h3>Photo Display</h3>
                <div class="main-photo" id="mainPhotoDisplay">
                    <p class="text-muted">Click a thumbnail to display photo here</p>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="thumbnail" onclick="displayPhoto('<?= htmlspecialchars($doc['applicant_id']) ?>')">
                            <img src="<?= htmlspecialchars($doc['applicant_id']) ?>" alt="Photo 1" class="img-thumbnail img-fluid">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail" onclick="displayPhoto('<?= htmlspecialchars($doc['mother_id']) ?>')">
                            <img src="<?= htmlspecialchars($doc['mother_id']) ?>" alt="Photo 2" class="img-thumbnail img-fluid">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail" onclick="displayPhoto('<?= htmlspecialchars($doc['father_id']) ?>')">
                            <img src="<?= htmlspecialchars($doc['father_id']) ?>" alt="Father ID" class="img-thumbnail img-fluid">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="thumbnail" onclick="displayPhoto('<?= htmlspecialchars($doc['birth_record']) ?>')">
                            <img src="<?= htmlspecialchars($doc['birth_record']) ?>" alt="Photo 3" class="img-thumbnail img-fluid">
                        </div>
                    </div>
                </div>

                <!-- Back to Dashboard Button -->
                <div class="mt-4">
                    <a href="dashboard.html" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>

            <!-- Right Side - Search Forms -->
            <div class="col-md-6">
                <div class="search-section">
                    <h3>Search Records</h3>

                    <!-- ID Search Form -->
                    <div class="d-flex gap-3 flex-wrap">
                        <div class="card flex-fill mb-4">
                            <div class="card-header bg-primary text-white">
                                Search by ID
                            </div>
                            <div class="card-body">
                                <form id="idSearchForm">
                                    <div class="mb-3">
                                        <label for="idNumber" class="form-label">ID Number</label>
                                        <input type="text" class="form-control" id="idNumber" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Search ID</button>
                                </form>
                            </div>
                        </div>

                        <!-- Birth Certificate Search Form -->
                        <div class="card flex-fill">
                            <div class="card-header bg-success text-white">
                                Search by Birth Certificate
                            </div>
                            <div class="card-body">
                                <form id="certificateSearchForm">
                                    <div class="mb-3">
                                        <label for="certificateNumber" class="form-label">Certificate Number</label>
                                        <input type="text" class="form-control" id="certificateNumber" required>
                                    </div>
                                    <button type="submit" class="btn btn-success">Search Certificate</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Display Area for Search Results -->
                    <div id="searchResults" class="mt-4">
                        <!-- Modern ID Card Template -->
                        <div id="idInfoTemplate" class="modern-id-card animate-show" style="display: none;">
                            <div class="id-card-header">
                                <h4>NATIONAL ID CARD</h4>
                            </div>
                            <div class="id-card-body">
                                <div class="id-photo-section">
                                    <img id="idPhoto" src="https://via.placeholder.com/150" alt="ID Photo" class="id-main-photo">
                                </div>
                                <div class="id-details-section">
                                    <div class="id-detail-row">
                                        <div class="id-detail-label">ID Number:</div>
                                        <div class="id-detail-value" id="idNoDisplay"></div>
                                    </div>
                                    <div class="id-detail-row">
                                        <div class="id-detail-label">Full Name:</div>
                                        <div class="id-detail-value">
                                            <span id="firstName"></span> <span id="middleName"></span> <span id="lastName"></span>
                                        </div>
                                    </div>
                                    <div class="id-detail-row">
                                        <div class="id-detail-label">Date of Birth:</div>
                                        <div class="id-detail-value" id="dob"></div>
                                    </div>
                                    <div class="id-detail-row">
                                        <div class="id-detail-label">Gender:</div>
                                        <div class="id-detail-value" id="gender"></div>
                                    </div>
                                    <div class="id-detail-row">
                                        <div class="id-detail-label">Nationality:</div>
                                        <div class="id-detail-value" id="nationality"></div>
                                    </div>
                                    <div class="id-detail-row">
                                        <div class="id-detail-label">Kebele:</div>
                                        <div class="id-detail-value" id="kebele"></div>
                                    </div>
                                    <div class="id-detail-row">
                                        <div class="id-detail-label">Issued Date:</div>
                                        <div class="id-detail-value" id="issuedDate"></div>
                                    </div>
                                </div>
                                <div class="id-card-qr">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=ID12345" alt="QR Code">
                                </div>
                                <div class="id-card-watermark">OFFICIAL</div>
                            </div>
                            <div class="id-card-footer">
                                Federal Democratic Republic of Ethiopia • Ministry of Interior
                            </div>
                        </div>

                        <!-- Modern Certificate Template -->
                        <div id="certificateInfoTemplate" class="certificate-modern animate-show" style="display: none;">
                            <div class="certificate-header-modern">
                                <h5>Federal Democratic Republic of Ethiopia</h5>
                                <h6>Ministry of Health</h6>
                                <h6 id="hospitalName">Hospital Name</h6>
                                <h4>Birth Certificate</h4>
                            </div>

                            <div class="certificate-content-modern">
                                <div>
                                    <img id="certificatePhoto" src="https://via.placeholder.com/150" alt="Certificate Photo" class="certificate-photo-modern">
                                </div>
                                <div class="certificate-details-modern">
                                    <table class="certificate-table-modern">
                                        <tr>
                                            <th>Certificate No:</th>
                                            <td><span id="certificateNoDisplay"></span></td>
                                        </tr>
                                        <tr>
                                            <th>Child Name:</th>
                                            <td><span id="childName"></span></td>
                                        </tr>
                                        <tr>
                                            <th>Father's Name:</th>
                                            <td><span id="fatherName"></span></td>
                                        </tr>
                                        <tr>
                                            <th>Mother's Name:</th>
                                            <td><span id="motherName"></span></td>
                                        </tr>
                                        <tr>
                                            <th>Date of Birth:</th>
                                            <td><span id="certDob"></span></td>
                                        </tr>
                                        <tr>
                                            <th>Place of Birth:</th>
                                            <td><span id="placeOfBirth"></span></td>
                                        </tr>
                                        <tr>
                                            <th>Issued Date:</th>
                                            <td><span id="certIssuedDate"></span></td>
                                        </tr>
                                        <tr>
                                            <th>Confirmer Name:</th>
                                            <td><span id="confirmerName"></span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="certificate-footer-modern">
                                <div class="signature-box-modern">
                                    <p><strong>Registrar Name:</strong> <span id="registrarName"></span></p>
                                    <p><strong>Registrar Signature:</strong></p>
                                    <img id="registrarSignature" src="../images/certificate.jpg" alt="Signature" class="img-fluid">
                                </div>
                                <div class="signature-box-modern">
                                    <p><strong>Official Seal / Stamp:</strong></p>
                                    <img id="officialStamp" src="../images/certificate.jpg" alt="Official Stamp" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">

    <script>
        function displayPhoto(photoSrc) {
            const mainPhotoDisplay = document.getElementById('mainPhotoDisplay');
            mainPhotoDisplay.innerHTML = `<img src="${photoSrc}" alt="Displayed Photo" class="img-fluid">`;
        }

        // ID Search - Hides certificate before showing ID
        document.getElementById('idSearchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            document.getElementById('certificateInfoTemplate').style.display = 'none';
            document.getElementById('idInfoTemplate').style.display = 'block';
            document.getElementById('idNoDisplay').textContent = "ID12345";
            document.getElementById('firstName').textContent = "John";
            document.getElementById('middleName').textContent = "Doe";
            document.getElementById('lastName').textContent = "Smith";
            document.getElementById('dob').textContent = "2000-01-01";
            document.getElementById('gender').textContent = "Male";
            document.getElementById('nationality').textContent = "Ethiopian";
            document.getElementById('kebele').textContent = "04";
            document.getElementById('issuedDate').textContent = "2023-05-01";
            document.getElementById('idPhoto').src = "https://via.placeholder.com/150";
        });

        // Certificate Search - Hides ID before showing certificate
        document.getElementById('certificateSearchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            document.getElementById('idInfoTemplate').style.display = 'none';
            document.getElementById('certificateInfoTemplate').style.display = 'block';
            document.getElementById('certificateNoDisplay').textContent = "CERT56789";
            document.getElementById('childName').textContent = "Baby John";
            document.getElementById('fatherName').textContent = "John Smith";
            document.getElementById('motherName').textContent = "Jane Smith";
            document.getElementById('certDob').textContent = "2023-03-15";
            document.getElementById('placeOfBirth').textContent = "Addis Ababa";
            document.getElementById('certIssuedDate').textContent = "2023-03-16";
            document.getElementById('confirmerName').textContent = "Health Officer";
            document.getElementById('registrarName').textContent = "Registrar X";
            document.getElementById('certificatePhoto').src = "https://via.placeholder.com/150";
        });
    </script>
</body>
</html>