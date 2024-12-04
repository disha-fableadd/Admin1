<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}


require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $userId = (int) $_POST['uid']; // User ID to be updated
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $role = (int) $_POST['type'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);


    $updateUserInfoQuery = "
        UPDATE user_info 
        SET fname = '$fname', lname = '$lname', gender = '$gender', address = '$address'
        WHERE uid = $userId
    ";


    $updateUsersQuery = "
        UPDATE userss 
        SET email = '$email', contact = '$contact', role = $role
        WHERE id = $userId
    ";


    if (mysqli_query($conn, $updateUserInfoQuery) && mysqli_query($conn, $updateUsersQuery)) {

        echo json_encode(['success' => true, 'message' => 'User updated successfully!']);
    } else {

        echo json_encode(['success' => false, 'message' => 'Error updating user: ' . mysqli_error($conn)]);
    }
}
?>
