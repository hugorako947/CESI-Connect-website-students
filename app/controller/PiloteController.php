<?php
// app/controller/PiloteController.php

require_once '../app/controller/AuthController.php';
require_once '../app/models/UserManager.php';
require_once '../app/models/OfferManager.php';
require_once '../app/models/CandidatureManager.php';

/**
 * PiloteController - Gestion du dashboard et des fonctionnalités pilote
 * Permet aux pilotes de suivre les étudiants, leurs wishlists et candidatures
 */
class PiloteController {

    /**
     * Afficher le dashboard pilote avec la liste des étudiants
     */
    public function dashboard() {
        // Vérifier que l'utilisateur est bien un pilote (rôle 2)
        AuthController::requireRole(2);

        $userManager = new UserManager();
        $offerManager = new OfferManager();
        $candidatureManager = new CandidatureManager();

        // Récupérer tous les étudiants (rôle 3)
        $etudiants = $userManager->getAllStudents();

        // Pour chaque étudiant, récupérer des statistiques
        foreach ($etudiants as &$etudiant) {
            // Nombre d'offres en wishlist
            $wishlist = $offerManager->getWishlistOffers($etudiant['id']);
            $etudiant['nb_wishlist'] = count($wishlist);

            // Nombre de candidatures
            $candidatures = $candidatureManager->getByUser($etudiant['id']);
            $etudiant['nb_candidatures'] = count($candidatures);

            // Statistiques des candidatures
            $etudiant['nb_en_attente'] = count(array_filter($candidatures, fn($c) => $c['statut'] === 'En attente'));
            $etudiant['nb_acceptees'] = count(array_filter($candidatures, fn($c) => $c['statut'] === 'Acceptée'));
            $etudiant['nb_refusees'] = count(array_filter($candidatures, fn($c) => $c['statut'] === 'Refusée'));
        }

        require_once '../app/views/dashboard-pilote.php';
    }

    /**
     * Afficher le détail d'un étudiant : wishlist + candidatures
     */
    public function etudiantDetail() {
        // Vérifier que l'utilisateur est bien un pilote
        AuthController::requireRole(2);

        $etudiantId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$etudiantId) {
            $_SESSION['erreur'] = "Étudiant invalide.";
            header('Location: index.php?route=pilote-dashboard');
            exit;
        }

        $userManager = new UserManager();
        $offerManager = new OfferManager();
        $candidatureManager = new CandidatureManager();

        // Récupérer l'étudiant
        $etudiant = $userManager->getUserById($etudiantId);

        if (!$etudiant || $etudiant['id_role'] != 3) {
            $_SESSION['erreur'] = "Étudiant introuvable.";
            header('Location: index.php?route=pilote-dashboard');
            exit;
        }

        // Récupérer la wishlist de l'étudiant
        $wishlist = $offerManager->getWishlistOffers($etudiantId);

        // Récupérer les candidatures de l'étudiant
        $candidatures = $candidatureManager->getByUser($etudiantId);

        require_once '../app/views/pilote-etudiant-detail.php';
    }

    /**
     * Vue d'ensemble : statistiques globales des étudiants
     */
    public function statistiques() {
        AuthController::requireRole(2);

        $userManager = new UserManager();
        $candidatureManager = new CandidatureManager();

        // Statistiques globales
        $stats = [];
        $stats['nb_etudiants'] = $userManager->countStudents();
        $stats['nb_candidatures_total'] = $candidatureManager->countAll();
        $stats['nb_en_attente'] = $candidatureManager->countByStatus('En attente');
        $stats['nb_acceptees'] = $candidatureManager->countByStatus('Acceptée');
        $stats['nb_refusees'] = $candidatureManager->countByStatus('Refusée');

        // Top 10 des offres les plus populaires (le plus de candidatures)
        $topOffres = $candidatureManager->getTopOffers(10);

        require_once '../app/views/pilote-statistiques.php';
    }
}
?>
