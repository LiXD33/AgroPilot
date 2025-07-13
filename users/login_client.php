<?php
session_start();
if (isset($_SESSION['client_id'])) {
    header("Location: client_dashboard.php"); // Rediriger vers le tableau de bord du client
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Client</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h2>Connexion Client</h2>
        <form action="process_login.php" method="POST">
            <input type="hidden" name="user_type" value="client">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Se connecter</button>
        </form>
        <p>Pas encore inscrit ? <a href="../inscription.php">Inscrivez-vous ici</a></p>
    </div>
</body>
</html> 