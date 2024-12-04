<?php
include 'db.php'; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect data from the POST request
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $gender = $_POST['gender'];
    $role = $_POST['type'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password hashing

    // Image handling
    $profileimage = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '';  // Folder to store uploaded images
        $profileimage = $uploadDir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $profileimage);  // Move the file to the destination
    }

    // Start database transaction
    mysqli_begin_transaction($conn);

    try {
        // Insert user data into the userss table
        $sql = "INSERT INTO userss (email, password, role) VALUES ('$email', '$password', $role)";
        if (mysqli_query($conn, $sql)) {
            $user_id = mysqli_insert_id($conn); // Get the inserted user ID

            // Insert user info into the user_info table
            $sql_user_info = "INSERT INTO user_info (user_id, fname, lname, profileimage, gender, contact, email, address) 
                              VALUES ($user_id, '$fname', '$lname', '$profileimage', '$gender', '$contact', '$email', '$address')";
            if (mysqli_query($conn, $sql_user_info)) {
                // Commit the transaction
                mysqli_commit($conn);
                echo 'success';
            } else {
                // Rollback the transaction if user_info insert fails
                mysqli_rollback($conn);
                echo 'Error inserting user info: ' . mysqli_error($conn);
            }
        } else {
            // Rollback the transaction if userss insert fails
            mysqli_rollback($conn);
            echo 'Error inserting user: ' . mysqli_error($conn);
        }
    } catch (Exception $e) {
        // Rollback the transaction on error
        mysqli_rollback($conn);
        echo 'Error: ' . $e->getMessage();
    }
}
?>
