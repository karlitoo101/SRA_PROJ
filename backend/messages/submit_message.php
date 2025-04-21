<?php
session_start();
require_once '../dbconnection/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sender = $_POST['sender'];
    $receiver = $_POST['receiver'];
    $message = $_POST['message'];

    $sql = "INSERT INTO chat_messages (sender, receiver, message) VALUES ('$sender', '$receiver', '$message')";
    $pdo->query($sql);
}
