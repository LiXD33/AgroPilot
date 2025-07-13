<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="/AgroPilot/users/styles.css?v=1.1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <meta http-equiv="Content-Security-Policy" content="script-src 'self';">
    <style>
        html, body { max-width: 100vw; overflow-x: hidden; }
        .login-container { box-sizing: border-box; }
        @media (max-width: 600px) {
            .login-container { padding: 0.5rem !important; }
            h2 { font-size: 1.2em !important; }
            label { font-size: 1em !important; }
        }
        .icon-inline { margin-right: 0.4em; }
        .form-group { display: flex; flex-direction: column; gap: 0.3em; }
        button[type="submit"] {
            display: flex;
            align-items: center;
            gap: 0.5em;
            justify-content: center;
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2><i class="fas fa-sign-in-alt icon-inline"></i>Connexion</h2>
        <form id="login-form">
            <div class="form-group">
                <label for="user_type"><i class="fas fa-users icon-inline"></i>Type d'utilisateur</label>
                <select id="user_type" name="user_type" required autocomplete="on">
                    <option value="client">Client</option>
                    <option value="marchand">Marchand</option>
                    <option value="agriculteur">Agriculteur</option>
                </select>
            </div>
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope icon-inline"></i>Email</label>
                <input type="email" id="email" name="email" required autocomplete="email">
            </div>
            <div class="form-group">
                <label for="password"><i class="fas fa-lock icon-inline"></i>Mot de passe</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>
            <button type="submit"><i class="fas fa-sign-in-alt icon-inline"></i>Se connecter</button>
        </form>
        <p>Pas encore inscrit ? <a href="../Index.php#inscription"><i class="fas fa-user-plus icon-inline"></i>Inscrivez-vous ici</a></p>
    </div>

    <!-- Pop-up d'erreur -->
    <div id="error-popup" class="popup">
        <div class="popup-content">
            <span id="error-message"></span>
            <button id="close-popup">Fermer</button>
        </div>
    </div>

    <script src="login.js"></script>
    <script>
        // Ajout de la gestion de la redirection après connexion selon le type d'utilisateur
        const loginForm = document.getElementById('login-form');
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(loginForm);
                fetch('process_login.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        document.getElementById('error-message').textContent = data.message || 'Erreur de connexion';
                        document.getElementById('error-popup').style.display = 'flex';
                    }
                })
                .catch(() => {
                    document.getElementById('error-message').textContent = 'Erreur serveur';
                    document.getElementById('error-popup').style.display = 'flex';
                });
            });
            document.getElementById('close-popup').onclick = function() {
                document.getElementById('error-popup').style.display = 'none';
            };
        }
    </script>
</body>
</html> 