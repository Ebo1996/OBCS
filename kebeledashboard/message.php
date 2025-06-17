<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Officer Communication System</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #3498db;
      --secondary-color: #2c3e50;
      --accent-color: #e74c3c;
      --light-color: #ecf0f1;
      --dark-color: #2c3e50;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f5f7fa;
      color: var(--dark-color);
    }

    .communication-container {
      max-width: 800px;
      margin: 30px auto;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      border-radius: 15px;
      overflow: hidden;
    }

    .header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 20px;
      text-align: center;
    }

    .toggle-buttons {
      display: flex;
      border-bottom: 1px solid #ddd;
    }

    .toggle-btn {
      flex: 1;
      padding: 15px;
      text-align: center;
      background-color: var(--light-color);
      border: none;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .toggle-btn.active {
      background-color: white;
      color: var(--primary-color);
      border-bottom: 3px solid var(--primary-color);
    }

    .toggle-btn:first-child {
      border-right: 1px solid #ddd;
    }

    .content-section {
      background-color: white;
      padding: 30px;
      display: none;
    }

    .content-section.active {
      display: block;
      animation: fadeIn 0.5s ease;
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

    .form-control,
    .form-select {
      border-radius: 8px;
      padding: 12px;
      border: 1px solid #ddd;
      margin-bottom: 15px;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
    }

    .btn-send {
      background-color: var(--primary-color);
      color: white;
      border: none;
      padding: 12px 25px;
      border-radius: 8px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-send:hover {
      background-color: #2980b9;
      transform: translateY(-2px);
    }

    .message-card {
      border-left: 4px solid var(--primary-color);
      margin-bottom: 20px;
      border-radius: 8px;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
      padding: 20px;
      transition: all 0.3s ease;
    }

    .message-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .message-header {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
      border-bottom: 1px solid #eee;
      padding-bottom: 10px;
    }

    .message-subject {
      font-weight: 600;
      color: var(--secondary-color);
    }

    .message-date {
      color: #7f8c8d;
      font-size: 0.9em;
    }

    .message-sender {
      color: var(--primary-color);
      font-weight: 500;
    }

    .no-messages {
      text-align: center;
      padding: 40px;
      color: #7f8c8d;
    }

    .no-messages i {
      font-size: 50px;
      margin-bottom: 20px;
      color: #bdc3c7;
    }
  </style>
</head>

<body>
  <div class="communication-container">
    <div class="header">
      <h2><i class="fas fa-comments me-2"></i> Officer Communication Portal</h2>
      <p class="mb-0">Communicate efficiently with your officers</p>
    </div>

    <div class="toggle-buttons">
      <button class="toggle-btn active" id="sendMessageBtn">
        <i class="fas fa-paper-plane me-2"></i>Message to Officer
      </button>
      <button class="toggle-btn" id="viewMessagesBtn">
        <i class="fas fa-inbox me-2"></i>Messages from Admin
      </button>
    </div>

    <!-- Send Message Form -->
    <div class="content-section active" id="sendMessageSection">
      <form id="messageForm">
        <div class="mb-3">
          <label for="officerName" class="form-label">Officer Name</label>
          <select class="form-select" id="officerName" required>
            <option value="" selected disabled>Select Officer</option>
            <option value="John Doe">ebisa berhanu (boss Officer)</option>
            <option value="Jane Smith">ababe cala (Detective)</option>
            <option value="Michael Brown">chaltu alemu (Sergeant)</option>
            <option value="Sarah Johnson">Sarah birhanu (Lieutenant)</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="officerEmail" class="form-label">Officer Email</label>
          <input type="email" class="form-control" id="officerEmail" required>
        </div>

        <div class="mb-3">
          <label for="subject" class="form-label">Subject</label>
          <input type="text" class="form-control" id="subject" required>
        </div>

        <div class="mb-3">
          <label for="message" class="form-label">Your Message</label>
          <textarea class="form-control" id="message" rows="5" required></textarea>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-send">
            <i class="fas fa-paper-plane me-2"></i>Send Message
          </button>
        </div>
      </form>
    </div>

    <!-- View Messages Section -->
    <div class="content-section" id="viewMessagesSection">
      <h4 class="mb-4"><i class="fas fa-envelope-open-text me-2"></i>Received Messages</h4>

      <div id="messagesList">
        <!-- Sample messages - in a real app these would come from a database -->
        <div class="message-card">
          <div class="message-header">
            <span class="message-subject">Update on Case #45721</span>
            <span class="message-date">May 10, 2023</span>
          </div>
          <div class="message-sender mb-2">From: Chief Administrator</div>
          <div class="message-content">
            Please review the attached documents regarding Case #45721. We need your input on the suspect identification
            process. The deadline for submission is May 15th.
          </div>
        </div>

        <div class="message-card">
          <div class="message-header">
            <span class="message-subject">Department Meeting Reminder</span>
            <span class="message-date">May 8, 2023</span>
          </div>
          <div class="message-sender mb-2">From: Admin Team</div>
          <div class="message-content">
            This is a reminder about the quarterly department meeting scheduled for May 12th at 10:00 AM in Conference
            Room B. Please bring your quarterly reports.
          </div>
        </div>

        <div class="message-card">
          <div class="message-header">
            <span class="message-subject">New Policy Implementation</span>
            <span class="message-date">May 5, 2023</span>
          </div>
          <div class="message-sender mb-2">From: Policy Department</div>
          <div class="message-content">
            The new evidence handling policy goes into effect next Monday. Please review the attached document and
            complete the mandatory training by Friday.
          </div>
        </div>
      </div>

      <!-- Empty state (hidden by default) -->
      <div class="no-messages d-none" id="noMessages">
        <i class="fas fa-envelope-open"></i>
        <h5>No Messages Yet</h5>
        <p>You don't have any messages from admin at this time.</p>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Toggle between sections
      const sendMessageBtn = document.getElementById('sendMessageBtn');
      const viewMessagesBtn = document.getElementById('viewMessagesBtn');
      const sendMessageSection = document.getElementById('sendMessageSection');
      const viewMessagesSection = document.getElementById('viewMessagesSection');

      sendMessageBtn.addEventListener('click', function () {
        this.classList.add('active');
        viewMessagesBtn.classList.remove('active');
        sendMessageSection.classList.add('active');
        viewMessagesSection.classList.remove('active');
      });

      viewMessagesBtn.addEventListener('click', function () {
        this.classList.add('active');
        sendMessageBtn.classList.remove('active');
        viewMessagesSection.classList.add('active');
        sendMessageSection.classList.remove('active');

        // In a real app, you would fetch messages from server here
        // For demo, we'll just show our sample messages
        document.getElementById('messagesList').classList.remove('d-none');
        document.getElementById('noMessages').classList.add('d-none');
      });

      // Form submission
      const messageForm = document.getElementById('messageForm');
      messageForm.addEventListener('submit', function (e) {
        e.preventDefault();

        // Get form values
        const officerName = document.getElementById('officerName').value;
        const officerEmail = document.getElementById('officerEmail').value;
        const subject = document.getElementById('subject').value;
        const message = document.getElementById('message').value;

        // In a real app, you would send this data to the server
        console.log('Message to be sent:', {
          officerName,
          officerEmail,
          subject,
          message
        });

        // Show success message
        alert(`Message to ${officerName} has been sent successfully!`);

        // Reset form
        messageForm.reset();
      });

      // Auto-fill email when officer is selected
      const officerNameSelect = document.getElementById('officerName');
      const officerEmailInput = document.getElementById('officerEmail');

      officerNameSelect.addEventListener('change', function () {
        const officer = this.value;
        let email = '';

        switch (officer) {
          case 'ebisa berhanu':
            email = 'debebeabebe@gmail.com';
            break;
          case 'ababecala@gmail.com':
            email = 'ababecala@gmail.com';
            break;
          case 'chaltu alemu':
            email = 'chaltualemu@gmail.com';
            break;
          case 'Sarah birhanu':
            email = 'Sarahbirhanu@gmail.com';
            break;
          default:
            email = '';
        }

        officerEmailInput.value = email;
      });
    });
  </script>
</body>

</html>