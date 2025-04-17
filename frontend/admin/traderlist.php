<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header("Location: ../login.html"); // Redirect if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="traderlist.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <img src="../../resources/SRA_thumbnail.png" alt="Agricultural Logo">
        </div>
        
        <div class="nav-section">
            <div class="nav-group">DASHBOARD</div>
            
            <a href="#" class="nav-item active" id="tradersNav">
                <div class="nav-item-content">
                    <div class="nav-icon">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <span>Traders</span>
                </div>
                <div class="arrow-icon">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </a>
            <a href="supplydemand.html" class="nav-item" id="supplyDemandNav">
                <div class="nav-item-content">
                    <div class="nav-icon1">
                        <i class="fa-solid fa-square-poll-vertical"></i>
                    </div>
                    <span>Supply and Demand</span>
                </div>
                <div class="arrow-icon">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </a>
            
        </div>
        
        <div class="nav-section">
            <div class="nav-group">PREFERENCES</div>
            <a href="accountsetting.html" class="nav-item" id="accountSettingsNav">
                <div class="nav-item-content">
                    <div class="nav-icon">
                        <i class="fa-solid fa-gear"></i>
                    </div>
                    <span>Account Settings</span>
                </div>
                <div class="arrow-icon">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </a>
        </div>
        
        <div class="user-profile">
            <div class="avatar">
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="user-info">
                <div class="name">Admin Name</div>
                <div class="role">Administrator</div>
            </div>
            <div class="logout" id="logoutBtn">
                <i class="fa-solid fa-right-from-bracket"></i>
            </div>
        </div>
    </div>
        
    <div class="page-content">
        <!-- Stepper Navigation -->
        <div class="stepper-nav">
            <div class="step step-current step-active">
                <div class="step-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
                <span>List of Traders</span>
            </div>
            <div class="step-divider">
                <i class="fa-solid fa-chevron-right"></i>
            </div>
            <div class="step step-pending">
                <div class="step-icon">
                    <i class="fa-solid fa-list"></i>
                </div>
                <span>List of Reports</span>
            </div>
            <div class="step-divider">
                <i class="fa-solid fa-chevron-right"></i>
            </div>
            <div class="step step-pending">
                <div class="step-icon">
                    <i class="fa-solid fa-file-lines"></i>
                </div>
                <span>Report</span>
            </div>
        </div>
        <div>Logged in as: <span><?php echo htmlspecialchars($_SESSION['name']); ?></span> </div>
        <div class="page-header">
            <div>
                <h1 class="page-title">All Traders</h1>
                <p class="page-subtitle">Manage and monitor all traders in the system.</p>
            </div>
            <div class="search-container">
                <div class="search-icon">
                    <i class="fa-solid fa-search"></i>
                </div>
                <input type="text" class="search-input" placeholder="Search...">
            </div>
        </div>
        
        <div class="sort-controls">
            <label for="sortSelect">Sort by:</label>
            <select id="sortSelect">
                <option value="alphabetical">Region (A-Z)</option>
                <option value="newest">Newest First</option>
            </select>
        </div>
        
        <table class="traders-table">
            <thead>
                <tr>
                    <th>Trader Name</th>
                    <th>Contact Number</th>
                    <th>Telephone Number</th>
                    <th>Region</th>
                    <th>Actions</th>
                </tr>
            </thead>    
            <tbody id="tradersTableBody">
                <!-- Traders data will be populated here -->
            </tbody>
        </table>
    </div>
    
    <!-- Chat Panel -->
    <div class="overlay" id="overlay"></div>
    <div class="chat-panel" id="chatPanel">
        <div class="chat-header">
            <div class="trader-info">
                <div class="trader-avatar">
                    <span id="traderInitials">JC</span>
                </div>
                <div class="trader-details">
                    <h3 id="traderName">Jane Cooper</h3>
                    <p id="traderEmail">jane@microsoft.com</p>
                </div>
            </div>
            <div class="close-chat" id="closeChat">
                <i class="fa-solid fa-xmark"></i>
            </div>
        </div>
        <div class="chat-messages" id="chatMessages">
            <!-- Sample messages will be populated here -->
        </div>
        <div class="chat-input">
            <input type="text" class="message-input" id="messageInput" placeholder="Type a message...">
            <button class="send-btn" id="sendMessageBtn">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </div>
    </div>
    
    <script>
        // Function to render traders table
    function renderTradersTable(sortOption = "alphabetical") {
    const tableBody = document.getElementById("tradersTableBody");
    tableBody.innerHTML = ""; // Clear the table body

    // Fetch traders from the backend
    fetch('../../backend/admin/traderlist.php')
        .then(response => response.json())
        .then(traders => {
            // Sort traders based on option
            let sortedTraders = [...traders];
            if (sortOption === "alphabetical") {
                sortedTraders.sort((a, b) => a.traderName.localeCompare(b.traderName));
            } else if (sortOption === "newest") {
                // Implement sorting by newest first, if applicable
            }

            // Populate the table with trader data
            sortedTraders.forEach(traders => {
                const row = document.createElement("tr");

                row.innerHTML = `
                    <td>${traders.traderName}</td>
                    <td>${traders.contactNumber}</td>
                    <td>${traders.telephoneNumber}</td>
                    <td>${traders.region}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <button class="view-reports-btn" data-trader="${traders.traderName}" data-region="${traders.region}">
                                View Reports
                            </button>
                            <button class="message-btn" data-trader="${traders.traderName}" data-region="${traders.region}">
                                <i class="fa-solid fa-comment"></i> Message
                            </button>
                        </div>
                    </td>
                `;
                tableBody.appendChild(row);
            });
            // Re-attach event listeners to new message buttons
            attachMessageButtonListeners();
            attachViewReportsButtonListeners();
        })
        .catch(error => console.error('Error fetching traders:', error));
}
            
            
        
        
        // Initial rendering
        renderTradersTable();
        
        // Sort dropdown change event
        document.getElementById("sortSelect").addEventListener("change", function() {
            renderTradersTable(this.value);
        });
        
        document.getElementById("logoutBtn").addEventListener("click", function() {
            const confirmLogout = confirm("Are you sure you want to log out?");
            if (confirmLogout) {
                window.location.href = "../logout.php";
            }
        });
        
        // Attach View Reports button listeners
        function attachViewReportsButtonListeners() {
            const viewReportsButtons = document.querySelectorAll(".view-reports-btn");
            
            viewReportsButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const traderName = this.getAttribute("data-trader");
                    const region = this.getAttribute("data-region");
                    
                    // Navigate to reports page with trader info
                    window.location.href = `trader-report-list.html?trader=${traderName}&region=${region}`;
                });
            });
        }

        // Chat functionality
        const chatPanel = document.getElementById("chatPanel");
        const overlay = document.getElementById("overlay");
        const closeChat = document.getElementById("closeChat");
        const traderName = document.getElementById("traderName");
        const traderEmail = document.getElementById("traderEmail");
        const traderInitials = document.getElementById("traderInitials");
        const messageInput = document.getElementById("messageInput");
        const sendMessageBtn = document.getElementById("sendMessageBtn");
        const chatMessages = document.getElementById("chatMessages");
        
        // Sample messages for Jane Cooper
        const sampleMessages = {
    "Jane Cooper": [  
         ]
        };

        
        // Function to get initials from name
        function getInitials(name) {
            return name.split(" ").map(part => part.charAt(0)).join("");
        }
        
        // Function to render chat messages
        function renderMessages(traderNameValue) {
            chatMessages.innerHTML = "";
            
            if (sampleMessages[traderNameValue]) {
                sampleMessages[traderNameValue].forEach(message => {
                    const messageDiv = document.createElement("div");
                    messageDiv.className = `message ${message.sender === "trader" ? "received" : "sent"}`;
                    
                    messageDiv.innerHTML = `

                        <div class="message-info">${message.sender === "trader" ? traderNameValue : "You"} • ${message.time}</div>
                        <div class="message-bubble">${message.text}</div>
                    `;
                    
                    chatMessages.appendChild(messageDiv);
                });
            }
            
            // Scroll to bottom of messages
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        // Function to attach message button listeners
        function attachMessageButtonListeners() {
            const messageButtons = document.querySelectorAll(".message-btn");
            
            messageButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const traderNameValue = this.getAttribute("data-trader");
                    const region = this.getAttribute("data-region");
                    
                    traderName.textContent = traderNameValue;
                    traderEmail.textContent = region; // Display region instead of email
                    traderInitials.textContent = getInitials(traderNameValue);
                    
                    renderMessages(traderNameValue);
                    
                    chatPanel.classList.add("active");
                    overlay.classList.add("active");
                });
            });
        }
        
        // Close chat panel
        closeChat.addEventListener("click", function() {
            chatPanel.classList.remove("active");
            overlay.classList.remove("active");
        });
        
        overlay.addEventListener("click", function() {
            chatPanel.classList.remove("active");
            overlay.classList.remove("active");
        });
        
        // Send message functionality
        sendMessageBtn.addEventListener("click", sendMessage);
        messageInput.addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                sendMessage();
            }
        });
        
        function sendMessage() {
            const messageText = messageInput.value.trim();
            if (messageText === "") return;
            
            const currentTrader = traderName.textContent;
            const now = new Date();
            const timeString = now.getHours() + ":" + (now.getMinutes() < 10 ? "0" : "") + now.getMinutes();
            
            // Create new message
            if (!sampleMessages[currentTrader]) {
                sampleMessages[currentTrader] = [];
            }
            
            sampleMessages[currentTrader].push({
                sender: "admin",
                text: messageText,
                time: "Just now"
            });
            
            // Render updated messages
            renderMessages(currentTrader);
            
            // Clear input
            messageInput.value = "";
        }
    </script>
</body>
</html>
