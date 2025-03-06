document.getElementById('fileInput').addEventListener('change', function() {
    document.getElementById('uploadForm').submit();
});

function setUploadData(phone, fileType) {
    document.getElementById('phone').value = phone;
    document.getElementById('fileType').value = fileType;
    document.getElementById('fileInput').click();
}

function confirmUpload(phone) {
    fetch('fetch_upload_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ phone: phone })
    })
    .then(response => response.json())
    .then(data => {
        if (data.report === null && data.bill === null) {
            alert("Either upload report or upload bill.");
        } else if (data.report === null || data.bill === null) {
            if (confirm("Are you sure to confirm?")) {
                markConfirmed(phone);
            }
        } else {
            markConfirmed(phone);
        }
    });
}

function markConfirmed(phone) {
    fetch('confirm_upload.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ phone: phone })
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        const confirmButton = document.getElementById(`confirm${phone}`);
        confirmButton.classList.add('confirmed');
        confirmButton.textContent = "Confirmed";
    });
}

function updateUploadButton(phone, fileType) {
    const buttonId = fileType === 'report' ? `uploadReport${phone}` : `uploadBill${phone}`;
    const button = document.getElementById(buttonId);
    button.classList.add('uploaded');
    button.textContent = `Uploaded ${fileType.charAt(0).toUpperCase() + fileType.slice(1)}`;
}
