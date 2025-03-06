<?php

include 'db.php';

if (isset($_POST['submit'])) {
    $healthcentre = mysqli_real_escape_string($conn, $_POST['healthcentre']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);

    if ($password != $cpassword) {
        $message[] = 'Confirm password does not match!';
    } else {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Check if user already exists
        $selectQuery = $conn->prepare("SELECT * FROM admin WHERE email = ?");
        $selectQuery->bind_param("s", $email);
        $selectQuery->execute();
        $result = $selectQuery->get_result();

        if ($result->num_rows > 0) {
            $message[] = 'User already exists';
        } else {
            // Insert new user
            $insertQuery = $conn->prepare("INSERT INTO admin (healthcentre, email, password) VALUES (?, ?, ?)");
            $insertQuery->bind_param("sss", $healthcentre, $email, $passwordHash);

            if ($insertQuery->execute()) {
                $message[] = 'Registered successfully!';
                header('Location: login.php');
                exit();
            } else {
                $message[] = 'Registration failed!';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>
   <link rel="stylesheet" href="style2.css">
</head>
<body>
<div class="form-container">
   <form action="" method="post" enctype="multipart/form-data">
      <h3>Register Now</h3>
      <?php
      if (isset($message)) {
         foreach ($message as $msg) {
            echo '<div class="message">'.$msg.'</div>';
         }
      }
      ?>
      <input type="text" name="healthcentre" placeholder="Enter health centre name" class="box" required>
      <input type="email" name="email" placeholder="Enter email" class="box" required>
      <input type="password" name="password" placeholder="Enter password" class="box" required>
      <input type="password" name="cpassword" placeholder="Confirm password" class="box" required>
      <input type="submit" name="submit" value="Register" class="btn">
      <p>Already have an account? <a href="login.php">Login now</a></p>
   </form>
</div>
</body>
</html>
