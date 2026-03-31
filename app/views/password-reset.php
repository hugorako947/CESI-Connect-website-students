<?php
// On récupère la connexion via le Singleton de ton projet
require_once __DIR__ . '/../models/database.php';
date_default_timezone_set('Europe/Paris');

try {
    $db = Database::getInstance()->getConnection();
} catch (Exception $e) {
    die("Erreur de connexion à la base de données.");
}

$token = $_GET['token'] ?? $_POST['token'] ?? null;
$error = null;
$success = null;

// 1. Vérifier si le token est valide et non expiré
// NOTE : On vérifie 'mot_de_passe' et non 'password' pour correspondre à ton UserManager
$stmt = $db->prepare("SELECT id FROM utilisateurs WHERE reset_token = ? AND reset_expires > ?");
$stmt->execute([$token, date('Y-m-d H:i:s')]);
$user = $stmt->fetch();

if (!$token || !$user) {
    die("Ce lien est invalide ou a expiré. <a href='index.php?route=mot-de-passe-oublie'>Recommencer la procédure</a>");
}

// 2. Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (!empty($password) && $password === $confirm && strlen($password) >= 8) {
        // Utilisation de PASSWORD_BCRYPT pour être raccord avec ton AuthController
        $hash = password_hash($password, PASSWORD_BCRYPT);

        // MISE À JOUR : On utilise 'mot_de_passe' (nom de colonne dans tes autres fichiers)
        $update = $db->prepare("UPDATE utilisateurs SET mot_de_passe = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
        $update->execute([$hash, $user['id']]);

        $success = "Votre mot de passe a été mis à jour avec succès !";
    } else {
        $error = "Les mots de passe ne correspondent pas ou sont trop courts (min. 8 caractères).";
    }
}

include 'header.php';
?>

<section class="auth-container">
    <div class="auth-card">
        <h1>Nouveau mot de passe</h1>

        <?php if($success): ?>
            <div class="alert alert-success" style="color: green; margin-bottom: 20px;"><?= $success ?></div>
            <a href="index.php?route=connexion" class="btn-submit" style="display:block; text-align:center; text-decoration:none;">Se connecter</a>
        <?php else: ?>
            <p class="auth-subtitle">Veuillez choisir un nouveau mot de passe sécurisé.</p>
            
            <?php if($error): ?>
                <div class="alert alert-error" style="color: red; margin-bottom: 20px;"><?= $error ?></div>
            <?php endif; ?>

            <form action="" method="POST" class="auth-form">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required placeholder="********">
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
