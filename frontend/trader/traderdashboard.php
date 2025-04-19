<?php
session_start();
if (!isset($_SESSION['userID']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 0) {
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
    <title>Sugar Regulatory Administration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Keeping your existing styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: white;
            transition: filter 0.3s ease;
        }
        
        body.blur-background {
            filter: blur(5px);
        }
        
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid #e0e0e0;
            background-color: white;
            z-index: 10;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
        }
        
        .logo {
            width: 70px;
            height: 70px;
            margin-left: 25px;
        
        }

        .secondLogo {
            width: 60px;
            height: 60px;
            margin-left: -1000px;

        }
        

        
        nav {
            display: flex;
            gap: 40px;
            align-items: center;
        }
        
        nav a {
            text-decoration: none;
            color: #000;
            font-weight: normal;
        }
        
        nav a.active {
            font-weight: bold;
            text-decoration: none; 
            position: relative;
        }

        nav a.active::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -4px; 
            width: 100%;
            height: 2px; 
            background-color: black; 
        }

        
        .sign-out-btn {
            font-size: 15px;
            padding: 8px 16px;
            padding: 8px 16px;
            background-color: white;
            padding: 8px 16px;
            border: 1px solid #b4b4b4;
            padding: 8px 16px;
            border-radius: 10px;
            padding: 8px 16px;
            cursor: pointer;
            padding: 8px 16px;
            transition: background-color 0.3s ease, border-color 0.3s ease; 
        }

        .sign-out-btn:hover {
            background-color: #f5f5f5; 
            border-color: #333; 
        }

        
        main {
            flex: 1;
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }
        
        .welcome-section {
            text-align: center;
            margin: 60px 0;
            margin-top: 30px;
        }
        
        .title-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: nowrap;
            white-space: nowrap;
            margin-bottom: 15px;
        }
        
        .welcome-section h1 {
            font-size: 40px;
            display: inline;
            font-weight: bolder;
        }
        
        .green-highlight {
            background-color: #0b8a1b;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 40px;
            font-weight: bolder;
            margin-left: 10px;
            white-space: nowrap;
        }
        
        .welcome-section p {
            color: #3f3f3f;
            margin-bottom: 30px;
            font-size: 20px;
            line-height: 1.5;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .create-report-btn {
            background-color: #fcd900;
            color: #000;
            border: none;
            padding: 12px 25px;
            font-weight: bold;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
        }

        .create-report-btn:hover {
            background-color: #ffe249;
            transform: scale(1.03); 
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .features {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 50px;
            width: 100%;
        }
        
        .feature-card {
            flex: 1;
            max-width: 350px;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .feature-icon {
            background-color: #d4f7d6;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            font-size: 20px;
        }
        
        .feature-card h3 {
            margin-bottom: 10px;
            font-size: 18px;
            text-align: center;
        }
        
        .feature-card p {
            color: #555;
            font-size: 14px;
            line-height: 1.5;
            text-align: center;
        }
        
        footer {
            background-color: #008000;
            color: white;
            padding: 50px;
            width: 100%;
            display: none; 
        }
        
        .footer-content {
            display: flex;
            flex-direction: column;
            max-width: 1200px;
            margin: 0 auto;
            margin-left: 10px;
        }
        
        .footer-description {
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .footer-links {
            display: flex;
            gap: 20px;
        }
        
        .footer-links a {
            color: white;
            font-size: 20px;
        }
        
        .footer-bottom {
            color: #d7d7d7;
            display: flex;
            justify-content: column;
            margin-top: 30px;
            font-size: 12px;
            margin-left: -2px;
        }
        
        .chat-bubble {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #0b8a1b;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 100;
        }
        
        .spacer {
            height: 10px;
            width: 100%;
        }

        .chat-window {
            position: fixed;
            bottom: 110px;
            right: 30px;
            width: 350px;
            height: 450px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            display: none;
            flex-direction: column;
            z-index: 1000;
            overflow: hidden;
        }

        .chat-header {
            background-color: #008000;
            color: white;
            padding: 15px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-header h3 {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .chat-close {
            cursor: pointer;
            background-color: #ff3b30;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .chat-messages {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .message {
            max-width: 80%;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 14px;
            line-height: 1.4;
        }

        .message-support {
            background-color: #f0f0f0;
            align-self: flex-start;
            border-top-left-radius: 0;
        }

        .message-user {
            background-color: #108a1b;
            color: white;
            align-self: flex-end;
            border-top-right-radius: 0;
        }

        .chat-input {
            display: flex;
            border-top: 1px solid #e0e0e0;
            padding: 10px;
        }

        .chat-input input {
            flex: 1;
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 8px 15px;
            outline: none;
        }


        .send-btn {
            background-color: #0b8a1b;
            color: white;
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            margin-left: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .message-info {
            font-size: 12px;
            color: #777;
            margin-bottom: 3px;
        }

        .support-name {
            font-weight: bold;
        }
        
        /* Overlay for when chat is active */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(5px);
            z-index: 999;
            display: none;
        }
    </style>
</head>
<body>
    <header>
        <img src="../../resources/SRA_thumbnail.png" alt="SRA Logo" class="logo">
        <img src="../../resources/REPUBLICOFPH.png" alt="SRA Logo" class="logo secondLogo">
        <nav>
            <a href="#" class="active">HOME</a>
            <a href="reportdraft.php">DRAFTS</a>
            <button class="sign-out-btn">Sign out</button>
        </nav>
    </header>
    
    <main>
    <div class="trader-info">
    Logged in as: <span><?php echo htmlspecialchars($_SESSION['name']); ?></span> </div>
        <div class="welcome-section">
            <div class="title-container">
                <h1>Welcome to the</h1>
                <span class="green-highlight">Sugar Regulatory Administration</span>
            </div>  
            <p>Monitoring System: Efficiently track and manage trader reports with real-time data and compliance updates.</p>    
            <button class="create-report-btn" onclick="window.location.href='createreport.php'">Create report</button>
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

    <script>

        //for log out notif
        document.querySelector('.sign-out-btn').addEventListener('click', function(event) {
        event.preventDefault();
        const userConfirmed = confirm("Are you sure you want to log out?");
        
        if (userConfirmed) {
            window.location.href = "../../logins/logout.php";
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