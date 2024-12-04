<?php
include 'db.php'; 

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $user_id = intval($_POST['user_id']);
    $password = $_POST['password'];
    $conPassword = $_POST['conPassword'];

    // Validate passwords match
    if ($password !== $conPassword) {
        $response['message'] = "Passwords do not match.";
        echo json_encode($response);
        exit;
    }

    // Uncomment to enforce strong password rules
    // if (!preg_match('/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
    //     $response['message'] = "Password must be at least 8 characters long, with at least one uppercase letter and one number.";
    //     echo json_encode($response);
    //     exit;
    // }

    // Verify token validity
    $sql = "SELECT * FROM password_reset WHERE token = '$token' AND user_id = $user_id AND created_at >= NOW() - INTERVAL 1 DAY";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $email = $row['email'];

        // Hash the new password securely
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Update the user's password in the database
        $updatePasswordSQL = "UPDATE userss SET password = '$hashedPassword' WHERE email = '$email'";
        if (mysqli_query($conn, $updatePasswordSQL)) {
            // Invalidate the token after successful password reset
            $updateTokenSQL = "UPDATE password_reset SET token = NULL WHERE token = '$token' AND user_id = $user_id";
            mysqli_query($conn, $updateTokenSQL);

            $response['success'] = true;
            $response['message'] = "Password has been reset successfully.";
        } else {
            $response['message'] = "Failed to reset password. Please try again.";
        }
    } else {
        $response['message'] = "Invalid or expired token.";
    }

    echo json_encode($response);
}
?>
