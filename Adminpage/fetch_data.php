<?php
include 'db.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['healthcentre'])) {
    echo json_encode([]);
    exit();
}

$healthcentre = $_SESSION['healthcentre'];

// SQL query to fetch required columns including document details
$sql = "SELECT id, fullname, phone, healthcenter, report, bill FROM bookingform WHERE healthcenter = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
    exit();
}

$stmt->bind_param("s", $healthcentre);
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    exit();
}

$result = $stmt->get_result();
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
