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

        $deleteOldTokens = "DELETE FROM password_reset WHERE email = '$email'"; 
        mysqli_query($conn, $deleteOldTokens);
        // Update the token in the userss table
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
            $mail->setFrom('smtp@fableadtechnolabs.com', 'MATERIO');
            $mail->addAddress($email);

            // Set email content with a button-like link
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "
                <html>
                <body>
                    <p>Hi,</p>
                    <p>We received a request to reset your password. Click the button below to reset your password:</p>
                    <a href='$resetLink' style='background-color: #4CAF50; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; border-radius: 5px;'>Reset Password</a>
                    <p>If you didn't request a password reset, you can ignore this email.</p>
                    <p>Best regards,<br>Your Website Team</p>
                </body>
                </html>
            ";

            // Send the email and show success or error message
            if ($mail->send()) {
                echo "Password reset link has been sent to your email.";
            } else {
                echo "Failed to send email. Please try again. Error: " . $mail->ErrorInfo;
            }
        } else {
            echo "Failed to update the token. Please try again.";
        }
    } else {
        echo "No account found with that email address.";
    }

?>
