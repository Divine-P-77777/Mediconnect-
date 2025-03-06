<?php
include 'db.php';
require 'vendor/autoload.php';

use Twilio\Rest\Client;

$clientId = $_GET['id'];

$sql = "SELECT * FROM bookingform WHERE id='$clientId'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$account_sid = 'your_account_sid';
$auth_token = 'your_auth_token';
$twilio_number = 'your_twilio_number';
$client_phone = $row['phone'];
$message = "You have booked a slot on " . $row['appointmentdate'] . " at " . $row['healthcenter'] . ". Kindly visit before 20 minutes from the selected slot time.";

$client = new Client($account_sid, $auth_token);
$client->messages->create(
    $client_phone,
    ['from' => $twilio_number, 'body' => $message]
);

echo "WhatsApp message sent successfully";
?>
