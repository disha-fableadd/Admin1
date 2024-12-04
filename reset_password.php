<?php
include 'db.php'; 

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get the token, user_id, password, and confirm password from the POST request
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $user_id = intval($_POST['user_id']);
    $password = $_POST['password'];
    $conPassword = $_POST['conPassword'];

    // Check if passwords match
    if ($password !== $conPassword) {
        $response['message'] = "Passwords do not match.";
        echo json_encode($response);
        exit;
    }

    // Validate password complexity
    // if (!preg_match('/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{6,}$/', $password)) {
    //     $response['message'] = "Password must be at least 6 characters long, with at least one uppercase letter and one number.";
    //     echo json_encode($response);
    //     exit;
    // }

    // Query to validate token and user_id from the password_reset table
    $sql = "SELECT * FROM password_reset WHERE token = '$token' AND user_id = $user_id AND created_at >= NOW() - INTERVAL 1 DAY";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Get email from user_info table using user_id
        $user_info_sql = "SELECT email FROM user_info WHERE user_id = $user_id";
        $user_info_result = mysqli_query($conn, $user_info_sql);

        if (mysqli_num_rows($user_info_result) > 0) {
            $user_info_row = mysqli_fetch_assoc($user_info_result);
            $email = $user_info_row['email'];

            // Hash the new password before updating
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // You should consider using a more secure hashing method like password_hash()

            // Update password in the userss table (use correct table)
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
            $response['message'] = "User information not found.";
        }
    } else {
        $response['message'] = "Invalid or expired token.";
    }

    // Return the response as JSON
    echo json_encode($response);
}
?>
