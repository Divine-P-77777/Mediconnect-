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

if (isset($_POST['submit'])) {
   
    
    $fullname = $_POST['fullname'];
    $dob = $_POST['dob'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $consultationdate = $_POST['consultationdate'];
    $consultationtime = $_POST['consultationtime'];
    $speciality = $_POST['speciality'];



    $sql = "INSERT INTO `mediconnect@77777`.`liveconsult` (`fullname`, `dob`, `phone`, `gender`, `consultationdate`, `consultationtime`, `speciality`, `dt`) VALUES (' $fullname', '$dob', '$phone', '$gender', ' $consultationdate', '$consultationtime', '$speciality', current_timestamp());";

    
    if ($conn->query($sql) === true) {

        $insert = true;
    } else {
        echo "ERROR: $sql <br> $conn->error";
    }

    
   
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Consultation - MediConnect</title>
    <link rel="stylesheet" href="liveconsult.css">
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
                <li class="nav-item"><a class="nav-link " href="home_page.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="book_slot.php">Book Slot</a></li>
                <li class="nav-item"><a class="nav-link active" href="#">Live Consult</a></li>
                <li class="nav-item"><a class="nav-link" href="download2.php">Download</a></li>
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
        <h1>Live Consultation Appointment</h1>
        <form id="consultationForm" action="liveconsult.php" method="post">
            <fieldset>
                <legend>Personal Details</legend>
                <label for="fullame">Full Name:</label>
                <input type="text" id="fullName" name="fullname" required>

                <label for="dob">DOB:</label>
                <input type="date" id="dob" name="dob" required>

                <label for="phone">Phone No:</label>
                <input type="tel" id="phone" name="phone" value="+91" pattern="\+91[0-9]{10}" required>

                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </fieldset>

            <fieldset>
                <legend>Consultation Details</legend>
                <label for="date">Date:</label>
                <input type="date" id="consultationdate" name="consultationdate" required>

                <label for="time">Time:</label>
                <select id="time" name="consultationtime" required>
                    <!-- Options will be populated by JavaScript -->
                </select>

                <label for="speciality">Speciality:</label>
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
                    <option value="Oncology">Oncology</option>
                    <option value="ENT (Ear, Nose, and Throat)">ENT (Ear, Nose, and Throat)</option>
                    <option value="Rheumatology">Rheumatology</option>
                    <option value="Allergy and Immunology">Allergy and Immunology</option>
                    <option value="Radiology">Radiology</option>
                    <option value="Physical Therapy and Rehabilitation">Physical Therapy and Rehabilitation</option>

                </select>
            </fieldset>

            <div class="disclaimer">
                <p>
                    <span class="red">Disclaimer:</span> <br> All consultations are conducted via Google Meet. Please
                    ensure you have Google Meet installed and configured on your device with a stable internet
                    connection, a working camera, and a microphone. By proceeding, you agree to use Google Meet for the
                    consultation and accept its privacy policy and terms of service. We are not responsible for
                    technical issues arising from the use of the app.
                </p>
            </div>

            <button  type="submit" name="submit">Submit</button>
        </form>
        <?php if (isset($insert) && $insert): ?>
            <p class="approval">Form Submitted Successfully, Kindly wait for approval!</p>
        <?php endif; ?>
    </main>
    <script src="liveconsult.js"></script>
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
        
            const dateInput = document.getElementById('consultationdate');
            const today = new Date().toISOString().split('T')[0];
            dateInput.setAttribute('min', today);
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
 
mysqli_close($conn);
?>