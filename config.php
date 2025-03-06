<?php 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mediconnect@77777";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// $server = "sql303.infinityfree.com";
// $username = "if0_36793398";
// $password = "ZIYBgZU9RFeL";
// $dbname = "if0_36793398_Mediconnect";

// $con = mysqli_connect($server, $username, $password, $dbname);

// if (!$con) {
//     die("Connection failed: " . mysqli_connect_error());
// }
?>