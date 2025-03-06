<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM specialists WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['speciality'] = $row['speciality'];
            header('Location: viewclients.php');
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No user found with that username!";
    }
}
$conn->close();
?>