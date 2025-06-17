<?php
include "../setup/dbconnection.php";

$sql = "SELECT * FROM payments ";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #0d924f;
            --accent-color: #e74c3c;
            --light-bg: #f8f9fa;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .header {
            background-color: var(--primary-color);
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 150px;
            height: 4px;
            background-color: var(--secondary-color);
            border-radius: 2px;
        }

        .payment-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
            margin-bottom: 25px;
        }

        .payment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
            font-weight: 600;
            border-bottom: none;
        }

        .btn-view {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .btn-view:hover {
            background-color: #1a252f;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-approve {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .btn-approve:hover {
            background-color: #0a7a40;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(13, 146, 79, 0.2);
        }

        .btn-reject {
            background-color: var(--accent-color);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .btn-reject:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(231, 76, 60, 0.2);
        }

        .details-container {
            display: none;
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
            margin-top: 25px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .screenshot-preview {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-top: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .screenshot-preview:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            display: inline-block;
        }

        .pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .table {
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .table thead th {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 15px;
        }

        .table tbody tr {
            background-color: white;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
        }

        .table tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-top: none;
            border-bottom: 1px solid #f0f0f0;
        }

        .table tbody tr:first-child td:first-child {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }

        .table tbody tr:first-child td:last-child {
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        .no-payments {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            color: var(--primary-color);
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .header {
                padding: 15px 0;
                margin-bottom: 20px;
            }

            .table thead {
                display: none;
            }

            .table tbody tr {
                display: block;
                margin-bottom: 15px;
            }

            .table tbody td {
                display: block;
                text-align: right;
                padding: 10px 15px;
                position: relative;
            }

            .table tbody td::before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                top: 10px;
                font-weight: bold;
                color: var(--primary-color);
            }

            .table tbody tr:first-child td:first-child,
            .table tbody tr:first-child td:last-child {
                border-radius: 0;
            }

            .table tbody tr td:first-child {
                border-top-left-radius: 8px;
                border-top-right-radius: 8px;
            }

            .table tbody tr td:last-child {
                border-bottom-left-radius: 8px;
                border-bottom-right-radius: 8px;
                border-bottom: none;
            }
        }
    </style>
</head>

<body>
    <div class="header text-center">
        <h1 class="mb-2">Payment Management System</h1>
        <p class="mb-0">Review and process birth certificate applications</p>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card payment-card">
                    <div class="card-header">
                        <h5 class="mb-0">Pending Payments</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Certificate ID</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="paymentList">
                                    <?php if ($result && $result->num_rows > 0): ?>
                                        <?php while ($payment = $result->fetch_assoc()): ?>
                                            <tr id="row-<?= $payment['id'] ?>">
                                                <td data-label="Full Name"><?= htmlspecialchars($payment['full_name']) ?></td>
                                                <td data-label="Certificate ID"><?= htmlspecialchars($payment['certificate_id']) ?></td>
                                                <td data-label="Action">
                                                    <button class="btn btn-view btn-sm" data-id="<?= $payment['id'] ?>">View Details</button>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="no-payments">No payments found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="paymentDetails" class="details-container">
                    <h4 class="text-primary">Payment Details</h4>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Full Name:</strong> <span id="detailName" class="text-muted"><?php echo isset($payment) ? $payment["full_name"] : '' ?></span></p>
                            <p><strong>Certificate ID:</strong> <span id="detailAppId" class="text-muted"><?php echo isset($payment) ? $payment["certificate_id"] : '' ?></span></p>
                            <p><strong>Phone Number:</strong> <span id="detailPhone" class="text-muted"><?php echo isset($payment) ? $payment["phone"] : '' ?></span></p>
                            <p><strong>Amount (ETB):</strong> <span id="detailAmount" class="text-muted"><?php echo isset($payment) ? $payment["amount"] : '' ?></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Payment Screenshot:</strong></p>
                            <img id="detailScreenshot" src="<?php echo isset($payment) ? '../userdashboard/' . $payment['payment_IMG'] : '' ?>" alt="Payment Screenshot" class="screenshot-preview">
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <button id="rejectBtn" class="btn btn-reject me-2">Reject</button>
                        <button id="approveBtn" class="btn btn-approve me-2">Approve</button>
                        <button id="closeBtn" class="btn btn-secondary">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Close button functionality
            document.getElementById('closeBtn').addEventListener('click', function() {
                document.getElementById('paymentDetails').style.display = 'none';
            });

            // View button functionality
            document.querySelectorAll('.btn-view').forEach(button => {
                button.addEventListener('click', function() {
                    const paymentId = this.getAttribute('data-id');
                    fetchPaymentDetails(paymentId);
                });
            });
        });

        // Fetch payment details via AJAX
        function fetchPaymentDetails(paymentId) {
            // Show loading state
            const detailsContainer = document.getElementById('paymentDetails');
            detailsContainer.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Loading payment details...</p></div>';
            detailsContainer.style.display = 'block';

            fetch('get_payment_detail.php?id=' + paymentId)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showPaymentDetails(data.payment);
                    } else {
                        throw new Error(data.message || 'Error loading payment details');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    detailsContainer.innerHTML = `
                        <div class="alert alert-danger">
                            <h5>Error Loading Details</h5>
                            <p>${error.message}</p>
                            <button onclick="document.getElementById('paymentDetails').style.display='none'" 
                                    class="btn btn-sm btn-secondary">Close</button>
                        </div>`;
                });
        }

        // Display payment details
        function showPaymentDetails(payment) {
            const detailsHTML = `
                <h4 class="text-primary">Payment Details</h4>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Full Name:</strong> <span id="detailName" class="text-muted">${payment.full_name}</span></p>
                        <p><strong>Certificate ID:</strong> <span id="detailAppId" class="text-muted">${payment.certificate_id}</span></p>
                        <p><strong>Phone Number:</strong> <span id="detailPhone" class="text-muted">${payment.phone}</span></p>
                        <p><strong>Amount (ETB):</strong> <span id="detailAmount" class="text-muted">${payment.amount}</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Payment Screenshot:</strong></p>
                        <img id="detailScreenshot" src="../userdashboard/${payment.payment_IMG}" alt="Payment Screenshot" class="screenshot-preview">
                    </div>
                </div>
                <div class="mt-4 text-end">
                    <button id="rejectBtn" class="btn btn-reject me-2">Reject</button>
                    <button id="approveBtn" class="btn btn-approve me-2">Approve</button>
                    <button id="closeBtn" class="btn btn-secondary">Close</button>
                </div>`;

            const detailsContainer = document.getElementById('paymentDetails');
            detailsContainer.innerHTML = detailsHTML;
            detailsContainer.style.display = 'block';

            // Set up new event listeners for the buttons
            document.getElementById('closeBtn').addEventListener('click', function() {
                detailsContainer.style.display = 'none';
            });

            document.getElementById('approveBtn').addEventListener('click', function() {
                updatePaymentStatus(payment.id, 'paid', payment.certificate_id);
            });

            document.getElementById('rejectBtn').addEventListener('click', function() {
                updatePaymentStatus(payment.id, 'rejected', payment.certificate_id);
            });

            // Scroll to details smoothly
            detailsContainer.scrollIntoView({
                behavior: 'smooth'
            });
        }

        // Update payment status
        function updatePaymentStatus(paymentId, status, certificate_id) {
            const buttons = document.querySelectorAll('#approveBtn, #rejectBtn, #closeBtn');
            buttons.forEach(btn => btn.disabled = true);

            // Add loading state to the clicked button
            const actionBtn = document.getElementById(status === 'paid' ? 'approveBtn' : 'rejectBtn');
            const originalText = actionBtn.innerHTML;
            actionBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...`;

            fetch('update_payment_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: paymentId,
                        status: status,
                        certificate_id: certificate_id
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Show success message
                        const detailsContainer = document.getElementById('paymentDetails');
                        detailsContainer.innerHTML = `
                        <div class="alert alert-success">
                            <h5>Payment ${status} successfully!</h5>
                            <p>The payment has been ${status}.</p>
                            <button onclick="location.reload()" class="btn btn-sm btn-primary">Refresh List</button>
                        </div>`;

                        // Remove the row from the table
                        const row = document.getElementById("row-" + paymentId);
                        if (row) row.remove();
                    } else {
                        throw new Error(data.message || 'Error updating payment status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const detailsContainer = document.getElementById('paymentDetails');
                    const errorHTML = `
                    <div class="alert alert-danger">
                        <h5>Error Processing Request</h5>
                        <p>${error.message}</p>
                        <div class="text-end">
                            <button onclick="document.getElementById('paymentDetails').style.display='none'" 
                                    class="btn btn-sm btn-secondary me-2">Close</button>
                            <button onclick="fetchPaymentDetails(${paymentId})" 
                                    class="btn btn-sm btn-primary">Try Again</button>
                        </div>
                    </div>`;

                    // Insert error message before the buttons
                    detailsContainer.innerHTML = errorHTML;
                })
                .finally(() => {
                    buttons.forEach(btn => btn.disabled = false);
                    actionBtn.innerHTML = originalText;
                });
        }
    </script>
</body>

</html>