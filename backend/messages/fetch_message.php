<?php
session_start();
require_once '../dbconnection/db.php';

header('Content-Type: application/json');

// Verify request method
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Only POST requests are allowed']);
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Authentication required']);
    exit();
}

// Validate required parameters
if (empty($_POST['sender']) || empty($_POST['receiver'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Sender and receiver parameters are required']);
    exit();
}

// Sanitize inputs
$sender = filter_var($_POST['sender'], FILTER_SANITIZE_STRING);
$receiver = filter_var($_POST['receiver'], FILTER_SANITIZE_STRING);

// Verify the requesting user has permission to view these messages
if ($sender != $_SESSION['userID'] && $receiver != $_SESSION['userID']) {
    http_response_code(403); // Forbidden
    echo json_encode(['error' => 'You are not authorized to view these messages']);
    exit();
}

try {
    // Get messages with user details
    $sql = "SELECT cm.*, u.name as sender_name
        FROM chat_messages cm
        JOIN users u ON cm.sender = u.userID
        WHERE (cm.sender = :sender AND cm.receiver = :receiver) 
           OR (cm.sender = :receiver AND cm.receiver = :sender)
        ORDER BY cm.timestamp ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'sender' => $sender,
        'receiver' => $receiver
    ]);

    $messages = [];
    $currentUser = $_SESSION['userID'];
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $isCurrentUser = ($row['sender'] == $currentUser);
        $senderName = $isCurrentUser ? 'You' : htmlspecialchars($row['sender_name']);
        
        $messages[] = [
            'id' => $row['id'],
            'sender' => $row['sender'],
            'senderName' => $senderName,
            'message' => htmlspecialchars($row['message']),
            'timestamp' => $row['timestamp'],
            'isCurrentUser' => $isCurrentUser,
            'formattedTime' => date('h:i A', strtotime($row['timestamp']))
        ];
        
        // Mark as read if receiver is current user
        if (!$isCurrentUser && $row['is_read'] == 0) {
            $updateSql = "UPDATE chat_messages SET is_read = 1 WHERE id = :messageId";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute(['messageId' => $row['id']]);
        }
    }

    // Return JSON response
    echo json_encode([
        'success' => true,
        'messages' => $messages,
        'count' => count($messages)
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['error' => 'Database error occurred']);
}
?>