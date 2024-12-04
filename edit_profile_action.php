<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Error: User not logged in";
    exit;
}

$user_id = $_SESSION['user_id'];

// Get POST data
$new_fname = mysqli_real_escape_string($conn, $_POST['fname']);
$new_lname = mysqli_real_escape_string($conn, $_POST['lname']);
$new_email = mysqli_real_escape_string($conn, $_POST['email']);
$new_gender = mysqli_real_escape_string($conn, $_POST['gender']);
$new_contact = mysqli_real_escape_string($conn, $_POST['contact']);
$new_address = mysqli_real_escape_string($conn, $_POST['address']);

// Handle profile image upload
if ($_FILES['profileimage']['name']) {
    $image = $_FILES['profileimage'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image['name']);
    if (move_uploaded_file($image['tmp_name'], $target_file)) {
        $new_profileimage = $image['name'];
    } else {
        echo "Error uploading image.";
        exit;
    }
} else {
    // If no image is uploaded, keep the current one
    $new_profileimage = $_POST['current_profileimage'];
}

// Update query
$update_query = "
    UPDATE user_info SET 
        fname = '$new_fname',
        lname = '$new_lname',
        profileimage = '$new_profileimage',
        gender = '$new_gender',
        contact = '$new_contact',
        address = '$new_address'
    WHERE user_id = $user_id
";

if (mysqli_query($conn, $update_query)) {
    echo "Success"; // Return a success message
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
