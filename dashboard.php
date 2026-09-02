<?php
    // Module 4: Auth Check
    // This file is now a .php file. This line makes sure only
    // logged-in users can see this page.
    include('php/includes/auth_check.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citizen Connect - Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --primary-color: #0056b3;
            --secondary-color: #f4f4f4;
            --accent-color: #ff6600;
            --text-color: #333;
            --light-text-color: #fff;
            --border-color: #ddd;
            --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --card-radius: 12px;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            background-color: var(--secondary-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        .site-header {
            background-color: var(--primary-color);
            color: var(--light-text-color);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .brand {
            font-size: 1.5rem;
            font-weight: bold;
        }

        nav button,
        .form-actions button,
        nav a { /* Added nav a for logout button */
            background-color: var(--accent-color);
            color: var(--light-text-color);
            border: none;
            padding: 0.6rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            transition: background-color 0.2s ease, transform 0.2s ease;
            text-decoration: none; /* For <a> tag */
            display: inline-block; /* For <a> tag */
        }
        
        nav a {
             background-color: #7f8c8d; /* Grey for logout */
        }
        nav a:hover {
            background-color: #6c7a7b;
            transform: translateY(-2px);
        }

        nav button:hover,
        .form-actions button:hover {
            background-color: #e65c00; /* Darker orange */
            transform: translateY(-2px);
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            padding: 1.5rem;
            gap: 1.5rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .left {
            flex: 1;
            min-width: 350px;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .right {
            flex: 2;
            min-width: 400px;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .card {
            background-color: #fff;
            border-radius: var(--card-radius);
            box-shadow: var(--card-shadow);
            padding: 1.5rem;
            border: 1px solid var(--border-color);
        }

        .card h2 {
            margin-top: 0;
            margin-bottom: 1.25rem;
            color: var(--primary-color);
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 0.5rem;
        }

        /* Service Tiles */
        .service-tiles {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .tile {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.9rem;
            background-color: #fafafa;
        }

        .tile:hover {
            border-color: var(--primary-color);
            background-color: #eef5ff;
            color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.05);
        }

        .tile.selected {
            background-color: var(--primary-color);
            color: var(--light-text-color);
            border-color: var(--primary-color);
            font-weight: 600;
        }

        .tile span {
            font-size: 1.8rem;
            display: block;
            margin-bottom: 0.5rem;
        }

        /* Form styling */
        #requestForm {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        #requestForm label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #555;
            margin-bottom: -4px;
        }

        #requestForm input,
        #requestForm select,
        #requestForm textarea,
        .filters select,
        .filters input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            box-sizing: border-box; 
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        #requestForm input:focus,
        #requestForm select:focus,
        #requestForm textarea:focus,
        .filters select:focus,
        .filters input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 86, 179, 0.2);
        }

        /* --- Conditional Fields (Aadhar) --- */
        .conditional-fields {
            display: none; /* Hidden by default */
            flex-direction: column;
            gap: 0.8rem;
            background-color: #f9f9f9;
            padding: 1rem;
            border-radius: 8px;
            border: 1px dashed var(--border-color);
        }
        
        .form-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        #resetBtn {
            background-color: #7f8c8d; /* Grey */
        }

        #resetBtn:hover {
            background-color: #6c7a7b;
        }

        /* Filters */
        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .filters select, .filters input {
            flex-grow: 1;
        }
        
        .filters input {
            min-width: 200px;
        }

        /* Requests List */
        .requests-list {
            max-height: 600px;
            min-height: 100px; /* So it doesn't collapse */
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            position: relative; /* For loading spinner */
        }
        
        /* --- Loading Spinner --- */
        .loading-spinner {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--primary-color);
            font-size: 1.1rem;
            border-radius: var(--card-radius);
            z-index: 10;
        }

        .request-item {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1rem;
            transition: box-shadow 0.2s ease;
            cursor: pointer;
        }

        .request-item:hover {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.07);
        }

        .request-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .request-item-header h3 {
            margin: 0;
            font-size: 1.1rem;
            color: var(--primary-color);
        }

        .status {
            padding: 0.25rem 0.6rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-Submitted {
            background-color: #fffbe6;
            color: #f39c12; /* Yellow */
        }
        .status-InProgress {
            background-color: #eef5ff;
            color: #3498db; /* Blue */
        }
        .status-Resolved {
            background-color: #eafaf1;
            color: #2ecc71; /* Green */
        }
        
        .request-item-details {
            font-size: 0.9rem;
            color: #555;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .request-item-details strong {
            color: #333;
        }

        .request-item-description {
            display: none; 
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px dashed var(--border-color);
            font-size: 0.95rem;
            background-color: #fafafa;
            padding: 0.5rem;
            border-radius: 6px;
        }

        .request-item.expanded .request-item-description {
            display: block;
        }
        
        /* Status Message (for form) */
        #statusMessage {
            text-align: center;
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 500;
            display: none; /* Hidden by default */
        }

        #statusMessage.success {
            background-color: #eafaf1;
            color: #2ecc71;
            display: block;
        }
        
        #statusMessage.error {
            background-color: #fbeae8;
            color: #e74c3c;
            display: block;
        }

        /* Footer */
        .site-footer {
            text-align: center;
            padding: 1.5rem;
            margin-top: 1.5rem;
            background-color: #e9ecef;
            color: #6c757d;
            font-size: 0.9rem;
            border-top: 1px solid var(--border-color);
        }

        /* Responsive */
        @media (max-width: 900px) {
            .container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="brand">Bharat Connect</div>
        
        <!-- PHP injects the user's name -->
        <div id="currentUserInfo" style="color:#fff;font-size:13px;margin-left:12px">
            Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!
        </div>
        <nav style="display: flex; gap: 10px;">
            <button id="newRequestBtn">New Request</button>
            <a href="php/logout.php" id="logoutBtn">Logout</a>
        </nav>
    </header>

    <main class="container">

        <section class="left">

            <div class="card" id="formCard"> 
                <h2>Register a Complaint / Request</h2>

                <div id="serviceTiles" class="service-tiles">
                    <!-- JS will render service tiles here -->
                </div>

                <form id="requestForm">
                    <!-- Name is pre-filled from session, but hidden. 
                         We can show it if we want. Let's show it and make it readonly. -->
                    <label for="name">Name</label>
                    <input id="name" name="name" required placeholder="Enter your full name" 
                           value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" readonly />

                    <label for="phone">Phone / Contact</label>
                    <input id="phone" name="phone" placeholder="Enter your mobile number" />

                    <label for="category">Category</label>
                    <select id="category" name="category" required>
                        <option value="">-- Select --</option>
                        <option>Electric repair</option>
                        <option>Streetlight repair</option>
                        <option>Water services</option>
                        <option>Birth/Death certificate</option>
                        <option>Air/Noise pollution complaint</option>
                        <option>Road/Pothole repair</option>
                    </select>
                    
                    <!-- === CONDITIONAL AADHAR FIELDS === -->
                    <div id="certificateFields" class="conditional-fields">
                        <p style="margin-top:0; font-size: 0.9em; color: #555;">
                            Please provide Aadhar details for certificate processing.
                        </p>
                        <label for="personAadhar">Person's Aadhar Number</label>
                        <input id="personAadhar" name="person_aadhar" placeholder="Aadhar of person for certificate">
                        
                        <label for="relativeAadhar">Relative's Aadhar Number (e.g., son, wife)</label>
                        <input id="relativeAadhar" name="relative_aadhar" placeholder="Your Aadhar or a close relative's">
                    </div>
                    <!-- ================================ -->

                    <label for="location">Location / Address</label>
                    <input id="location" name="location" placeholder="e.g., Near City Park, Main Street" required />

                    <label for="region">Region</label>
                    <select id="region" name="region">
                        <option value="">Select region</option>
                        <option>North</option>
                        <option>South</option>
                        <option>East</option>
                        <option>West</option>
                        <option>Central</option>
                    </select>

                    <label for="areaCode">Area / PIN code</label>
                    <input id="areaCode" name="areaCode" placeholder="e.g. 560001" />

                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4" placeholder="Please provide details..."></textarea>

                    <div class="form-actions">
                        <button type="submit" id="submitBtn">Submit</button>
                        <button type="button" id="resetBtn">Reset</button>
                    </div>
                    <div id="statusMessage"></div> 
                </form>
            </div>

            <div class="card">
                <h2>Filters</h2>
                <div class="filters">
                    <select id="filterCategory">
                        <option value="">All categories</option>
                        <option>Electric repair</option>
                        <option>Streetlight repair</option>
                        <option>Water services</option>
                        <option>Birth/Death certificate</option>
                        <option>Air/Noise pollution complaint</option>
                        <option>Road/Pothole repair</option>
                    </select>
                    <select id="filterStatus">
                        <option value="">All statuses</option>
                        <option>Submitted</option>
                        <option>In Progress</option>
                        <option>Resolved</option>
                    </select>
                    <input id="searchQuery" placeholder="Search by location..." />
                </div>
            </div>

        </section>

        <section class="right">
            <div class="card">
                <h2>Requests</h2>
                <div id="requestsList" class="requests-list">
                    <!-- JS will render a loading spinner here -->
                </div>
            </div>

            <div class="card" style="height:260px;">
                <h2>Statistics</h2>
                <canvas id="statsChart" width="400" height="220"></canvas>
            </div>
        </section>

    </main>

    <footer class="site-footer">
        <small>Citizen Connect — Local Government Portal prototype</small>
    </footer>

    <!-- =================================================================== -->
    <!-- === JAVASCRIPT CONNECTED TO PHP API (MODULE 2 & 3) === -->
    <!-- =================================================================== -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // === GLOBAL VARIABLES ===
            // This array will hold data from the server
            let requestsDB = []; 
            let statsChart; // To hold the chart object
            
            // === DOM ELEMENTS ===
            const serviceTilesContainer = document.getElementById('serviceTiles');
            const categorySelect = document.getElementById('category');
            const requestForm = document.getElementById('requestForm');
            const submitBtn = document.getElementById('submitBtn');
            const resetBtn = document.getElementById('resetBtn');
            const statusMessage = document.getElementById('statusMessage');
            const requestsList = document.getElementById('requestsList');
            const filterCategory = document.getElementById('filterCategory');
            const filterStatus = document.getElementById('filterStatus');
            const searchQuery = document.getElementById('searchQuery');
            const ctx = document.getElementById('statsChart').getContext('2d');
            const certificateFields = document.getElementById('certificateFields');

            // === SERVICE TILES ===
            const services = [
                { name: "Electric", icon: "⚡" },
                { name: "Streetlight", icon: "💡" },
                { name: "Water", icon: "💧" },
                { name: "Certificate", icon: "📜" },
                { name: "Pollution", icon: "💨" },
                { name: "Road/Pothole", icon: "🚧" }
            ];
            const categoryMap = {
                "Electric": "Electric repair",
                "Streetlight": "Streetlight repair",
                "Water": "Water services",
                "Certificate": "Birth/Death certificate",
                "Pollution": "Air/Noise pollution complaint",
                "Road/Pothole": "Road/Pothole repair"
            };

            services.forEach(service => {
                const tile = document.createElement('div');
                tile.className = 'tile';
                tile.dataset.category = categoryMap[service.name];
                tile.innerHTML = `<span>${service.icon}</span>${service.name}`;
                serviceTilesContainer.appendChild(tile);
                tile.addEventListener('click', () => {
                    categorySelect.value = tile.dataset.category;
                    updateTileSelection();
                    toggleCertificateFields(); // Also check fields on tile click
                });
            });

            function updateTileSelection() {
                const selectedValue = categorySelect.value;
                document.querySelectorAll('.tile').forEach(tile => {
                    tile.classList.toggle('selected', tile.dataset.category === selectedValue);
                });
            }
            categorySelect.addEventListener('change', () => {
                updateTileSelection();
                toggleCertificateFields(); // Check fields on dropdown change
            });

            // === CONDITIONAL FORM LOGIC ===
            function toggleCertificateFields() {
                const isCertificate = categorySelect.value === 'Birth/Death certificate';
                certificateFields.style.display = isCertificate ? 'flex' : 'none';
                document.getElementById('personAadhar').required = isCertificate;
                document.getElementById('relativeAadhar').required = isCertificate;
            }

            // === FORM HANDLING (CONNECTED TO API) ===
            requestForm.addEventListener('submit', (e) => {
                e.preventDefault(); 
                
                showStatusMessage("Submitting...", "success"); // Use success as a "pending" style
                submitBtn.disabled = true;

                const formData = new FormData(requestForm);

                // --- API IMPLEMENTATION (Module 3) ---
                fetch('php/submit_request.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showStatusMessage(data.message, "success");
                        requestForm.reset();
                        updateTileSelection();
                        toggleCertificateFields();
                        // --- RELOAD DATA ---
                        // After successful submit, reload all dashboard data
                        loadDashboardData(); 
                    } else {
                        showStatusMessage(data.message, "error");
                    }
                })
                .catch(error => {
                    console.error("Fetch Error:", error);
                    showStatusMessage("A network error occurred.", "error");
                })
                .finally(() => {
                    submitBtn.disabled = false;
                });
            });
            
            resetBtn.addEventListener('click', () => {
                requestForm.reset();
                statusMessage.style.display = 'none';
                updateTileSelection();
                toggleCertificateFields();
            });

            function showStatusMessage(message, type) {
                statusMessage.textContent = message;
                statusMessage.className = type; // 'success' or 'error'
                statusMessage.style.display = 'block';
                
                // Hide message after 3 seconds, unless it's a pending message
                if (message !== "Submitting...") {
                    setTimeout(() => {
                        statusMessage.style.display = 'none';
                        statusMessage.className = '';
                    }, 3000);
                }
            }

            // === REQUEST LIST & FILTERING ===
            function renderRequests() {
                const category = filterCategory.value;
                const status = filterStatus.value;
                const search = searchQuery.value.toLowerCase();
                
                // Apply filters to the data we have in requestsDB
                const filtered = requestsDB.filter(req => {
                    const matchesCategory = !category || req.category === category;
                    const matchesStatus = !status || req.status === status;
                    const matchesSearch = !search || 
                                          req.location.toLowerCase().includes(search) ||
                                          (req.description && req.description.toLowerCase().includes(search)) ||
                                          (req.areaCode && req.areaCode.toLowerCase().includes(search));
                    return matchesCategory && matchesStatus && matchesSearch;
                });

                requestsList.innerHTML = ''; // Clear existing list
                
                if (filtered.length === 0) {
                    requestsList.innerHTML = '<p style="text-align:center;color:#777;">No requests found matching your criteria.</p>';
                    return;
                }
                
                filtered.forEach(req => {
                    const item = document.createElement('div');
                    item.className = 'request-item';
                    item.innerHTML = `
                        <div class="request-item-header">
                            <h3>${req.category}</h3>
                            <span class="status status-${req.status.replace(' ', '')}">${req.status}</span>
                        </div>
                        <div class="request-item-details">
                            <!-- Admin/Citizen view logic can be added here -->
                            <!-- For this prototype, we show all data -->
                            <div><strong>Name:</strong> ${req.user_name || req.name}</div>
                            <div><strong>Location:</strong> ${req.location} (${req.region})</div>
                            <div><strong>Date:</strong> ${req.date || req.created_at}</div>
                        </div>
                        <div class="request-item-description">
                            <strong>Details:</strong> ${req.description || 'No details provided.'}
                        </div>
                    `;
                    item.addEventListener('click', () => {
                        item.classList.toggle('expanded');
                    });
                    requestsList.appendChild(item);
                });
            }

            // Add event listeners for filters
            filterCategory.addEventListener('change', renderRequests);
            filterStatus.addEventListener('change', renderRequests);
            searchQuery.addEventListener('input', renderRequests);

            // === CHART ===
            function updateChart(stats) {
                const counts = [
                    stats.Submitted || 0,
                    stats.InProgress || 0,
                    stats.Resolved || 0
                ];

                if (statsChart) {
                    statsChart.data.datasets[0].data = counts;
                    statsChart.update();
                } else {
                    statsChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ["Submitted", "In Progress", "Resolved"],
                            datasets: [{
                                label: 'Request Status',
                                data: counts,
                                backgroundColor: ['#f39c12', '#3498db', '#2ecc71'],
                                borderWidth: 2,
                                borderColor: '#fff'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'right' }
                            }
                        }
                    });
                }
            }
            
            // === INITIALIZATION (CONNECTED TO API) ===
            function showLoadingSpinner() {
                requestsList.innerHTML = '<div class="loading-spinner">Loading...</div>';
            }
            
            function loadDashboardData() {
                showLoadingSpinner();
                
                // --- API IMPLEMENTATION (Module 3 / 4) ---
                // This one fetch call gets all data for the dashboard
                fetch('php/api/get_requests.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            throw new Error(data.error);
                        }
                        
                        // 1. Store the data from server
                        requestsDB = data.requests || [];
                        
                        // 2. Render the list with the new data
                        renderRequests();
                        
                        // 3. Update the chart with stats from server
                        updateChart(data.stats || {}); 
                    
                    })
                    .catch(error => {
                        console.error("Failed to load dashboard data:", error);
                        requestsList.innerHTML = `<p style="text-align:center;color:#e74c3c;">Error: ${error.message}</p>`;
                    });
            }

            // "New Request" button scrolls to form
            document.getElementById('newRequestBtn').addEventListener('click', () => {
                document.getElementById('formCard').scrollIntoView({ behavior: 'smooth' });
                document.getElementById('name').focus(); 
            });
            
            // --- Initial Page Load ---
            toggleCertificateFields(); // Run once to set up form
            loadDashboardData();       // Load all data from server

        });
    </script>
</body>
</html>