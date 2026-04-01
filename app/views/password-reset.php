<?php
// On s'assure que la session est démarrée pour vérifier l'utilisateur connecté
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/database.php';
date_default_timezone_set('Europe/Paris');

try {
    $db = Database::getInstance()->getConnection();
} catch (Exception $e) {
    die("Erreur de connexion.");
}

// Initialisation des variables pour éviter les "Warning: Undefined variable"
$token = $_GET['token'] ?? $_POST['token'] ?? null;
$error = $error ?? null;   // Garde la valeur si le contrôleur l'a définie
$success = $success ?? null; // Garde la valeur si le contrôleur l'a définie
$user_id = null;

// --- VÉRIFICATION DE L'IDENTITÉ ---
if ($token) {
    // Mode "Mot de passe oublié" (via email)
    $stmt = $db->prepare("SELECT id FROM utilisateurs WHERE reset_token = ? AND reset_expires > NOW()");
    $stmt->execute([$token]);
    $res = $stmt->fetch();
    if ($res) {
        $user_id = $res['id'];
    }
} elseif (isset($_SESSION['user_id'])) {
    // Mode "Changer mon mot de passe" (via le profil)
    $user_id = $_SESSION['user_id'];
}

// Si aucune méthode d'identification ne fonctionne, on bloque l'accès
if (!$user_id) {
    die("Accès refusé : lien invalide ou vous n'êtes pas connecté. <a href='index.php?route=connexion'>Retour</a>");
}

include 'header.php'; 
?>

<section class="auth-container">
    <div class="auth-card">
        <h1>Nouveau mot de passe</h1>

        <?php if($success): ?>
            <div class="alert alert-success" style="color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                <?= $success ?>
            </div>
            <a href="index.php?route=<?= isset($_SESSION['user_id']) ? 'profil' : 'connexion' ?>" class="btn-submit" style="display:block; text-align:center; text-decoration:none;">Retour</a>
        <?php else: ?>
            <p class="auth-subtitle">Veuillez choisir un nouveau mot de passe sécurisé.</p>
            
            <?php if($error): ?>
                <div class="alert alert-error" style="color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="index.php?route=reinitialiser-mot-de-passe" method="POST" class="auth-form">
                <?php if($token): ?>
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="password">Nouveau mot de passe</label>
                    <input type="password" id="password" name="password" required placeholder="********" minlength="8">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirmez le mot de passe</label>
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder="********">
                </div>
                <button type="submit" class="btn-submit">Confirmez</button>
            </form>
        <?php endif; ?>
    </div>
</section>

<?php include 'footer.php'; ?>
