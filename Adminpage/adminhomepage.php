<?php

include 'db.php';
session_start();
$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_GET['logout'])){
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
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="logo.png" alt="MediConnect Logo" class="logo">
            <span class="logo-text">MediConnect</span>
        </div>
        <nav>
            <!-- Hamburger menu and navigation links -->
            <div class="nav-list">
                    <div class="hamburger">
                        <div class="bar" id="line"></div>
                    </div>
                 <ul>
                 <li><a href="#" class="active">Home</a></li>
                <li><a  href="dashboard.php">Dashboard</a></li>
                <li><a href="upload_file.php">Upload Documents</a></li>
                <li><a href="Adminpage/liveconsult/login.php">Live Consult Clients</a></li>
                
                <li><a href="aboutus.php">About Us</a></li>
                
                 </ul>
            </div>
            <div class="profile-circle" onclick="toggleProfilePopup()">
                <?php
                $select = mysqli_query($conn, "SELECT * FROM `admin` WHERE id = '$user_id'") or die('query failed');
                if(mysqli_num_rows($select) > 0){
                    $fetch = mysqli_fetch_assoc($select);
                }
                if($fetch['image'] == ''){
                    echo '<img src="images/default-avatar.png">';
                } else {
                    echo '<img src="uploaded_img/'.$fetch['image'].'">';
                }
                ?>
            </div>
        </nav>
    </header>
    <div id="profilePopup" class="profile-popup">
        <div class="profile-content">
            <h3><?php echo $fetch['healthcentre']; ?></h3>
            <a href="adminupdateprofile.php" class="btn">update profile</a>
            <a href="admin_settings.php" class="delete-btn">Setting</a>

            <a href="adminhomepage.php?logout=<?php echo $user_id; ?>" class="delete-btn">logout</a>
            <!-- <p>new <a href="login.php">login</a> or <a href="register.php">register</a></p> -->
        </div>
    </div>
    <main>
        <!-- class="welcome-section" -->
        <section > 
            <div class="welcome-text">
                <h1>Welcome in MEDICONNECT</h1>
                <p>Your All-in-One Healthcare Platform</p>
                <p>With MEDICONNECT, you can easily book appointments, have live consultations with doctors, and instantly download your medical reports. Our user-friendly platform makes managing your healthcare simple and convenient. Experience the ease and efficiency of MEDICONNECT, your go-to solution for comprehensive healthcare.</p>
            </div>
            <!-- <div class="welcome-image">
                <img src="login/bg_img2.png" alt="Doctor Consultation">
            </div> -->
        </section>
        <section class="features-section">
            <div class="feature"><a href="dashboard.php" style="text-decoration: none;">
                <img src="../login/dashboard.png" alt="Dashboard Icon">
                <h2>Dashboard</h2>
                <p>View Live Status</p>
            </a> </div>
            <div class="feature"><a href="upload_file.php" style="text-decoration: none;">
                <img src="../login/upload.png" alt=" Upload Icon">
                <h2>Upload Documents</h2>
                <p>Upload respective client's reports and bills</p>
                </a> </div>
            <div class="feature"><a href="Adminpage/liveconsult/login.php" style="text-decoration: none;">
                <img src="../login/consult.png" alt="Consult Icon">
                <h2>Take Consultation</h2>
                <p>View live consult candidants</p>
            </a> </div>
        </section>
    </main>
    <script src="homepage.js"></script>
    <script>
    function toggleProfilePopup() {
        var popup = document.getElementById("profilePopup");
        popup.classList.toggle("show");
    }
    </script>
    
   
    </script>
</body>
</html>
