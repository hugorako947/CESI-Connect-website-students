<?php
// app/controller/StudentController.php

require_once '../app/controller/AuthController.php';
require_once '../app/models/OfferManager.php';
require_once '../app/models/UserManager.php';
require_once '../app/models/CandidatureManager.php';

class StudentController {

// Affiche la page de profil
public function profile() {
    AuthController::requireAuth();

    $userId = (int) ($_SESSION['user_id'] ?? 0);
    $userManager = new UserManager();
    $offerManager = new OfferManager();
    $candidatureManager = new CandidatureManager();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($nom === '' || $prenom === '' || $email === '') {
            $_SESSION['erreur'] = "Tous les champs sont obligatoires.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['erreur'] = "Adresse email invalide.";
        } elseif ($userManager->emailExistsForOtherUser($email, $userId)) {
            $_SESSION['erreur'] = "Cette adresse email est déjà utilisée.";
        } else {
            if ($userManager->updateProfile($userId, $nom, $prenom, $email)) {
                $_SESSION['user_nom'] = $nom;
                $_SESSION['user_prenom'] = $prenom;
                $_SESSION['user_email'] = $email;
                $_SESSION['success'] = "Informations personnelles mises à jour.";
            } else {
                $_SESSION['erreur'] = "Impossible de mettre à jour le profil.";
            }
        }

        header('Location: index.php?route=profil');
        exit;
    }

    $user = $userManager->getUserById($userId);
    $wishlist_offers = $offerManager->getWishlistOffers($userId);
    $candidatures = $candidatureManager->getByUser($userId);

    require_once '../app/views/profil.php';
}

// Affiche la wishlist de l'étudiant
public function wishlist() {
    AuthController::requireAuth();
    $offerManager = new OfferManager();
    $userId = (int) ($_SESSION['user_id'] ?? 0);
    $wishlist_offers = $userId ? $offerManager->getWishlistOffers($userId) : [];
require_once '../app/views/wishlist.php';
}

public function addToWishlist() {
    AuthController::requireAuth();

    $offerId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $userId = (int) ($_SESSION['user_id'] ?? 0);
    $redirect = $_GET['redirect'] ?? 'index.php?route=offres';

    if (strpos($redirect, 'index.php') !== 0) {
        $redirect = 'index.php?route=offres';
    }

    if (!$offerId) {
        $_SESSION['erreur'] = "Offre invalide.";
        header('Location: ' . $redirect);
        exit;
    }

    $offerManager = new OfferManager();

    if (!$offerManager->isInWishlist($userId, $offerId)) {
        $offerManager->addToWishlist($userId, $offerId);
        $_SESSION['success'] = "Offre ajoutée à votre wish-list.";
    }

    header('Location: ' . $redirect);
    exit;
}

public function removeFromWishlist() {
    AuthController::requireAuth();

    $offerId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $userId = (int) ($_SESSION['user_id'] ?? 0);
    $redirect = $_GET['redirect'] ?? 'index.php?route=offres';

    if (strpos($redirect, 'index.php') !== 0) {
        $redirect = 'index.php?route=offres';
    }

    if (!$offerId) {
        $_SESSION['erreur'] = "Offre invalide.";
        header('Location: ' . $redirect);
        exit;
    }

    $offerManager = new OfferManager();
    $offerManager->removeFromWishlist($userId, $offerId);
    $_SESSION['success'] = "Offre retirée de votre wish-list.";

    header('Location: ' . $redirect);
    exit;
}

// Affiche les candidatures envoyées
public function applications() {
// TODO: Vérifier si l'utilisateur est bien connecté
// TODO: Récupérer les candidatures de l'étudiant depuis la BDD
require_once '../app/views/candidatures-envoyees.php';
}
}