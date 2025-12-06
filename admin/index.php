<?php
session_start();

// Configuration
define('ADMIN_PASSWORD', 'dozadmin2025'); // À changer après premier test
define('DATA_DIR', __DIR__ . '/../data/');

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

// Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    if ($_POST['password'] === ADMIN_PASSWORD) {
        $_SESSION['logged_in'] = true;
    }
}

// Vérification connexion
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

// Sauvegarder les données
if ($isLoggedIn && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_planning'])) {
    $planning = [
        'monday' => json_decode($_POST['monday'], true),
        'tuesday' => json_decode($_POST['tuesday'], true),
        'wednesday' => json_decode($_POST['wednesday'], true),
        'thursday' => json_decode($_POST['thursday'], true),
        'friday' => json_decode($_POST['friday'], true),
        'saturday' => json_decode($_POST['saturday'], true),
        'sunday' => json_decode($_POST['sunday'], true)
    ];
    file_put_contents(DATA_DIR . 'planning.json', json_encode($planning, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $message = "Planning mis à jour avec succès !";
}

if ($isLoggedIn && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_menu'])) {
    $breakfast = [
        'salty' => json_decode($_POST['salty'], true),
        'sweet' => json_decode($_POST['sweet'], true)
    ];
    file_put_contents(DATA_DIR . 'menu_breakfast.json', json_encode($breakfast, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    $lunch = [
        'dishes' => json_decode($_POST['dishes'], true)
    ];
    file_put_contents(DATA_DIR . 'menu_lunch.json', json_encode($lunch, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $message = "Menus mis à jour avec succès !";
}

// Charger les données
$planning = json_decode(file_get_contents(DATA_DIR . 'planning.json'), true);
$breakfast = json_decode(file_get_contents(DATA_DIR . 'menu_breakfast.json'), true);
$lunch = json_decode(file_get_contents(DATA_DIR . 'menu_lunch.json'), true);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOZ Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; 
            background: #f5f5f5;
            color: #1a1a1a;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #4A6C58 0%, #C8C6E3 100%);
        }
        .login-box {
            background: white;
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
        }
        .login-box h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: #4A6C58;
        }
        .login-box p {
            color: #666;
            margin-bottom: 2rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }
        input[type="password"],
        input[type="text"],
        textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e0e0e0;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        input:focus, textarea:focus {
            outline: none;
            border-color: #4A6C58;
        }
        textarea {
            min-height: 150px;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
        }
        button {
            width: 100%;
            padding: 1rem;
            background: #4A6C58;
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #3a5648;
        }
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        .header {
            background: white;
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            border-radius: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .header h1 {
            font-size: 1.5rem;
            color: #4A6C58;
        }
        .logout-btn {
            width: auto;
            padding: 0.5rem 1.5rem;
            background: #dc3545;
            font-size: 0.9rem;
        }
        .logout-btn:hover {
            background: #c82333;
        }
        .tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            background: white;
            padding: 1rem;
            border-radius: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .tab {
            padding: 0.75rem 1.5rem;
            background: #f5f5f5;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        .tab.active {
            background: #4A6C58;
            color: white;
        }
        .tab-content {
            display: none;
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .tab-content.active {
            display: block;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #28a745;
        }
        .help-text {
            font-size: 0.85rem;
            color: #666;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>

<?php if (!$isLoggedIn): ?>
    <div class="login-container">
        <div class="login-box">
            <h1>🔐 DOZ Admin</h1>
            <p>Connexion requise</p>
            <form method="POST">
                <div class="form-group">
                    <label>Mot de passe</label>
                    <input type="password" name="password" required autofocus>
                </div>
                <button type="submit">Se connecter</button>
            </form>
        </div>
    </div>
<?php else: ?>
    <div class="admin-container">
        <div class="header">
            <h1>⚙️ DOZ Admin</h1>
            <a href="?logout" class="logout-btn" style="text-decoration: none; color: white;">Déconnexion</a>
        </div>

        <?php if (isset($message)): ?>
            <div class="success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="tabs">
            <button class="tab active" onclick="showTab('planning')">📅 Planning</button>
            <button class="tab" onclick="showTab('menu')">☕ Menu Coffee</button>
        </div>

        <!-- PLANNING -->
        <div id="tab-planning" class="tab-content active">
            <h2 style="margin-bottom: 1.5rem;">Gérer le Planning Lagree</h2>
            <form method="POST">
                <p class="help-text">Format JSON : [{"time": "07:15", "course": "LAGREE ESSENTIEL"}, ...]</p>
                
                <div class="form-group">
                    <label>Lundi</label>
                    <textarea name="monday"><?= json_encode($planning['monday'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Mardi</label>
                    <textarea name="tuesday"><?= json_encode($planning['tuesday'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Mercredi</label>
                    <textarea name="wednesday"><?= json_encode($planning['wednesday'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Jeudi</label>
                    <textarea name="thursday"><?= json_encode($planning['thursday'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Vendredi</label>
                    <textarea name="friday"><?= json_encode($planning['friday'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Samedi</label>
                    <textarea name="saturday"><?= json_encode($planning['saturday'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Dimanche</label>
                    <textarea name="sunday"><?= json_encode($planning['sunday'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></textarea>
                </div>

                <button type="submit" name="save_planning">💾 Sauvegarder le Planning</button>
            </form>
        </div>

        <!-- MENU -->
        <div id="tab-menu" class="tab-content">
            <h2 style="margin-bottom: 1.5rem;">Gérer le Menu Coffee</h2>
            <form method="POST">
                <h3 style="margin-bottom: 1rem;">Petit Déjeuner</h3>
                
                <div class="form-group">
                    <label>Plats Salés</label>
                    <textarea name="salty"><?= json_encode($breakfast['salty'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></textarea>
                    <p class="help-text">Format : [{"name": "Nom du plat", "desc": "Description"}, ...]</p>
                </div>

                <div class="form-group">
                    <label>Plats Sucrés</label>
                    <textarea name="sweet"><?= json_encode($breakfast['sweet'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></textarea>
                    <p class="help-text">Format : [{"name": "Nom du plat"}, ...]</p>
                </div>

                <h3 style="margin: 2rem 0 1rem;">Déjeuner</h3>
                
                <div class="form-group">
                    <label>Plats</label>
                    <textarea name="dishes"><?= json_encode($lunch['dishes'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></textarea>
                    <p class="help-text">Format : [{"name": "Nom", "desc": "Description", "options": ["Opt1", "Opt2"]}, ...]</p>
                </div>

                <button type="submit" name="save_menu">💾 Sauvegarder les Menus</button>
            </form>
        </div>

    </div>

    <script>
        function showTab(tabName) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            event.target.classList.add('active');
            document.getElementById('tab-' + tabName).classList.add('active');
        }
    </script>
<?php endif; ?>

</body>
</html>
