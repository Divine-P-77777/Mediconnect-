<?php
session_start();
if (!isset($_SESSION['healthcentre'])) {
    header("Location: login.php");
    exit();
}
include 'db.php'; 

// Assuming user_id is also required, ensuring it's set in the session
$user_id = $_SESSION['user_id'];
$healthcentre = mysqli_real_escape_string($conn, $_SESSION['healthcentre']);

// Handle form submission to update settings
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle dateslots update
    $dateslots = isset($_POST['dateslots']) ? $_POST['dateslots'] : [];
    $dateslot_query = "UPDATE settings SET dateslots = '" . implode(",", array_map('mysqli_real_escape_string', array_fill(0, count($dateslots), $conn), $dateslots)) . "' WHERE healthcenter = '$healthcentre'";
    mysqli_query($conn, $dateslot_query);

    // Handle timeslots update
    $timeslots = isset($_POST['timeslots']) ? $_POST['timeslots'] : [];
    $timeslot_query = "UPDATE settings SET timeslots = '" . implode(",", array_map('mysqli_real_escape_string', array_fill(0, count($timeslots), $conn), $timeslots)) . "' WHERE healthcenter = '$healthcentre'";
    mysqli_query($conn, $timeslot_query);

    // Handle purposes update
    $purposes = isset($_POST['purposes']) ? $_POST['purposes'] : [];
    $purpose_query = "UPDATE settings SET purposes = '" . implode(",", array_map('mysqli_real_escape_string', array_fill(0, count($purposes), $conn), $purposes)) . "' WHERE healthcenter = '$healthcentre'";
    mysqli_query($conn, $purpose_query);
}

// Fetch current settings
$sql = "SELECT dateslots, timeslots, purposes FROM settings WHERE healthcenter = '$healthcentre'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$current_dateslots = isset($row['dateslots']) ? explode(",", $row['dateslots']) : [];
$current_timeslots = isset($row['timeslots']) ? explode(",", $row['timeslots']) : [];
$current_purposes = isset($row['purposes']) ? explode(",", $row['purposes']) : [];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings - MediConnect</title>
</head>
<body>
    <h1>Admin Settings for <?= htmlspecialchars($healthcentre) ?></h1>
    <form method="post" action="admin_settings.php">
        <fieldset>
            <legend>Appointment Dates</legend>
            <label><input type="checkbox" name="dateslots[]" value="2024-06-25" <?= in_array("2024-06-25", $current_dateslots) ? 'checked' : '' ?>> June 25, 2024</label>
            <label><input type="checkbox" name="dateslots[]" value="2024-06-26" <?= in_array("2024-06-26", $current_dateslots) ? 'checked' : '' ?>> June 26, 2024</label>
            <label><input type="checkbox" name="dateslots[]" value="2024-06-27" <?= in_array("2024-06-27", $current_dateslots) ? 'checked' : '' ?>> June 27, 2024</label>
        </fieldset>
        <fieldset>
            <legend>Appointment Times</legend>
            <label><input type="checkbox" name="timeslots[]" value="09:00AM-12:00PM" <?= in_array("09:00AM-12:00PM", $current_timeslots) ? 'checked' : '' ?>> 09:00AM-12:00PM</label>
            <label><input type="checkbox" name="timeslots[]" value="12:00PM-03:00PM" <?= in_array("12:00PM-03:00PM", $current_timeslots) ? 'checked' : '' ?>> 12:00PM-03:00PM</label>
            <label><input type="checkbox" name="timeslots[]" value="03:00PM-06:00PM" <?= in_array("03:00PM-06:00PM", $current_timeslots) ? 'checked' : '' ?>> 03:00PM-06:00PM</label>
        </fieldset>
        <fieldset>
            <legend>Purpose of Visit</legend>
            <label><input type="checkbox" name="purposes[]" value="eye_checkup" <?= in_array("eye_checkup", $current_purposes) ? 'checked' : '' ?>> Eye Checkup</label>
            <label><input type="checkbox" name="purposes[]" value="hypertension" <?= in_array("hypertension", $current_purposes) ? 'checked' : '' ?>> Hypertension</label>
            <label><input type="checkbox" name="purposes[]" value="x_ray" <?= in_array("x_ray", $current_purposes) ? 'checked' : '' ?>> X-Ray</label>
            <label><input type="checkbox" name="purposes[]" value="consultation" <?= in_array("consultation", $current_purposes) ? 'checked' : '' ?>> Consultation</label>
        </fieldset>
        <button type="submit">Save Settings</button>
    </form>
</body>
</html>
<?php
mysqli_close($conn);
?>