<?php
session_start();
include 'db.php'; // Include file with database connection

$user_id = $_SESSION['user_id'];
$healthcentre = $_SESSION['healthcentre'];

// Fetch data from bookingform table for the logged-in health centre in reverse order
$query = "SELECT dt, fullname, phone, healthcenter FROM bookingform WHERE healthcenter = ? ORDER BY dt DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $healthcentre);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Handle file upload
if (isset($_POST['uploadFormSubmitted'])) {
    $phone = $_POST['phone'];
    $fileType = $_POST['fileType'];
    $uploadDirectory = "../uploads/";

    // Determine which file is being uploaded
    $file = $_FILES['file']['name'];

    // Move uploaded file to the upload directory
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadDirectory . $file)) {
        // Insert or update the download table
        // Check if an entry already exists for the given phone and fileType
        $checkQuery = "SELECT id FROM download WHERE phone = ? AND healthcentre = ?";
        $checkStmt = mysqli_prepare($conn, $checkQuery);
        mysqli_stmt_bind_param($checkStmt, "ss", $phone, $healthcentre);
        mysqli_stmt_execute($checkStmt);
        $checkResult = mysqli_stmt_get_result($checkStmt);

        if ($checkResult->num_rows > 0) {
            // If an entry exists, update it
            $updateQuery = "UPDATE download SET $fileType = ?, upload_date = NOW() WHERE phone = ? AND healthcentre = ?";
            $updateStmt = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($updateStmt, "sss", $file, $phone, $healthcentre);
            mysqli_stmt_execute($updateStmt);
            mysqli_stmt_close($updateStmt);
        } else {
            // If no entry exists, insert a new one
            $insertQuery = "INSERT INTO download (fullname, phone, healthcentre, $fileType, confirmed, upload_date)
                            SELECT fullname, phone, healthcenter, ?, 0, NOW() FROM bookingform WHERE phone = ?";
            $insertStmt = mysqli_prepare($conn, $insertQuery);
            mysqli_stmt_bind_param($insertStmt, "ss", $file, $phone);
            mysqli_stmt_execute($insertStmt);
            mysqli_stmt_close($insertStmt);
        }
        
        echo "File uploaded successfully.";
    } else {
        echo "Error uploading file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Documents - MediConnect</title>
    <link rel="stylesheet" href="upload.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="admin.css">
    <style>
        .table-container {
            max-height: 400px;
            overflow-y: scroll;
            border: 1px solid #ddd;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .uploaded {
            background-color: #d4edda;
            color: white;
        }
        .upload-btn {
            margin: auto 10px;
            background-color: #78d6e7;
            color: black;
        }
        .confirm-btn {
            background-color:  #78d6e7;
            color: black;
        }
        .confirmed {
            background-color: #87ceeb;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <h1 class="logo">MediConnect</h1>
            <nav>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Dashboard</a></li>
                    <li><a href="#">Upload Documents</a></li>
                    <li><a href="#">Live Consult Client</a></li>
                    <li><a href="admin_settings.php">Setting</a></li>
                    <li><a href="dashboard.php?logout=<?php echo $user_id; ?>" class="delete-btn">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <p>Upload Clients Reports and Bills.</p>
        
        <div class="search-filter-container">
            <input type="text" id="search" placeholder="Search...">
            <button id="filterButton">Filter</button>
        </div>
        <div class="container">
            <!-- Form to upload report and bill -->
            <form id="uploadForm" method="post" enctype="multipart/form-data" style="display:none;">
                <input type="file" name="file" id="fileInput" accept="application/pdf,image/jpeg,image/png">
                <input type="hidden" name="uploadFormSubmitted" value="1">
                <input type="hidden" name="phone" id="phone">
                <input type="hidden" name="fileType" id="fileType">
            </form>
            <!-- Table to display client information -->
            <div class="table-container">
                <table id="clientsTable">
                    <thead>
                        <tr>
                            <th>Submission Date</th>
                            <th>Full Name</th>
                            <th>Phone Number</th>
                            <th>Health Centre</th>
                            <th>Upload Report</th>
                            <th>Upload Bill</th>
                            <th>Confirm</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>{$row['dt']}</td>";
                                echo "<td>{$row['fullname']}</td>";
                                echo "<td>{$row['phone']}</td>";
                                echo "<td>{$row['healthcenter']}</td>";
                                echo "<td><button class='upload-btn' id='uploadReport{$row['phone']}' onclick=\"setUploadData('{$row['phone']}', 'report')\">Upload Report</button></td>";
                                echo "<td><button class='upload-btn' id='uploadBill{$row['phone']}' onclick=\"setUploadData('{$row['phone']}', 'bill')\">Upload Bill</button></td>";
                                echo "<td><button class='confirm-btn' id='confirm{$row['phone']}' onclick=\"confirmUpload('{$row['phone']}')\">Confirm</button></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No records found.</td></tr>";
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <script src="upload.js"></script>
    </main>
</body>
</html>

