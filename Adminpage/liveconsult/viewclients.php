<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

include 'db.php';

$speciality = $_SESSION['speciality'];

$sql = "SELECT * FROM liveconsult WHERE speciality='$speciality'";
$result = $conn->query($sql);

$consultations = [];
while ($row = $result->fetch_assoc()) {
    $consultations[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediConnect - Dashboard</title>
    <link rel="stylesheet" href="vcp.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="dashboard.php">Home</a></li>
            <li><a href="upload_documents.php">Upload Documents</a></li>
            <li><a href="live_consult.php">Live Consult Client</a></li>
        </ul>
        <button onclick="openSettings()">Settings</button>
    </nav>
    
    <div class="container">
        <h2>Live Consult Clients</h2>
        <div class="table-container">
            <table id="consultTable">
                <thead>
                    <tr>
                        <th>Submission Date</th>
                        <th>Full Name</th>
                        <th>Date of Consultation</th>
                        <th>Time of Consultation</th>
                        <th>DOB</th>
                        <th>Gender</th>
                        <th>Join Meeting</th>
                        <th>Share Link</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($consultations as $consult): ?>
                        <tr>
                            <td><?php echo $consult['dt']; ?></td>
                            <td><?php echo $consult['fullname']; ?></td>
                            <td><?php echo $consult['consultationdate']; ?></td>
                            <td><?php echo $consult['consultationtime']; ?></td>
                            <td><?php echo $consult['dob']; ?></td>
                            <td><?php echo $consult['gender']; ?></td>
                            <td><button onclick="joinMeeting('<?php echo $consult['meeting_link']; ?>', this)">Join</button></td>
                            <td><button onclick="shareLink('<?php echo $consult['meeting_link']; ?>', '<?php echo $consult['phone']; ?>', this)">Share</button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="settingsPopup" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeSettings()">&times;</span>
            <form id="settingsForm" method="post">

          
                
                <label for="mobileNumber">Phone No:</label>
                <input type="tel" id="mobileNumber" name="mobileNumber" pattern="\+91[0-9]{10}" value="<?php echo $_SESSION['mobileNumber'] ?? ''; ?>" required>
                <label for="meetingLink">Paste Team Meeting Link:</label>
                <input type="text" id="meetingLink" name="meetingLink" value="<?php echo $_SESSION['meetingLink'] ?? ''; ?>" required>
                <button type="submit">Save Changes</button>
            </form>
            <a href="https://teams.microsoft.com/meeting-create-link" target="_blank">Create Meeting Click Here</a>
        </div>
    </div>

    <script src="scripts.js"></script>
</body>
</html>
