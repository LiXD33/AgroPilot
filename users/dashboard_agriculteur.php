<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');

// Vérification de connexion
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'agriculteur') {
    header("Location: login.php");
    exit();
}

$agriculteur_nom = isset($_SESSION['agriculteur_nom']) ? $_SESSION['agriculteur_nom'] : 'Agriculteur';
?>
<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroPilot Agriculteur - Dashboard</title>
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
        /* Ajoute ici des styles spécifiques si besoin */
        .tab-btn.active { background: var(--primary); color: #fff; }
        .tab-btn { background: var(--secondary); color: var(--text); border: none; border-radius: 8px 8px 0 0; padding: 0.7rem 2.2rem; font-weight: 600; cursor: pointer; margin-right: 1rem; }
        .tab-btn:not(.active):hover { background: #23272f; }
        .badge-state { border-radius: 999px; padding: 0.2em 1em; font-size: 0.95em; font-weight: 700; }
        .badge-excellent { background: var(--badge-green); color: #fff; }
        .badge-bonne { background: var(--badge-blue); color: #fff; }
        .badge-surveillee { background: var(--badge-yellow); color: #232323; }
        .message-section { margin-top: 2rem; }
        .message-form textarea { width: 100%; min-height: 60px; border-radius: 10px; border: none; padding: 0.7rem; font-size: 1rem; }
        .message-form button { margin-top: 0.5rem; }
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
                        <h1>AgroPilot Agriculteur</h1>
                        <p>Interface producteur</p>
                    </div>
                </div>
            </div>
            <div class="header-right">
                <button class="favorites-btn" style="display:none"></button>
                <button class="cart-btn" style="display:none"></button>
                <button class="add-to-cart-btn" style="background:var(--primary);margin-right:1rem;" onclick="alert('Nouvelle culture !')"><i class="fas fa-plus icon-inline"></i>Nouvelle culture</button>
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
            // Récupérer le nom de l'agriculteur depuis la session
            if (isset($_SESSION['agriculteur_nom'])) {
                $nom = htmlspecialchars($_SESSION['agriculteur_nom']);
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
            <aside class="sidebar" style="max-width:350px;">
                <!-- Statistiques -->
                <div class="card" style="display:flex;gap:1.2rem;flex-wrap:wrap;">
                    <div style="flex:1;min-width:120px;">
                        <div style="font-size:1.3rem;font-weight:700;color:var(--primary);">12.5T</div>
                        <div style="color:var(--text-muted);font-size:1.05em;">Production totale</div>
                        <div style="color:#4caf50;font-size:0.95em;">+15.2%</div>
                    </div>
                    <div style="flex:1;min-width:120px;">
                        <div style="font-size:1.3rem;font-weight:700;color:var(--primary);">18 450€</div>
                        <div style="color:var(--text-muted);font-size:1.05em;">Revenus ce mois</div>
                        <div style="color:#4caf50;font-size:0.95em;">+8.7%</div>
                    </div>
                </div>
                <div class="card" style="display:flex;gap:1.2rem;flex-wrap:wrap;">
                    <div style="flex:1;min-width:120px;">
                        <div style="font-size:1.3rem;font-weight:700;color:var(--primary);">8</div>
                        <div style="color:var(--text-muted);font-size:1.05em;">Cultures actives</div>
                        <div style="color:#4caf50;font-size:0.95em;">+2</div>
                    </div>
                    <div style="flex:1;min-width:120px;">
                        <div style="font-size:1.3rem;font-weight:700;color:var(--primary);">24</div>
                        <div style="color:var(--text-muted);font-size:1.05em;">Commandes en attente</div>
                        <div style="color:#4caf50;font-size:0.95em;">+12</div>
                    </div>
                </div>
                <!-- Météo agricole -->
                <div class="card">
                    <h3 class="card-title">🌱 Météo agricole</h3>
                    <div style="margin-top:1rem;">
                        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:0.7rem;">
                            <span style="font-size:2rem;">☀️</span>
                            <div>
                                <div>Aujourd’hui</div>
                                <div style="font-size:1.1em;color:var(--primary);">24°C</div>
                                <div style="font-size:0.95em;color:var(--text-muted);">Ensoleillé</div>
                            </div>
                            <div style="margin-left:auto;font-size:0.95em;color:#4caf50;">0%</div>
                        </div>
                        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:0.7rem;">
                            <span style="font-size:2rem;">⛅</span>
                            <div>
                                <div>Demain</div>
                                <div style="font-size:1.1em;color:var(--primary);">22°C</div>
                                <div style="font-size:0.95em;color:var(--text-muted);">Nuageux</div>
                            </div>
                            <div style="margin-left:auto;font-size:0.95em;color:#4caf50;">15%</div>
                        </div>
                        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:0.7rem;">
                            <span style="font-size:2rem;">🌧️</span>
                            <div>
                                <div>Vendredi</div>
                                <div style="font-size:1.1em;color:var(--primary);">19°C</div>
                                <div style="font-size:0.95em;color:var(--text-muted);">Pluvieux</div>
                            </div>
                            <div style="margin-left:auto;font-size:0.95em;color:#4caf50;">85%</div>
                        </div>
                        <div style="display:flex;align-items:center;gap:1rem;">
                            <span style="font-size:2rem;">☀️</span>
                            <div>
                                <div>Samedi</div>
                                <div style="font-size:1.1em;color:var(--primary);">25°C</div>
                                <div style="font-size:0.95em;color:var(--text-muted);">Ensoleillé</div>
                            </div>
                            <div style="margin-left:auto;font-size:0.95em;color:#4caf50;">5%</div>
                        </div>
                    </div>
                </div>
                <!-- Actions rapides -->
                <div class="card">
                    <h3 class="card-title">Actions rapides</h3>
                    <button class="add-to-cart-btn" style="width:100%;margin-bottom:0.5rem;">Surveiller température</button>
                    <button class="add-to-cart-btn" style="width:100%;margin-bottom:0.5rem;">Planifier irrigation</button>
                    <button class="add-to-cart-btn" style="width:100%;">Programmer traitement</button>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="main-content">
                <div style="display:flex;gap:1rem;margin-bottom:1.2rem;">
                    <button class="tab-btn active" onclick="showTab('cultures')">Mes cultures</button>
                    <button class="tab-btn" onclick="showTab('commandes')">Commandes</button>
                </div>
                <div id="tab-cultures">
                    <div class="card">
                        <h3>Suivi des cultures</h3>
                        <p>Surveillez l’évolution de vos parcelles</p>
                        <!-- Culture 1 -->
                        <div class="product-card" style="margin-bottom:1.2rem;">
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <div>
                                    <strong>Tomates Bio</strong>
                                    <div>🌱 Stade: <b>Floraison</b></div>
                                    <div>📏 Surface: <b>2.5 ha</b></div>
                                </div>
                                <span class="badge-state badge-excellent">Excellente</span>
                            </div>
                            <div style="display:flex;gap:2rem;margin-top:0.7rem;">
                                <div>📅 Récolte prévue: <b>15/07/2024</b></div>
                                <div>🥔 Rendement estimé: <span style="color:var(--primary);font-weight:700;">45T</span></div>
                            </div>
                            <div style="margin-top:0.7rem;">
                                <button class="add-to-cart-btn" style="margin-right:0.7rem;">Voir détails</button>
                                <button class="add-to-cart-btn">Planifier tâche</button>
                            </div>
                        </div>
                        <!-- Culture 2 -->
                        <div class="product-card" style="margin-bottom:1.2rem;">
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <div>
                                    <strong>Pommes Golden</strong>
                                    <div>🌱 Stade: <b>Développement fruit</b></div>
                                    <div>📏 Surface: <b>4.2 ha</b></div>
                                </div>
                                <span class="badge-state badge-bonne">Bonne</span>
                            </div>
                            <div style="display:flex;gap:2rem;margin-top:0.7rem;">
                                <div>📅 Récolte prévue: <b>20/09/2024</b></div>
                                <div>🥔 Rendement estimé: <span style="color:var(--primary);font-weight:700;">8T</span></div>
                            </div>
                            <div style="margin-top:0.7rem;">
                                <button class="add-to-cart-btn" style="margin-right:0.7rem;">Voir détails</button>
                                <button class="add-to-cart-btn">Planifier tâche</button>
                            </div>
                        </div>
                        <!-- Culture 3 -->
                        <div class="product-card">
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <div>
                                    <strong>Blé tendre</strong>
                                    <div>🌱 Stade: <b>Maturation</b></div>
                                    <div>📏 Surface: <b>12 ha</b></div>
                                </div>
                                <span class="badge-state badge-surveillee">Surveillée</span>
                            </div>
                            <div style="display:flex;gap:2rem;margin-top:0.7rem;">
                                <div>📅 Récolte prévue: <b>25/07/2024</b></div>
                                <div>🥔 Rendement estimé: <span style="color:var(--primary);font-weight:700;">95T</span></div>
                            </div>
                            <div style="margin-top:0.7rem;">
                                <button class="add-to-cart-btn" style="margin-right:0.7rem;">Voir détails</button>
                                <button class="add-to-cart-btn">Planifier tâche</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="tab-commandes" style="display:none;">
                    <div class="card">
                        <h3>Commandes en attente</h3>
                        <p>Liste des commandes à préparer ou livrer...</p>
                    </div>
                </div>
                <!-- Espace message -->
                <div class="message-section card">
                    <h3>Envoyer un message</h3>
                    <form class="message-form">
                        <label for="destinataire"><i class="fas fa-user icon-inline"></i>Destinataire :</label>
                        <select id="destinataire" name="destinataire" required>
                            <option value="">Choisir un destinataire</option>
                            <option value="marchand">Marchand</option>
                            <option value="client">Client</option>
                        </select>
                        <input type="email" name="receiver_email" id="receiver_email" placeholder="Email du client" required style="margin-bottom:0.7rem;width:100%;border-radius:10px;padding:0.7rem 1rem;" />
                        <textarea name="message" placeholder="Votre message..." required></textarea>
                        <button type="submit" class="add-to-cart-btn"><i class="fas fa-paper-plane icon-inline"></i>Envoyer</button>
                    </form>
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
                    <option value="client">Client</option>
                </select>
                <input type="email" name="receiver_email" id="receiver_email" placeholder="Email du client" required style="margin-bottom:0.7rem;width:100%;border-radius:10px;padding:0.7rem 1rem;" />
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
                    <i class="fas fa-seedling"></i>
                </div>
                <h2>Bienvenue !</h2>
            </div>
            <div class="welcome-popup-body">
                <p id="welcome-message">Bienvenue sur votre dashboard agriculteur</p>
            </div>
            <button class="welcome-popup-close" onclick="closeWelcomePopup()">
                <i class="fas fa-check"></i>
                Entrer
            </button>
        </div>
    </div>

    <script>
        // Gestion des onglets
        function showTab(tab) {
            document.getElementById('tab-cultures').style.display = (tab === 'cultures') ? '' : 'none';
            document.getElementById('tab-commandes').style.display = (tab === 'commandes') ? '' : 'none';
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelector('.tab-btn[onclick*="' + tab + '"]').classList.add('active');
        }
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
            const userName = '<?php echo isset($_SESSION["agriculteur_nom"]) ? htmlspecialchars($_SESSION["agriculteur_nom"]) : "Agriculteur"; ?>';
            
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
            welcomeMessage.textContent = `${greeting} ${userName} ! Prêt à gérer vos cultures et suivre vos parcelles ?`;
            
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