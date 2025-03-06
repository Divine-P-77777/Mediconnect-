<?php
include 'db.php';
session_start();

$message = []; // Initialize an array to store messages

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $healthcentre = mysqli_real_escape_string($conn, $_POST['healthcentre']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM admin WHERE healthcentre = ?");
    $stmt->bind_param("s", $healthcentre);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify password using password_verify
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['healthcentre'] = $row['healthcentre'];
            $_SESSION['email'] = $row['email'];
            header("Location:adminhomepage.php");
            exit();
        } else {
            $message[] = "Invalid credentials";
        }
    } else {
        $message[] = "Healthcentre not found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
   <link rel="stylesheet" href="style2.css">
</head>
<body>
<div class="form-container">
   <form action="login.php" method="post">
      <h3>Login Now</h3>
      <?php
      if (!empty($message)) {
         foreach ($message as $msg) {
            echo '<div class="message">'.$msg.'</div>';
         }
      }
      ?>
      <input type="text" name="healthcentre" placeholder="Enter Healthcentre Name" class="box" required>
      <input type="password" name="password" placeholder="Enter Password" class="box" required>
      <input type="submit" name="submit" value="Login" class="btn">
      <p class="space">Don't have an account? <a href="register.php">Register Now</a></p>
      <p class="space">Are you a client ? <a href="../login.php">User Login</a></p>

   </form>
</div>
</body>
</html>
