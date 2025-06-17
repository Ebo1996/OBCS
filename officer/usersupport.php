<?php
// Database configuration
include "../setup/dbconnection.php";

// Initialize variables
$complaints = [];
$selected_complaint = null;
$reply_message = '';
$success_message = '';
$error_message = '';

try {
    // Fetch all complaints
    $sql = "SELECT * FROM applications_support ORDER BY created_at";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $complaints = $result->fetch_all(MYSQLI_ASSOC);

    // Handle viewing a specific complaint
    if (isset($_GET['view']) && is_numeric($_GET['view'])) {
        $view_id = $_GET['view'];
        $stmt = $conn->prepare("SELECT * FROM applications_support WHERE id = ?");
        $stmt->bind_param("i", $view_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $selected_complaint = $result->fetch_assoc();
    }

    // Handle replying to a complaint
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_reply'])) {
        $complaint_id = $_POST['complaint_id'];
        $reply_message = htmlspecialchars(trim($_POST['reply_message']));

        if (empty($reply_message)) {
            $error_message = 'Reply message cannot be empty!';
        } else {
            // Update complaint with admin reply
            $stmt = $conn->prepare("UPDATE applications_support SET admin_reply = ?, status = 'resolved', reply_at = NOW() WHERE id = ?");
            $stmt->bind_param("si", $reply_message, $complaint_id);
            $stmt->execute();
            $feedback = "for your question";
            $current_time = date('Y-m-d H:i:s');

            $sqltomessage = "INSERT INTO messages (user_id , title , body , sent_at) VALUES (?,?,?,?)";
            $stmt = $conn->prepare($sqltomessage);
            $stmt->bind_param("isss", $complaint_id, $feedback, $reply_message, $current_time);
            $stmt->execute();

            $success_message = 'Reply sent successfully!';

            // Refresh the selected complaint
            $stmt = $conn->prepare("SELECT * FROM applications_support WHERE id = ?");
            $stmt->bind_param("i", $complaint_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $selected_complaint = $result->fetch_assoc();

            // Refresh complaints list
            $stmt = $conn->prepare("SELECT * FROM applications_support ORDER BY created_at DESC");
            $stmt->execute();
            $result = $stmt->get_result();
            $complaints = $result->fetch_all(MYSQLI_ASSOC);
        }
    }
} catch (Exception $e) {
    $error_message = 'Database error: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Support Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #0d924f;
            --accent-color: #e74c3c;
            --light-bg: #f8f9fa;
        }

        body {
            background-color: var(--light-bg);
            min-height: 100vh;
        }

        .header {
            background: linear-gradient(135deg, var(--primary-color), #1a252f);
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #1a252f;
            border-color: #1a252f;
        }

        .btn-success {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-success:hover {
            background-color: #0b7a43;
            border-color: #0b7a43;
        }

        .btn-danger {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .btn-danger:hover {
            background-color: #c0392b;
            border-color: #c0392b;
        }

        .status-pending {
            color: #f39c12;
            font-weight: bold;
        }

        .status-resolved {
            color: var(--secondary-color);
            font-weight: bold;
        }

        .table th {
            background-color: var(--primary-color);
            color: white;
        }

        .highlight-row:hover {
            background-color: rgba(44, 62, 80, 0.05);
            cursor: pointer;
        }

        .success-alert {
            background-color: var(--secondary-color);
            color: white;
        }

        .error-alert {
            background-color: var(--accent-color);
            color: white;
        }

        .complaint-details {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .reply-form {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="container">
            <h1 class="display-5"><i class="fas fa-headset me-2"></i> User Support Management</h1>
            <p class="lead">Manage and respond to user complaints</p>
        </div>
    </div>

    <div class="container">
        <?php if ($success_message): ?>
            <div class="alert success-alert alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert error-alert alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i> User Complaints</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>email</th>
                                        <th>subject</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($complaints) > 0): ?>
                                        <?php foreach ($complaints as $complain):
                                            if ($complain["status"] == "not resolved"): ?>
                                                <tr class="highlight-row">
                                                    <td><?php echo $complain['id']; ?></td>
                                                    <td><?php echo htmlspecialchars($complain['email']); ?></td>
                                                    <td><?php echo htmlspecialchars($complain['subject']); ?></td>
                                                    <td><?php echo date('M j, Y g:i a', strtotime($complain['created_at'])); ?></td>
                                                    <td>
                                                        <a href="usersupport.php?view=<?php echo $complain['id']; ?>" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-eye me-1"></i> View
                                                        </a>
                                                    </td>
                                                </tr>
                                        <?php endif;
                                        endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="no-results">No complaints found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($selected_complaint): ?>
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="complaint-details">
                        <h4><i class="fas fa-file-alt me-2"></i> Complaint Details</h4>
                        <div class="mb-3">
                            <a href="usersupport.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back to User Support
                            </a>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>email:</strong> <?php echo htmlspecialchars($selected_complaint['email']); ?></p>
                                <p><strong>Subject:</strong> <?php echo htmlspecialchars($selected_complaint['subject']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Submitted:</strong> <?php echo date('M j, Y g:i a', strtotime($selected_complaint['created_at'])); ?></p>
                                <?php if ($selected_complaint['reply_at']): ?>
                                    <p><strong>Replied:</strong> <?php echo date('M j, Y g:i a', strtotime($selected_complaint['reply_at'])); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mt-3">
                            <h5>Complaint Message:</h5>
                            <div class="p-3 bg-light rounded">
                                <?php echo nl2br(htmlspecialchars($selected_complaint['message'])); ?>
                            </div>
                        </div>

                        <?php if ($selected_complaint['admin_reply']): ?>
                            <div class="mt-4">
                                <h5>Your Reply:</h5>
                                <div class="p-3 bg-light rounded">
                                    <?php echo nl2br(htmlspecialchars($selected_complaint['admin_reply'])); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($selected_complaint['status'] !== 'resolved'): ?>
                        <div class="reply-form mt-4">
                            <h4><i class="fas fa-reply me-2"></i> Reply to Complaint</h4>
                            <hr>
                            <form method="POST" action="">
                                <input type="hidden" name="complaint_id" value="<?php echo $selected_complaint['user_id']; ?>">
                                <div class="mb-3">
                                    <label for="reply_message" class="form-label">Your Response</label>
                                    <textarea class="form-control" id="reply_message" name="reply_message" rows="5" required></textarea>
                                </div>
                                <div class="text-end">
                                    <button type="submit" name="send_reply" class="btn btn-success">
                                        <i class="fas fa-paper-plane me-2"></i> Send Reply
                                    </button>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
</body>

</html>