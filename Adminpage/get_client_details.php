<?php
include 'db.php';

$id = $_GET['id'];
$query = "SELECT * FROM bookingform WHERE id='$id'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($result);

echo json_encode($row);
?>
