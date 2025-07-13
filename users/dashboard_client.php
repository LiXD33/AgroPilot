<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');

// Vérifier si l'utilisateur est connecté et est un client
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'client') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroPilot Client - Dashboard</title>
    <link rel="stylesheet" href="client-dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <style>
        html, body { max-width: 100vw; overflow-x: hidden; }
        .main-container, .sidebar, .card, .header-container, .header, .layout, .product-card, .message-section, .orders-list, .category-list {
            box-sizing: border-box;
        }
        @media (max-width: 800px) {
            .main-container, .sidebar, .card, .header-container, .header, .layout, .product-card, .message-section {
                padding-left: 0.2rem !important; padding-right: 0.2rem !important;
            }
            h1, h2, h3 { font-size: 1.1em !important; }
        }
        .icon-inline { margin-right: 0.4em; }
        .add-to-cart-btn, .logout-btn, .favorites-btn, .cart-btn, .compose-btn, .tab-btn {
            display: inline-flex; align-items: center; gap: 0.5em;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <div class="header-left">
                <div class="brand">
                    <div class="brand-icon">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M7 20h10"/>
                            <path d="M10 20c5.5-2.5.8-6.4 3-10"/>
                            <path d="M9.5 9.4c1.1.8 1.8 2.2 2.3 3.7-2 .4-3.5.4-4.8-.3-1.2-.6-2.3-1.9-3-4.2 2.8-.5 4.4 0 5.5.8z"/>
                            <path d="M14.1 6a7 7 0 0 0-1.1 4c1.9-.1 3.3-.7 4.3-1.4 1-1.1 1.6-2.7 1.7-4.6-2.7.1-4 1-4.9 2z"/>
                        </svg>
        </div>
                    <div class="brand-text">
                        <h1>AgroPilot Client</h1>
                        <p>Interface consommateur</p>
        </div>
            </div>
            </div>
            <div class="header-right">
                <button class="favorites-btn"><i class="fas fa-heart icon-inline"></i>Favoris</button>
                <button class="cart-btn"><i class="fas fa-shopping-basket icon-inline"></i>Panier (3)</button>
                <button class="add-to-cart-btn" id="open-message-modal" style="background:var(--primary);margin-right:1rem;position:relative;"><i class="fas fa-envelope icon-inline"></i>Messages
                    <span id="msg-notif" style="display:none;position:absolute;top:2px;right:6px;background:var(--badge-red);color:#fff;border-radius:50%;font-size:0.85em;padding:2px 7px;font-weight:700;">0</span>
                </button>
                <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt icon-inline"></i>Déconnexion</a>
            </div>
        </div>
    </header>

    <div class="main-container">
        <div class="welcome-message" style="font-size:1.3rem;font-weight:600;color:var(--primary);margin-bottom:1.2rem;">
            <?php
            // Récupérer le nom du client depuis la session
            if (isset($_SESSION['client_nom'])) {
                $nom = htmlspecialchars($_SESSION['client_nom']);
                $hour = date('H');
                if ($hour < 12) {
                    $greeting = 'Bonjour';
                } elseif ($hour < 18) {
                    $greeting = 'Bon après-midi';
                } else {
                    $greeting = 'Bonsoir';
                }
                echo "$greeting $nom !";
            } else {
                echo "Bonjour !";
            }
            ?>
        </div>
        <div class="layout">
            <!-- Sidebar -->
            <aside class="sidebar">
                <!-- Search Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="m21 21-4.35-4.35"/>
                            </svg>
                            Recherche
                        </h3>
                    </div>
                    <div class="card-content">
                        <input type="text" placeholder="Rechercher un produit..." class="search-input">
                    </div>
                </div>

                <!-- Categories Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="22,3 2,3 10,12.46 10,19 14,21 14,12.46 22,3"/>
                            </svg>
                            Catégories
                        </h3>
                    </div>
                    <div class="card-content">
                        <div class="category-list">
                            <button class="category-item active">
                                <span>Tous</span>
                                <span class="badge">24</span>
                            </button>
                            <button class="category-item">
                                <span>Fruits</span>
                                <span class="badge">8</span>
                            </button>
                            <button class="category-item">
                                <span>Légumes</span>
                                <span class="badge">12</span>
                            </button>
                            <button class="category-item">
                                <span>Céréales</span>
                                <span class="badge">4</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Mes commandes récentes</h3>
                    </div>
                    <div class="card-content">
                        <div class="orders-list">
                            <div class="order-item">
                                <div class="order-header">
                                    <span class="order-id">#1</span>
                                    <span class="badge status-delivery">En livraison</span>
                                </div>
                                <p class="order-items">Tomates Bio x2kg</p>
                                <p class="order-total">9.00€</p>
                            </div>
                            <div class="order-item">
                                <div class="order-header">
                                    <span class="order-id">#2</span>
                                    <span class="badge status-prepared">Préparé</span>
                                </div>
                                <p class="order-items">Pommes Golden x5kg</p>
                                <p class="order-total">16.00€</p>
                            </div>
                            <div class="order-item">
                                <div class="order-header">
                                    <span class="order-id">#3</span>
                                    <span class="badge status-delivered">Livré</span>
                                </div>
                                <p class="order-items">Courgettes x3kg</p>
                                <p class="order-total">6.30€</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bloc message supprimé et remplacé par un espace d'envoi de message -->
                <div class="card message-section">
                    <h3>Envoyer un message</h3>
                    <form class="message-form">
                        <label for="destinataire"><i class="fas fa-user icon-inline"></i>Destinataire :</label>
                        <select id="destinataire" name="destinataire" required>
                            <option value="">Choisir un destinataire</option>
                            <option value="marchand">Marchand</option>
                            <option value="agriculteur">Agriculteur</option>
                        </select>
                        <input type="email" name="receiver_email" id="receiver_email" placeholder="Email de l'agriculteur" required style="margin-bottom:0.7rem;width:100%;border-radius:10px;padding:0.7rem 1rem;" />
                        <textarea name="message" placeholder="Votre message..." required></textarea>
                        <button type="submit" class="add-to-cart-btn"><i class="fas fa-paper-plane icon-inline"></i>Envoyer</button>
                    </form>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="main-content">
                <div class="content-header">
                    <h2 class="content-header"><i class="fas fa-store icon-inline"></i>Produits locaux disponibles</h2>
                    <p>Découvrez les meilleurs produits de nos agriculteurs partenaires</p>
                </div>

                <div class="products-grid">
                    <!-- Product 1 -->
                    <div class="product-card">
                        <div class="product-header">
                            <div class="product-image">🍅</div>
                            <div class="product-info">
                                <h4 class="product-name">Tomates Bio</h4>
                                <p class="product-farmer">Ferme Martin</p>
                            </div>
                        </div>
                        <div class="product-content">
                            <div class="product-pricing">
                                <span class="product-price">4.50€/kg</span>
                                <div class="product-rating">
                                    <svg class="star-icon" viewBox="0 0 24 24" fill="currentColor">
                                        <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/>
                                    </svg>
                                    <span>4.8</span>
                                </div>
                            </div>
                            <div class="product-location">
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                                Provence, 15km
                            </div>
                            <button class="add-to-cart-btn"><i class="fas fa-shopping-cart icon-inline"></i>Ajouter au panier</button>
                        </div>
                    </div>

                    <!-- Product 2 -->
                    <div class="product-card">
                        <div class="product-header">
                            <div class="product-image">🍎</div>
                            <div class="product-info">
                                <h4 class="product-name">Pommes Golden</h4>
                                <p class="product-farmer">Vergers Dubois</p>
                            </div>
                        </div>
                        <div class="product-content">
                            <div class="product-pricing">
                                <span class="product-price">3.20€/kg</span>
                                <div class="product-rating">
                                    <svg class="star-icon" viewBox="0 0 24 24" fill="currentColor">
                                        <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/>
                                    </svg>
                                    <span>4.6</span>
                                </div>
                            </div>
                            <div class="product-location">
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                                Normandie, 8km
                            </div>
                            <button class="add-to-cart-btn">Ajouter au panier</button>
                        </div>
                    </div>

                    <!-- Product 3 -->
                    <div class="product-card">
                        <div class="product-header">
                            <div class="product-image">🌾</div>
                            <div class="product-info">
                                <h4 class="product-name">Blé Bio</h4>
                                <p class="product-farmer">Exploitation Leroy</p>
                            </div>
                            <div class="out-of-stock-badge">Rupture</div>
                        </div>
                        <div class="product-content">
                            <div class="product-pricing">
                                <span class="product-price">2.80€/kg</span>
                                <div class="product-rating">
                                    <svg class="star-icon" viewBox="0 0 24 24" fill="currentColor">
                                        <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/>
                                    </svg>
                                    <span>4.9</span>
                                </div>
                            </div>
                            <div class="product-location">
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                                Beauce, 25km
                            </div>
                            <button class="add-to-cart-btn disabled" disabled>Non disponible</button>
                        </div>
                    </div>

                    <!-- Product 4 -->
                    <div class="product-card">
                        <div class="product-header">
                            <div class="product-image">🥒</div>
                            <div class="product-info">
                                <h4 class="product-name">Courgettes</h4>
                                <p class="product-farmer">Maraîchers Verts</p>
                            </div>
                        </div>
                        <div class="product-content">
                            <div class="product-pricing">
                                <span class="product-price">2.10€/kg</span>
                                <div class="product-rating">
                                    <svg class="star-icon" viewBox="0 0 24 24" fill="currentColor">
                                        <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/>
                                    </svg>
                                    <span>4.7</span>
        </div>
    </div>
                            <div class="product-location">
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                </svg>
                                Local, 5km
                            </div>
                            <button class="add-to-cart-btn">Ajouter au panier</button>
                        </div>
                    </div>
            </div>
            </main>
        </div>
            </div>

    <!-- Popup message -->
    <div id="message-modal" class="card message-section" style="display:none;position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:1000;min-width:340px;max-width:95vw;width:400px;box-shadow:0 8px 32px 0 rgba(31,38,135,0.18);">
        <div style="display:flex;gap:1rem;margin-bottom:1rem;">
            <button id="tab-inbox" class="tab-btn active" style="flex:1;">Boîte de réception</button>
            <button id="tab-newmsg" class="tab-btn" style="flex:1;">Nouveau message</button>
            </div>
        <div id="inbox-section">
            <div id="inbox-list" style="max-height:260px;overflow-y:auto;"></div>
            </div>
        <div id="newmsg-section" style="display:none;">
            <form class="message-form" id="send-msg-form">
                <label for="destinataire">Destinataire :</label>
                <select id="destinataire" name="destinataire" required>
                    <option value="">Choisir un destinataire</option>
                    <option value="agriculteur">Agriculteur</option>
                </select>
                <input type="email" name="receiver_email" id="receiver_email" placeholder="Email de l'agriculteur" required style="margin-bottom:0.7rem;width:100%;border-radius:10px;padding:0.7rem 1rem;" />
                <textarea name="message" placeholder="Votre message..." required></textarea>
                <button type="submit" class="add-to-cart-btn"><i class="fas fa-paper-plane icon-inline"></i>Envoyer</button>
            </form>
        </div>
        <button type="button" id="close-message-modal" class="logout-btn" style="margin-top:0.7rem;background:var(--badge-grey);color:#fff;">Fermer</button>
    </div>
    <div id="modal-overlay" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.45);z-index:999;"></div>

    <!-- Popup de bienvenue -->
    <div id="welcome-popup" class="welcome-popup" style="display:none;">
        <div class="welcome-popup-content">
            <div class="welcome-popup-header">
                <div class="welcome-icon">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <h2>Bienvenue !</h2>
            </div>
            <div class="welcome-popup-body">
                <p id="welcome-message">Bienvenue sur votre dashboard client</p>
            </div>
            <button class="welcome-popup-close" onclick="closeWelcomePopup()">
                <i class="fas fa-check"></i>
                Entrer
            </button>
        </div>
    </div>

    <script>
        // Simple interaction scripts
        document.addEventListener('DOMContentLoaded', function() {
            // Category filtering
            const categoryItems = document.querySelectorAll('.category-item');
            categoryItems.forEach(item => {
                item.addEventListener('click', function() {
                    categoryItems.forEach(cat => cat.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Search functionality
            const searchInput = document.querySelector('.search-input');
            searchInput.addEventListener('input', function() {
                console.log('Searching for:', this.value);
                // Add search logic here
            });

            // Add to cart buttons
            const addToCartBtns = document.querySelectorAll('.add-to-cart-btn:not(.disabled)');
            addToCartBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    console.log('Added to cart');
                    // Add to cart logic here
                });
            });
        });

        // Message form (exemple, à adapter pour l'envoi réel)
        document.querySelector('.message-form').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Message envoyé à ' + this.destinataire.value + ' : ' + this.message.value);
            this.reset();
        });

        // Messagerie avec notifications
        const openMsgBtn = document.getElementById('open-message-modal');
        const msgModal = document.getElementById('message-modal');
        const closeMsgBtn = document.getElementById('close-message-modal');
        const overlay = document.getElementById('modal-overlay');
        const notif = document.getElementById('msg-notif');
        const inboxSection = document.getElementById('inbox-section');
        const newmsgSection = document.getElementById('newmsg-section');
        const tabInbox = document.getElementById('tab-inbox');
        const tabNewmsg = document.getElementById('tab-newmsg');
        const inboxList = document.getElementById('inbox-list');
        const sendMsgForm = document.getElementById('send-msg-form');

        function showInbox() {
            inboxSection.style.display = '';
            newmsgSection.style.display = 'none';
            tabInbox.classList.add('active');
            tabNewmsg.classList.remove('active');
        }
        function showNewMsg() {
            inboxSection.style.display = 'none';
            newmsgSection.style.display = '';
            tabInbox.classList.remove('active');
            tabNewmsg.classList.add('active');
        }
        tabInbox.onclick = showInbox;
        tabNewmsg.onclick = showNewMsg;

        function fetchMessages() {
            fetch('get_messages.php')
                .then(r=>r.json())
                .then(data => {
                    if(data.success) {
                        // Affichage notification
                        if(data.unread > 0) {
                            notif.style.display = 'inline-block';
                            notif.textContent = data.unread;
                        } else {
                            notif.style.display = 'none';
                        }
                        // Affichage messages
                        inboxList.innerHTML = '';
                        if(data.messages.length === 0) {
                            inboxList.innerHTML = '<div style="color:#aaa;text-align:center;margin-top:2rem;">Aucun message reçu.</div>';
                        } else {
                            data.messages.forEach(msg => {
                                const div = document.createElement('div');
                                div.className = 'card';
                                div.style.marginBottom = '0.7rem';
                                let typeLabel = msg.sender_type ? (msg.sender_type.charAt(0).toUpperCase() + msg.sender_type.slice(1)) : '';
                                div.innerHTML = `<div style='font-weight:600;color:var(--primary);'>De : ${msg.sender_nom || 'Utilisateur'} - ${typeLabel}</div><div style='font-size:0.98em;margin:0.3em 0;'>${msg.message}</div><div style='font-size:0.92em;color:#aaa;'>${msg.created_at}</div>`;
                                if(msg.is_read == 0) div.style.background = 'rgba(40,167,69,0.10)';
                                inboxList.appendChild(div);
                            });
                        }
                    }
                });
        }
        function markAllRead() {
            fetch('get_messages.php')
                .then(r=>r.json())
                .then(data => {
                    if(data.success && data.messages.length > 0) {
                        data.messages.forEach(msg => {
                            if(msg.is_read == 0) {
                                fetch('read_message.php', {
                                    method:'POST',
                                    headers:{'Content-Type':'application/x-www-form-urlencoded'},
                                    body:'id='+msg.id
                                });
                            }
                        });
                    }
                });
        }
        openMsgBtn.onclick = function() {
            msgModal.style.display = 'block';
            overlay.style.display = 'block';
            showInbox();
            fetchMessages();
            setTimeout(markAllRead, 500);
        };
        closeMsgBtn.onclick = function() {
            msgModal.style.display = 'none';
            overlay.style.display = 'none';
        };
        overlay.onclick = function() {
            msgModal.style.display = 'none';
            overlay.style.display = 'none';
        };
        // Envoi message AJAX
        if(sendMsgForm) {
            sendMsgForm.onsubmit = function(e) {
                e.preventDefault();
                const formData = new FormData(sendMsgForm);
                fetch('send_message.php', {
                    method:'POST',
                    body:formData
                })
                .then(r=>r.json())
                .then(data => {
                    if(data.success) {
                        alert('Message envoyé !');
                        sendMsgForm.reset();
                        showInbox();
                        fetchMessages();
                    } else {
                        alert('Erreur : ' + (data.error || 'Envoi impossible'));
                    }
                });
            };
        }
        // Rafraîchissement périodique des notifications
        setInterval(fetchMessages, 30000);
        fetchMessages();

        // Popup de bienvenue
        function showWelcomePopup() {
            const welcomePopup = document.getElementById('welcome-popup');
            const welcomeMessage = document.getElementById('welcome-message');
            
            // Récupérer le nom de l'utilisateur depuis PHP
            const userName = '<?php echo isset($_SESSION["client_nom"]) ? htmlspecialchars($_SESSION["client_nom"]) : "Client"; ?>';
            
            // Messages personnalisés selon l'heure
            const hour = new Date().getHours();
            let greeting = '';
            
            if (hour < 12) {
                greeting = 'Bonjour';
            } else if (hour < 18) {
                greeting = 'Bon après-midi';
            } else {
                greeting = 'Bonsoir';
            }
            
            // Personnaliser le message
            welcomeMessage.textContent = `${greeting} ${userName} ! Nous sommes ravis de vous revoir sur votre espace client.`;
            
            // Afficher le popup avec animation
            welcomePopup.style.display = 'flex';
            setTimeout(() => {
                welcomePopup.classList.add('active');
            }, 100);
        }

        function closeWelcomePopup() {
            const welcomePopup = document.getElementById('welcome-popup');
            welcomePopup.classList.remove('active');
            setTimeout(() => {
                welcomePopup.style.display = 'none';
            }, 300);
        }

        // Afficher le popup de bienvenue au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            // Délai pour laisser la page se charger
            setTimeout(showWelcomePopup, 500);
        });
    </script>
</body>
</html> 