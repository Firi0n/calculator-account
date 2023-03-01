<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require __DIR__."/IMail.php";
    class Mailer implements IMail
    {
        // Send email
        public function send($contact, $header, $message) : bool
        {
            // Import PHPMailer
            require __DIR__."/credentials.php";
            require_once __DIR__.'../PHPMailer/src/Exception.php';
            require_once __DIR__.'../PHPMailer/src/PHPMailer.php';
            require_once __DIR__.'../PHPMailer/src/SMTP.php';
            // Create PHPMailer object
            $mail = new PHPMailer(true);
            // Try to send email
            try {
                // Server settings
                $mail->SMTPDebug = 0;
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = $username;
                $mail->Password = $password;
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                $mail->setFrom('rossini.pasquale@itiscassino.edu.it', 'Mailer');
                // Receiver
                $mail->addCC($contact);
                // Content
                $mail->isHTML(true); 
                $mail->Subject = $header;
                $mail->Body = $message;
    
                $mail->send();
                return true;
            } catch (Exception $e) {
                // Return false if the email is not sent
                return false;
            }
        }
    }
