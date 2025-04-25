<?php
session_start();
require_once '../dbconnection/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sender = $_POST['sender'];
    $receiver = $_POST['receiver'];

    $sql = "SELECT * FROM chat_messages 
            WHERE (sender=? AND receiver=?) OR (sender=? AND receiver=?) 
            ORDER BY created_at";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $sender, $receiver, $receiver, $sender);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $msgSender = htmlspecialchars(ucfirst($row['sender']));
            $message = htmlspecialchars($row['message']);
            $timestamp = date("H:i", strtotime($row['created_at']));
            $status = isset($row['status']) ? htmlspecialchars($row['status']) : '';

            // Check if it's the current user's message
            $isOwnMessage = ($row['sender'] === $_POST['sender']);

            echo '<div class="message-container ' . ($isOwnMessage ? 'own' : 'other') . '">';
            
            echo    '<div class="meta">';
            echo        '<span class="sender-name">' . $msgSender . '</span>';
            echo        '<span class="timestamp">' . $timestamp . '</span>';
            echo    '<div class="message">' . $message . '</div>';

            if ($isOwnMessage) {
                echo    '<span class="status">(' . $status . ')</span>';
            }

            echo    '</div>';
            echo '</div>';
        }
    } else {
        echo '<div class="message no-msg">No messages yet.</div>';
    }

    $stmt->close();
}
?>
