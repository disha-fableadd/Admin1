<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Check if a new profile image is uploaded
    if (isset($_FILES['profileimage']) && $_FILES['profileimage']['error'] == UPLOAD_ERR_OK) {
        $profileimage = $_FILES['profileimage']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($profileimage);

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['profileimage']['tmp_name'], $target_file)) {
            // Update the profile image path in the database
        } else {
            echo "Failed to upload profile image.";
            exit;
        }
    } else {
        // If no new image is uploaded, retain the existing image
        $query = "SELECT profileimage FROM user_info WHERE user_id = $user_id";
        $result = mysqli_query($conn, $query);
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $profileimage = $row['profileimage'];
        } else {
            echo "Failed to fetch current profile image.";
            exit;
        }
    }

    // Update the user info in the database
    $update_query = "
        UPDATE user_info 
        SET fname = '$fname', lname = '$lname', gender = '$gender', contact = '$contact', address = '$address', profileimage = '$profileimage'
        WHERE user_id = $user_id
    ";

    if (mysqli_query($conn, $update_query)) {
        echo "Profile updated successfully.";
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
}
?>
