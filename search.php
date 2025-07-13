<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agropilot";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = $_GET['query'];
$type = $_GET['type'];

// Définir la table en fonction du type de filtre
$table = '';
switch ($type) {
    case 'agriculteur':
        $table = 'agriculteurs';
        break;
    case 'client':
        $table = 'clients';
        break;
    case 'marchand':
        $table = 'marchands';
        break;
    default:
        // Si aucun filtre n'est sélectionné, rechercher dans toutes les tables
        $table = 'agriculteurs, clients, marchands';
}

// Construire la requête SQL en fonction de la table
if ($table === 'agriculteurs, clients, marchands') {
    $sql = "(SELECT nom AS name, email, 'agriculteur' AS type FROM agriculteurs WHERE nom LIKE ?)
            UNION
            (SELECT nom AS name, email, 'client' AS type FROM clients WHERE nom LIKE ?)
            UNION
            (SELECT nom_entreprise AS name, email, 'marchand' AS type FROM marchands WHERE nom_entreprise LIKE ?)";
    $stmt = $conn->prepare($sql);
    $searchQuery = "%$query%";
    $stmt->bind_param("sss", $searchQuery, $searchQuery, $searchQuery);
} else {
    $sql = "SELECT ";
    if ($table === 'marchands') {
        $sql .= "nom_entreprise AS name, email FROM $table WHERE nom_entreprise LIKE ?";
    } else {
        $sql .= "nom AS name, email FROM $table WHERE nom LIKE ?";
    }
    $stmt = $conn->prepare($sql);
    $searchQuery = "%$query%";
    $stmt->bind_param("s", $searchQuery);
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$stmt->close();
$conn->close();
?>