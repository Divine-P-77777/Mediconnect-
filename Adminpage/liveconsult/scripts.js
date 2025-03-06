document.addEventListener('DOMContentLoaded', function() {
    fetchConsultations();
    loadSettings();
});

function fetchConsultations() {
    fetch('fetch_consultations.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#consultTable tbody');
            tableBody.innerHTML = '';
            data.forEach(consult => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${consult.dt}</td>
                    <td>${consult.fullname}</td>
                    <td>${consult.consultationdate}</td>
                    <td>${consult.consultationtime}</td>
                    <td>${consult.dob}</td>
                    <td>${consult.gender}</td>
                    <td><button onclick="joinMeeting('${consult.meeting_link}', this)">Join</button></td>
                    <td><button onclick="shareLink('${consult.meeting_link}', '${consult.phone}', this)">Share</button></td>
                `;
                tableBody.appendChild(row);
            });
        });
}

function loadSettings() {
    fetch('settings.php')
        .then(response => response.json())
        .then(data => {
            if (data.meetingLink && data.mobileNumber) {
                window.meetingLink = data.meetingLink;
                window.mobileNumber = data.mobileNumber;
            }
        });
}

function joinMeeting(link, button) {
    if (!window.meetingLink) {
        alert("Set phone number and paste meeting link in the settings.");
        return;
    }

    window.open(window.meetingLink, '_blank');
    button.textContent = 'Joined';
    button.style.backgroundColor = 'green';
    button.disabled = true;
}

function shareLink(link, phone, button) {
    if (!window.meetingLink) {
        alert("Set phone number and paste meeting link in the settings.");
        return;
    }

    const joinButton = button.closest('tr').querySelector('button[disabled]');
    if (!joinButton) {
        alert("First join the meeting.");
        return;
    }

    const message = `Kindly join 10 minutes before the scheduled time. Your consultation meeting link: ${window.meetingLink}`;
    const whatsappUrl = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
}

function openSettings() {
    document.getElementById('settingsPopup').style.display = 'block';
}

function closeSettings() {
    document.getElementById('settingsPopup').style.display = 'none';
}

document.getElementById('settingsForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch('settings.php', {
        method: 'POST',
        body: formData
    }).then(response => response.text())
      .then(data => {
          alert(data);
          loadSettings();
          closeSettings();
      });
});


document.getElementById('mobileNumber').addEventListener('focus', function() {
    if (!this.value.startsWith('+91')) {
        this.value = '+91' + this.value;
    }
});

function validatePhoneNumber() {
    const phoneInput = document.getElementById('mobileNumber').value;
    const phonePattern = /^\+91[0-9]{10}$/;
    if (!phonePattern.test(phoneInput)) {
        alert('Please enter a valid 10-digit phone number after +91');
        return false;
    }
    return true;
}