<?php
session_start();
if (!isset($_SESSION['userID']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../../logins/logout.php");
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
            <tbody id="tradersTableBody"></tbody>
        </table>
    </div>

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
            <div id="chat-box-body"></div>
        </div>
        <form class="chat-input" id="chat-form">
            <input type="hidden" id="sender" value="<?php echo $_SESSION['name']; ?>">
            <input type="hidden" id="receiver" value="">
            <input type="text" id="message" placeholder="Type your message..." required>
            <button type="submit" class="send-btn"><i class="fa-solid fa-paper-plane"></i></button>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        let allTraders = [];
        const searchInput = document.querySelector('.search-input');
        const tableBody = document.getElementById("tradersTableBody");

        fetch('../../backend/admin/displaytraders.php')
            .then(response => response.json())
            .then(traders => {
                allTraders = traders;
                renderTradersTable();
            })
            .catch(error => console.error('Error fetching traders:', error));

        function renderTradersTable() {
            const searchTerm = searchInput.value.toLowerCase();
            let filteredTraders = allTraders.filter(trader =>
                trader.traderName.toLowerCase().includes(searchTerm) ||
                trader.contactNumber.toLowerCase().includes(searchTerm) ||
                trader.telephoneNumber.toLowerCase().includes(searchTerm) ||
                trader.region.toLowerCase().includes(searchTerm)
            );
            filteredTraders.sort((a, b) => a.traderName.localeCompare(b.traderName));
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
                            <button class="view-reports-btn" data-trader="${trader.traderName}" data-region="${trader.region}">View Reports</button>
                            <button class="message-btn" data-trader="${trader.traderName}" data-region="${trader.region}"><i class="fa-solid fa-comment"></i> Message</button>
                        </div>
                    </td>`;
                tableBody.appendChild(row);
            });
            attachMessageButtonListeners();
            attachViewReportsButtonListeners();
        }

        searchInput.addEventListener('input', renderTradersTable);

        document.getElementById("sortSelect").addEventListener("change", function () {
            renderTradersTable();
        });

        document.getElementById("logoutBtn").addEventListener("click", function () {
            if (confirm("Are you sure you want to log out?")) {
                window.location.href = "../../logins/logout.php";
            }
        });

        function attachViewReportsButtonListeners() {
            document.querySelectorAll(".view-reports-btn").forEach(button => {
                button.addEventListener("click", function () {
                    const traderName = this.getAttribute("data-trader");
                    const region = this.getAttribute("data-region");
                    window.location.href = `trader-report-list.php?trader=${traderName}&region=${region}`;
                });
            });
        }

        function attachMessageButtonListeners() {
            document.querySelectorAll(".message-btn").forEach(button => {
                button.addEventListener("click", function () {
                    const traderNameValue = this.getAttribute("data-trader");
                    const region = this.getAttribute("data-region");
                    document.getElementById("traderName").textContent = traderNameValue;
                    document.getElementById("traderEmail").textContent = region;
                    document.getElementById("traderInitials").textContent = traderNameValue.split(" ").map(n => n[0]).join("");
                    document.getElementById("receiver").value = traderNameValue;
                    chatPanel.classList.add("active");
                    overlay.classList.add("active");
                });
            });
        }

        const chatPanel = document.getElementById("chatPanel");
        const overlay = document.getElementById("overlay");
        const closeChat = document.getElementById("closeChat");

        closeChat.addEventListener("click", () => {
            chatPanel.classList.remove("active");
            overlay.classList.remove("active");
        });

        overlay.addEventListener("click", () => {
            chatPanel.classList.remove("active");
            overlay.classList.remove("active");
        });

        function fetchMessages() {
            var sender = $('#sender').val();
            var receiver = $('#receiver').val();
            $.post('../../backend/messages/fetch_message.php', { sender, receiver }, function (data) {
                $('#chat-box-body').html(data);
                $('#chat-box-body').scrollTop($('#chat-box-body')[0].scrollHeight);
            });
        }

        $('#chat-form').submit(function (e) {
            e.preventDefault();
            var sender = $('#sender').val();
            var receiver = $('#receiver').val();
            var message = $('#message').val();
            $.post('../../backend/messages/submit_message.php', { sender, receiver, message }, function () {
                $('#message').val('');
                fetchMessages();
            });
        });

        $(document).ready(function () {
            fetchMessages();
            setInterval(fetchMessages, 1000);
        });
    </script>
</body>
</html>
