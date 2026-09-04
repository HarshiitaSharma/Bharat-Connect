document.addEventListener("DOMContentLoaded", () => {
    
    const requestForm = document.getElementById("requestForm");
    
    if (requestForm) {
        handleRequestForm();
    }

    const requestsList = document.getElementById("requestsList");
    if (requestsList) {
        loadDashboardData();
    }

    const registerForm = document.getElementById("registerForm");
    if (registerForm) {
        handleRegistrationForm();
    }

});

/**
 * Handles logic for the New Request form (new_request.html)
 */
function handleRequestForm(requestForm) {
    const categorySelect = document.getElementById("category");
    const certificateFields = document.getElementById("certificateFields");

    const toggleCertificateFields = () => {
        if (categorySelect.value === "Birth/Death certificate") {
            certificateFields.style.display = "flex";
            document.getElementById("personAadhar").required = true;
            document.getElementById("relativeAadhar").required = true;
        } else {
            certificateFields.style.display = "none";
            document.getElementById("personAadhar").required = false;
            document.getElementById("relativeAadhar").required = false;
        }
    };
    
    toggleCertificateFields();
    categorySelect.addEventListener("change", toggleCertificateFields);

    requestForm.addEventListener("submit", (e) => {
        e.preventDefault(); 

        const submitBtn = document.getElementById("submitBtn");
        const spinner = document.getElementById("formSpinner");
        const statusMessage = document.getElementById("statusMessage");

        submitBtn.disabled = true;
        spinner.style.display = "inline-block";
        statusMessage.style.display = "none";

        const formData = new FormData(requestForm);

        // --- This is now LIVE ---
        fetch("php/submit_request.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                statusMessage.textContent = data.message;
                statusMessage.className = "message success";
                requestForm.reset();
                toggleCertificateFields();
            } else {
                statusMessage.textContent = data.message || "An unknown error occurred.";
                statusMessage.className = "message error";
            }
        })
        .catch(error => {
            statusMessage.textContent = "Error: Could not connect to the server.";
            statusMessage.className = "message error";
        })
        .finally(() => {
            submitBtn.disabled = false;
            spinner.style.display = "none";
        });
    });
}

function loadDashboardData(requestsList) {
    const statsChartCanvas = document.getElementById("statsChart");

    fetch("php/api/get_requests.php")
        .then(response => response.json())
        .then(data => {
            if (data && data.requests && data.stats) {
                renderRequestList(data.requests, requestsList);
                renderStatsChart(data.stats, statsChartCanvas);
            } else {
                requestsList.innerHTML = '<p style="text-align:center;color:#777;">Could not load data.</p>';
            }
        })
        .catch(error => {
            console.error("Error loading dashboard data:", error);
            requestsList.innerHTML = '<p style="text-align:center;color:#777;">Error connecting to server.</p>';
        });
}

function renderRequestList(requests, listElement) {
    listElement.innerHTML = ""; 
    
    if (requests.length === 0) {
        listElement.innerHTML = '<p style="text-align:center;color:#777;">No requests found. Submit a new one to get started!</p>';
        return;
    }
    
    requests.forEach(req => {
        const item = document.createElement("div");
        item.className = "request-item";
        
        const statusClass = `status-${req.status.replace(' ', '')}`;
        
        item.innerHTML = `
            <div class="request-item-header">
                <h3>${req.category}</h3>
                <span class="status ${statusClass}">${req.status}</span>
            </div>
            <div><strong>Location:</strong> ${req.location}</div>
            <div><strong>Submitted:</strong> ${req.date}</div>
        `;
        listElement.appendChild(item);
    });
}
function renderStatsChart(stats, canvasElement) {
    if (!canvasElement) return; 

    const data = {
        labels: ["Submitted", "In Progress", "Resolved"],
        datasets: [{
            data: [
                stats.Submitted || 0,
                stats.InProgress || 0,
                stats.Resolved || 0
            ],
            backgroundColor: [
                '#f39c12', 
                '#3498db',
                '#2ecc71'  
            ],
            borderWidth: 2
        }]
    };

    new Chart(canvasElement, {
        type: 'doughnut',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });
}

function handleRegistrationForm() {
    const registerForm = document.getElementById("registerForm");
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirm_password");

    registerForm.addEventListener("submit", (e) => {
        if (password.value !== confirmPassword.value) {
            e.preventDefault(); // Stop the form from submitting
            
            alert("Error: Passwords do not match.");
            
            confirmPassword.style.borderColor = "#e74c3c";
            password.style.borderColor = "#e74c3c";
        }
    });
}