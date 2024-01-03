<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require __DIR__ . '/../vendor/mailer/autoload.php';

//   Sent Email
function sentEmail($fullName, $toAddress, $fromAddress, $mailSubject, $mailBodyHtml)
{
    $resultArray = array();
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    try {
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'mail.pharmacollege.lk';                //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'no-reply@pharmacollege.lk';             //SMTP username
        $mail->Password   = 'HxeX6O]{zwB.';                         //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                      //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('info@pharmacollege.lk', 'Ceylon Pharma College');
        $mail->addAddress($toAddress, $fullName);     //Add a recipient
        // $mail->addReplyTo('info@inspirelk.com', 'InspireLK');
        // $mail->addCC('connect@thilinaruwan.inspirelk.com', 'Thilina Ruwan | InspireLK');
        // $mail->addCC('info@inspirelk.com', 'Info | InspireLK');

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $mailSubject;
        $mail->Body    = $mailBodyHtml;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        $mail->send();
        $resultArray = array("status" => "success", "message" => 'Activation Email has been sent. Inform to Check the Mail box.');
    } catch (Exception $e) {
        $resultArray = array("status" => "error", "message" => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }

    return json_encode($resultArray);
}
