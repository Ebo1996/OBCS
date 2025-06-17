<?php
session_start();
include "../setup/dbconnection.php";

// Retrieve all user applications from the database
$sql = "SELECT id, CONCAT(first_name, ' ', middle_name,' ', last_name) AS full_name, dob, place_of_birth, gender,father_name, mother_name, curent_address FROM aplications";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Review Applications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .hidden {
            display: none;

        }

        .visible {
            display: block;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">User Applications</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>App ID</th>
                    <th>Full Name</th>
                    <th>Date of Birth</th>
                    <th>Place of Birth</th>
                    <th>Gender</th>
                    <th>Mother Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr id="row-<?= $row['id']; ?>">
                            <td><?= $row['id']; ?></td>
                            <td><?= $row['full_name']; ?></td>
                            <td><?= $row['dob']; ?></td>
                            <td><?= $row['place_of_birth']; ?></td>
                            <td><?= $row['gender']; ?></td>
                            <td><?= $row['mother_name']; ?></td>
                            <td>
                                <!-- Button to view documents (assumes you have a page to display docs) -->
                                <a href="viewdocument.php?app_id=<?= $row['id']; ?>" id="viewdcoment" class="btn btn-info btn-sm">View Document</a>
                                <!-- Approve button -->
                                <button class="btn btn-success btn-sm approveBtn" data-appid="<?= $row['id']; ?>">Approve</button>
                                <!-- Reject button -->
                                <button class="btn btn-danger btn-sm rejectBtn" onclick="feedbackfunction()" data-appid=" <?= $row['id']; ?>">Reject</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No applications found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for rejection reason  -->
    <div class="modal  hidden" id="feedback" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog" id="feedback">
            <form id="rejectForm" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectModalLabel">Reject Application</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="app_id" id="rejectAppId" value="">
                        <div class="mb-3">
                            <label for="reject_reason" class="form-label">Reason for Rejection</label>
                            <textarea name="reject_reason" id="reject_reason" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="action" value="reject" class="btn btn-danger" onclick="regectfunction(<?= $row['id'] ?>)">Submit Rejection</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- <div class="hidden" id="feedback">
        <input type="text">;
        <input type="submit" onclick="regectfunction(<?= $row['id'] ?>)">;
    </div> -->

    <!-- Bootstrap JS bundle -->
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // When the document is ready
        // $(document).ready(function() {
        //     // Handle Reject button click: show the modal and set the application ID
        //     $('.rejectBtn').on('click', function() {
        //         var appId = $(this).data('appid');
        //         $('#rejectAppId').val(appId);
        //         var rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
        //         rejectModal.show();
        //     });

        //     // Handle Approve button click: confirmation and send an AJAX POST request
        //     $('.approveBtn').on('click', function() {
        //         var appId = $(this).data('appid');
        //         if (confirm("Are you sure you want to approve this application?")) {
        //             $.post("process_application.php", {
        //                 app_id: appId,
        //                 action: 'approve'
        //             }, function(response) {
        //                 // Optionally, handle the response from your server (e.g., display a message)
        //                 alert(response);
        //                 location.reload(); // Reload to update the list
        //             });
        //         }
        //     });
        // });
        function feedbackfunction() {
            document.getElementById("feedback").classList.remove("hidden");
            document.getElementById("feedback").classList.add("visible");
        }

        function regectfunction(id) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {

                    document.getElementById("row-" + id).remove();
                }
            }
            xmlhttp.open("GET", "regect.php?user_id=" + id, true);
            xmlhttp.send();


        }

        
    </script>
</body>

</html>