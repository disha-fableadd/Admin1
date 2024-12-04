<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    include 'db.php';

    require 'lib/Exception.php';
    require 'lib/PHPMailer.php';
    require 'lib/SMTP.php';

    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Modified SQL query to check for the email in the 'userss' table
    $sql = "SELECT id FROM userss WHERE email = '$email'"; 
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['id'];
        
        // Generate a unique token for password reset
        $token = bin2hex(random_bytes(50));

        // Delete any old tokens for the user
        $deleteOldTokens = "DELETE FROM password_reset WHERE email = '$email'"; 
        mysqli_query($conn, $deleteOldTokens);

        // Insert the new token for password reset
        $insertToken = "INSERT INTO password_reset (email, token, user_id) VALUES ('$email', '$token', $user_id)";
        mysqli_query($conn, $insertToken);

        // Generate the password reset link with user_id and token
        $resetLink = "http://localhost/disha/Admin1/reset_pass.php?user_id=$user_id&token=$token";

        // PHPMailer setup
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'mail.fableadtechnolabs.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'smtp@fableadtechnolabs.com';
        $mail->Password = '#w8(_4@wdc0M';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Set the sender's email and recipient's email
        $mail->setFrom('smtp@fableadtechnolabs.com', 'My Website');
        $mail->addAddress($email);

        // Set email content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body = "Click the link below to reset your password:<br><a href='$resetLink'>$resetLink</a>";

        // Send the email and show success or error message
        if ($mail->send()) {
            echo "Password reset link has been sent to your email.";
        } else {
            echo "Failed to send email. Please try again. Error: " . $mail->ErrorInfo;
        }
    } else {
        echo "No account found with that email address.";
    }
}
?>
