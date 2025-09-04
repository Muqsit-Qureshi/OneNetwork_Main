<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // If using Composer
// OR if downloaded manually:
// require 'PHPMailer/src/Exception.php';
// require 'PHPMailer/src/PHPMailer.php';
// require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = htmlspecialchars($_POST['name']);
    $email   = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'info@onenetworktech.com'; // Your email
        $mail->Password   = 'Ashflame@123';     // Your email password
        $mail->SMTPSecure = 'ssl'; // 'ssl' for port 465, 'tls' for port 587
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom('info@onenetworktech.com', 'One Network Tech');
        $mail->addAddress('info@onenetworktech.com', 'Admin'); // Send to yourself

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'New Consultation Request';
        $mail->Body    = "
            <h3>New message from your website:</h3>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Message:</strong><br>{$message}</p>
        ";

        if ($mail->send()) {
            echo "Message has been sent. We'll get back to you soon.";
        } else {
            echo "Message could not be sent.";
        }
    } catch (Exception $e) {
        echo "Mail Error: {$mail->ErrorInfo}";
    }
}
?>
