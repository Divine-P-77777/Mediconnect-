document.addEventListener('DOMContentLoaded', function() {
    // Array of health centers with their pin codes and payment requirements
    const healthCenters = [
        { name: 'Kiran Jyoti', pinCode: '781040', paymentRequired: true },
        { name: 'Apollo Clinic Jyotikuchi', pinCode: '781040', paymentRequired: false },
        { name: 'Malati Sen Memorial Hospital', pinCode: '781037', paymentRequired: true },
        { name: 'Healthcare Hospital', pinCode: '700001', paymentRequired: true },
        { name: 'City Clinic', pinCode: '700032', paymentRequired: false },
        { name: 'Sunshine Hospital', pinCode: '500032', paymentRequired: true },
        { name: 'Green Leaf Medical Center', pinCode: '110001', paymentRequired: false },
        { name: 'Evergreen Clinic', pinCode: '400001', paymentRequired: true },
        { name: 'Rainbow Children\'s Hospital', pinCode: '500082', paymentRequired: false },
        { name: 'Grace Hospital', pinCode: '600001', paymentRequired: true },
        { name: 'Sanjeevani Hospital', pinCode: '780001', paymentRequired: true },
        { name: 'Lifeline Medical Center', pinCode: '780002', paymentRequired: false },
        { name: 'Samarth Hospital', pinCode: '780003', paymentRequired: true },
        { name: 'Pratham Clinic', pinCode: '780004', paymentRequired: false },
        { name: 'Surya Hospital', pinCode: '780005', paymentRequired: true },
        { name: 'Arogya Nursing Home', pinCode: '780006', paymentRequired: false },
        { name: 'Seva Hospital', pinCode: '780007', paymentRequired: true },
        { name: 'Sai Health Center', pinCode: '780008', paymentRequired: false },
        { name: 'Shivam Medical Services', pinCode: '780009', paymentRequired: true },
        { name: 'Jeevan Jyoti Hospital', pinCode: '780010', paymentRequired: false }
    ];

    const healthCenterSelect = document.getElementById('healthCenter');
    const pinCodeInput = document.getElementById('pinCode');
    const paymentButton = document.getElementById('paymentButton');

    // Function to populate health centers based on selected pin code
    function populateHealthCenters() {
        const selectedPin = pinCodeInput.value.trim();
        // Clear current options
        healthCenterSelect.innerHTML = '';

        let centersFound = false;

        // Filter and populate options based on pin code
        healthCenters.forEach(center => {
            if (center.pinCode === selectedPin) {
                centersFound = true;
                const option = document.createElement('option');
                option.textContent = center.name;
                option.value = center.name;
                healthCenterSelect.appendChild(option);
            }
        });

        if (!centersFound) {
            const option = document.createElement('option');
            option.textContent = 'No health centres available for this pin code';
            option.value = '';
            healthCenterSelect.appendChild(option);
        }

        updateAppointmentDetails();
    }

    // Event listener for pin code change
    pinCodeInput.addEventListener('input', populateHealthCenters);

    // Function to update appointment details based on health center selection
    function updateAppointmentDetails() {
        const selectedCenter = healthCenters.find(center => center.name === healthCenterSelect.value);
        if (selectedCenter && selectedCenter.paymentRequired) {
            paymentButton.style.display = 'block';
        } else {
            paymentButton.style.display = 'none';
        }
    }

    // Event listener for health center change
    healthCenterSelect.addEventListener('change', updateAppointmentDetails);

    // Function to handle payment button click
    window.handlePayment = function() {
        alert('Redirecting to payment gateway...');
    };

    // Function to get today's date in the format YYYY-MM-DD
    function getTodayDate() {
        const today = new Date();
        let month = String(today.getMonth() + 1).padStart(2, '0');
        let day = String(today.getDate()).padStart(2, '0');
        const year = today.getFullYear();

        return `${year}-${month}-${day}`;
    }

    // Initialize appointment date input element
    const appointmentDateInput = document.getElementById('date');
    // Set min attribute to today's date in the appointment date input
    appointmentDateInput.setAttribute('min', getTodayDate());

    // Event listener to format phone number
    document.getElementById('phone').addEventListener('focus', function() {
        if (!this.value.startsWith('+91')) {
            this.value = '+91' + this.value;
        }
    });

    // Function to validate phone number
    window.validatePhoneNumber = function() {
        const phoneInput = document.getElementById('phone').value;
        const phonePattern = /^\+91[0-9]{10}$/;
        if (!phonePattern.test(phoneInput)) {
            alert('Please enter a valid 10-digit phone number after +91');
            return false;
        }
        return true;
    };
});
