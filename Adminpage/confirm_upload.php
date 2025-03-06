<?php
include 'db.php'; // Include file with database connection

$data = json_decode(file_get_contents('php://input'), true);
$phone = $data['phone'];

$query = "UPDATE download SET confirmed = 1 WHERE phone = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $phone);
if (mysqli_stmt_execute($stmt)) {
    echo "Upload confirmed.";
} else {
    echo "Error: " . mysqli_stmt_error($stmt);
}
mysqli_stmt_close($stmt);
?>
