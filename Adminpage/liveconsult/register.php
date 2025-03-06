<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediConnect - Registration</title>
    <link rel="stylesheet" href="vcp.css">
</head>
<body>
<div class="form-container">
        <h2>Specialist Registration</h2>
        <form id="registerForm" method="POST" action="register.php">
            <input type="text" name="username" class="box" placeholder="Agent Fullname" required>
            <label for="speciality" class="box">Speciality:</label>
            <select id="speciality" name="speciality" required>
                 <option value="General Medicine">General Medicine</option>
                 <option value="Pediatrics">Pediatrics</option>
                 <option value="Cardiology">Cardiology</option>
                 <option value="Orthopedics">Orthopedics</option>
                 <option value="Gynecology and Obstetrics">Gynecology and Obstetrics</option>
                 <option value="Dermatology">Dermatology</option>
                 <option value="Ophthalmology">Ophthalmology</option>
                 <option value="Neurology">Neurology</option>
                 <option value="Psychiatry and Psychology">Psychiatry and Psychology</option>
                 <option value="Gastroenterology">Gastroenterology</option>
                 <option value="Pulmonology">Pulmonology</option>
                 <option value="Endocrinology">Endocrinology</option>
                 <option value="Nephrology">Nephrology</option>
                 <option value="Urology">Urology</option>
                 <option value="Rheumatology">Rheumatology</option>
                 <option value="Allergy and Immunology">Allergy and Immunology</option>
                 <option value="Radiology">Radiology</option>
                 <option value="Physical Therapy and Rehabilitation">Physical Therapy and Rehabilitation</option>
                <!-- Add other specialities here -->
            </select>
            <input type="password" name="password" class="box" placeholder="Enter Password" required>
            <input type="password" name="confirm_password" class="box" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $speciality = $_POST['speciality'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO specialists (username, speciality, password) VALUES ('$username', '$speciality', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
        header('Location: index.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close();
?>





