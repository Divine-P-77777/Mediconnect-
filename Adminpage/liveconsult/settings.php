<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['meetingLink']) && isset($_POST['mobileNumber'])) {
        $_SESSION['meetingLink'] = $_POST['meetingLink'];
        $_SESSION['mobileNumber'] = $_POST['mobileNumber'];
        echo "Settings saved!";
    } else {
        echo "Error: Meeting link or mobile number not provided.";
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $response = [
        'meetingLink' => $_SESSION['meetingLink'] ?? '',
        'mobileNumber' => $_SESSION['mobileNumber'] ?? ''
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
}
