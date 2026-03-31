<?php
require_once __DIR__ . '/../models/UserManager.php';
date_default_timezone_set('Europe/Paris');

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    $userManager = new UserManager();
    $user = $userManager->getUserByEmail($email);
    
    if ($user) {
        $token = bin2hex(random_bytes(32));
        $userManager->saveResetToken($email, $token);

        $resetLink = "index.php?route=reinitialiser-mot-de-passe&token=" . $token;
        $success = "Lien envoyé par mail : <a href='$resetLink' style='font-weight:bold; color:#155724;'>Cliquez ici pour réinitialiser le mot de passe</a>";
    } else {
        $error = "Email non reconnu.";
    }
}

include 'header.php';
?>

<section class="auth-container">
    <div class="auth-card">
        <h1>Mot de passe oublié</h1>

        <?php if($success): ?>
            <div class="alert alert-success">
                <?= $success ?>
            </div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="auth-form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="exemple@viacesi.fr">
            </div>
            <button type="submit" class="btn-submit">Envoyer</button>
        </form>
    </div>
</section>

<?php include 'footer.php'; ?>
