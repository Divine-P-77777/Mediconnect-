<?php
session_start();
if (!isset($_SESSION['healthcentre'])) {
    header("Location: login.php");
    exit();
}

include 'db.php'; // Ensure this file establishes a connection to the database

$user_id = $_SESSION['user_id'];
$healthcentre = $_SESSION['healthcentre'];

if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:login.php');
    exit();
}

// Prepare SQL query with a WHERE clause to filter by healthcentre
$query = "SELECT * FROM bookingform WHERE healthcenter = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $healthcentre);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediConnect Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
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
                    <div class="bar"></div>
                </div>
                <ul>
                    <li><a href="adminhomepage.php">Home</a></li>
                    <li><a href="#" class="active">Dashboard</a></li>
                    <li><a href="upload_file.php">Upload Documents</a></li>
                    <li><a href="Adminpage/liveconsult/login.php">Live Consult Clients</a></li>
                    <li><a href="aboutus.php">About Us</a></li>
                </ul>
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
    <main>
        

        <div class="search-filter-container">
            <input type="text" id="search" placeholder="Search...">
            <button id="filterButton">Filter</button>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Submission Date</th>
                        <th>Full Name</th>
                        <th>DOB</th>
                        <th>Purpose Of Visit</th>
                        <th>View Details</th>
                        <th>Appointment Time</th>
                        <th>Appointment Date</th>
                        <th>Approval</th>
                    </tr>
                </thead>
                <tbody id="bookingTable">
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['dt']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fullname']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['dob']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['purposeofvisit']) . "</td>";
                        echo "<td><button class='view-details' data-fullname='" . htmlspecialchars($row['fullname']) . "' data-dob='" . htmlspecialchars($row['dob']) . "' data-gender='" . htmlspecialchars($row['gender']) . "' data-phone='" . htmlspecialchars($row['phone']) . "' data-purpose='" . htmlspecialchars($row['purposeofvisit']) . "' data-appointmenttime='" . htmlspecialchars($row['appointmenttime']) . "' data-appointmentdate='" . htmlspecialchars($row['appointmentdate']) . "'>View </button></td>";
                        echo "<td>" . htmlspecialchars($row['appointmenttime']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['appointmentdate']) . "</td>";
                        echo "<td><button class='approve' >Approve</button></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Filter Modal -->
        <div id="filterModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Apply Filters</h2>
                <form id="filterForm">
                    <label for="filterPurpose">Purpose of Visit:</label>
                    <input type="text" id="filterPurpose" name="filterPurpose"><br><br>
                    <label for="filterAppointmentTime">Appointment Time:</label>
                    <input type="text" id="filterAppointmentTime" name="filterAppointmentTime"><br><br>
                    <label for="filterAppointmentDate">Appointment Date:</label>
                    <input type="text" id="filterAppointmentDate" name="filterAppointmentDate"><br><br>
                    <label for="filterSubmissionDate">Submission Date:</label>
                    <input type="text" id="filterSubmissionDate" name="filterSubmissionDate"><br><br>
                    <button type="button" id="applyFilters">Apply Filters</button>
                    <button type="button" id="clearFilters">Clear All</button>
                </form>
            </div>
        </div>

        <!-- Detail Modal -->
        <div id="detailModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div id="clientDetails">
                    <!-- Client details will be displayed here -->
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const approveButtons = document.querySelectorAll('.approve');

            approveButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const email = button.getAttribute('data-email');
                    const appointmentTime = button.getAttribute('data-appointmenttime');
                    const appointmentDate = button.getAttribute('data-appointmentdate');

                    if (confirm(`Are you sure to approve the booking for ${email}?`)) {
                        // AJAX request or form submission to mark approval in the database
                        fetch('handle_approval.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                email: email,
                                appointmentTime: appointmentTime,
                                appointmentDate: appointmentDate
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(`Booking for ${email} approved successfully.`);

                                // Send email to user
                                fetch('send_email.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify({
                                        email: email,
                                        subject: "Slot Booking Approved",
                                        message: `Dear User,\n\nYour slot booking for ${appointmentDate} at ${appointmentTime} has been approved. Please be available at least 20 minutes before your appointment time.\n\nRegards,\nMediConnect Team`
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        console.log(`Email sent to ${email} upon approval.`);
                                    } else {
                                        console.error(`Failed to send email to ${email} upon approval.`);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                });
                            } else {
                                alert(`Failed to approve booking for ${email}.`);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                    }
                });
            });

            const filterButton = document.getElementById('filterButton');
            const filterModal = document.getElementById('filterModal');
            const closeFilterModal = filterModal.querySelector('.close');
            const applyFilters = document.getElementById('applyFilters');
            const clearFilters = document.getElementById('clearFilters');
            const searchInput = document.getElementById('search');
            const tableBody = document.getElementById('bookingTable');
            const tableRows = tableBody.getElementsByTagName('tr');

            filterButton.addEventListener('click', () => {
                filterModal.style.display = 'block';
            });

            closeFilterModal.addEventListener('click', () => {
                filterModal.style.display = 'none';
            });

            window.onclick = function(event) {
                if (event.target == filterModal) {
                    filterModal.style.display = 'none';
                }
            };

            // Search Functionality
            searchInput.addEventListener('keyup', () => {
                const filter = searchInput.value.toLowerCase();
                Array.from(tableRows).forEach(row => {
                    const cells = row.getElementsByTagName('td');
                    let rowMatch = false;
                    Array.from(cells).forEach(cell => {
                        if (cell.innerText.toLowerCase().indexOf(filter) > -1) {
                            rowMatch = true;
                        }
                    });
                    row.style.display = rowMatch ? '' : 'none';
                });
            });

            // Apply Filters Functionality
            applyFilters.addEventListener('click', () => {
                const filterPurpose = document.getElementById('filterPurpose').value.toLowerCase();
                const filterAppointmentTime = document.getElementById('filterAppointmentTime').value.toLowerCase();
                const filterAppointmentDate = document.getElementById('filterAppointmentDate').value.toLowerCase();
                const filterSubmissionDate = document.getElementById('filterSubmissionDate').value.toLowerCase();

                Array.from(tableRows).forEach(row => {
                    const purpose = row.getElementsByTagName('td')[3].innerText.toLowerCase();
                    const appointmentTime = row.getElementsByTagName('td')[5].innerText.toLowerCase();
                    const appointmentDate = row.getElementsByTagName('td')[6].innerText.toLowerCase();
                    const submissionDate = row.getElementsByTagName('td')[0].innerText.toLowerCase();

                    let showRow = true;

                    if (filterPurpose && !purpose.includes(filterPurpose)) {
                        showRow = false;
                    }
                    if (filterAppointmentTime && !appointmentTime.includes(filterAppointmentTime)) {
                        showRow = false;
                    }
                    if (filterAppointmentDate && !appointmentDate.includes(filterAppointmentDate)) {
                        showRow = false;
                    }
                    if (filterSubmissionDate && !submissionDate.includes(filterSubmissionDate)) {
                        showRow = false;
                    }

                    row.style.display = showRow ? '' : 'none';
                });

                filterModal.style.display = 'none';
            });

            // Clear Filters Functionality
            clearFilters.addEventListener('click', () => {
                document.getElementById('filterPurpose').value = '';
                document.getElementById('filterAppointmentTime').value = '';
                document.getElementById('filterAppointmentDate').value = '';
                document.getElementById('filterSubmissionDate').value = '';

                Array.from(tableRows).forEach(row => {
                    row.style.display = '';
                });

                filterModal.style.display = 'none';
            });

            // View Details Functionality
            const detailModal = document.getElementById('detailModal');
            const closeDetailModal = detailModal.querySelector('.close');
            const clientDetails = document.getElementById('clientDetails');

            tableBody.addEventListener('click', (event) => {
                if (event.target.classList.contains('view-details')) {
                    const fullname = event.target.getAttribute('data-fullname');
                    const dob = event.target.getAttribute('data-dob');
                    const gender = event.target.getAttribute('data-gender');
                    const phone = event.target.getAttribute('data-phone');
                    const purpose = event.target.getAttribute('data-purpose');
                    const appointmentTime = event.target.getAttribute('data-appointmenttime');
                    const appointmentDate = event.target.getAttribute('data-appointmentdate');

                    clientDetails.innerHTML = `
                        <p><strong>Full Name:</strong> ${fullname}</p>
                        <p><strong>Date of Birth:</strong> ${dob}</p>
                        <p><strong>Gender:</strong> ${gender}</p>
                        <p><strong>Phone:</strong> ${phone}</p>
                        <p><strong>Purpose of Visit:</strong> ${purpose}</p>
                        <p><strong>Appointment Time:</strong> ${appointmentTime}</p>
                        <p><strong>Appointment Date:</strong> ${appointmentDate}</p>
                    `;

                    detailModal.style.display = 'block';
                }
            });

            closeDetailModal.addEventListener('click', () => {
                detailModal.style.display = 'none';
            });

            window.onclick = function(event) {
                if (event.target == detailModal) {
                    detailModal.style.display = 'none';
                }
            };
        });

        function toggleProfilePopup() {
            var popup = document.getElementById("profilePopup");
            popup.classList.toggle("show");
        }
    </script>
</body>
</html>

<?php
// Close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
