<?php
// app/controller/StudentController.php

require_once '../app/controller/AuthController.php';
require_once '../app/models/OfferManager.php';

class StudentController {

// Affiche la page de profil
public function profile() {
// TODO: Vérifier si l'utilisateur est bien connecté
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

// Affiche les candidatures envoyées
public function applications() {
// TODO: Vérifier si l'utilisateur est bien connecté
// TODO: Récupérer les candidatures de l'étudiant depuis la BDD
require_once '../app/views/candidatures-envoyees.php';
}
}