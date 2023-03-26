<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
class Email{
	public function __construct(){
		//do nothing
	}
	public function send($to,$subject,$message){
            
            require("Vendors/PHPMailer/src/PHPMailer.php");
            require("Vendors/PHPMailer/src/SMTP.php");

            $mail = new PHPMailer();

            try {
                //Server settings
                //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
                $mail->isSMTP();                                            // Send using SMTP
                $mail->Host       = 'asmtp.mail.hostpoint.ch';                    // Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                $mail->Username   = 'info@mkultra.site';                     // SMTP username
                $mail->Password   = 'gottistmitmir';                               // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

                //Recipients
                $mail->setFrom('info@mkultra.site', 'pykme');
                $mail->addAddress($to);     
                $mail->addReplyTo('info@mkultra.site', 'pykme');


                // Attachments
                /*
                $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                */
                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = $subject;
                $mail->Body    = $message;
                $mail->AltBody = 'HTML not supported';

                $mail->send();
                return true;
            } catch (Exception $e) {
                /*
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                 */
                return false;
            }
		
	}
}