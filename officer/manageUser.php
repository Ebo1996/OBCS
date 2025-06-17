<?php
include "../setup/dbconnection.php";
$sql = "SELECT id, CONCAT(first_name, ' ', last_name) AS full_name, email, phone, role FROM users LIMIT 10";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Document</title>

    <style>
        body {
            margin: auto;
            padding: auto;
        }

        .container {
            max-width: 1000px;

        }
    </style>
</head>

<body>
    <div class="container mt-5 d-flex flex-column justify-content-center align-items-center">
        <h2 class="mb-4 p-3 shadow-lg rounded bg-primary text-white text-center fw-bold">User account list</h2>
        <table class="table table-bordered shadow-lg table-striped table-hover table-sm text-center">
            <thead>
                <tr>
                    <th>user ID</th>
                    <th>Full Name</th>
                    <th>Date of Birth</th>
                    <th>email</th>
                    <th>phone</th>
                    <th>role</th>
                    <!-- <th>Actions</th> -->
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0) {
                    while ($user = $result->fetch_assoc()) { ?>
                        <tr id="row-<?= $user['id']; ?>">
                            <td><?= $user['id']; ?></td>
                            <td><?= $user['full_name']; ?></td>
                            <td><?= $user['email']; ?></td>
                            <td><?= $user['phone']; ?></td>
                            <td><?= $user['role']; ?></td>
                            <td>
                                <!-- Button to view documents (assumes you have a page to display docs) -->
                                <a href="userprofile.php?app_id=<?= $user['id'] ?>" id="viewdcoment" class="btn btn-info btn-sm">View Profile</a>
                                <!-- Approve button -->
                                <button class="btn btn-success btn-sm approveBtn" data-appid="<?= $user['id']; ?>">assing role</button>
                                <!-- Reject button -->
                                <button class="btn btn-danger btn-sm rejectBtn" onclick="deleteUser(<?= $user['id']; ?>)" data-appid="<?= $row['id']; ?>">Delete user</button>

                            </td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="7">No applications found.</td>
                    </tr>
                <?php } ?>


            </tbody>
        </table>
    </div>

    <script>
        function deleteUser(id){
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                    document.getElementById("row-"+ id).remove();
                }
            }
            xmlhttp.open("GET", "deleteuser.php?user_id=" +id,true);
            xmlhttp.send();
        }
    </script>

</body>

</html>