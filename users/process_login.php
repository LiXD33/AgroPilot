<?php
session_start();
require_once 'config.php'; // Fichier de configuration pour la connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    // Vérifier les informations de connexion
    $table = '';
    $id_column = '';
    switch ($user_type) {
        case 'client':
            $table = 'clients';
            $id_column = 'id';
            break;
        case 'marchand':
            $table = 'marchands';
            $id_column = 'id';
            break;
        case 'agriculteur':
            $table = 'agriculteurs';
            $id_column = 'id';
            break;
        default:
            echo json_encode(['success' => false, 'message' => "Type d'utilisateur invalide"]);
            exit();
    }

    // Récupérer le nom de l'utilisateur selon le type
    $name_column = '';
    switch ($user_type) {
        case 'client':
            $name_column = 'nom';
            break;
        case 'marchand':
            $name_column = 'nom_entreprise';
            break;
        case 'agriculteur':
            $name_column = 'nom';
            break;
    }
    
    $sql = "SELECT $id_column, mot_de_passe, $name_column FROM $table WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user[$id_column];
            $_SESSION['user_type'] = $user_type;
            
            // Stocker le nom de l'utilisateur dans la session
            $session_name_key = $user_type . '_nom';
            $_SESSION[$session_name_key] = $user[$name_column];
            
            // Rediriger vers la page appropriée selon le type d'utilisateur
            $redirect_page = '';
            switch ($user_type) {
                case 'client':
                    $redirect_page = 'dashboard_client.php';
                    break;
                case 'marchand':
                    $redirect_page = 'dashboard_marchand.php';
                    break;
                case 'agriculteur':
                    $redirect_page = 'dashboard_agriculteur.php';
                    break;
                default:
                    $redirect_page = 'dashboard.php';
            }
            
            echo json_encode(['success' => true, 'redirect' => $redirect_page]);
            exit();
        } else {
            echo json_encode(['success' => false, 'message' => 'Mot de passe incorrect']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Email incorrect']);
        exit();
    }
}
?> 