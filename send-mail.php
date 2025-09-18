<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Common fields
    $name    = htmlspecialchars($_POST['name'] ?? '');
    $email   = htmlspecialchars($_POST['email'] ?? '');
    $mobile  = htmlspecialchars($_POST['mobile'] ?? '');

    // Form-specific fields
    $service = htmlspecialchars($_POST['service'] ?? '');        // from project form
    $course  = htmlspecialchars($_POST['courseName'] ?? '');     // from consultation form
    $subject = htmlspecialchars($_POST['subject'] ?? '');        // from contact form
    $message = htmlspecialchars($_POST['message'] ?? '');

    // Decide final label for "Service / Subject"
    if (!empty($course)) {
        $service = "Course Inquiry - $course";
    } elseif (!empty($subject)) {
        // Map subject dropdown to readable text
        $subjectMap = [
            "general"     => "General Inquiry",
            "support"     => "Technical Support",
            "quote"       => "Request a Quote",
            "partnership" => "Partnership",
            "other"       => "Other"
        ];
        $service = $subjectMap[$subject] ?? ucfirst($subject);
    } elseif (empty($service)) {
        $service = "General Inquiry";
    }

    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'noreply@onenetworktech.com';
        $mail->Password   = 'Noreply@2025'; 
        $mail->SMTPSecure = 'ssl'; 
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom('noreply@onenetworktech.com', 'Website Contact Form');
        $mail->addAddress('noreply@onenetworktech.com');
        if (!empty($email)) {
            $mail->addReplyTo($email, $name);
        }

        // Build HTML email body
        $body = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; color: #333; line-height: 1.5; }
                .container { width: 100%; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background: #f9f9f9; }
                h2 { color: #0056b3; }
                table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                td { padding: 8px; border-bottom: 1px solid #ddd; vertical-align: top; }
                strong { color: #000; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>New Website Submission</h2>
                <table>
                    <tr>
                        <td><strong>Subject / Service:</strong></td>
                        <td>$service</td>
                    </tr>
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td>$name</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>$email</td>
                    </tr>";

        if (!empty($mobile)) {
            $body .= "
                    <tr>
                        <td><strong>Mobile:</strong></td>
                        <td>$mobile</td>
                    </tr>";
        }

        if (!empty($message)) {
            $body .= "
                    <tr>
                        <td><strong>Message:</strong></td>
                        <td>$message</td>
                    </tr>";
        }

        $body .= "
                </table>
            </div>
        </body>
        </html>
        ";

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = "[$service] New Submission from $name";
        $mail->Body    = $body;

        $mail->send();
        echo "success";
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
