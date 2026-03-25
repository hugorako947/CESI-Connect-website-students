<?php
// app/controller/AuthController.php

class AuthController {

// Affiche le formulaire de connexion
public function showLoginForm() {
require_once '../app/views/connexion.php';
}

// Affiche le formulaire d'inscription
public function showRegisterForm() {
require_once '../app/views/inscription.php';
}

// Gère la déconnexion
public function logout() {
// On détruit la session
session_destroy();
// On redirige vers l'accueil
header('Location: index.php?route=accueil');
exit();
}

// Affiche le formulaire mot de passe oublié
public function showForgotPasswordForm() {
    require_once '../app/views/password-forgotten.php';
}

// Traite la demande de mot de passe oublié
public function handleForgotPassword() {
    // On récupère l'email soumis par le formulaire
    $email = trim($_POST['email'] ?? '');

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Veuillez entrer une adresse email valide.";
        require_once '../app/views/password-forgotten.php';
        return;
    }

    // On se connecte à la BDD
    require_once '../app/models/database.php';
    $database = new Database();
    $conn = $database->getConnection();

    // On vérifie si l'email existe dans la table utilisateurs
    $stmt = $conn->prepare("SELECT id, nom, prenom FROM utilisateurs WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // On affiche un message neutre pour ne pas révéler si l'email existe
        $success = "Si cette adresse est associée à un compte, un mot de passe temporaire a été envoyé.";
        require_once '../app/views/password-forgotten.php';
        return;
    }

    // On génère un mot de passe temporaire aléatoire de 10 caractères
    $nouveauMotDePasse = $this->genererMotDePasseAleatoire(10);

    // On le hache avant de le stocker en BDD
    $motDePasseHache = password_hash($nouveauMotDePasse, PASSWORD_DEFAULT);

    // On met à jour le mot de passe en BDD
    $stmtUpdate = $conn->prepare("UPDATE utilisateurs SET mot_de_passe = :mdp WHERE id = :id");
    $stmtUpdate->execute([
        ':mdp' => $motDePasseHache,
        ':id'  => $user['id']
    ]);

    // On envoie l'email avec le mot de passe temporaire
    $envoiReussi = $this->envoyerEmailMotDePasse(
        $email,
        $user['prenom'] . ' ' . $user['nom'],
        $nouveauMotDePasse
    );

    if ($envoiReussi) {
        $success = "Un mot de passe temporaire a été envoyé à l'adresse " . htmlspecialchars($email) . ". Pensez à le changer après connexion.";
    } else {
        $error = "Une erreur est survenue lors de l'envoi de l'email. Veuillez réessayer.";
    }

    require_once '../app/views/password-forgrotten.php';
}

// Génère un mot de passe aléatoire sécurisé
private function genererMotDePasseAleatoire($longueur = 10) {
    $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%';
    $motDePasse = '';
    $max = strlen($caracteres) - 1;
    for ($i = 0; $i < $longueur; $i++) {
        $motDePasse .= $caracteres[random_int(0, $max)];
    }
    return $motDePasse;
}

// Envoie l'email avec le mot de passe temporaire
private function envoyerEmailMotDePasse($destinataire, $nomComplet, $motDePasseTemporaire) {
    $sujet = "CESI Connect - Votre mot de passe temporaire";

    $message = "
    <html>
    <head><meta charset='UTF-8'></head>
    <body style='font-family: sans-serif; color: #111827;'>
        <h2 style='color: #2563eb;'>CESI Connect</h2>
        <p>Bonjour <strong>" . htmlspecialchars($nomComplet) . "</strong>,</p>
        <p>Vous avez demandé la réinitialisation de votre mot de passe.</p>
        <p>Voici votre mot de passe temporaire :</p>
        <div style='background:#f3f4f6; padding:1rem; border-radius:8px; font-size:1.4rem; font-weight:bold; letter-spacing:2px; text-align:center; color:#2563eb;'>
            " . htmlspecialchars($motDePasseTemporaire) . "
        </div>
        <p style='margin-top:1.5rem;'>Connectez-vous avec ce mot de passe puis changez-le immédiatement depuis votre profil.</p>
        <p style='color:#6b7280; font-size:0.85rem;'>Si vous n'êtes pas à l'origine de cette demande, ignorez cet email.</p>
        <hr>
        <p style='color:#6b7280; font-size:0.8rem;'>&copy; 2026 CESI Connect</p>
    </body>
    </html>
    ";

    // En-têtes pour un email HTML
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: noreply@cesi-connect.fr\r\n";
    $headers .= "Reply-To: noreply@cesi-connect.fr\r\n";

    return mail($destinataire, $sujet, $message, $headers);
}
}
