<?php
session_start();
include "db.php";

if (isset($_SESSION['user_id'])) {
    // Redirect if user is already logged in
    header('Location: dashboard.php');
    exit();
}

if (
    $_SERVER["REQUEST_METHOD"] == "POST" && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
) {

    $response = [
        'success' => false,
        'message' => 'Invalid request.'
    ];

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {

        $email = mysqli_real_escape_string($conn, $email);


        $query = "SELECT id, email, role, password FROM userss WHERE email = '$email'";
        $result = mysqli_query($conn, $query);  

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);


            if (password_verify($password, $user['password'])) {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];


                if ($user['role'] == 1) { // Admin
                    $response['redirect'] = 'dashboard.php';
                    $response['success'] = true;
                    $response['message'] = 'Admin login successful!';
                } elseif ($user['role'] == 2) { // Manager
                    $response['redirect'] = 'dashboard.php';
                    $response['success'] = true;
                    $response['message'] = 'Manager login successful!';
                } elseif ($user['role'] == 3) { // Staff
                    $response['redirect'] = 'dashboard.php';
                    $response['success'] = true;
                    $response['message'] = 'Staff login successful!';
                } else {
                    $response['message'] = 'Invalid role.';
                }
            } else {
                $response['message'] = 'Invalid email or password.';
            }
        } else {
            $response['message'] = 'Invalid email or password.';
        }

        mysqli_free_result($result);
    } else {
        $response['message'] = 'Please fill out all fields.';
    }

    mysqli_close($conn);

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>
