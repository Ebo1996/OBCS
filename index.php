<?php
include "setup/dbconnection.php";
$sql = "SELECT * FROM feedback ";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$sql = "SELECT * FROM announcements";
$stmt = $conn->prepare($sql);
$stmt->execute();
$resultannounce = $stmt->get_result();




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Official website of Ifaa Bulaa Kebele - Providing government services to our community with efficiency and transparency">
    <meta name="keywords" content="Kebele, Ethiopia, Oromia, government services, birth certificate, community">
    <meta name="author" content="Ifaa Bulaa Kebele Administration">
    <title>Ifaa Bulaa Kebele - Official Government Services Portal</title>
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #0d924f;
            --accent-color: #e74c3c;
            --light-bg: #f8f9fa;
            --dark-color: #212529;
            --text-color: #495057;
            --text-light: #6c757d;
            --white: #ffffff;
            --gray-light: #e9ecef;
            --gray-border: #dee2e6;
            --success-color: #28a745;
            --info-color: #17a2b8;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
            --header-height: 80px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-color);
            line-height: 1.7;
            background-color: var(--light-bg);
            overflow-x: hidden;
            padding-top: var(--header-height);
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            line-height: 1.3;
            color: var(--dark-color);
        }

        a {
            text-decoration: none;
            transition: var(--transition);
            color: var(--primary-color);
        }

        a:hover {
            color: var(--secondary-color);
        }

        .btn {
            padding: 12px 28px;
            font-weight: 600;
            letter-spacing: 0.5px;
            border-radius: 50px;
            transition: var(--transition);
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .btn-primary {
            background-color: #2968c6;
            border-color: #0d6efd;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #3fa2b6;
            border-color: #198754;
            color: #ffffff;
            transform: translateY(-4px);
            box-shadow: 0 5px 15px rgba(2, 99, 209, 0.45);
        }

        .btn-outline-primary {
            color: #0d6efd;
            border-color: #0d6efd;
            background-color: transparent;
            transition: all 0.3s ease;
        }

        .btn-outline-primary.reg {
            color: #ffffff;
            border-color: #0d6efd;
            background-color: transparent;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background-color: #0e56c3;
            border-color: #0d6efd;
            color: #ffffff;
            box-shadow: 0 6px 18px rgba(13, 110, 253, 0.35);
            transform: translateY(-2px);
        }

        .section {
            padding: 100px 0;
            position: relative;
        }

        .section-title {
            font-size: 2.5rem;
            margin-bottom: 30px;
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: -15px;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            border-radius: 2px;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: var(--text-light);
            max-width: 700px;
            margin: 0 auto 50px;
        }

        .navbar {
            padding: 15px 0;
            background-color: var(--white) !important;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            height: var(--header-height);
        }

        .navbar.scrolled {
            padding: 10px 0;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.15);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
            display: flex;
            align-items: center;
        }

        .navbar-brand img {
            height: 45px;
            width: 45px;
            border-radius: 50%;
            margin-right: 12px;
            object-fit: cover;
            border: 2px solid var(--primary-color);
            transition: var(--transition);
        }

        .navbar-brand:hover img {
            transform: rotate(15deg);
        }

        .nav-link {
            font-weight: 600;
            padding: 12px 18px !important;
            color: var(--dark-color) !important;
            position: relative;
            margin: 0 8px;
            font-size: 0.95rem;
            border-radius: 8px;
            transition: var(--transition);
        }

        .nav-link:hover {
            background-color: rgba(44, 62, 80, 0.05);
        }

        .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 18px;
            width: 0;
            height: 3px;
            background-color: var(--secondary-color);
            transition: var(--transition);
            border-radius: 3px;
        }

        .nav-link:hover::before,
        .nav-link.active::before {
            width: calc(100% - 36px);
        }

        .dropdown-menu {
            border: none;
            box-shadow: var(--shadow);
            border-radius: 12px;
            padding: 10px 0;
            margin-top: 10px;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .dropdown-item {
            padding: 10px 20px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background-color: rgba(13, 146, 79, 0.1);
            color: var(--secondary-color);
            transform: translateX(5px);
        }

        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.4)), url('assets/assetsindex1/jimmacitybackground.png') no-repeat center center/cover;
            color: white;
            height: calc(100vh - var(--header-height));
            min-height: 600px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            color: var(--white);
        }

        .hero-title {
            font-size: 4.5rem;
            font-weight: 900;
            margin-bottom: 32px;
            line-height: 1.1;
            background: linear-gradient(90deg, #ffffff 0%, #28ace4 50%, #00e0ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 4px 4px 20px rgba(0, 0, 0, 0.5);
            letter-spacing: -1px;
            animation: fadeInUp 1.2s ease-out both;
            text-align: center;
        }

        .hero-subtitle {
            color: rgb(255, 255, 255);
            font-size: 1.6rem;
            margin-bottom: 30px;
            opacity: 0.9;
            max-width: 700px;
            font-family: "Segoe UI", "Helvetica Neue", Helvetica, Arial, sans-serif;
            text-shadow: 1px 1px 2px rgba(148, 137, 137, 0.5);
        }

        .services {
            background-color: var(--white);
        }

        .service-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            background-color: var(--white);
            height: 100%;
            border: 1px solid rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            border-color: rgba(44, 62, 80, 0.2);
        }

        .service-img {
            height: 220px;
            overflow: hidden;
        }

        .service-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .service-card:hover .service-img img {
            transform: scale(1.05);
        }

        .service-content {
            padding: 25px;
        }

        .service-title {
            font-size: 1.4rem;
            margin-bottom: 15px;
            color: var(--primary-color);
        }

        .service-icon {
            font-size: 2.5rem;
            color: var(--accent-color);
            margin-bottom: 15px;
        }

        .service-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: var(--accent-color);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 1;
            animation: pulse 2s infinite;
        }

        .about {
            background-color: var(--gray-light);
            position: relative;
            overflow: hidden;
        }

        .about-img {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            border: 5px solid var(--white);
            position: relative;
        }

        .about-img::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 20px;
            right: -20px;
            bottom: -20px;
            border: 2px solid var(--accent-color);
            border-radius: 12px;
            z-index: -1;
        }

        .about-img img {
            width: 100%;
            height: auto;
            display: block;
        }

        .about-text h3 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: var(--primary-color);
        }

        .feature-list {
            list-style: none;
            padding-left: 0;
        }

        .feature-list li {
            margin-bottom: 15px;
            position: relative;
            padding-left: 35px;
        }

        .feature-list i {
            position: absolute;
            left: 0;
            top: 5px;
            color: var(--accent-color);
            font-size: 1.2rem;
        }

        .testimonials {
            background-color: var(--primary-color);
            color: var(--white);
            position: relative;
        }

        .testimonial-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            height: 100%;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: var(--transition);
        }

        .testimonial-card:hover {
            transform: translateY(-10px);
            background-color: rgba(255, 255, 255, 0.15);
        }

        .testimonial-text {
            font-style: italic;
            margin-bottom: 20px;
            position: relative;
        }

        .testimonial-text::before {
            content: '"';
            font-size: 4rem;
            position: absolute;
            top: -20px;
            left: -15px;
            opacity: 0.2;
            line-height: 1;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
        }

        .testimonial-author img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
            object-fit: cover;
            border: 2px solid var(--accent-color);
        }

        .author-info h5 {
            margin-bottom: 5px;
            color: var(--white);
        }

        .author-info p {
            margin-bottom: 0;
            opacity: 0.8;
            font-size: 0.9rem;
        }

        .announcements {
            background-color: var(--white);
        }

        .announcement-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            background-color: var(--white);
            height: 100%;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .announcement-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .announcement-date {
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            color: var(--white);
            padding: 12px 20px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.85rem;
        }

        .announcement-content {
            padding: 20px;
        }

        .announcement-title {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: var(--dark-color);
        }

        .contact {
            background-color: var(--gray-light);
        }

        .contact-info-box {
            background-color: var(--white);
            padding: 30px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            height: 100%;
            transition: var(--transition);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .contact-info-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .contact-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(44, 62, 80, 0.1);
            border-radius: 50%;
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-right: 20px;
            flex-shrink: 0;
        }

        .contact-form {
            background-color: var(--white);
            padding: 40px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .form-control {
            height: 50px;
            border: 1px solid var(--gray-border);
            border-radius: 8px;
            padding: 10px 20px;
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(44, 62, 80, 0.2);
        }

        textarea.form-control {
            height: auto;
            min-height: 150px;
        }

        footer {
            background: linear-gradient(135deg, var(--dark-color), #16213e);
            color: var(--white);
            padding: 80px 0 20px;
            position: relative;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        }

        .footer-col h3 {
            font-size: 1.3rem;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 15px;
            color: var(--white);
        }

        .footer-col h3::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 2px;
            background-color: var(--accent-color);
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            transition: var(--transition);
            display: inline-block;
        }

        .footer-links a:hover {
            color: var(--accent-color);
            transform: translateX(5px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            margin-top: 50px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.9rem;
            opacity: 0.7;
        }

        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background-color: var(--primary-color);
            color: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .back-to-top.active {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            background-color: var(--secondary-color);
            transform: translateY(-5px);
        }

        #servicesCarousel .carousel-inner {
            padding: 20px 0;
        }

        #servicesCarousel .carousel-item {
            transition: transform 0.6s ease-in-out;
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: 5%;
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 50%;
            height: 50px;
            width: 50px;
            top: 50%;
            transform: translateY(-50%);
            opacity: 1;
            transition: var(--transition);
        }

        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            background-color: var(--primary-color);
            transform: translateY(-50%) scale(1.1);
        }

        .carousel-indicators button {
            background-color: var(--primary-color);
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin: 0 5px;
            transition: var(--transition);
        }

        .carousel-indicators button.active {
            background-color: var(--accent-color);
            transform: scale(1.2);
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .bounce-in {
            animation: bounceIn 1s;
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0.1);
                opacity: 0;
            }

            60% {
                transform: scale(1.2);
                opacity: 1;
            }

            100% {
                transform: scale(1);
            }
        }

        .zoom-in {
            animation: zoomIn 1s;
        }

        @keyframes zoomIn {
            from {
                transform: scale(0);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .fade-in {
            animation: fadeIn 1.5s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .slide-in-left {
            animation: slideInLeft 1s;
        }

        @keyframes slideInLeft {
            from {
                transform: translateX(-100px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .text-gradient {
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        }

        .shadow-lg-primary {
            box-shadow: 0 10px 20px rgba(44, 62, 80, 0.2);
        }

        .dropdown-toggle::after {
            margin-left: 0.5em;
            vertical-align: 0.15em;
        }

        .login-dropdown .dropdown-menu {
            min-width: 220px;
        }

        @media (max-width: 1199.98px) {
            .hero-title {
                font-size: 3rem;
            }

            .navbar-nav .nav-item {
                margin: 2px 0;
            }

            .navbar-collapse {
                padding: 15px 0;
            }
        }

        @media (max-width: 991.98px) {
            .section {
                padding: 80px 0;
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .about-img {
                margin-bottom: 40px;
            }

            .navbar-brand img {
                height: 35px;
                width: 35px;
            }

            .navbar-brand span {
                font-size: 1.3rem;
            }

            .login-dropdown {
                margin: 10px 0;
            }
        }

        @media (max-width: 767.98px) {
            .section {
                padding: 60px 0;
            }

            .section-title {
                font-size: 2rem;
            }

            .hero {
                height: auto;
                padding: 120px 0 80px;
                min-height: auto;
                text-align: center;
            }

            .hero-title {
                font-size: 2.2rem;
            }

            .hero-buttons .btn {
                display: block;
                width: 100%;
                margin-bottom: 15px;
            }

            .hero-buttons .btn:last-child {
                margin-bottom: 0;
            }

            .carousel-control-prev,
            .carousel-control-next {
                height: 40px;
                width: 40px;
            }

            .navbar-brand img {
                height: 30px;
                width: 30px;
            }

            .navbar-brand span {
                font-size: 1.2rem;
            }

            #servicesCarousel .carousel-item {
                display: block;
            }
        }

        @media (max-width: 575.98px) {
            .section-title {
                font-size: 1.8rem;
            }

            .hero-title {
                font-size: 2rem;
            }

            .contact-form {
                padding: 30px 20px;
            }

            .navbar-brand span {
                display: inline;
            }

            .service-img {
                height: 180px;
            }

            .footer-col {
                margin-bottom: 30px;
            }
        }
    </style>
</head>

<body>
    <a href="#" class="back-to-top" aria-label="Back to top"><i class="bi bi-arrow-up"></i></a>

    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.html">
                <img src="assets/assetsindex1/ifabulalogo.jpg" alt="Ifaa Bulaa Kebele Logo" width="40" height="40" class="pulse">
                <span>Ifaa Bulaa Kebele</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#announcements">Announcements</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                </ul>

                <div class="dropdown ms-lg-3 login-dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="loginDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Login
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="loginDropdown">
                        <li><a class="dropdown-item" href="public/login_new/addminlogin.php"><i class="bi bi-person-fill-gear me-2"></i>Admin</a></li>
                        <li><a class="dropdown-item" href="public/login_new/hopsitalogin.php"><i class="bi bi-hospital me-2"></i>Hospital</a></li>
                        <li><a class="dropdown-item" href="public/login_new/userlogin.php"><i class="bi bi-person-fill me-2"></i>User</a></li>
                        <li><a class="dropdown-item" href="public/login_new/kebelelogin.php"><i class="bi bi-building me-2"></i>Kebele Staff</a></li>
                        <li><a class="dropdown-item" href="public/login_new/officerlogin.php"><i class="bi bi-person-badge me-2"></i>Officer</a></li>
                    </ul>
                </div>

                <a class="btn btn-primary ms-lg-3 mt-3 mt-lg-0" href="public/signUp.php"><i class="bi bi-person-plus me-1"></i> Register</a>
            </div>
        </div>
    </nav>

    <section class="hero" id="home">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h1 class="hero-title" data-aos="fade-down">Ifaa Bulaa Kebele Administration</h1>
                    <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="100">Providing efficient government services to our community with transparency and dedication</p>
                    <div class="hero-buttons mt-4" data-aos="fade-up" data-aos-delay="200">
                        <a href="#services" class="btn btn-primary me-3"><i class="bi bi-list-check me-1"></i> Our Services</a>
                        <a href="sign-up.php" class="btn btn-outline-primary reg"><i class="bi bi-person-plus me-1"></i> Register Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section services" id="services">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="section-title" data-aos="fade-up">Our Services</h2>
                    <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">We provide various government services to our community members with efficiency and care.</p>
                </div>
            </div>

            <div id="servicesCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#servicesCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#servicesCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row g-4">
                            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                                <div class="service-card">
                                    <div class="service-badge">Popular</div>
                                    <div class="service-img">
                                        <img src="assets/assetsindex1/birthregistration.png" alt="New Birth Registration" class="img-fluid" loading="lazy">
                                    </div>
                                    <div class="service-content">
                                        <div class="service-icon">
                                            <i class="bi bi-file-earmark-medical"></i>
                                        </div>
                                        <h3 class="service-title">Birth Registration</h3>
                                        <p>Officially register a newborn and obtain a legally recognized birth certificate through our streamlined process.</p>
                                        <a href="services.html" class="btn btn-sm btn-outline-primary mt-3">Learn More</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                                <div class="service-card">
                                    <div class="service-img">
                                        <img src="assets/assetsindex1/birthcertificatereprint.png" alt="Birth Certificate Reprint" class="img-fluid" loading="lazy">
                                    </div>
                                    <div class="service-content">
                                        <div class="service-icon">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </div>
                                        <h3 class="service-title">Birth Certificate Reprint</h3>
                                        <p>Request a certified replacement for lost or damaged birth certificates with our efficient reissue process.</p>
                                        <a href="services.html" class="btn btn-sm btn-outline-primary mt-3">Learn More</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                                <div class="service-card">
                                    <div class="service-badge">New</div>
                                    <div class="service-img">
                                        <img src="assets/assetsindex1/onlineservice.png" alt="Online Application Submission" class="img-fluid" loading="lazy">
                                    </div>
                                    <div class="service-content">
                                        <div class="service-icon">
                                            <i class="bi bi-laptop"></i>
                                        </div>
                                        <h3 class="service-title">Online Services</h3>
                                        <p>Submit birth registration applications and other requests online for faster processing and convenience.</p>
                                        <a href="services.html" class="btn btn-sm btn-outline-primary mt-3">Learn More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="row g-4">
                            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                                <div class="service-card">
                                    <div class="service-img">
                                        <img src="assets/assetsindex1/e_certificate.png" alt="E-Certificate" class="img-fluid" loading="lazy">
                                    </div>
                                    <div class="service-content">
                                        <div class="service-icon">
                                            <i class="bi bi-house"></i>
                                        </div>
                                        <h3 class="service-title">E-Certificate</h3>
                                        <p>Securely download and print official digital birth certificates.</p>
                                        <a href="services.html" class="btn btn-sm btn-outline-primary mt-3">Learn More</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                                <div class="service-card">
                                    <div class="service-badge">Popular</div>
                                    <div class="service-img">
                                        <img src="assets/assetsindex1/certificateverification.png" alt="Certificate Verification" class="img-fluid" loading="lazy">
                                    </div>
                                    <div class="service-content">
                                        <div class="service-icon">
                                            <i class="bi bi-briefcase"></i>
                                        </div>
                                        <h3 class="service-title">Certificate Verification</h3>
                                        <p>Verify the legitimacy of birth certificates through our online system.</p>
                                        <a href="services.html" class="btn btn-sm btn-outline-primary mt-3">Learn More</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                                <div class="service-card">
                                    <div class="service-img">
                                        <img src="assets/assetsindex1/communitysupport.png" alt="Community Support" class="img-fluid" loading="lazy">
                                    </div>
                                    <div class="service-content">
                                        <div class="service-icon">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <h3 class="service-title">Community Support</h3>
                                        <p>Access various social support programs and community development initiatives we offer.</p>
                                        <a href="services.html" class="btn btn-sm btn-outline-primary mt-3">Learn More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#servicesCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#servicesCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>

    <section class="section about" id="about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <h2 class="section-title">About Ifaa Bulaa Kebele</h2>
                    <div class="about-text">
                        <h3 class="text-gradient">Our Administrative Unit</h3>
                        <p>Ifaa Bulaa Kebele is a local government administrative unit serving the community with various governmental and social services. We are committed to providing efficient, transparent, and accessible services to all residents.</p>

                        <ul class="feature-list mt-4">
                            <li><i class="bi bi-check-circle-fill"></i> Transparent and accountable administration</li>
                            <li><i class="bi bi-check-circle-fill"></i> Efficient service delivery systems</li>
                            <li><i class="bi bi-check-circle-fill"></i> Community-focused development programs</li>
                            <li><i class="bi bi-check-circle-fill"></i> Digital transformation initiatives</li>
                            <li><i class="bi bi-check-circle-fill"></i> Accessible to all residents</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="about-img floating">
                        <img src="https://images.unsplash.com/photo-1526778548025-fa2f459cd5c1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1973&q=80" alt="Ifaa Bulaa Kebele Office" class="img-fluid" loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section testimonials bg-gradient-primary">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="section-title text-white" data-aos="fade-up">Community Feedback</h2>
                    <p class="section-subtitle text-white-50" data-aos="fade-up" data-aos-delay="100">Hear from our residents about their experiences with our services.</p>
                </div>
            </div>

            <div class="row g-4">
                <?php if ($result->num_rows > 0) {
                    while ($feedback = $result->fetch_assoc()) { ?>
                        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                            <div class="testimonial-card bounce-in">
                                <div class="testimonial-text">
                                    <?php echo $feedback["message"] ?>
                                </div>
                                <div class="testimonial-author">
                                    <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Amina Mohammed" loading="lazy">
                                    <div class="author-info">
                                        <h5><?php echo $feedback["name"] ?></h5>
                                        <p><?php echo $feedback["email"] ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                <?php }
                } ?>
            </div>
        </div>
    </section>

    <section class="section announcements">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="section-title " data-aos="fade-up">Latest Announcements</h2>
                    <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Stay updated with our latest news and important notices for the community.</p>
                </div>
            </div>

            <div class="row g-4">
                <?php if ($resultannounce->num_rows > 0) {
                    while ($announcement = $resultannounce->fetch_assoc()) { ?>
                        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                            <div class="announcement-card zoom-in">
                                <div class="announcement-date"><?php echo $announcement["posted_at"] ?></div>
                                <div class="announcement-content">
                                    <h3 class="announcement-title"><?php echo $announcement["title"] ?></h3>
                                    <p><?php echo $announcement["body"] ?></p>
                                    <a href="announcements/community-meeting-oct2023.html" class="btn btn-sm btn-outline-primary mt-2">Read Details</a>
                                </div>
                            </div>
                        </div>
                <?php }
                } ?>
            </div>

            <div class="text-center mt-5" data-aos="fade-up">
                <a href="announcements.html" class="btn btn-primary">View All Announcements</a>
            </div>
        </div>
    </section>

    <section class="section contact" id="contact">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="section-title" data-aos="fade-up">Contact Our Office</h2>
                    <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Reach out to us for any inquiries or assistance you may need regarding our services.</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-5" data-aos="fade-right">
                    <div class="contact-info-box fade-in shadow-lg-primary">
                        <div class="d-flex mb-4">
                            <div class="contact-icon">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <h3>Office Location</h3>
                                <p>Ifaa Bulaa Kebele, Jimma, Oromia, Ethiopia</p>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="contact-icon">
                                <i class="bi bi-telephone-fill"></i>
                            </div>
                            <div>
                                <h3>Contact Numbers</h3>
                                <p>Main Office: +251 953 835 589</p>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="contact-icon">
                                <i class="bi bi-envelope-fill"></i>
                            </div>
                            <div>
                                <h3>Email Addresses</h3>
                                <p>General Inquiries: ifaabulakebele@gmail.com</p>
                            </div>
                        </div>

                        <div class="d-flex">
                            <div class="contact-icon">
                                <i class="bi bi-clock-fill"></i>
                            </div>
                            <div>
                                <h3>Working Hours</h3>
                                <p>Monday - Friday: 8:30 AM - 5:30 PM</p>
                                <p>Saturday: 9:00 AM - 1:00 PM</p>
                                <p>Sunday: Closed</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7" data-aos="fade-left">
                    <div class="contact-form fade-in shadow-lg-primary">
                        <form id="contactForm" method="POST">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Full Name *</label>
                                        <input type="text" class="form-control" id="name" name="name" required
                                            pattern="[A-Za-z\s]+" title="Please enter only letters and spaces">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email Address *</label>
                                        <input type="email" class="form-control" id="email" name="email" required
                                            pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Please enter a valid email address">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="subject" class="form-label">Subject *</label>
                                        <input type="text" class="form-control" id="subject" name="subject" required
                                            pattern="[A-Za-z0-9\s\-.,]+" title="Please enter only letters, numbers, and basic punctuation">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="message" class="form-label">Your Message *</label>
                                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="consent" required>
                                        <label class="form-check-label" for="consent">
                                            I consent to the collection and processing of my personal data *
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up">
                    <div class="footer-col">
                        <div class="footer-brand mb-3">
                            <img src="assets/img/logo-white.png" alt="Ifaa Bulaa Logo" width="40" height="40" loading="lazy">
                            <span class="footer-brand-name">Ifaa Bulaa Kebele</span>
                        </div>
                        <p class="footer-about-text">
                            Committed to serving our community with transparency, efficiency, and dedication
                            through accessible government services and community development programs.
                        </p>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="footer-col">
                        <h3 class="footer-title">Quick Links</h3>
                        <ul class="footer-links">
                            <li class="footer-link-item">
                                <a href="index.html" class="footer-link">Home</a>
                            </li>
                            <li class="footer-link-item">
                                <a href="services.html" class="footer-link">Services</a>
                            </li>
                            <li class="footer-link-item">
                                <a href="about.html" class="footer-link">About Us</a>
                            </li>
                            <li class="footer-link-item">
                                <a href="announcements.html" class="footer-link">Announcements</a>
                            </li>
                            <li class="footer-link-item">
                                <a href="contact.html" class="footer-link">Contact</a>
                            </li>
                            <li class="footer-link-item">
                                <a href="faq.html" class="footer-link">FAQs</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="footer-col">
                        <h3 class="footer-title">Our Services</h3>
                        <ul class="footer-links">
                            <li class="footer-link-item">
                                <a href="services.html" class="footer-link">Birth Registration</a>
                            </li>
                            <li class="footer-link-item">
                                <a href="services.html" class="footer-link">Birth Certificate Reprint</a>
                            </li>
                            <li class="footer-link-item">
                                <a href="services.html" class="footer-link">E-Certificate</a>
                            </li>
                            <li class="footer-link-item">
                                <a href="services.html" class="footer-link">Certificate Verification</a>
                            </li>
                            <li class="footer-link-item">
                                <a href="services.html" class="footer-link">Community Support</a>
                            </li>
                            <li class="footer-link-item">
                                <a href="services.html" class="footer-link">Online Services</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="footer-col">
                        <h3 class="footer-title">Newsletter</h3>
                        <p class="footer-newsletter-text">
                            Subscribe to receive updates on our latest services, community announcements, and development projects.
                        </p>
                        <form class="footer-newsletter-form mt-3">
                            <div class="input-group mb-3">
                                <input
                                    type="email"
                                    class="form-control"
                                    placeholder="Your Email"
                                    aria-label="Email address"
                                    required
                                    pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                                <button class="btn btn-subscribe" type="submit" aria-label="Subscribe">
                                    <i class="bi bi-send-fill"></i>
                                </button>
                            </div>
                        </form>
                        <div class="footer-contact-info mt-4">
                            <div class="contact-item d-flex align-items-center mb-2">
                                <i class="bi bi-geo-alt-fill contact-icon"></i>
                                <span>Ifaa Bulaa, Jimma, Oromia, Ethiopia</span>
                            </div>
                            <div class="contact-item d-flex align-items-center mb-2">
                                <i class="bi bi-telephone-fill contact-icon"></i>
                                <span>+251 953 835 589</span>
                            </div>
                            <div class="contact-item d-flex align-items-center">
                                <i class="bi bi-envelope-fill contact-icon"></i>
                                <span>ifaabulakebele@gmail.com</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom" data-aos="fade-up">
                <div class="row">
                    <div class="col-md-6">
                        <p class="copyright-text">
                            &copy; 2025 Ifaa Bulaa Kebele Administration. All Rights Reserved.
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="footer-legal-links">
                            <a href="privacy-policy.html" class="legal-link">Privacy Policy</a>
                            <a href="terms-of-service.html" class="legal-link">Terms of Service</a>
                            <a href="accessibility.html" class="legal-link">Accessibility</a>
                            <a href="sitemap.html" class="legal-link">Sitemap</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });

        const backToTopButton = document.querySelector('.back-to-top');
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('active');
            } else {
                backToTopButton.classList.remove('active');
            }
        });

        backToTopButton.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        const navbar = document.querySelector('.navbar');
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const name = this.querySelector('#name').value.trim();
                const email = this.querySelector('#email').value.trim();
                const subject = this.querySelector('#subject').value.trim();
                const message = this.querySelector('#message').value.trim();
                const consent = this.querySelector('#consent').checked;

                if (!name || !email || !subject || !message || !consent) {
                    alert('Please fill in all required fields and provide consent.');
                    return;
                }

                const namePattern = /^[A-Za-z\s]+$/;
                if (!namePattern.test(name)) {
                    alert('Please enter a valid name (only letters and spaces allowed).');
                    return;
                }

                const emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i;
                if (!emailPattern.test(email)) {
                    alert('Please enter a valid email address.');
                    return;
                }

                this.reset();
                alert('Thank you for your message! We will get back to you soon.');
            });
        }

        const newsletterForm = document.querySelector('.footer-newsletter-form');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const emailInput = this.querySelector('input[type="email"]');

                if (emailInput.value.trim() === '') {
                    alert('Please enter your email address');
                    return;
                }

                const emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i;
                if (!emailPattern.test(emailInput.value.trim())) {
                    alert('Please enter a valid email address.');
                    return;
                }

                emailInput.value = '';
                alert('Thank you for subscribing to our newsletter!');
            });
        }
    </script>
</body>

</html>