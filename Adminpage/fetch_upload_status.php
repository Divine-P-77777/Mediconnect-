<?php
include 'db.php'; // Include file with database connection

$data = json_decode(file_get_contents('php://input'), true);
$phone = $data['phone'];

$query = "SELECT report, bill FROM download WHERE phone = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $phone);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = $result->fetch_assoc();

echo json_encode($row);
?>
