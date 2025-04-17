<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sugar Regulatory Administration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/traderdashboard.css">
</head>
<body>
    <header>
        <img src="../../resources/SRA_thumbnail.png" alt="SRA Logo" class="logo">
        <img src="../../resources/REPUBLICOFPH.png" alt="SRA Logo" class="logo secondLogo">
        <nav>
            <a href="#" class="active">HOME</a>
            <a href="reportdraft.html">DRAFTS</a>
            <button class="sign-out-btn">Sign out</button>
        </nav>
    </header>
    
    <main>
        <div class="welcome-section">
            <div class="title-container">
                <h1>Welcome to the</h1>
                <span class="green-highlight">Sugar Regulatory Administration</span>
            </div>  
            <p>Monitoring System: Efficiently track and manage trader reports with real-time data and compliance updates.</p>    
            <button class="create-report-btn" onclick="window.location.href='createreport.html'">Create report</button>
        </div>
        
        <div class="features">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa-solid fa-file"></i>
                </div>
                <h3>Easy Reporting</h3>
                <p>Submit and manage reports effortlessly with our smooth, trader-friendly system built for regulatory compliance.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <h3>Real-time Updates</h3>
                <p>Stay updated with real-time notifications, market trends, and instant chats for seamless communication.</p>
            </div>
        </div>
        
        <div class="spacer"></div>
    </main>
    
    <footer id="footer">
        <div class="footer-content">
            <div class="footer-description">
                The Sugar Regulatory Administration (SRA) monitors and regulates the sugar industry to ensure fair trade practices and market stability.
            </div>
            
            <div class="footer-links">
                <i class="fa-solid fa-phone"></i>
                <i class="fa-regular fa-envelope"></i>
                <i class="fa-brands fa-facebook"></i>
            </div>
            
            <div class="footer-bottom">
                SRA © 2025, All Rights Reserved.
            </div>
        </div>
    </footer>
    
    <!-- Overlay div for blur effect -->
    <div class="overlay" id="overlay"></div>
    
    <!-- Chat Bubble -->
    <div class="chat-bubble">
        <i class="fa-solid fa-comment"></i>
    </div>

    <!-- Chat Window -->
    <div class="chat-window">
        <div class="chat-header">
            <h3><i class="fa-solid fa-circle"></i> SRA Chat Support</h3>
            <div class="chat-close">
                <i class="fa-solid fa-xmark"></i>
            </div>
        </div>
        <div class="chat-messages">
            <div class="message message-support">
                <div class="message-info">
                    <span class="support-name">SRA Support</span>
                </div>
                Hello! How can I help you with the Sugar Regulatory Administration system today?
            </div>
        </div>
        <div class="chat-input">
            <input type="text" placeholder="Type a message">
            <button class="send-btn">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>

        //for log out notif
        document.querySelector('.sign-out-btn').addEventListener('click', function(event) {
        event.preventDefault();
        const userConfirmed = confirm("Are you sure you want to log out?");
        
        if (userConfirmed) {
            window.location.href = "../login.html";
            }
        });


        // Show footer only when scrolling down
        window.addEventListener('scroll', function() {
            const footer = document.getElementById('footer');
            const scrollPosition = window.scrollY;
            
            // Show footer when scrolled enough
            if (scrollPosition > 100) {
                footer.style.display = 'block';
            } else {
                footer.style.display = 'none';
            }
        });
        
        // Force a small scroll event to check if scrollbar is available
        window.addEventListener('load', function() {
            // Add enough content to make page scrollable if it isn't already
            const body = document.body;
            const html = document.documentElement;
            const height = Math.max(
                body.scrollHeight, body.offsetHeight,
                html.clientHeight, html.scrollHeight, html.offsetHeight
            );
            
            if (height <= window.innerHeight) {
                // Page isn't tall enough to scroll, add some padding to main
                document.querySelector('main').style.paddingBottom = '300px';
            }
        });

        // Chat functionality
        document.addEventListener('DOMContentLoaded', () => {
            const chatBubble = document.querySelector('.chat-bubble');
            const chatWindow = document.querySelector('.chat-window');
            const chatClose = document.querySelector('.chat-close');
            const messageInput = document.querySelector('.chat-input input');
            const sendButton = document.querySelector('.send-btn');
            const chatMessages = document.querySelector('.chat-messages');
            const overlay = document.getElementById('overlay');

            // Toggle chat window visibility
            chatBubble.addEventListener('click', () => {
                chatWindow.style.display = 'flex';
                chatBubble.style.display = 'none';
                overlay.style.display = 'block';
                
                // Focus on input field
                setTimeout(() => {
                    messageInput.focus();
                }, 300);
            });

            // Close chat window
            chatClose.addEventListener('click', () => {
                chatWindow.style.display = 'none';
                chatBubble.style.display = 'flex';
                overlay.style.display = 'none';
            });
            
            // Also close chat when clicking overlay
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) {
                    chatWindow.style.display = 'none';
                    chatBubble.style.display = 'flex';
                    overlay.style.display = 'none';
                }
            });

            // Send message function
            function sendMessage() {
                const message = messageInput.value.trim();
                if (message) {
                    // Add user message
                    addMessage(message, 'user');
                    messageInput.value = '';
                    
                    // Simulate response after a short delay
                    setTimeout(() => {
                        let response = "Thank you for your message. Our team will get back to you shortly.";
                        
                        // Simple responses based on keywords
                        if (message.toLowerCase().includes('report')) {
                            response = "To create a report, click the 'Create report' button on the main page. If you need help with an existing report, please provide the report ID.";
                        } else if (message.toLowerCase().includes('login') || message.toLowerCase().includes('sign in')) {
                            response = "If you're having trouble logging in, please make sure your credentials are correct. For password reset, please use the 'Forgot Password' link on the login page.";
                        } else if (message.toLowerCase().includes('update')) {
                            response = "I'd be happy to get you the latest updates on the sugar export regulations. Please let me know if you need specific information.";
                        }
                        
                        addMessage(response, 'support');
                    }, 1000);
                }
            }

            // Add message to chat window
            function addMessage(text, type) {
                const messageDiv = document.createElement('div');
                messageDiv.classList.add('message');
                
                if (type === 'user') {
                    messageDiv.classList.add('message-user');
                    messageDiv.textContent = text;
                } else {
                    messageDiv.classList.add('message-support');
                    const messageInfo = document.createElement('div');
                    messageInfo.classList.add('message-info');
                    
                    const supportName = document.createElement('span');
                    supportName.classList.add('support-name');
                    supportName.textContent = 'SRA Support';
                    
                    messageInfo.appendChild(supportName);
                    messageDiv.appendChild(messageInfo);
                    messageDiv.appendChild(document.createTextNode(text));
                }
                
                chatMessages.appendChild(messageDiv);
                
                // Scroll to the latest message
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            // Send message on button click
            sendButton.addEventListener('click', sendMessage);

            // Send message on Enter key
            messageInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });
        });
    </script>
</body>
</html>