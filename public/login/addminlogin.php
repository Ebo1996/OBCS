 <?php
    session_start(); // Start session for user login


    include '../../setup/dbconnection.php'; // Include database connection


    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {

        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);

        // Prepare the SQL statement to prevent SQL Injection
        // if($role == 'user' || $role =='addmin'){
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            if (password_verify($password, $user["password"])) {
                // Store user session
                $_SESSION["id"] = $user["id"];
                $_SESSION["role"] = $user["role"]; // Store role for admin/user handling
                $_SESSION["email"] = $user["email"];

                // Redirect based on user role
                if ($user["role"] === "admin") {
                    header("Location: ../../ADMIN1/dashboard.php");
                } else {
                    header("location: ../../userdashboard/user.php");
                }
                exit;
            } else {
                echo "<script>alert('Incorrect password!');</script>";
            }
        } else {
            echo "<script>alert('User not found! Please sign up.');</script>";
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
        // }elseif($role == 'hospital'){
        //     $sql = "SELECT * FROM hospitals WHERE email = ?";
        //     $stmt = $conn->prepare($sql);
        //     $stmt->bind_param("s", $email);
        //     $stmt->execute();
        //     $result = $stmt->get_result();
        //     if($result->num_rows > 0){
        //             $hospital = $result->fetch_assoc();
        //             $_SESSION["id"]= $hospital["hospital_id"];
        //             $_SESSION["email"] = $hospital["email"];
        //             include "../hospitaldashboard/hospitalDashboard.php";

    }






    ?>


 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Document</title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <style>
         body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
        }

         .login-container {
             max-width: 400px;
             margin: 0 auto;
             background: #fff;
             padding: 30px;
             border-radius: 10px;
             box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
         }

         .login-header {
             text-align: center;
             margin-bottom: 30px;
         }

         .login-header i {
             font-size: 50px;
             color: #0d6efd;
             margin-bottom: 15px;
         }

         .form-control {
             padding: 12px;
         }

         .input-group-text {
             cursor: pointer;
         }

         .btn-login {
             width: 100%;
             padding: 12px;
             font-weight: 600;
         }

         .invalid-feedback {
             display: none;
         }

         .is-invalid~.invalid-feedback {
             display: block;
         }
     </style>
 </head>

 <body>
     <div class="container">
         <div class="login-container">
             <div class="login-header">
                 <i class="fas fa-user-shield"></i>
                 <h2>Admin Login</h2>
                 <p class="text-muted">Please enter your credentials</p>
             </div>

             <form id="loginForm" action="" method="POST">
                 <input type="hidden" name="role" value="admin">
                 <div class="mb-3">
                     <label for="email" class="form-label">email</label>
                     <div class="input-group">
                         <span class="input-group-text"><i class="fas fa-user"></i></span>
                         <input type="email" name="email" class="form-control" id="username" placeholder="Enter email" required>
                     </div>
                     <div class="invalid-feedback">
                         Please enter a valid email.
                     </div>
                 </div>

                 <div class="mb-3">
                     <label for="password" class="form-label">Password</label>
                     <div class="input-group">
                         <span class="input-group-text"><i class="fas fa-lock"></i></span>
                         <input type="password" name="password" class="form-control" id="password" placeholder="Enter password" required>
                         <span class="input-group-text toggle-password" id="togglePassword">
                             <i class="fas fa-eye"></i>
                         </span>
                     </div>
                     <div class="invalid-feedback">
                         Please enter your password.
                     </div>
                 </div>

                 <div class="d-grid gap-2 mb-3">
                     <button type="submit" name="submit" class="btn btn-primary btn-login">
                         <i class="fas fa-sign-in-alt"></i> Login
                     </button>
                 </div>


             </form>
         </div>
     </div>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

     <script>
        
     </script>

 </body>

 </html>



 <!-- Bootstrap JS Bundle with Popper -->