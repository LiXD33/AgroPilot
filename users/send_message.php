<?php
session_start();
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    echo json_encode(['success' => false, 'error' => 'Non authentifié']);
    exit();
}

$sender_id = $_SESSION['user_id'];
$sender_type = $_SESSION['user_type'];
$receiver_email = isset($_POST['receiver_email']) ? trim($_POST['receiver_email']) : '';
$receiver_type = isset($_POST['destinataire']) ? $_POST['destinataire'] : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if (!$receiver_email || !$receiver_type || !$message) {
    echo json_encode(['success' => false, 'error' => 'Champs manquants']);
    exit();
}

// Trouver l'ID du destinataire à partir de l'email et du type
$table = $receiver_type === 'agriculteur' ? 'agriculteurs' : 'clients';
$sql = "SELECT id FROM $table WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $receiver_email);
$stmt->execute();
$res = $stmt->get_result();
if ($row = $res->fetch_assoc()) {
    $receiver_id = $row['id'];
    $stmt2 = $conn->prepare("INSERT INTO messages (sender_id, sender_type, receiver_id, receiver_type, message) VALUES (?, ?, ?, ?, ?)");
    $stmt2->bind_param('isiss', $sender_id, $sender_type, $receiver_id, $receiver_type, $message);
    if ($stmt2->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => "Erreur lors de l'envoi"]);
    }
} else {
    echo json_encode(['success' => false, 'error' => "Aucun utilisateur trouvé avec cet email"]);
} 