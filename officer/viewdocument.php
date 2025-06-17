<?php
session_start();
include "../setup/dbconnection.php";


if (!isset($_GET['app_id']) || empty($_GET['app_id'])) {
    die("Application ID not provided.");
}

$app_id = intval($_GET['app_id']);

$sql = "SELECT father_id, mother_id, applicant_id, birth_record FROM applications WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $app_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Application not found.");
}

$doc = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Documents</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .document {
            margin-bottom: 20px;
        }

        .document img {
            max-height: 300px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2>Uploaded Documents for Application #<?= htmlspecialchars($app_id) ?></h2>

        <div class="row document">
            <div class="col-md-6">
                <h4>Father's ID Proof</h4>
                <?php if (!empty($doc['father_id'])): ?>
                    <img src="<?= htmlspecialchars($doc['father_id']) ?>" alt="Father's ID Proof" class="img-fluid">
                <?php else: ?>
                    <p>No document uploaded.</p>
                <?php endif; ?>
            </div>
          
            <div class="col-md-6">
                <h4>Mother's ID Proof</h4>
                <?php if (!empty($doc['mother_id'])): ?>
                    <img src="<?= htmlspecialchars($doc['mother_id']) ?>" alt="Mother's ID Proof" class="img-fluid">
                <?php else: ?>
                    <p>No document uploaded.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="row document">
            <div class="col-md-6">
                <h4>Applicant ID Proof</h4>
                <?php if (!empty($doc['applicant_id'])): ?>
                    <img src="<?= htmlspecialchars($doc['applicant_id']) ?>" alt="Applicant ID Proof" class="img-fluid">
                <?php else: ?>
                    <p>No document uploaded.</p>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <h4>Birth Record</h4>
                <?php if (!empty($doc['birth_record'])): ?>
                    <?php
                    $ext = strtolower(pathinfo($doc['birth_record'], PATHINFO_EXTENSION));
                    if ($ext == 'pdf'):
                    ?>
                        <a href="<?= htmlspecialchars($doc['birth_record']) ?>" target="_blank" class="btn btn-primary">View PDF</a>
                    <?php else: ?>
                        <img src="<?= htmlspecialchars($doc['birth_record']) ?>" alt="Birth Record" class="img-fluid">
                    <?php endif; ?>
                <?php else: ?>
                    <p>No document uploaded.</p>
                <?php endif; ?>
            </div>
        </div>

        <a href="addmin.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>