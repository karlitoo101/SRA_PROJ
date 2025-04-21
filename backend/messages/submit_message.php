<?php
session_start();
require_once '../dbconnection/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sender = $_POST['sender'];
    $receiver = $_POST['receiver'];
    $message = $_POST['message'];

    try {
        $sql = "INSERT INTO chat_messages (sender, receiver, message, created_at) 
                VALUES (:sender, :receiver, :message, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':sender', $sender);
        $stmt->bindParam(':receiver', $receiver);
        $stmt->bindParam(':message', $message);
        $stmt->execute();
    } catch (PDOException $e) {
        error_log("Message insert failed: " . $e->getMessage());
        http_response_code(500);
        echo "Error sending message.";
    }
}
?>
