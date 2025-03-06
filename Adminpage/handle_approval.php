<?php
include 'db.php';
session_start();

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

$user_id = $_SESSION['user_id'];

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Adjust require statements based on the location of your PHPMailer files

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data['email'];
    $appointmentTime = $data['appointmentTime'];
    $appointmentDate = $data['appointmentDate'];

    // Update the database to mark the appointment as approved
    $query = "UPDATE bookingform SET status = 'approved' WHERE email = ? AND appointmenttime = ? AND appointmentdate = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sss", $email, $appointmentTime, $appointmentDate);
    
    if (mysqli_stmt_execute($stmt)) {
        // Send email notification to the client
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'deepakpmahato123@gmail.com';         // SMTP username
            $mail->Password   = 'zzdu lsqk zreq kzll';                  // SMTP password (use App Password if 2FA is enabled)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            // Enable implicit TLS encryption
            $mail->Port       = 465;                                    // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
            // Recipients
            $mail->setFrom('deepakpmahato123@gmail.com', 'Mailer');
            $mail->addAddress('email', 'Mediconnect');

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Slot Booking Approved';
            $mail->Body = "Dear User,<br><br>Your slot booking for <strong>$appointmentDate</strong> at <strong>$appointmentTime</strong> has been approved. Please be available at least 20 minutes before your appointment time.<br><br>Regards,<br>MediConnect Team";

            $mail->send();
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => "Mailer Error: {$mail->ErrorInfo}"]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to approve booking.']);
    }

    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
?>
