<?php
session_start();
include 'db.php';

// Function to fetch data based on fullname and phone
function fetchData($conn, $fullname, $phone) {
    $stmt = $conn->prepare("SELECT upload_date, healthcentre, report, bill FROM download WHERE fullname = ? AND phone = ?");
    $stmt->bind_param("ss", $fullname, $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['fullname'], $_POST['phone'])) {
        $fullname = $_POST['fullname'];
        $phone = $_POST['phone'];

        $data = fetchData($conn, $fullname, $phone);
        echo json_encode($data);
        exit; // Stop further execution
    } else {
        echo json_encode(['error' => 'Missing parameters']);
        exit;
    }
}
?>
