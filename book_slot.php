<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['user_email'];  // Retrieve email from session

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
    $pincode = $_POST['pincode'];
    $healthcenter = $_POST['healthcenter'];
    $appointmentdate = $_POST['appointmentdate'];
    $appointmenttime = $_POST['appointmenttime'];
    $purposeofvisit = $_POST['purposeofvisit'];

    $sql = "INSERT INTO bookingform (fullname, dob, phone, gender, pincode, healthcenter, appointmentdate, appointmenttime, purposeofvisit, user_email, dt) 
            VALUES ('$fullname', '$dob', '$phone', '$gender', '$pincode', '$healthcenter', '$appointmentdate', '$appointmenttime', '$purposeofvisit', '$user_email', NOW())";

    if ($conn->query($sql) === TRUE) {
        $insert = true;
    } else {
        echo "ERROR: $sql <br> $conn->error";
    }
}

$sql = "SELECT timeslots, purposes, dateslots FROM settings";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$current_timeslots = explode(",", $row['timeslots']);
$current_purposes = explode(",", $row['purposes']);
$available_dates = explode(",", $row['dateslots']);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Slot - MediConnect</title>
    <link rel="stylesheet" href="bookslot.css">
    <link rel="stylesheet" href="hamburger.css">
    <link rel="stylesheet" href="homepage.css">

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
                <li class="nav-item"><a class="nav-link active" href="#">Book Slot</a></li>
                <li class="nav-item"><a class="nav-link" href="liveconsult.php">Live Consult</a></li>
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
        <h1>Book Your Healthcare Appointment</h1>
        <form id="bookingForm" action="" method="post">
            <fieldset>
                <legend>Personal Details</legend>
                <label for="fullName">Full Name:</label>
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
                <legend>Appointment Details</legend>
                <label for="pinCode">Pin code:</label>
                <input type="text" id="pinCode" name="pincode" required>

                <label for="healthCenter">Choose Health Center:</label>
                <select id="healthCenter" name="healthcenter" required>
                    <!-- Options can be populated by JavaScript if needed -->
                </select>

                <label for="date">Date:</label>
                <input type="date" id="date" name="appointmentdate" required>

                <label for="time">Time:</label>
                <select id="time" name="appointmenttime" required>
                    <option value="09:00 AM - 09:30 AM">09:00 AM - 09:30 AM</option>
                    <option value="09:30 AM - 10:00 AM">09:30 AM - 10:00 AM</option>
                    <option value="10:00 AM - 10:30 AM">10:00 AM - 10:30 AM</option>
                    <option value="10:30 AM - 11:00 AM">10:30 AM - 11:00 AM</option>
                    <option value="11:00 AM - 11:30 AM">11:00 AM - 11:30 AM</option>
                    <option value="11:30 AM - 12:00 PM">11:30 AM - 12:00 PM</option>
                    <option value="12:00 PM - 12:30 PM">12:00 PM - 12:30 PM</option>
                    <option value="12:30 PM - 01:00 PM">12:30 PM - 01:00 PM</option>
                    <option value="01:00 PM - 01:30 PM">01:00 PM - 01:30 PM</option>
                    <option value="01:30 PM - 02:00 PM">01:30 PM - 02:00 PM</option>
                    <option value="02:00 PM - 02:30 PM">02:00 PM - 02:30 PM</option>
                    <option value="02:30 PM - 03:00 PM">02:30 PM - 03:00 PM</option>
                    <option value="03:00 PM - 03:30 PM">03:00 PM - 03:30 PM</option>
                    <option value="03:30 PM - 04:00 PM">03:30 PM - 04:00 PM</option>
                    <option value="04:00 PM - 04:30 PM">04:00 PM - 04:30 PM</option>
                    <option value="04:30 PM - 05:00 PM">04:30 PM - 05:00 PM</option>
                    <option value="05:00 PM - 05:30 PM">05:00 PM - 05:30 PM</option>
                    <option value="05:30 PM - 06:00 PM">05:30 PM - 06:00 PM</option>
                </select>

                <label for="purpose">Purpose of Visit:</label>
                <select id="purpose" name="purposeofvisit" required>
                    <option value="consultation">Consultation</option>
                    <option value="general_checkup">General Checkup</option>
                    <option value="eye_checkup">Eye Checkup</option>
                    <option value="dental_checkup">Dental Checkup</option>
                    <option value="vaccination">Vaccination</option>
                    <option value="follow_up">Follow-up</option>
                    <option value="prescription_refill">Prescription Refill</option>
                    <option value="lab_tests">Lab Tests</option>
                    <option value="x_ray">X-Ray</option>
                    <option value="ultrasound">Ultrasound</option>
                    <option value="physiotherapy">Physiotherapy</option>
                    <option value="mental_health">Mental Health</option>
                    <option value="nutrition_consultation">Nutrition Consultation</option>
                    <option value="hypertension">Hypertension</option>
                    <option value="diabetes_management">Diabetes Management</option>

                </select>
            </fieldset>

            <button type="submit" class="s1" name="submit">Submit</button>
        </form>

        <?php if (isset($insert) && $insert): ?>
            <p class="approval">Form Submitted Successfully, Kindly wait for approval!</p>
        <?php endif; ?>
    </main>

    <script src="bookslot.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dobInput = document.getElementById('dob');

            // Calculate the date 4 years ago from today
            const today = new Date();
            const minAgeDate = new Date(today.getFullYear() - 4, today.getMonth(), today.getDate());

            // Convert to the format YYYY-MM-DD
            const minAgeDateString = minAgeDate.toISOString().split('T')[0];

            // Set the max attribute to restrict date selection
            dobInput.setAttribute('max', minAgeDateString);
        });


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
mysqli_close($conn);
?>