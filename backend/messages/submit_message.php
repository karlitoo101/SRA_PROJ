<?php
session_start();
require_once '../dbconnection/db.php';

header('Content-Type: application/json');

// Verify the request method
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Only POST requests are allowed']);
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'You must be logged in to send messages']);
    exit();
}

// Validate required fields
if (empty($_POST['sender']) || empty($_POST['receiver']) || empty($_POST['message'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'All fields are required']);
    exit();
}

// Sanitize and validate inputs
$sender = filter_var($_POST['sender'], FILTER_SANITIZE_STRING);
$receiver = filter_var($_POST['receiver'], FILTER_SANITIZE_STRING);
$message = trim(filter_var($_POST['message'], FILTER_SANITIZE_STRING));

// In submit_message.php, add this validation after input sanitization:
if ($receiver != getAdminId()) {
    http_response_code(403); // Forbidden
    echo json_encode(['error' => 'Messages can only be sent to admin']);
    exit();
}

// Additional validation
if (empty($message)) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Message cannot be empty']);
    exit();
}

// Verify sender matches logged in user (security check)
if ($sender != $_SESSION['userID']) {
    http_response_code(403); // Forbidden
    echo json_encode(['error' => 'You can only send messages as yourself']);
    exit();
}

try {
    // Prepare SQL statement with parameters to prevent SQL injection
    $sql = "INSERT INTO chat_messages (sender, receiver, message, timestamp) 
            VALUES (:sender, :receiver, :message, NOW())";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':sender', $sender, PDO::PARAM_STR);
    $stmt->bindParam(':receiver', $receiver, PDO::PARAM_STR);
    $stmt->bindParam(':message', $message, PDO::PARAM_STR);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Failed to send message']);
    }
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['error' => 'Database error occurred']);
}
?>