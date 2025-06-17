<?php
include "../setup/dbconnection.php";
$sql = "SELECT id, CONCAT(first_name, ' ', middle_name,' ', last_name) AS full_name, dob, place_of_birth, gender, father_name, mother_name, current_address FROM applications";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Scoped styles to prevent conflicts */
        .apps-content {
            /* margin-left: 280px; */
            padding: 25px;
            transition: margin-left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: #f5f7fa;
            min-height: 100vh;
        }

        .apps-main-sidebar.hidden~.apps-content {
            margin-left: 0;
        }

        .apps-header {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .apps-table-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
        }

        .apps-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .apps-table thead th {
            background-color: #2c3e50;
            color: white;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .apps-table th,
        .apps-table td {
            padding: 12px 15px;
            vertical-align: middle;
        }

        .apps-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .apps-table tbody tr:hover {
            background-color: #e9ecef;
        }

        .apps-btn {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .apps-btn-view {
            background-color: rgba(52, 152, 219, 0.1);
            color: #3498db;
            border: 1px solid rgba(52, 152, 219, 0.3);
        }

        .apps-btn-view:hover {
            background-color: rgba(52, 152, 219, 0.2);
        }

        .apps-btn-approve {
            background-color: rgba(46, 204, 113, 0.1);
            color: #2ecc71;
            border: 1px solid rgba(46, 204, 113, 0.3);
        }

        .apps-btn-approve:hover {
            background-color: rgba(46, 204, 113, 0.2);
        }

        .apps-btn-reject {
            background-color: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
            border: 1px solid rgba(231, 76, 60, 0.3);
        }

        .apps-btn-reject:hover {
            background-color: rgba(231, 76, 60, 0.2);
        }

        .apps-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .apps-modal-content {
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .apps-status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .apps-badge-pending {
            background-color: rgba(241, 196, 15, 0.1);
            color: #f39c12;
        }

        .apps-badge-approved {
            background-color: rgba(46, 204, 113, 0.1);
            color: #27ae60;
        }

        .apps-badge-rejected {
            background-color: rgba(231, 76, 60, 0.1);
            color: #c0392b;
        }
    </style>
</head>

<body>
    <main class="apps-content">
        <div class="apps-header">
            <h2 class="mb-0">Birth Certificate Applications</h2>
            <p class="text-muted mb-0">Manage all pending applications</p>
        </div>

        <div class="apps-table-container">
            <div class="table-responsive">
                <table class="apps-table">
                    <thead>
                        <tr>
                            <th>App ID</th>
                            <th>Full Name</th>
                            <th>Date of Birth</th>
                            <th>Place of Birth</th>
                            <th>Gender</th>
                            <th>Mother's Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) { ?>
                                <tr id="apps-row-<?= $row['id']; ?>">
                                    <td><?= $row['id']; ?></td>
                                    <td><?= $row['full_name']; ?></td>
                                    <td><?= date('M d, Y', strtotime($row['dob'])); ?></td>
                                    <td><?= $row['place_of_birth']; ?></td>
                                    <td>
                                        <span class="apps-status-badge <?= $row['gender'] == 'Male' ? 'apps-badge-approved' : 'apps-badge-pending' ?>">
                                            <?= $row['gender']; ?>
                                        </span>
                                    </td>
                                    <td><?= $row['mother_name']; ?></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="updatdetailview.php?app_id=<?= $row['id'] ?>" class="apps-btn apps-btn-view">
                                                <i class="fas fa-file-alt"></i> View
                                            </a>
                                            <!-- Uncomment these when ready -->
                                            <!--
                                            <button class="apps-btn apps-btn-approve" onclick="approved(<?= $row['id']; ?>)" data-appid="<?= $row['id']; ?>">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                            <button class="apps-btn apps-btn-reject" onclick="showRejectModal(<?= $row['id']; ?>)">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                            -->
                                        </div>
                                    </td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-3"></i><br>
                                    No applications found
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Reject Modal -->
    <div class="apps-modal" id="apps-reject-modal">
        <div class="apps-modal-content">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Reject Application</h5>
                <button type="button" class="btn-close" onclick="hideRejectModal()"></button>
            </div>
            <form id="apps-reject-form">
                <div class="mb-3">
                    <label for="apps-reject-reason" class="form-label">Reason for rejection</label>
                    <textarea class="form-control" id="apps-reject-reason" rows="3" required></textarea>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary" onclick="hideRejectModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">Submit Rejection</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Current application ID for rejection
        let currentRejectAppId = null;

        function showRejectModal(appId) {
            currentRejectAppId = appId;
            document.getElementById('apps-reject-modal').style.display = 'flex';
        }

        function hideRejectModal() {
            document.getElementById('apps-reject-modal').style.display = 'none';
            document.getElementById('apps-reject-form').reset();
            currentRejectAppId = null;
        }

        function approved(id) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4) {
                    if (xmlhttp.status == 200 && xmlhttp.responseText.trim() === "Certificate approved and email sent successfully!") {
                        document.getElementById("apps-row-" + id).remove();
                        alert("Certificate approved and email sent successfully!");
                    } else {
                        alert("Error: " + xmlhttp.responseText);
                    }
                }
            }
            xmlhttp.open("GET", "approved.php?approve_id=" + id, true);
            xmlhttp.send();
        }

        function regectfunction(id) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("apps-row-" + id).remove();
                    hideRejectModal();
                }
            }
            xmlhttp.open("GET", "regect.php?user_id=" + id, true);
            xmlhttp.send();
        }

        // Handle reject form submission
        document.getElementById('apps-reject-form').addEventListener('submit', function(e) {
            e.preventDefault();
            if (currentRejectAppId) {
                regectfunction(currentRejectAppId);
            }
        });
    </script>
</body>

</html>