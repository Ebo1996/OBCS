<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <meta charset="UTF-8">
    <title>Admin Announcement & Messaging</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #msg_body{
            background-color: green;
        }
    </style>
</head>

<body>
    <div class="card shadow-sm">
        <div class="card-header card-header-blue fw-semibold">Send Message to User</div>
        <div class="card-body">
            <form action="handleAnnouncement.php" method="POST">
                <input type="hidden" name="type" value="message">
                <div class="mb-3">
                    <label for="user_id" class="form-label">Select User</label>
                    <select name="user_id" id="user_id" class="form-select" required>
                        <option value="">-- Select User --</option>
                        <?php while ($user = $users_result->fetch_assoc()): ?>
                            <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['full_name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="msg_title" class="form-label">Title</label>
                    <input type="text" name="title" id="msg_title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="msg_body" class="form-label">Message</label>
                    <textarea name="body" id="msg_body" rows="4" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary text-white">Send Message</button>
            </form>
        </div>
    </div>

</body>

</html>