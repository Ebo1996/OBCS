<?php
include '../setup/dbconnection.php';

// Fetch users for dropdown
$users_result = $conn->query("SELECT id, CONCAT(first_name, ' ', last_name) AS full_name FROM users");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Announcement & Messaging</title>
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

        .custom-container {
            max-width: 700px;
            margin: auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .card {
            border: none;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 15px 20px;
            border-bottom: none;
        }

        .nav-tabs {
            border-bottom: 2px solid #dee2e6;
        }

        .nav-tabs .nav-link {
            color: var(--primary-color);
            border: none;
            padding: 12px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-right: 5px;
            border-radius: 8px 8px 0 0;
        }

        .nav-tabs .nav-link:hover {
            background-color: rgba(44, 62, 80, 0.1);
            border-color: transparent;
        }

        .nav-tabs .nav-link.active {
            color: white;
            background-color: var(--primary-color);
            border: none;
            font-weight: 600;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #1a252f;
            border-color: #1a252f;
            transform: translateY(-2px);
        }

        .form-control,
        .form-select {
            border: 1px solid #ced4da;
            border-radius: 6px;
            padding: 10px 15px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(13, 146, 79, 0.25);
        }

        .searchable-select {
            position: relative;
        }

        .searchable-select select {
            width: 100%;
            border-radius: 6px;
        }

        /* Hide the dropdown arrow in Firefox */
        .searchable-select select {
            -moz-appearance: none;
            text-indent: 0.01px;
            text-overflow: '';
        }

        /* Hide the dropdown arrow in Chrome/Safari */
        .searchable-select select::-ms-expand {
            display: none;
        }

        h2 {
            color: var(--primary-color);
            position: relative;
            padding-bottom: 10px;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background-color: var(--secondary-color);
            border-radius: 3px;
        }

        /* Accent color for important elements */
        .text-accent {
            color: var(--accent-color);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .custom-container {
                margin: 20px 10px;
                padding: 15px;
            }

            .nav-tabs .nav-link {
                padding: 10px 15px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

    <div class="container custom-container">
        <h2 class="text-center mb-4 fw-bold">📢 Admin Announcement & Messaging</h2>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs mb-4" id="announcementTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="announcement-tab" data-bs-toggle="tab" data-bs-target="#announcement" type="button" role="tab" aria-controls="announcement" aria-selected="true">Public Announcement</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="message-tab" data-bs-toggle="tab" data-bs-target="#message" type="button" role="tab" aria-controls="message" aria-selected="false">Send Message</button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="announcementTabsContent">
            <!-- Public Announcement Form -->
            <div class="tab-pane fade show active" id="announcement" role="tabpanel" aria-labelledby="announcement-tab">
                <div class="card shadow-sm">
                    <div class="card-header fw-semibold">Post Public Announcement</div>
                    <div class="card-body">
                        <form action="handleAnnouncement.php" method="POST">
                            <input type="hidden" name="type" value="announcement">
                            <div class="mb-3">
                                <label for="ann_title" class="form-label">Title</label>
                                <input type="text" name="title" id="ann_title" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="ann_body" class="form-label">Content</label>
                                <textarea name="body" id="ann_body" rows="4" class="form-control" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Post Announcement</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Private Message Form -->
            <div class="tab-pane fade" id="message" role="tabpanel" aria-labelledby="message-tab">
                <div class="card shadow-sm">
                    <div class="card-header fw-semibold">Send Message to User</div>
                    <div class="card-body">
                        <form action="handleAnnouncement.php" method="POST">
                            <input type="hidden" name="type" value="message">

                            <div class="mb-3">
                                <label for="user_id" class="form-label">Select User</label>
                                <div class="searchable-select">
                                    <input type="text" id="userSearch" class="form-control mb-2" placeholder="Search users..." onkeyup="filterUsers()">
                                    <select name="user_id" id="user_id" class="form-select" required size="5" style="height: auto;">
                                        <option value="">-- Select User --</option>
                                        <?php while ($user = $users_result->fetch_assoc()): ?>
                                            <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['full_name']) ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="msg_title" class="form-label">Title</label>
                                <input type="text" name="title" id="msg_title" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="msg_body" class="form-label">Message</label>
                                <textarea name="body" id="msg_body" rows="4" class="form-control" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function filterUsers() {
            const input = document.getElementById('userSearch');
            const filter = input.value.toUpperCase();
            const select = document.getElementById('user_id');
            const options = select.getElementsByTagName('option');

            for (let i = 0; i < options.length; i++) {
                const text = options[i].textContent || options[i].innerText;
                if (text.toUpperCase().indexOf(filter) > -1) {
                    options[i].style.display = "";
                } else {
                    options[i].style.display = "none";
                }
            }
        }
    </script>
</body>

</html>