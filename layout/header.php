<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Check if the user is logged in and has a valid role
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Include the database connection
include 'db.php';

$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Initialize variables
$profileImage = "uploads/default.png"; // Default image if no profile image is found
$fullName = "User";

// Depending on the role, fetch user details
if ($role == 1) {  // Admin
    $sql = "SELECT ui.fname, ui.lname, ui.profileimage 
            FROM userss u
            JOIN user_info ui ON u.id = ui.user_id
            WHERE u.id = $userId AND u.role = 1";  // Query for admin
} elseif ($role == 2) {  // Manager
    $sql = "SELECT ui.fname, ui.lname, ui.profileimage 
            FROM userss u
            JOIN user_info ui ON u.id = ui.user_id
            WHERE u.id = $userId AND u.role = 2";  // Query for manager
} elseif ($role == 3) {  // Staff
    $sql = "SELECT ui.fname, ui.lname, ui.profileimage 
            FROM userss u
            JOIN user_info ui ON u.id = ui.user_id
            WHERE u.id = $userId AND u.role = 3";  // Query for staff
} else {
    // If the role is invalid, redirect to login
    header('Location: login.php');
    exit();
}

// Execute the query
$result = $conn->query($sql);

// Check if the query was successful and data was found
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fullName = $row['fname'] . ' ' . $row['lname'];
    $profileImage = $row['profileimage'] ? $row['profileimage'] : "uploads/default.png"; // Fallback to default if no profile image
} else {
    // Fallback to default if no user data found
    $fullName = "User Not Found";
    $profileImage = "uploads/default.png";
}

$conn->close();
?>


<!doctype html>

<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Admin Dashboard</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Parkinsans:wght@300..800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto+Slab:wght@100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/vendor/fonts/remixicon/remixicon.css" />

    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="assets/vendor/libs/node-waves/node-waves.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>


    <script src="assets/js/config.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {

            font-family: "Roboto Slab", serif;
        }

        #password_feedback {
            margin-top: 10px;
            font-size: 14px;
        }

        .bg-menu-theme .menu-link,
        .bg-menu-theme .menu-horizontal-prev,
        .bg-menu-theme .menu-horizontal-next {
            color: #8c57ff;
        }

        /* nav {
            margin: 0;
            width: 100% !important;
        } */

        .bg-menu-theme {
            background-color: dark !important;
            color: white;
        }

        #layout-menu {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: 250px;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1000;
            background-color: var(--bs-secondary-bg);
        }

        .container-p-y {
            margin-left: calc(250px);
            padding: 20px;
            width: calc(100% - 250px);
            overflow-x: hidden;
        }

        #layout-navbar {
            margin-left: calc(250px);

            width: calc(100% - 250px);
            overflow-x: hidden;

        }

        .content-footer {
            margin-left: calc(250px);

            width: calc(100% - 250px);
            overflow-x: hidden;

        }

        .navbar-nav {
            margin-right: 20px;
        }
    </style>
</head>

<body>

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container ">



            <?php
            include 'layout/sidebar.php';
            ?>



            <div class="layout-page">


                <?php
                include 'layout/navbar.php';
                ?>



                <div class="content-wrapper">


                    <script>
                        $(document).ready(function () {

                            $('#logoutBtn').on('click', function (e) {
                                e.preventDefault();
                                $.ajax({
                                    url: 'logout.php',
                                    type: 'POST',
                                    dataType: 'json',
                                    success: function (response) {
                                        if (response.success) {
                                            // alert('Logout successful!');
                                            window.location.href = 'login.php';
                                        } else {
                                            alert('Logout failed: ' + response.message);
                                        }
                                    },
                                    error: function () {
                                        alert('An error occurred. Please try again.');
                                    }
                                });

                            });
                        });
                    </script>