document.addEventListener('DOMContentLoaded', () => {
    const approveButtons = document.querySelectorAll('.approve');

    approveButtons.forEach(button => {
        button.addEventListener('click', () => {
            const email = button.getAttribute('data-email');
            const appointmentTime = button.getAttribute('data-appointmenttime');
            const appointmentDate = button.getAttribute('data-appointmentdate');

            if (confirm(`Are you sure to approve the booking for ${email}?`)) {
                // Send request to handle_approval.php
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
