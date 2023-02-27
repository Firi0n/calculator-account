<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require $home."class/IMail.php";
    class Mailer implements IMail
    {
        // home path
        private $home;

        public function __construct($home)
        {
            // Set home path
            $this->home = $home;
        }
        // Send email
        public function send($contact, $header, $message) : bool
        {
            // Import PHPMailer
            require $this->home."class/credentials.php";
            require_once $this->home.'PHPMailer/src/Exception.php';
            require_once $this->home.'PHPMailer/src/PHPMailer.php';
            require_once $this->home.'PHPMailer/src/SMTP.php';
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
