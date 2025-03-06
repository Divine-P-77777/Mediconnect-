document.addEventListener('DOMContentLoaded', function() {
    // Function to populate time slots
    function populateTimeSlots() {
        const timeSelect = document.getElementById('time');
        const defaultOption = document.createElement('option');
        defaultOption.textContent = 'Select Time';
        defaultOption.value = '';
        timeSelect.appendChild(defaultOption);

        // Generate time slots (change as per your requirement)
        const timeSlots = [
            '09:00 AM',
            '10:00 AM',
            '11:00 AM',
            '12:00 PM',
            '01:00 PM',
            '02:00 PM',
            '03:00 PM',
            '04:00 PM',
            '05:00 PM',
            '06:00 PM'
        ];

        // Populate select options
        timeSlots.forEach(slot => {
            const option = document.createElement('option');
            option.textContent = slot;
            option.value = slot;
            timeSelect.appendChild(option);
        });

        // Set default selection to 'Select Time'
        timeSelect.value = '';
    }

    // Call function to populate time slots
    populateTimeSlots();

    // Event listener for form submission
    const form = document.getElementById('consultationForm');
    form.addEventListener('submit', function(event) {
        const confirmed = confirm('Are you sure you want to submit the form?');
        if (!confirmed) {
            event.preventDefault();
        }
    });
});


document.getElementById('phone').addEventListener('focus', function() {
    if (!this.value.startsWith('+91')) {
        this.value = '+91' + this.value;
    }
});

function validatePhoneNumber() {
    const phoneInput = document.getElementById('phone').value;
    const phonePattern = /^\+91[0-9]{10}$/;
    if (!phonePattern.test(phoneInput)) {
        alert('Please enter a valid 10-digit phone number after +91');
        return false;
    }
    return true;
}