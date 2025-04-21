<?php
session_start();
if (!isset($_SESSION['userID']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    // Redirect to login page if not an admin
    header("Location: ../../logins/logout.php"); // Change the path as needed
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
            <a href="supplydemand.php" class="nav-item" id="supplyDemandNav">
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
            <a href="accountsetting.php" class="nav-item" id="accountSettingsNav">
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
                <div class="name"><?php echo htmlspecialchars($_SESSION['name']); ?></div>
                <div class="email"><?php echo htmlspecialchars($_SESSION['email']); ?></div>
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
                <option value="alphabetical">Trader name (A-Z)</option>
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
        <form class="chat-input" id="chat-form">
            <input type="hidden" id="sender" value="<?php echo htmlspecialchars($_SESSION['adminName']); ?>">
            <input type="hidden" id="receiver" value="<?php echo htmlspecialchars($_SESSION['']); ?>">
            <input type="text" class="message-input" id="messageInput" placeholder="Type your message..." required>
            <button type="submit" class="send-btn"><i class="fa-solid fa-paper-plane"></i></button>
        </form>
    </div>
    
    <script>
    let allTraders = [];
let sortOption = "alphabetical"; // Default sort
const searchInput = document.querySelector('.search-input');
const tableBody = document.getElementById("tradersTableBody");

// Fetch traders from the backend
fetch('../../backend/admin/displaytraders.php')
    .then(response => response.json())
    .then(traders => {
        allTraders = traders;
        renderTradersTable(); // Initial render
    })
    .catch(error => console.error('Error fetching traders:', error));

// Render table with search and sorting
function renderTradersTable() {
    const searchTerm = searchInput.value.toLowerCase();

    // Filter based on search input
    let filteredTraders = allTraders.filter(trader =>
        trader.traderName.toLowerCase().includes(searchTerm) ||
        trader.contactNumber.toLowerCase().includes(searchTerm) ||
        trader.telephoneNumber.toLowerCase().includes(searchTerm) ||
        trader.region.toLowerCase().includes(searchTerm)
    );

    // Apply sorting
    if (sortOption === "alphabetical") {
        filteredTraders.sort((a, b) => a.traderName.localeCompare(b.traderName));
    } else if (sortOption === "newest") {

    }

    // Clear and populate table
    tableBody.innerHTML = "";
    filteredTraders.forEach(trader => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${trader.traderName}</td>
            <td>${trader.contactNumber}</td>
            <td>${trader.telephoneNumber}</td>
            <td>${trader.region}</td>
            <td>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <button class="view-reports-btn" data-trader="${trader.traderName}" data-region="${trader.region}">
                        View Reports
                    </button>
                    <button class="message-btn" data-trader="${trader.traderName}" data-region="${trader.region}">
                        <i class="fa-solid fa-comment"></i> Message
                    </button>
                </div>
            </td>
        `;
                tableBody.appendChild(row);
            });

            // Re-attach event listeners
            attachMessageButtonListeners();
            attachViewReportsButtonListeners();
        }

        // Search listener
        searchInput.addEventListener('input', renderTradersTable);
  
        // Sort dropdown change event
        document.getElementById("sortSelect").addEventListener("change", function() {
            renderTradersTable(this.value);
        });
        
        document.getElementById("logoutBtn").addEventListener("click", function() {
            const confirmLogout = confirm("Are you sure you want to log out?");
            if (confirmLogout) {
                window.location.href = "../../logins/logout.php";
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
                    window.location.href = `trader-report-list.php?trader=${traderName}&region=${region}`;
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
        // Function to fetch messages
        function fetchMessages() {
                var sender = $('#sender').val();
                var receiver = $('#receiver').val();

                $.ajax({
                    url: '../../backend/messages/fetch_message.php',
                    type: 'POST',
                    data: { sender: sender, receiver: receiver },
                    success: function (data) {
                        $('#chatMessages').html(data);
                        scrollChatToBottom();
                    }
                });
            }


            $(document).ready(function () {
                // Fetch messages every 3 seconds

                fetchMessages();
                setInterval(fetchMessages, 3000);
            });


            // Submit the chat message
            $('#chat-form').submit(function (e) {
                e.preventDefault();
                var sender = $('#sender').val();
                var receiver = $('#receiver').val();
                var message = $('#message').val();

                $.ajax({
                    url: '../../backend/messages/submit_message.php',
                    type: 'POST',
                    data: { sender: sender, receiver: receiver, message: message },
                    success: function () {
                        $('#message').val('');
                        fetchMessages(); // Fetch messages after submitting
                    }
                });

            });
        
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
