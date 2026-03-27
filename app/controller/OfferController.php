<?php

namespace App\Controller;

use App\Model\Database;
use App\Model\OfferManager;
use App\Model\CandidatureManager;

/**
 * OfferController - Gestion des offres de stage et d'alternance
 * Responsable de l'affichage des listes et détails d'offres
 */
class OfferController
{
    private OfferManager $offerManager;
    private ?CandidatureManager $candidatureManager = null;

    /**
     * Constructeur - Initialise l'OfferManager
     */
    public function __construct()
    {
        $db = Database::getInstance()->getConnection();
        $this->offerManager = new OfferManager($db);
        
        // Initialiser le CandidatureManager si nécessaire
        if (class_exists('App\Model\CandidatureManager')) {
            $this->candidatureManager = new CandidatureManager($db);
        }

        // Démarrer la session si nécessaire
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Afficher la liste de toutes les offres
     */
    public function index(): void
    {
        // Pagination (optionnel, à activer plus tard)
        $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        // Récupérer les offres
        $offers = $this->offerManager->getAllOffers($perPage, $offset);
        $totalOffers = $this->offerManager->countAll();
        $totalPages = ceil($totalOffers / $perPage);

        // Afficher la vue
        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/liste-offres.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    /**
     * Afficher les détails d'une offre spécifique
     */
    public function details(): void
    {
        // Récupérer l'ID de l'offre depuis l'URL
        $offerId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        // Validation : ID présent et valide
        if (!$offerId) {
            $_SESSION['errors'] = ["Offre invalide."];
            header('Location: index.php?route=offres');
            exit;
        }

        // Récupérer les détails de l'offre
        $offer = $this->offerManager->getById($offerId);

        // Vérifier si l'offre existe
        if (!$offer) {
            $_SESSION['errors'] = ["Cette offre n'existe pas ou a été supprimée."];
            header('Location: index.php?route=offres');
            exit;
        }

        // Vérifier si l'utilisateur a déjà candidaté (si connecté)
        $hasApplied = false;
        if (isset($_SESSION['user_id']) && $this->candidatureManager) {
            $hasApplied = $this->candidatureManager->hasApplied(
                $_SESSION['user_id'], 
                $offerId
            );
        }

        // Récupérer le nombre de candidatures pour cette offre (optionnel)
        $candidaturesCount = 0;
        if ($this->candidatureManager) {
            $candidaturesCount = $this->candidatureManager->countByOffer($offerId);
        }

        // Afficher la vue
        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/details-offre.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    /**
     * Rechercher des offres par mot-clé
     */
    public function search(): void
    {
        $keyword = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (empty($keyword)) {
            header('Location: index.php?route=offres');
            exit;
        }

        $offers = $this->offerManager->search($keyword);

        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/liste-offres.php';
        require_once __DIR__ . '/../views/footer.php';
    }
}
