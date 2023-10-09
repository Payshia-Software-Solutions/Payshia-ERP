<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$RootDirectly = __DIR__;
require '../../vendor/mailer/autoload.php';


function sendEmail($to, $subject, $message, $fromEmail, $fromName)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'mail.jlktours.com';                //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'no-reply@jlktours.com';             //SMTP username
        $mail->Password   = 'BwkLVqpn-vnc';                         //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                       //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`


        // Sender and recipient
        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($to);
        $mail->addBCC('info@jlktours.com');
        $mail->addBCC('thilinaruwan112@gmail.com');

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

$response = ['status' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Parse incoming JSON data (assuming JSON input)
    $data = json_decode(file_get_contents('php://input'), true);

    // Check for required fields
    if (isset($data['to'], $data['subject'], $data['message'], $data['fromEmail'], $data['fromName'])) {
        // Send the email
        $result = sendEmail($data['to'], $data['subject'], $data['message'], $data['fromEmail'], $data['fromName']);

        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Email sent successfully';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Email could not be sent';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Missing required fields';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Method not allowed';
}

// Encode the response as JSON
echo json_encode($response);
