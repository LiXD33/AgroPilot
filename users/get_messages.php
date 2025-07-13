<?php
session_start();
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    echo json_encode(['success' => false, 'error' => 'Non authentifié']);
    exit();
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

// Récupérer les messages reçus
$stmt = $conn->prepare("SELECT m.*, u.nom AS sender_nom, m.sender_type FROM messages m JOIN ".($user_type=='client'?'agriculteurs':'clients')." u ON m.sender_id = u.id WHERE m.receiver_id = ? AND m.receiver_type = ? ORDER BY m.created_at DESC LIMIT 30");
$stmt->bind_param('is', $user_id, $user_type);
$stmt->execute();
$result = $stmt->get_result();
$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}
// Nombre de messages non lus
$stmt2 = $conn->prepare("SELECT COUNT(*) as unread FROM messages WHERE receiver_id = ? AND receiver_type = ? AND is_read = 0");
$stmt2->bind_param('is', $user_id, $user_type);
$stmt2->execute();
$res2 = $stmt2->get_result();
$unread = $res2->fetch_assoc()['unread'];

echo json_encode(['success' => true, 'messages' => $messages, 'unread' => $unread]); 