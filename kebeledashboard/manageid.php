<?php
include "../setup/dbconnection.php";
$sql = "SELECT id,  Kebele_id_number, CONCAT(first_name, ' ',middle_name, ' ', last_name) AS full_name, date_of_birth, gender, address, nationality, photo_path role FROM kebele_ids LIMIT 10";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Management</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome for icons -->
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
      padding-top: 20px;
    }

    .card {
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .table-responsive {
      border-radius: 10px;
      overflow: hidden;
    }

    .table thead th {
      background-color: var(--primary-color);
      color: white;
      border-bottom: none;
    }

    .btn-reject {
      background-color: var(--accent-color);
      color: white;
    }

    .btn-message {
      background-color: var(--secondary-color);
      color: white;
    }

    .modal-header {
      background-color: var(--primary-color);
      color: white;
    }

    .search-container {
      margin-bottom: 20px;
    }

    .no-results {
      text-align: center;
      padding: 20px;
      font-style: italic;
      color: #6c757d;
    }

    /* New styles for right-aligned actions */
    .actions-cell {
      text-align: right;
      white-space: nowrap;
    }

    .margin_left {
      margin-left: 80px;
    }

    .table td,
    .table th {
      padding: 12px 15px;
      vertical-align: middle;
    }
  </style>
</head>

<body>
  <div class="container margin_left">
    <div class="row mb-4">
      <div class="col-12">
        <h2 class="text-center">ID Management</h2>
      </div>
    </div>

    <!-- Search Bar -->
    <div class="row search-container">
      <div class="col-md-6 mx-auto">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Search users..." id="searchInput"
            onkeyup="searchUsers()">
          <button class="btn btn-primary" type="button" id="searchButton">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover" id="usersTable">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>dob</th>
                <th>gender</th>
                <th>address</th>
                <th>nationality</th>
                <th class="actions-cell">Actions</th>
              </tr>
            </thead>
            <tbody id="usersTableBody">
              <?php if ($result->num_rows > 0) {
                while ($user = $result->fetch_assoc()) { ?>
                  <tr id="row-<?= $user["id"] ?>">
                    <td><?= $user["Kebele_id_number"] ?></td>
                    <td><?= $user["full_name"] ?></td>
                    <td><?= $user["date_of_birth"] ?></td>
                    <td><?= $user["gender"] ?></td>
                    <td><?= $user["address"] ?></td>
                    <td><?= $user["nationality"] ?></td>
                    <td class="actions-cell">
                      <button class="btn btn-sm btn-reject me-2"
                        onclick="deleteUser(<?= $user['id'] ?>, '<?= addslashes($user['full_name']) ?>')">
                        <i class="fas fa-user-times me-1"></i> delete
                      </button>
                      <button class="btn btn-sm btn-message"
                        onclick="showMessageModal(<?= $user['id'] ?>, '<?= addslashes($user['full_name']) ?>')">
                        <i class="fas fa-envelope me-1"></i> Message
                      </button>
                    </td>
                  </tr>
                <?php }
              } else { ?>
                <tr>
                  <td colspan="6" class="no-results">No users found.</td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
          <div id="noResultsMessage" class="no-results" style="display: none;">No users match your search.</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete User Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Delete User</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete <strong><span id="deleteUserName"></span></strong> (ID: <span
              id="deleteUserId"></span>)?</p>
          <div class="mb-3">
            <label for="deleteReason" class="form-label">Reason (optional):</label>
            <textarea class="form-control" id="deleteReason" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" id="confirmDelete">Delete User</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Message User Modal -->
  <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="messageModalLabel">Send Message</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Send message to <strong><span id="messageUserName"></span></strong> (ID: <span id="messageUserId"></span>):
          </p>
          <div class="mb-3">
            <label for="messageSubject" class="form-label">Subject:</label>
            <input type="text" class="form-control" id="messageSubject">
          </div>
          <div class="mb-3">
            <label for="messageContent" class="form-label">Message:</label>
            <textarea class="form-control" id="messageContent" rows="5"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="sendMessage">Send Message</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Delete User function
    function deleteUser(id, name) {
      // Set the user info in the modal
      document.getElementById('deleteUserName').textContent = name;
      document.getElementById('deleteUserId').textContent = id;

      // Store the user ID in the confirm button
      document.getElementById('confirmDelete').setAttribute('data-user-id', id);
      const deletereason = document.getElementById('deleteReason').value;

      // Show the modal
      const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
      deleteModal.show();
    }

    // Show Message Modal function
    function showMessageModal(id, name) {
      // Set the user info in the modal
      document.getElementById('messageUserName').textContent = name;
      document.getElementById('messageUserId').textContent = id;

      // Store the user ID in the send button
      document.getElementById('sendMessage').setAttribute('data-user-id', id);

      // Show the modal
      const messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
      messageModal.show();
    }

    // Confirm delete action
    document.getElementById('confirmDelete').addEventListener('click', function () {
      const userId = this.getAttribute('data-user-id');
      const reason = document.getElementById('deleteReason').value;

      // Make an AJAX call to delete the user
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          // Remove the row from the table
          document.getElementById("row-" + userId).remove();

          // Hide the modal
          const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
          deleteModal.hide();

          // Show success message
          alert('User deleted successfully');

          // Check if any users are left
          checkNoResults();
        }
      };
      xhr.open("GET", "deleteuser.php?user_id=" + userId + "&reason=" + encodeURIComponent(reason), true);
      xhr.send();
    });

    // Send message action
    document.getElementById('sendMessage').addEventListener('click', function () {
      const userId = this.getAttribute('data-user-id');
      const subject = document.getElementById('messageSubject').value;
      const content = document.getElementById('messageContent').value;

      if (!subject || !content) {
        alert('Please fill in both subject and message content.');
        return;
      }
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          // Remove the row from the table
          messageModal.hide();
        }
      };
      xhr.open("GET", "sentmessage.php?user_id=" + userId + "&subject=" + encodeURIComponent(subject) + "&content=" + encodeURIComponent(content), true);
      xhr.send();

      console.log(`Sending message to user ${userId}: Subject - ${subject}, Content - ${content}`);

      // Show success message
      alert(`Message sent to user ${userId}.`);

      // Close the modal
      const messageModal = bootstrap.Modal.getInstance(document.getElementById('messageModal'));

      // Reset form
      document.getElementById('messageSubject').value = '';
      document.getElementById('messageContent').value = '';
    });

    function searchUsers() {
      const input = document.getElementById('searchInput').value.toLowerCase();
      const table = document.getElementById('usersTableBody');
      const rows = table.getElementsByTagName('tr');
      let visibleCount = 0;

      for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let match = false;

        // Check if any cell in the row matches the search input
        for (let j = 0; j < cells.length - 1; j++) { // exclude last cell (Actions)
          if (cells[j].textContent.toLowerCase().includes(input)) {
            match = true;
            break;
          }
        }

        if (match) {
          rows[i].style.display = '';
          visibleCount++;
        } else {
          rows[i].style.display = 'none';
        }
      }

      // Show or hide the "No users match your search" message
      const noResultsMessage = document.getElementById('noResultsMessage');
      noResultsMessage.style.display = visibleCount === 0 ? 'block' : 'none';
    }

    function checkNoResults() {
      const visibleRows = document.querySelectorAll('#usersTableBody tr:not([style*="display: none" ])');
      document.getElementById('noResultsMessage').style.display = visibleRows.length === 0 ? 'block' : 'none';
    }
  </script>
</body>

</html>