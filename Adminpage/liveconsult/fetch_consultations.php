<?php
session_start();
include 'db.php';

$speciality = $_SESSION['speciality'];
$sql = "SELECT * FROM liveconsult WHERE speciality='$speciality'";
$result = $conn->query($sql);

$consultations = [];
while($row = $result->fetch_assoc()) {
    $consultations[] = $row;
}

echo json_encode($consultations);
$conn->close();
?>
