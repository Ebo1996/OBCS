<?php
require 'init.php';

                                                                                        include "../setup/dbconnection.php";
$user_id = $_SESSION["id"];
$sql = "SELECT certificate_id, CONCAT(first_name, ' ', middle_name,' ', last_name) AS full_name, dob,gender,place_of_birth,father_name,mother_name, issued_at  FROM certificates WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$certificate = $result->fetch_assoc();




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Birth Certificate | Ifaa Bulaa Kebele</title>
    <style>
        :root {
            --primary: #5F9EA0;
            --primary-dark: #4a7d7f;
            --accent: #ADD8E6;
            --light: #F0F8FF;
            --dark: #333;
            --white: #fff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, sans-serif;
        }

        body {
            background: white;
            color: var(--dark);
            line-height: 1.6;
            padding: 0;
        }

        .certificate {
            background: var(--white);
            border: 15px solid var(--primary);
            padding: 2rem;
            position: relative;
        }

        .certificate::before {
            content: "OFFICIAL BIRTH CERTIFICATE";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 4rem;
            color: rgba(95, 158, 160, 0.1);
            font-weight: bold;
            z-index: 0;
            pointer-events: none;
        }

        .certificate-header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
        }

        .certificate-title {
            color: var(--primary);
            font-size: 2rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
        }

        .certificate-seal {
            position: absolute;
            right: 2rem;
            top: 2rem;
            width: 80px;
            opacity: 0.8;
        }

        .certificate-body {
            margin: 2rem 0;
            position: relative;
            z-index: 1;
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
        }

        .certificate-info {
            flex: 2;
            min-width: 300px;
        }

        .certificate-photo {
            flex: 1;
            min-width: 200px;
            text-align: center;
        }

        .photo-preview {
            width: 150px;
            height: 150px;
            border: 1px solid #ddd;
            margin: 0 auto;
            background-size: cover;
            background-position: center;
            border-radius: 50%;
            object-fit: cover;
        }

        .certificate-row {
            display: flex;
            margin-bottom: 1rem;
            align-items: flex-end;
        }

        .certificate-label {
            min-width: 200px;
            font-weight: 600;
            margin-right: 1rem;
        }

        .certificate-value {
            border-bottom: 1px solid var(--dark);
            flex-grow: 1;
            padding-bottom: 0.25rem;
            min-height: 1.5rem;
        }

        .certificate-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 3rem;
        }

        .signature-block {
            text-align: center;
            width: 200px;
        }

        .signature-line {
            border-top: 1px solid var(--dark);
            margin: 1rem 0 0.5rem;
            height: 30px;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 30"><path d="M10 15 Q40 5 70 15 T130 15 T190 15" stroke="black" fill="none" stroke-width="1.5"/></svg>');
            background-repeat: no-repeat;
            background-position: center;
        }

        @media (max-width: 768px) {
            .certificate-body {
                flex-direction: column;
            }

            .certificate-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .certificate-label {
                margin-bottom: 0.5rem;
                min-width: auto;
            }

            .certificate-footer {
                flex-direction: column;
                gap: 2rem;
            }

            .signature-block {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <!-- Certificate Output -->
    <section id="certificate" class="certificate">
        <img src="https://upload.wikimedia.org/wikipedia/commons/7/71/Emblem_of_Ethiopia.svg" alt="Official Seal" class="certificate-seal">

        <div class="certificate-header">
            <h1 class="certificate-title">Birth Certificate</h1>
            <p>Federal Democratic Republic of Ethiopia</p>
            <p>Oromia Region • Ifaa Bulaa Kebele</p>
        </div>

        <div class="certificate-body">
            <div class="certificate-info">
                <div class="certificate-row">
                    <div class="certificate-label">Full Name: </div>
                    <div class="certificate-value" id="certFullName">:<?php echo $certificate["full_name"] ?></div>
                </div>

                <div class="certificate-row">
                    <div class="certificate-label">Gender:</div>
                    <div class="certificate-value" id="certGender">:<?php echo $certificate["gender"] ?></div>
                </div>

                <div class="certificate-row">
                    <div class="certificate-label">Date of Birth:</div>
                    <div class="certificate-value" id="certDob">:<?php echo $certificate["dob"] ?></div>
                </div>

                <div class="certificate-row">
                    <div class="certificate-label">Place of Birth:</div>
                    <div class="certificate-value" id="certPlaceOfBirth">:<?php echo $certificate["place_of_birth"] ?></div>
                </div>

                <div class="certificate-row">
                    <div class="certificate-label">Father's Name:</div>
                    <div class="certificate-value" id="certFatherName"><?php echo  $certificate["father_name"] ?></div>
                </div>

                <div class="certificate-row">
                    <div class="certificate-label">Mother's Name:</div>
                    <div class="certificate-value" id="certMotherName"><?php echo $certificate["mother_name"] ?></div>
                </div>

                <div class="certificate-row">
                    <div class="certificate-label">certificate ID :</div>
                    <div class="certificate-value" id="certPersonId"><?php echo $certificate["certificate_id"] ?></div>
                </div>
            </div>

            <div class="certificate-photo">
                <div class="photo-preview" id="photoPreview"></div>
                <p>Identification Photo</p>
            </div>
            <div class="certificate-photo">
                <!-- <img src="generate_qr.php?data=" alt="QR Code" style="width:150px;"> -->
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo urlencode('Certificate ID: ' . $certificate['certificate_id'] . ', Name: ' . $certificate['full_name']); ?>"
                    alt="QR Code">
                <p>Scan to verify</p>
            </div>

        </div>

        <div class="certificate-footer">
            <div class="signature-block">
                <div class="signature-line"></div>
                <p>Authorized Signatory</p>
            </div>
            <div class="signature-block">
                <div class="signature-line"></div>
                <p>Official Stamp</p>
                <p>Ifaa Bulaa Kebele</p>
            </div>
        </div>
    </section>
</body>

</html>