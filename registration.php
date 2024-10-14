<?php

$connection = mysqli_connect('localhost', 'root', '', 'userdb');

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $full_name = mysqli_real_escape_string($connection, $_POST['full_name']);
    $email_address = mysqli_real_escape_string($connection, $_POST['email_address']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    // Generate verification code
    $verification_code = sha1($email_address . time());
    $verification_URL = "http://localhost/email/verify.php?code=" . $verification_code;

    $query = "INSERT INTO user (full_name, email_address, password, verification_code, is_active) 
              VALUES ('{$full_name}', '{$email_address}', '{$password}', '{$verification_code}', false)";

    $result = mysqli_query($connection, $query);

    if ($result) {
        // Mail sending code
        $to = $email_address; 
        $sender = 'nimendyayashini2001@gmail.com'; 
        $mail_subject = 'Verify Email Address';
        $email_body = '<p>Dear ' . $full_name . '</p>';
        $email_body .= '<p>Thank you for signing up. There is one more step. Click the link below to verify your email address in order to activate your account.</p>';
        $email_body .= '<p><a href="' . $verification_URL . '">' . $verification_URL . '</a></p>';
        $email_body .= '<p>Thank You, <br>Yashini</p>';

        $header = "From: {$sender}\r\n";
        $header .= "Content-Type: text/html; charset=UTF-8";

        $send_mail_result = mail($to, $mail_subject, $email_body, $header);

        if ($send_mail_result) {
            // Mail sent successfully
            echo 'Please check your email for verification.';
        } else {
            // Mail could not be sent
            echo 'Error in sending email.';
        }
    } else {
        echo 'Error: ' . mysqli_error($connection);
    }
}

?>
