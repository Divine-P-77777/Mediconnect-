<?php
// Database credentials
$server = "localhost";
$username = "root";
$password = "";
$database = "mediconnect@77777"; // Replace with your MySQL database name (including the schema identifier)

// Create connection
$conn = new mysqli($server, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch data from bookingform table
$sql = " SELECT fullname, dob, phone, gender, pincode, appointmentdate, appointmenttime, purposeofvisit from bookingform";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    echo "<table>";
    echo "<tr><th>Full Name</th><th>DOB</th><th>Phone No</th><th>Gender</th><th>Pincode</th><th>Appointment Date</th><th>Appointment Time</th><th>Purpose</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["fullname"] . "</td>";
        echo "<td>" . $row["dob"] . "</td>";
        echo "<td>" . $row["phone"] . "</td>";
        echo "<td>" . $row["gender"] . "</td>";
        
        echo "<td>" . $row["pincode"] . "</td>";
        // echo "<td>" . $row["healthcentre"] . "</td>";
        echo "<td>" . $row["appointmentdate"] . "</td>";
        echo "<td>" . $row["appointmenttime"] . "</td>";
        echo "<td>" . $row["purposeofvisit"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

$conn->close();
?>
