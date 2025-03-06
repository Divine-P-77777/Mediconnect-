<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['email']) && isset($data['subject']) && isset($data['message'])) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'deepakpmahato123@gmail.com';           // SMTP username
        $mail->Password   = 'zzdu lsqk zreq kzll';                  // SMTP password (use App Password if 2FA is enabled)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            // Enable implicit TLS encryption
        $mail->Port       = 465; 

        // Recipients
        $mail->setFrom('deepakpmahato123@gmail.com', 'MediConnect');
        $mail->addAddress($data['email']);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $data['subject'];
        $mail->Body    = nl2br($data['message']);

        $mail->send();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $mail->ErrorInfo]);
    }
}
?>
