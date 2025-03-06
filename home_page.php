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
    <title>MediConnect</title>
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
                <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="book_slot.php">Book Slot</a></li>
                <li class="nav-item"><a class="nav-link" href="liveconsult.php">Live Consult</a></li>
                <li class="nav-item"><a class="nav-link" href="download.html">Download</a></li>
                <li class="nav-item"><a class="nav-link" href="aboutus.php">About Us</a></li>
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
        <!-- class="welcome-section" -->
        <section>
            <div class="welcome-text">
                <h1>Welcome in MEDICONNECT</h1>
                <p>Your All-in-One Healthcare Platform</p>
                <p>With MEDICONNECT, you can easily book appointments, have live consultations with doctors, and
                    instantly download your medical reports. Our user-friendly platform makes managing your healthcare
                    simple and convenient. Experience the ease and efficiency of MEDICONNECT, your go-to solution for
                    comprehensive healthcare.</p>
            </div>
            <div class="welcome-image">
                <img src="login/bg_img2.png" alt="Doctor Consultation">
            </div>
        </section>
        <section class="features-section">
            <div class="feature"><a href="book_slot.php" style="text-decoration: none;">
                    <img src="login/bookslot.png" alt="Book Slot Icon">
                    <h2>Book Slot</h2>
                    <p>Book your slot now for convenient, fast, and personalized care with top specialists at your
                        nearest healthcare center.</p>
                </a> </div>
            <div class="feature"><a href="liveconsult.php" style="text-decoration: none;">
                    <img src="login/Liveconsult.png" alt="Live Consult Icon">
                    <h2>Live Consult</h2>
                    <p>Secure your live consultation now for convenient, fast care across specialties, with easy booking
                        steps from home.</p>
                </a> </div>
            <div class="feature"><a href="download.html" style="text-decoration: none;">
                    <img src="login/download.png" alt="Download Icon">
                    <h2>Download</h2>
                    <p>Download your reports and bills from home effortlessly with our user-friendly download section.
                    </p>
                </a> </div>
        </section>
    </main>
    <script src="homepage.js"></script>
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
        document.addEventListener("DOMContentLoaded", function() {
    // Get all links within the navigation menu
    const navLinks = document.querySelectorAll('.nav-menu a');
    
    // Add click event listeners to all links
    navLinks.forEach(function(link) {
        link.addEventListener("click", function(event) {
            event.preventDefault(); // Prevent the default action (navigation)
            
            // Navigate to the href attribute of the clicked link
            window.location.href = link.getAttribute('href');
        });
    });
});

    </script>
</body>

</html>