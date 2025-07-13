<?php
session_start(); // Démarrer la session

if (isset($_SESSION['inscription_success'])) {
    echo "<script>showSuccessPopup();</script>"; // Afficher le pop-up
    unset($_SESSION['inscription_success']); // Supprimer le message de succès de la session
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroPilot - Dashboard</title>
    <link rel="stylesheet" href="AgroPilot.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <style>
        /* Responsive et anti-débordement */
        html, body { max-width: 100vw; overflow-x: hidden; }
        .container, .card, .card-content, .card-header, .card-grid, .stats-grid, .footer-content, .footer-section {
            box-sizing: border-box;
        }
        @media (max-width: 1100px) {
            .container { padding: 0 0.5rem; }
            .stats-grid, .card-grid, .footer-content { flex-direction: column !important; gap: 1.2rem !important; }
        }
        @media (max-width: 800px) {
            .container { padding: 0 0.2rem; }
            .stats-grid, .card-grid, .footer-content { flex-direction: column !important; gap: 1.2rem !important; }
            .footer-section { min-width: 0 !important; }
        }
        @media (max-width: 500px) {
            .container, .card, .card-content, .card-header, .card-grid, .stats-grid, .footer-content, .footer-section {
                padding-left: 0.2rem !important; padding-right: 0.2rem !important;
            }
            h1, h2, h3 { font-size: 1.1em !important; }
        }
        nav a, .btn, .form-button, .add-button, .mobile-menu-button {
            display: inline-flex; align-items: center; gap: 0.5em;
        }
        .icon-inline { margin-right: 0.4em; }
        .input-icon-group {
            position: relative;
            display: flex;
            align-items: center;
            margin-bottom: 1.1em;
        }
        .input-icon-group .input-icon {
            position: absolute;
            left: 12px;
            color: #888;
            font-size: 1.1em;
            pointer-events: none;
        }
        .input-icon-group input {
            padding-left: 2.2em !important;
        }
        .form-title-icon {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 0.3em;
            color: var(--primary, #28a745);
            font-size: 2.2em;
        }
        .form-title-text {
            text-align: center;
            margin-bottom: 1.2em;
        }
        .form-button {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 1.2em auto 0 auto;
            min-width: 180px;
        }
        .inscription-buttons {
            display: flex;
            justify-content: center;
            gap: 1.2em;
            margin-top: 1.5em;
        }
        .inscription-buttons button {
            display: flex;
            align-items: center;
            gap: 0.5em;
            font-size: 1em;
            padding: 0.7em 1.5em;
            border-radius: 8px;
            border: 1px solid var(--primary, #28a745);
            background: #fff;
            color: var(--primary, #28a745);
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
        }
        .inscription-buttons button:hover {
            background: var(--primary, #28a745);
            color: #fff;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 3L4 7.5V12M12 3L20 7.5V12M12 3V7.5M4 12L12 16.5L20 12M4 12V16.5L12 21L20 16.5V12" 
                              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h1><span>Agro</span>Pilot</h1>
                </div>
                
                <nav class="desktop-nav">
                    <a href="#" class="active"><i class="fas fa-home icon-inline"></i>Accueil</a>
                    <a href="#rechercher"><i class="fas fa-search icon-inline"></i>Rechercher</a>
                    <a href="users/login.php"><i class="fas fa-sign-in-alt icon-inline"></i>Connexion</a>
                    <a href="#inscription"><i class="fas fa-user-plus icon-inline"></i>Inscription</a>
                </nav>
                
                <button class="mobile-menu-button" id="mobile-menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <!-- Menu mobile déroulant -->
                <nav class="mobile-nav" id="mobile-nav">
                    <div class="mobile-nav-header">
                        <span>Menu</span>
                        <button class="mobile-nav-close" id="mobile-nav-close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="mobile-nav-content">
                        <a href="#" class="mobile-nav-link active">
                            <i class="fas fa-home icon-inline"></i>Accueil
                        </a>
                        <a href="#rechercher" class="mobile-nav-link">
                            <i class="fas fa-search icon-inline"></i>Rechercher
                        </a>
                        <a href="users/login.php" class="mobile-nav-link">
                            <i class="fas fa-sign-in-alt icon-inline"></i>Connexion
                        </a>
                        <a href="#inscription" class="mobile-nav-link">
                            <i class="fas fa-user-plus icon-inline"></i>Inscription
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <section class="hero container">
            <div class="hero-content">
                <h1>Bienvenue sur <span style="color: var(--primary);">Agro</span>Pilot</h1>
                <p>Connectez-vous avec des agriculteurs, marchands et clients pour faciliter vos échanges agricoles.</p>
                <div class="hero-buttons">
                    <button onclick="showForm('clientForm')"><i class="fas fa-user icon-inline"></i>Je suis un Client</button>
                    <button onclick="showForm('merchantForm')"><i class="fas fa-store icon-inline"></i>Je suis un Marchand</button>
                    <button onclick="showForm('farmerForm')"><i class="fas fa-tractor icon-inline"></i>Je suis un Agriculteur</button>
                </div>
            </div>
        </section>

        <section class="container">
            <div class="stats-grid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-seedling icon-inline"></i>Parcelles</h3>
                        <div class="card-description">Gestion de vos parcelles agricoles</div>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">2</div>
                        <button class="btn btn-secondary">Voir toutes les parcelles</button>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-leaf icon-inline"></i>Cultures</h3>
                        <div class="card-description">Suivi de vos cultures en cours</div>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">2</div>
                        <button class="btn btn-secondary">Voir toutes les cultures</button>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-line icon-inline"></i>Rendements</h3>
                        <div class="card-description">Analyse de vos rendements agricoles</div>
                    </div>
                    <div class="card-content">
                        <div class="stat-number">3</div>
                        <button class="btn btn-secondary">Voir tous les rendements</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="container">
            <h2 class="section-title"><i class="fas fa-map-marked-alt icon-inline"></i>Aperçu des parcelles</h2>
            <div class="card-grid">
                <!-- Parcel Card 1 -->
                <div class="card parcel-card">
                    <div class="parcel-card-header">
                        <div class="parcel-card-title">
                            Parcelle Nord
                            <span class="parcel-card-area">2.5 ha</span>
                        </div>
                    </div>
                    <div class="parcel-card-content">
                        <p><span class="parcel-card-label">Localisation:</span> Secteur Nord-Est</p>
                        <div class="mt-2">
                            <span class="parcel-card-label">Cultures:</span>
                            <div class="tag-list">
                                <span class="tag">Maïs</span>
                                <span class="tag">Soja</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Parcel Card 2 -->
                <div class="card parcel-card">
                    <div class="parcel-card-header">
                        <div class="parcel-card-title">
                            Parcelle Sud
                            <span class="parcel-card-area">3.2 ha</span>
                        </div>
                    </div>
                    <div class="parcel-card-content">
                        <p><span class="parcel-card-label">Localisation:</span> Secteur Sud</p>
                        <div class="mt-2">
                            <span class="parcel-card-label">Cultures:</span>
                            <div class="tag-list">
                                <span class="tag">Blé</span>
                                <span class="tag">Tournesol</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Parcel Card -->
                <div class="card add-card">
                    <button class="add-button">
                        <i class="fas fa-plus icon-inline"></i>
                        <span>Ajouter une parcelle</span>
                    </button>
                </div>
            </div>
        </section>

        <section class="container">
            <h2 class="section-title"><i class="fas fa-seedling icon-inline"></i>Cultures en cours</h2>
            <div class="card-grid">
                <!-- Crop Card 1 -->
                <div class="card crop-card">
                    <div class="crop-card-header">
                        <div class="crop-card-title">
                            Maïs
                            <span class="crop-status status-growth">En croissance</span>
                        </div>
                    </div>
                    <div class="crop-card-content">
                        <p><span class="crop-card-label">Variété:</span> Hybride F1</p>
                        <p><span class="crop-card-label">Parcelle:</span> Parcelle Nord</p>
                        <p><span class="crop-card-label">Date de plantation:</span> 15/04/2023</p>
                        <p><span class="crop-card-label">Quantité:</span> 500 kg</p>
                    </div>
                </div>

                <!-- Crop Card 2 -->
                <div class="card crop-card">
                    <div class="crop-card-header">
                        <div class="crop-card-title">
                            Blé
                            <span class="crop-status status-harvest">Prêt pour récolte</span>
                        </div>
                    </div>
                    <div class="crop-card-content">
                        <p><span class="crop-card-label">Variété:</span> Blé dur</p>
                        <p><span class="crop-card-label">Parcelle:</span> Parcelle Sud</p>
                        <p><span class="crop-card-label">Date de plantation:</span> 10/03/2023</p>
                        <p><span class="crop-card-label">Quantité:</span> 800 kg</p>
                    </div>
                </div>

                <!-- Add Crop Card -->
                <div class="card add-card">
                    <button class="add-button">
                        <i class="fas fa-plus icon-inline"></i>
                        <span>Ajouter une culture</span>
                    </button>
                </div>
            </div>
        </section>

        <section class="container chart-container">
            <h2 class="section-title"><i class="fas fa-chart-bar icon-inline"></i>Rendements récents</h2>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Rendements par culture (kg)</h3>
                </div>
                <div class="card-content" style="height: 300px; display: flex; align-items: center; justify-content: center;">
                    <!-- Diagramme fictif professionnel de rendements de culture (kg) -->
                    <div style="background:#fff; border-radius:12px; box-shadow:0 2px 8px rgba(40,167,69,0.10); padding: 1.5rem 1rem; width:95%; min-width:340px; min-height:220px; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                        <strong style="margin-bottom:8px; color:#232323;">Rendements de cultures (kg)</strong>
                        <svg width="95%" height="180" viewBox="0 0 600 180" style="max-width:100%; height:auto;">
                            <!-- Axes -->
                            <line x1="60" y1="20" x2="60" y2="150" stroke="#888" stroke-width="2" />
                            <line x1="60" y1="150" x2="560" y2="150" stroke="#888" stroke-width="2" />
                            <!-- Barres -->
                            <rect x="110" y="70" width="60" height="80" fill="#28a745" rx="10"/>
                            <rect x="210" y="100" width="60" height="50" fill="#218838" rx="10"/>
                            <rect x="310" y="50" width="60" height="100" fill="#ffc107" rx="10"/>
                            <rect x="410" y="120" width="60" height="30" fill="#007bff" rx="10"/>
                            <!-- Labels -->
                            <text x="140" y="170" font-size="22" text-anchor="middle" fill="#232323">Maïs</text>
                            <text x="240" y="170" font-size="22" text-anchor="middle" fill="#232323">Blé</text>
                            <text x="340" y="170" font-size="22" text-anchor="middle" fill="#232323">Soja</text>
                            <text x="440" y="170" font-size="22" text-anchor="middle" fill="#232323">Riz</text>
                            <!-- Valeurs -->
                            <text x="140" y="65" font-size="18" text-anchor="middle" fill="#28a745">800</text>
                            <text x="240" y="95" font-size="18" text-anchor="middle" fill="#218838">500</text>
                            <text x="340" y="45" font-size="18" text-anchor="middle" fill="#ffc107">1000</text>
                            <text x="440" y="115" font-size="18" text-anchor="middle" fill="#007bff">300</text>
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        <section id="inscription">
            <h2 class="section-title"><i class="fas fa-user-plus icon-inline"></i>Inscription</h2>
            <div class="card">
                <div class="card-content">
                    <form id="clientForm" class="user-form" style="display: none;" action="inscription.php" method="POST">
                        <input type="hidden" name="userType" value="client">
                        <div class="form-title-icon"><i class="fas fa-user"></i></div>
                        <h3 class="form-title-text">Client</h3>
                        <div class="form-group input-icon-group">
                            <span class="input-icon"><i class="fas fa-user"></i></span>
                            <input type="text" id="clientName" name="clientName" placeholder="Votre nom" required>
                        </div>
                        <div class="form-group input-icon-group">
                            <span class="input-icon"><i class="fas fa-envelope"></i></span>
                            <input type="email" id="clientEmail" name="clientEmail" placeholder="Votre email" required>
                        </div>
                        <div class="form-group input-icon-group">
                            <span class="input-icon"><i class="fas fa-lock"></i></span>
                            <input type="password" id="clientPassword" name="clientPassword" placeholder="Votre mot de passe" required>
                        </div>
                        <button type="submit" class="form-button"><i class="fas fa-user-plus icon-inline"></i>S'inscrire</button>
                    </form>
                    <form id="merchantForm" class="user-form" style="display: none;" action="inscription.php" method="POST">
                        <input type="hidden" name="userType" value="merchant">
                        <div class="form-title-icon"><i class="fas fa-store"></i></div>
                        <h3 class="form-title-text">Marchand</h3>
                        <div class="form-group input-icon-group">
                            <span class="input-icon"><i class="fas fa-building"></i></span>
                            <input type="text" id="merchantName" name="merchantName" placeholder="Nom de l'entreprise" required>
                        </div>
                        <div class="form-group input-icon-group">
                            <span class="input-icon"><i class="fas fa-envelope"></i></span>
                            <input type="email" id="merchantEmail" name="merchantEmail" placeholder="Votre email" required>
                        </div>
                        <div class="form-group input-icon-group">
                            <span class="input-icon"><i class="fas fa-lock"></i></span>
                            <input type="password" id="merchantPassword" name="merchantPassword" placeholder="Votre mot de passe" required>
                        </div>
                        <div class="form-group input-icon-group">
                            <span class="input-icon"><i class="fas fa-map-marker-alt"></i></span>
                            <input type="text" id="merchantAddress" name="merchantAddress" placeholder="Adresse de l'entreprise" required>
                        </div>
                        <button type="submit" class="form-button"><i class="fas fa-user-plus icon-inline"></i>S'inscrire</button>
                    </form>
                    <form id="farmerForm" class="user-form" style="display: none;" action="inscription.php" method="POST">
                        <input type="hidden" name="userType" value="farmer">
                        <div class="form-title-icon"><i class="fas fa-tractor"></i></div>
                        <h3 class="form-title-text">Agriculteur</h3>
                        <div class="form-group input-icon-group">
                            <span class="input-icon"><i class="fas fa-user"></i></span>
                            <input type="text" id="farmerName" name="farmerName" placeholder="Votre nom" required>
                        </div>
                        <div class="form-group input-icon-group">
                            <span class="input-icon"><i class="fas fa-envelope"></i></span>
                            <input type="email" id="farmerEmail" name="farmerEmail" placeholder="Votre email" required>
                        </div>
                        <div class="form-group input-icon-group">
                            <span class="input-icon"><i class="fas fa-lock"></i></span>
                            <input type="password" id="farmerPassword" name="farmerPassword" placeholder="Votre mot de passe" required>
                        </div>
                        <div class="form-group input-icon-group">
                            <span class="input-icon"><i class="fas fa-map-marker-alt"></i></span>
                            <input type="text" id="farmerLocation" name="farmerLocation" placeholder="Votre localisation" required>
                        </div>
                        <button type="submit" class="form-button"><i class="fas fa-user-plus icon-inline"></i>S'inscrire</button>
                    </form>
                    <div class="inscription-buttons">
                        <button onclick="showForm('clientForm')" aria-label="Je suis un Client"><i class="fas fa-user"></i>Client</button>
                        <button onclick="showForm('merchantForm')" aria-label="Je suis un Marchand"><i class="fas fa-store"></i>Marchand</button>
                        <button onclick="showForm('farmerForm')" aria-label="Je suis un Agriculteur"><i class="fas fa-tractor"></i>Agriculteur</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="container" id="rechercher">
            <h2 class="section-title"><i class="fas fa-search icon-inline"></i>Rechercher des Partenaires ou des entreprises marchands!</h2>
            <div class="card">
                <div class="card-content search-content">
                    <input type="text" id="searchInput" placeholder="Rechercher...">
                    <label for="filterType">Filtrer par type :</label>
                    <select id="filterType" aria-label="Filtrer par type">
                        <option value="all">Tous</option>
                        <option value="agriculteur">Agriculteurs</option>
                        <option value="client">Clients</option>
                        <option value="marchand">Marchands</option>
                    </select>
                    <button onclick="performSearch()"><i class="fas fa-search icon-inline"></i>Rechercher</button>
                    <div id="searchResults"></div>
                </div>
            </div>
        </section>

        <section class="container">
            <h2 class="section-title"><i class="fas fa-users icon-inline"></i>Profils</h2>
            <div class="card-grid">
                <!-- Exemple de profil -->
                <div class="card profile-card">
                    <div class="profile-card-header">
                        <h3>Agriculteur Tadjo Nde</h3>
                        <span class="profile-location">Douala, Cameroun</span>
                    </div>
                    <div class="profile-card-content">
                        <p><span class="profile-label">Spécialité:</span> Légumes biologiques</p>
                        <p><span class="profile-label">Contact:</span> tadjonde@gmail.com</p>
                        <button class="btn btn-secondary"><i class="fas fa-eye icon-inline"></i>Contacter</button>
                    </div>
                </div>
                <!-- Ajouter plus de profils ici -->
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>À propos</h3>
                    <p>AgroPilot est une plateforme de mise en relation pour les agriculteurs, marchands et clients.</p>
                </div>
                <div class="footer-section">
                    <h3>Liens utiles</h3>
                    <ul>
                        <li><a href="#">Accueil</a></li>
                        <li><a href="#">Rechercher</a></li>
                        <li><a href="#">Connexion</a></li>
                        <li><a href="#">Inscription</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact</h3>
                    <p>Email: contact@agropilot.com</p>
                    <p>Téléphone: +33 1 23 45 67 89</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 AgroPilot. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <div id="successPopup" class="popup">
        <div class="popup-content">
            <span class="close-popup">&times;</span>
            <p>Votre inscription a été réussie !</p>
        </div>
    </div>

    <script>
        function showForm(formId) {
            document.getElementById('clientForm').style.display = 'none';
            document.getElementById('merchantForm').style.display = 'none';
            document.getElementById('farmerForm').style.display = 'none';
            document.getElementById(formId).style.display = 'block';
        }

        let currentPage = 1;

        function performSearch() {
            const query = document.getElementById('searchInput').value.trim();
            if (query === '') {
                alert('Veuillez entrer un terme de recherche.');
                return;
            }
            const type = document.getElementById('filterType').value;
            fetch(`search.php?query=${query}&type=${type}`)
                .then(response => response.json())
                .then(data => {
                    const results = document.getElementById('searchResults');
                    results.innerHTML = '';
                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'search-result-item';
                        const pName = document.createElement('p');
                        pName.textContent = `Nom: ${item.name}`;
                        div.appendChild(pName);
                        const pEmail = document.createElement('p');
                        pEmail.textContent = `Email: ${item.email}`;
                        div.appendChild(pEmail);
                        if (item.type) {
                            const pType = document.createElement('p');
                            pType.textContent = `Type: ${item.type}`;
                            div.appendChild(pType);
                        }
                        results.appendChild(div);
                    });
                });
        }

        function nextPage() {
            currentPage++;
            performSearch();
        }

        function previousPage() {
            if (currentPage > 1) {
                currentPage--;
                performSearch();
            }
        }

        function showSuccessPopup() {
            document.getElementById('successPopup').style.display = 'flex';
        }

        document.querySelector('.close-popup').addEventListener('click', function() {
            document.getElementById('successPopup').style.display = 'none';
        });

        // Gérer la soumission du formulaire via AJAX
        document.querySelectorAll('.user-form').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Empêcher la soumission normale du formulaire

                const formData = new FormData(form); // Récupérer les données du formulaire

                fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessPopup(); // Afficher le pop-up en cas de succès
                        form.reset(); // Vider le formulaire
                    } else {
                        alert("Erreur lors de l'inscription : " + data.error); // Afficher une alerte en cas d'erreur
                    }
                })
                .catch(error => {
                    console.error('Erreur :', error);
                });
            });
        });

        document.getElementById('searchInput').addEventListener('input', function() {
            performSearch();
        });

        // Menu mobile déroulant
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileNav = document.getElementById('mobile-nav');
        const mobileNavClose = document.getElementById('mobile-nav-close');
        const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');

        // Ouvrir le menu mobile
        mobileMenuToggle.addEventListener('click', function() {
            mobileNav.classList.add('active');
            document.body.style.overflow = 'hidden'; // Empêcher le scroll
        });

        // Fermer le menu mobile
        function closeMobileNav() {
            mobileNav.classList.remove('active');
            document.body.style.overflow = ''; // Réactiver le scroll
        }

        mobileNavClose.addEventListener('click', closeMobileNav);

        // Fermer le menu en cliquant sur un lien
        mobileNavLinks.forEach(link => {
            link.addEventListener('click', function() {
                closeMobileNav();
            });
        });

        // Fermer le menu en cliquant en dehors
        mobileNav.addEventListener('click', function(e) {
            if (e.target === mobileNav) {
                closeMobileNav();
            }
        });

        // Fermer le menu avec la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileNav.classList.contains('active')) {
                closeMobileNav();
            }
        });
    </script>
</body>
</html>

