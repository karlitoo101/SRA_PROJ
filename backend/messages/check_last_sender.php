<?php
session_start();
require_once '../dbconnection/db.php';

$adminName = $_SESSION['name'];
$traderName = $_POST['trader'];

$sql = "SELECT sender FROM chat_messages 
        WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?)
        ORDER BY created_at DESC LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $adminName, $traderName, $traderName, $adminName);
$stmt->execute();
$stmt->bind_result($lastSender);

$response = ["last_sender" => null];

if ($stmt->fetch()) {
    $response["last_sender"] = $lastSender;
}

$stmt->close();
echo json_encode($response);
?>
