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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Download Page - MediConnect</title>
    <link rel="stylesheet" href="download.css">
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
                <li class="nav-item"><a class="nav-link active" href="#">Download</a></li>
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
        <h1>Download Page</h1>
        <form id="downloadForm" action="download.php" method="post">
            <fieldset>
                <legend>Personal Information</legend>
                <label for="fullname">Full Name:</label>
                <input type="text" id="fullname" name="fullname" required />

                <label for="phone">Phone No:</label>
                <input type="tel" id="phone" name="phone" value="+91" pattern="\+91[0-9]{10}" required />
            </fieldset>

            <button type="submit" id="submit">Submit</button>
        </form>

        <div id="resultsTable" class="results-table">
            <!-- Table will be populated by JavaScript -->
        </div>
    </main>

    <script src="download.js"></script>
    <script>
        document.getElementById("phone").addEventListener("focus", function () {
            if (!this.value.startsWith("+91")) {
                this.value = "+91" + this.value;
            }
        });

        function validatePhoneNumber() {
            const phoneInput = document.getElementById("phone").value;
            const phonePattern = /^\+91[0-9]{10}$/;
            if (!phonePattern.test(phoneInput)) {
                alert("Please enter a valid 10-digit phone number after +91");
                return false;
            }
            return true;
        }
    </script>
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

        document.querySelectorAll(".nav-link").forEach((n) =>
            n.addEventListener("click", () => {
                hamburger.classList.remove("active");
                navMenu.classList.remove("active");
            })
        );
        document.addEventListener("DOMContentLoaded", function () {
            const navLinks = document.querySelectorAll(".nav-menu a");

            navLinks.forEach(function (link) {
                link.addEventListener("click", function (event) {
                    event.preventDefault();
                    window.location.href = link.getAttribute("href");
                });
            });
        });
    </script>
</body>

</html>