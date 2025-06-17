<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* Main Layout Styles - Scoped to avoid conflicts */
        .main-layout {
            position: relative;
            min-height: 100vh;
            background-color: #f5f7fa;
        }

        /* Modern Sidebar Design */
        .main-sidebar {
            width: 280px;
            background: linear-gradient(135deg, #2c3e50 0%, #4a6491 100%);
            color: white;
            padding: 25px 15px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 100;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .main-sidebar.hidden {
            transform: translateX(-100%);
            box-shadow: none;
        }

        .main-sidebar .sidebar-btn {
            width: 100%;
            margin-bottom: 12px;
            text-align: left;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            padding: 12px 15px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border: none;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .main-sidebar .sidebar-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(5px);
        }

        .main-sidebar .sidebar-btn.active {
            background: #3498db;
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
        }

        .main-sidebar .sidebar-btn img {
            width: 20px;
            height: 20px;
            margin-right: 12px;
            filter: brightness(0) invert(1);
        }

        .main-content {
            margin-left: 280px;
            transition: margin-left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 25px;
            background-color: #f5f7fa;
        }

        .main-sidebar.hidden~.main-content {
            margin-left: 0;
        }

        .main-logo {
            width: 120px;
            display: block;
            margin: 0 auto 30px;
            transition: all 0.4s ease;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
        }

        /* Header Styles */
        .main-header {
            position: fixed;
            top: 0;
            right: 0;
            left: 280px;
            height: 70px;
            background: #fff;
            z-index: 99;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
        }

        .main-sidebar.hidden~.main-header {
            left: 0;
        }

        .main-header h2 {
            color: #2c3e50;
            font-weight: 600;
            font-size: 1.5rem;
            margin: 0;
        }

        /* Logout Button */
        .main-logout-btn {
            color: #e74c3c;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 8px 15px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
            background: rgba(231, 76, 60, 0.1);
        }

        .main-logout-btn:hover {
            background: rgba(231, 76, 60, 0.2);
            color: #c0392b;
        }

        .main-logout-btn img {
            width: 16px;
            height: 16px;
            margin-right: 8px;
        }

        /* Toggle Button */
        .main-toggle-btn {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1000;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #3498db;
            color: white;
            border: none;
            box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3);
            transition: all 0.3s ease;
        }

        .main-toggle-btn:hover {
            background: #2980b9;
            transform: scale(1.05);
        }

        /* Dynamic Content Container */
        .dynamic-content-container {
            margin-top: 15px;
            /* background: white; */
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            min-height: calc(100vh - 110px);
        }

        /* Sidebar Footer */
        .sidebar-footer {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
            font-size: 12px;
            padding: 0 20px;
        }
    </style>
</head>

<body class="main-layout">
    <button class="main-toggle-btn" onclick="toggleMainSidebar()">☰</button>

    <header class="main-header">
        <h2>Welcome to Efa Bula Kebele</h2>
        <a class="main-logout-btn" href="../public/logOut.php">
            <img src="../image/logout.png" alt="Logout"> Logout
        </a>
    </header>

    <aside class="main-sidebar">
        <img src="../image/birth.png" alt="Logo" class="main-logo">
        <button class="sidebar-btn" onclick="location.href='sidebar1.php'">
            <img src="../image/home.png" alt="Home"> HOME
        </button>
        <button class="sidebar-btn" onclick="location.href='sidebar1.php?page=application'">
            <img src="../image/apply.png" alt="Apply"> Applications
        </button>
        <button class="sidebar-btn" onclick="location.href='sidebar1.php?page=announcement'">
            <img src="../image/announcement.png" alt="Announcement"> ANNOUNCEMENT
        </button>
        <button class="sidebar-btn" onclick="location.href='sidebar1.php?page=payments'">
            <img src="../image/payment-method.png" alt="Payments"> PAYMENTS
        </button>
        <button class="sidebar-btn" onclick="location.href='sidebar1.php?page=usersupport'">
            <img src="../image/business-team.png" alt="Support"> USER SUPPORT
        </button>
        <button class="sidebar-btn" onclick="location.href='sidebar1.php?page=editprofile'">
            <img src="../image/account-settings.png" alt="Profile"> MY PROFILE
        </button>

        <div class="sidebar-footer">
            © 2023 Efa Bula Kebele<br>
            v1.0.0
        </div>
    </aside>

    <main class="main-content">
        <div class="dynamic-content-container">
            <?php
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
                if ($page == "application") {
                    include 'applications.php';
                } elseif ($page == "editprofile") {
                    include 'editprofile.php';
                } elseif ($page == "announcement") {
                    include 'announcement.php';
                }  else if ($page == "payments") {
                    include "payments.php";
                } else if ($page == "usersupport") {
                    include "usersupport.php";
                } else {
                    echo "<h3>Page Not Found</h3>";
                }
            } else {
                include 'home.php';
            }
            ?>
        </div>
    </main>

    <script>
        function toggleMainSidebar() {
            document.querySelector('.main-sidebar').classList.toggle('hidden');
        }

        // Highlight active button based on current page
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = new URLSearchParams(window.location.search).get('page') || 'home';
            const buttons = document.querySelectorAll('.sidebar-btn');

            buttons.forEach(button => {
                const page = button.getAttribute('onclick').match(/page=([^'"]+)/);
                if (page && page[1] === currentPage) {
                    button.classList.add('active');
                } else if (!page && currentPage === 'home') {
                    button.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>