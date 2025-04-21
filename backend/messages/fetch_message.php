<?php
session_start();
require_once '../dbconnection/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sender = $_POST['sender'] ?? '';
    $receiver = $_POST['receiver'] ?? '';

    if ($sender && $receiver) {
        $sql = "SELECT * FROM chat_messages 
                WHERE (sender = :sender AND receiver = :receiver) 
                   OR (sender = :receiver AND receiver = :sender) 
                ORDER BY created_at";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':sender' => $sender,
            ':receiver' => $receiver
        ]);

        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($messages) {
            foreach ($messages as $row) {
                echo '<div class="message"><strong>' . htmlspecialchars(ucfirst($row['sender'])) . ':</strong> ' . htmlspecialchars($row['message']) . '</div>';
            }
        } else {
            echo '<div class="message">No messages found.</div>';
        }
    } else {
        echo '<div class="message">Sender and receiver must be provided.</div>';
    }
}
?>
