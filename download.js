document.addEventListener('DOMContentLoaded', function() {
    const downloadForm = document.getElementById('downloadForm');
    const resultsTable = document.getElementById('resultsTable');

    downloadForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const fullname = document.getElementById('fullname').value;
        const phone = document.getElementById('phone').value;

        fetch('download.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `fullname=${encodeURIComponent(fullname)}&phone=${encodeURIComponent(phone)}`
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.text(); // Use text() instead of json() to handle unexpected HTML responses
        })
        .then(data => {
            try {
                const jsonData = JSON.parse(data); // Attempt to parse JSON
                displayResults(jsonData);
            } catch (error) {
                console.error('Error parsing JSON:', error);
                console.log('Response data:', data);
                resultsTable.innerHTML = `<p>Error processing request. See console for details.</p>`;
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            resultsTable.innerHTML = `<p>Error fetching data. See console for details.</p>`;
        });
    });

    function displayResults(data) {
        resultsTable.innerHTML = '';

        if (data.error) {
            resultsTable.innerHTML = `<p>${data.error}</p>`;
            return;
        }

        if (data.length === 0) {
            resultsTable.innerHTML = '<p>No Documents Yet</p>';
            return;
        }

        const table = document.createElement('table');
        const thead = document.createElement('thead');
        const tbody = document.createElement('tbody');

        const headers = ['Date', 'Health Centre', 'Report', 'Bill'];
        const headerRow = document.createElement('tr');
        headers.forEach(headerText => {
            const th = document.createElement('th');
            th.textContent = headerText;
            headerRow.appendChild(th);
        });
        thead.appendChild(headerRow);
        table.appendChild(thead);

        data.forEach(item => {
            const row = document.createElement('tr');
            ['upload_date', 'healthcentre', 'report', 'bill'].forEach(key => {
                const cell = document.createElement('td');
                if (key === 'report' || key === 'bill') {
                    if (item[key]) {
                        const downloadBtn = document.createElement('a');
                        downloadBtn.textContent = 'Download';
                        downloadBtn.classList.add('download-btn');
                        // Adjust the path to point to ADMINpage/uploads
                        downloadBtn.href = 'Adminpage/uploads/' + item[key];
                        downloadBtn.setAttribute('download', item[key]); // Specify filename for download
                        cell.appendChild(downloadBtn);
                    } else {
                        cell.textContent = 'No document';
                        cell.classList.add('no-document');
                    }
                } else {
                    cell.textContent = item[key];
                }
                row.appendChild(cell);
            });
            tbody.appendChild(row);
        });
        
        table.appendChild(tbody);
        resultsTable.appendChild(table);
        
        // Log all download URLs to the console
        document.querySelectorAll('.download-btn').forEach(btn => {
            console.log('Download URL:', btn.href);
        });
    }
});
