<?php
// app/controller/OfferController.php

require_once '../app/models/OfferManager.php';
require_once '../app/models/CandidatureManager.php';

/**
 * OfferController - Gestion des offres de stage et d'alternance
 * Version adaptée pour Web4All
 */
class OfferController {
    private $offerManager;
    private $candidatureManager;

    /**
     * Constructeur - Initialise les managers
     */
    public function __construct() {
        $this->offerManager = new OfferManager();
        $this->candidatureManager = new CandidatureManager();
    }

    /**
     * Afficher la liste de toutes les offres
     * (Nom de la méthode : "list" comme dans ton index.php)
     */
    public function list() {
        // Pagination
        $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        // Récupérer les offres
        $offres = $this->offerManager->getAllOffers($perPage, $offset);
        $totalOffers = $this->offerManager->countAll();
        $totalPages = ceil($totalOffers / $perPage);
        $wishlistOfferIds = [];

        if (isset($_SESSION['user_id'])) {
            $wishlistOfferIds = $this->offerManager->getWishlistOfferIds((int) $_SESSION['user_id']);
        }

        // Afficher la vue
        require_once '../app/views/liste-offres.php';
    }

    /**
     * Afficher les détails d'une offre spécifique
     */
    public function details() {
        // Récupérer l'ID de l'offre depuis l'URL
        $offerId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        // Validation : ID présent et valide
        if (!$offerId) {
            $_SESSION['erreur'] = "Offre invalide.";
            header('Location: index.php?route=offres');
            exit;
        }

        // Récupérer les détails de l'offre
        $offer = $this->offerManager->getById($offerId);

        // Vérifier si l'offre existe
        if (!$offer) {
            $_SESSION['erreur'] = "Cette offre n'existe pas ou a été supprimée.";
            header('Location: index.php?route=offres');
            exit;
        }

        // Vérifier si l'utilisateur a déjà candidaté (si connecté)
        $hasApplied = false;
        $isInWishlist = false;
        if (isset($_SESSION['user_id'])) {
            $hasApplied = $this->candidatureManager->hasApplied(
                $_SESSION['user_id'], 
                $offerId
            );
            $isInWishlist = $this->offerManager->isInWishlist((int) $_SESSION['user_id'], $offerId);
        }

        // Récupérer le nombre de candidatures pour cette offre
        $candidaturesCount = $this->candidatureManager->countByOffer($offerId);

        // Afficher la vue
        require_once '../app/views/details-offre.php';
    }

    /**
     * Rechercher des offres par mot-clé
     */
  public function search() {
        $filters = [];
        $filters['q'] = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $filters['skill'] = filter_input(INPUT_GET, 'skill', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $filters['city'] = filter_input(INPUT_GET, 'city', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $filters['type'] = isset($_GET['type']) ? (array) $_GET['type'] : [];
        $filters['min_money'] = filter_input(INPUT_GET, 'min_money', FILTER_VALIDATE_INT);

        if (empty($filters['q']) && empty($filters['skill']) && empty($filters['city']) && empty($filters['type']) && empty($filters['min_money'])) {
            header('Location: index.php?route=offres');
            exit;
        }

        $offres = $this->offerManager->searchWithFilters($filters);
        $wishlistOfferIds = [];

        if (isset($_SESSION['user_id'])) {
            $wishlistOfferIds = $this->offerManager->getWishlistOfferIds((int) $_SESSION['user_id']);
        }

        require_once '../app/views/liste-offres.php';
    }
}
?>
