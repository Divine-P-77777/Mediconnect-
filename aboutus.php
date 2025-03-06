<?php

include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}
;

if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:login.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - MediConnect</title>
    <link rel="stylesheet" href="aboutus.css">
    <link rel="stylesheet" href="homepage.css">
    <link rel="stylesheet" href="hamburger.css">
</head>
<body>
    <header>
    <nav class="navbar">
            <div class="logo-container">
                <img src="login/logo.png" alt="MediConnect Logo" class="logo">
                <span class="logo-text">MediConnect</span>
            </div>
            <ul class="nav-menu">
                <li class="nav-item"><a class="nav-link" href="home_page.php">Home</a></li>
                <li class="nav-item"><a class="nav-link " href="book_slot.php">Book Slot</a></li>
                <li class="nav-item"><a class="nav-link" href="liveconsult.php">Live Consult</a></li>
                <li class="nav-item"><a class="nav-link" href="download2.php">Download</a></li>
                <li class="nav-item"><a class="nav-link active" href="#">About Us</a></li>
            </ul>
            <div class="hamburger">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
            <div class="profile-circle" onclick="toggleProfilePopup()">
                <?php
                $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('query failed');
                if (mysqli_num_rows($select) > 0) {
                    $fetch = mysqli_fetch_assoc($select);
                }
                if ($fetch['image'] == '') {
                    echo '<img src="images/default-avatar.png">';
                } else {
                    echo '<img src="login/uploaded_img/' . $fetch['image'] . '">';
                }
                ?>
            </div>
        </nav>

    </header>
    <div id="profilePopup" class="profile-popup">
        <div class="profile-content">
            <h3><?php echo $fetch['name']; ?></h3>
            <a href="update_profile.php" class="btn">update profile</a>
            <a href="home_page.php?logout=<?php echo $user_id; ?>" class="delete-btn">logout</a>
            <!-- <p>new <a href="login.php">login</a> or <a href="register.php">register</a></p> -->
        </div>
    </div>
    <main>
        <section id="introduction">
            <h1>About MediConnect</h1>
            <p>Welcome to MediConnect, your trusted platform for healthcare appointments and consultations. We strive to provide seamless access to healthcare services through our advanced booking system and live consultation features.</p>
            <p>Our platform is designed to connect patients with healthcare professionals efficiently, ensuring timely and convenient access to medical care.</p>
        </section>

        <section id="feedback">
            <h2>Send Us Your Feedback</h2>
            <form id="feedbackForm" action="" method="post">
                <label for="fullName">Full Name:</label>
                <input type="text" id="fullName" name="fullname" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="4" required></textarea>
                
                <button type="submit" name="submit">Submit Feedback</button>
            </form>
        </section>
    </main>
    <script src="aboutus.css"></script>
    <script>
         function toggleProfilePopup() {
            var popup = document.getElementById("profilePopup");
            popup.classList.toggle("show");
        }

        document.addEventListener("DOMContentLoaded", function () {
            const hamburger = document.querySelector(".hamburger");
            const navMenu = document.querySelector(".nav-menu");

            hamburger.addEventListener("click", function () {
                hamburger.classList.toggle("active");
                navMenu.classList.toggle("active");
            });
        });

        document.querySelectorAll(".nav-link").forEach(n => n.addEventListener("click", () => {
            hamburger.classList.remove("active");
            navMenu.classList.remove("active");
        }));
        document.addEventListener("DOMContentLoaded", function () {

            const navLinks = document.querySelectorAll('.nav-menu a');


            navLinks.forEach(function (link) {
                link.addEventListener("click", function (event) {
                    event.preventDefault();
                    window.location.href = link.getAttribute('href');
                });
            });
        });

    </script>
</body>
</html>
<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['submit'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Load Composer's autoloader
    require 'PHPMailer/Exception.php';
    require 'PHPMailer/PHPMailer.php';
    require 'PHPMailer/SMTP.php';

    // Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        // Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'deepakpmahato123@gmail.com';         // SMTP username
        $mail->Password   = 'zzdu lsqk zreq kzll';                  // SMTP password (use App Password if 2FA is enabled)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            // Enable implicit TLS encryption
        $mail->Port       = 465;                                    // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        // Recipients
        $mail->setFrom('deepakpmahato123@gmail.com', 'Mailer');
        $mail->addAddress('dynamicphillic77777@gmail.com', 'Mediconnect'); // Add a recipient

        // Content
        $mail->isHTML(true);                                        // Set email format to HTML
        $mail->Subject = 'MAIL FROM MEDICONNECT';
        $mail->Body    = "Sender Name- $fullname <br> Sender Email- $email <br> Message- $message";

        $mail->send();
        echo "<div class='success'>Thanks for sharing feedback!</div>";
    } catch (Exception $e) {
        echo "<div class='alert'>Feedback form couldn't send!</div>";
    }
}
?>
<?php
mysqli_close($conn);
?>