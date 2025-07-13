// Afficher le pop-up d'erreur
function showErrorPopup(message) {
    const popup = document.getElementById('error-popup');
    const errorMessage = document.getElementById('error-message');
    if (popup && errorMessage) {
        errorMessage.textContent = message;
        popup.style.display = 'flex';
    } else {
        console.error("Élément pop-up ou message d'erreur introuvable.");
    }
}

// Fermer le pop-up d'erreur
document.addEventListener('DOMContentLoaded', function() {
    const closeButton = document.getElementById('close-popup');
    if (closeButton) {
        closeButton.addEventListener('click', function() {
            const popup = document.getElementById('error-popup');
            if (popup) {
                popup.style.display = 'none';
            }
        });
    }

    // Gérer la soumission du formulaire via AJAX
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Empêcher le rechargement de la page

            const formData = new FormData(this); // Récupérer les données du formulaire

            fetch('process_login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Rediriger vers la page appropriée selon le type d'utilisateur
                    const redirectPage = data.redirect || 'dashboard.php';
                    window.location.href = redirectPage;
                } else {
                    showErrorPopup(data.message); // Afficher le pop-up d'erreur
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showErrorPopup('Une erreur s\'est produite. Veuillez réessayer.');
            });
        });
    }
}); 