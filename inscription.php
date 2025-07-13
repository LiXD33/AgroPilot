<?php
session_start(); // Démarrer la session

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agropilot";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer le type d'utilisateur
$userType = $_POST['userType'];

// Récupérer les données du formulaire en fonction du type d'utilisateur
if ($userType === 'client') {
    $name = $_POST['clientName'];
    $email = $_POST['clientEmail'];
    $password = password_hash($_POST['clientPassword'], PASSWORD_DEFAULT);
    $table = 'clients';
} elseif ($userType === 'merchant') {
    $name = $_POST['merchantName'];
    $email = $_POST['merchantEmail'];
    $password = password_hash($_POST['merchantPassword'], PASSWORD_DEFAULT);
    $address = $_POST['merchantAddress'];
    $table = 'marchands';
} elseif ($userType === 'farmer') {
    $name = $_POST['farmerName'];
    $email = $_POST['farmerEmail'];
    $password = password_hash($_POST['farmerPassword'], PASSWORD_DEFAULT);
    $location = $_POST['farmerLocation'];
    $table = 'agriculteurs';
}

// Insérer les données dans la base de données
if ($userType === 'client') {
    $sql = "INSERT INTO $table (nom, email, mot_de_passe) VALUES ('$name', '$email', '$password')";
} elseif ($userType === 'merchant') {
    $sql = "INSERT INTO $table (nom_entreprise, email, mot_de_passe, adresse) VALUES ('$name', '$email', '$password', '$address')";
} elseif ($userType === 'farmer') {
    $sql = "INSERT INTO $table (nom, email, mot_de_passe, localisation) VALUES ('$name', '$email', '$password', '$location')";
}

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]); // Retourner une réponse JSON en cas de succès
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]); // Retourner une réponse JSON en cas d'erreur
}

$conn->close();
?> 