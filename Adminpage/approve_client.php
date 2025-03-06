<?php
include 'db.php';
require 'vendor/autoload.php'; // if using Composer for Twilio

use Twilio\Rest\Client;

$id = $_GET['id'];
$whatsappNumber = $_GET['whatsapp'];

$query = "UPDATE bookingform SET approval='Approved' WHERE id='$id'";
if (mysqli_query($con, $query)) {
    // Twilio API configuration
    $sid = 'your_account_sid';
    $token = 'your_auth_token';
    $twilio = new Client($sid, $token);

    try {
        $message = $twilio->messages->create(
            "whatsapp:".$whatsappNumber,
            array(
                "from" => "whatsapp:your_twilio_whatsapp_number",
                "body" => "Your appointment has been approved. Thank you for booking with MediConnect."
            )
        );
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($con)]);
}
?>
