<?php
session_start();
include '../setup/dbconnection.php';
if (isset($_GET["app_id"]) && !empty($_GET["app_id"])) {
    $app_id = $_GET["app_id"];

    $sql = "SELECT father_id, mother_id, applicant_id, birth_record FROM applications WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $app_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("application isnot found");
    } else {
        $doc = $result->fetch_assoc();
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .container {
            display: flex;
            justify-content: center;
            /* align-items: center; */
            gap: 1rem;
            width: 100vw;
            margin: auto;
            padding: auto;
            max-height: 90vh;

        }

        .minor_content {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        img {
            border-radius: 1rem;

        }

        .image-primary {
            max-width: 40%;
            height: 75%;
        }

        .image-secondary {
            max-width: 40%;
            height: 25%;
        }
    </style>
</head>

<body>
    <button>back to Dashboard</button>
    <div class="container">
        <img class="image-primary" src="<?= htmlspecialchars($doc["father_id"]) ?>" alt="father id not uploaded">
        <div class="minor_content">
            <figure class="mother_id">
                <img class="image-secondary" src="<?= htmlspecialchars($doc["mother_id"]) ?>" alt=" mother id not uploded">
            </figure>
            <figure class="image-secondary">
                <img class="image-secondary" src="<?= htmlspecialchars($doc["applicant_id"]) ?>" alt="applicant id not uploaded">

            </figure>
            <figure class="image-secondary">
                <img class="image-secondary" src="<?= htmlspecialchars($doc["birth_record"]) ?>" alt="birth record no uploaded">
            </figure>

        </div>
    </div>


</body>

</html>