<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load our autoloader
require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';

class Mailer {
    public function sendMail($mailTo, $subject, $altBody){
        $mail = new PHPMailer(true);

        //Enable SMTP debugging.
        // $mail->SMTPDebug = 3;                               
        //Set PHPMailer to use SMTP.
        $mail->isSMTP();            
        //Set SMTP host name                          
        $mail->Host = "smtp.gmail.com";
        //Set this to true if SMTP host requires authentication to send email
        $mail->SMTPAuth = true;                          
        //Provide username and password     
        $mail->Username = "example@me.com";                 
        $mail->Password = "password";                           
        //If SMTP requires TLS encryption then set it
        $mail->SMTPSecure = "tls";                           
        //Set TCP port to connect to
        $mail->Port = 587;                                   

        $mail->From = "example@me.com";
        $mail->FromName = "HOST EMAIL";

        $mail->addAddress($mailTo, "");

        $mail->isHTML(true);

        $mail->Subject = $subject;
        $mail->Body = "<header style='background-color:#11cdef; color:#fff;'>
                <img src='#'>
        </header>
        <section style='margin-top:20px;'>
        <p>{$altBody}</p><br>
        <p> Sincerely yours, </p>
        <p> Team Payperlez </p>
        </section>
        ";
        
        // $mail->AltBody = "{$altBody}";

        try {
            $mail->send();
           return $this->alert = true;
        } catch (Exception $e) {
            // echo "Mailer Error: " . $mail->ErrorInfo;
           return $this->alert = false;

        }
    }
}