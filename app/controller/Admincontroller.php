<?php
// app/controller/AdminController.php

require_once '../app/controller/AuthController.php';
require_once '../app/models/UserManager.php';
require_once '../app/models/OfferManager.php';
require_once '../app/models/EnterpriseManager.php';
require_once '../app/models/CandidatureManager.php';

/**
 * AdminController - Gestion du dashboard administrateur
 * Accès exclusif au rôle 1 (Admin)
 */
class AdminController {

    private $userManager;
    private $offerManager;
    private $enterpriseManager;
    private $candidatureManager;

    public function __construct() {
        $this->userManager       = new UserManager();
        $this->offerManager      = new OfferManager();
        $this->enterpriseManager = new EnterpriseManager();
        $this->candidatureManager = new CandidatureManager();
    }

    // ──────────────────────────────────────────────────────────────
    // Données pour le panel admin (appelé depuis StudentController)
    // ──────────────────────────────────────────────────────────────

    /**
     * Retourne toutes les données nécessaires au dashboard admin
     */
    public function getDashboardData(string $search = ''): array {
        return [
            // Stats globales
            'stats' => [
                'nb_etudiants'    => $this->userManager->countByRole(3),
                'nb_pilotes'      => $this->userManager->countByRole(2),
                'nb_admins'       => $this->userManager->countByRole(1),
                'nb_offres_stage' => $this->offerManager->countByType('stage'),
                'nb_offres_alt'   => $this->offerManager->countByType('alternance'),
                'nb_entreprises'  => $this->enterpriseManager->countAll(),
            ],
            // Listes filtrées par recherche
            'utilisateurs'  => $this->userManager->searchAll($search),
            'offres'        => $this->offerManager->searchAdmin($search),
            'entreprises'   => $this->enterpriseManager->searchAll($search),
        ];
    }

    // ──────────────────────────────────────────────────────────────
    // SUPPRESSION — Utilisateur
    // ──────────────────────────────────────────────────────────────

    public function deleteUser(): void {
        AuthController::requireRole(1);

        $targetId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$targetId) {
            $_SESSION['erreur'] = "Utilisateur invalide.";
            header('Location: index.php?route=profil&tab=admin');
            exit;
        }

        // Empêcher l'admin de se supprimer lui-même via cette route
        if ($targetId === (int) ($_SESSION['user_id'] ?? 0)) {
            $_SESSION['erreur'] = "Vous ne pouvez pas supprimer votre propre compte depuis le dashboard admin. Utilisez 'Supprimer mon compte' dans vos informations personnelles.";
            header('Location: index.php?route=profil&tab=admin');
            exit;
        }

        if ($this->userManager->deleteUser($targetId)) {
            $_SESSION['success'] = "Utilisateur supprimé avec succès.";
        } else {
            $_SESSION['erreur'] = "Erreur lors de la suppression de l'utilisateur.";
        }

        header('Location: index.php?route=profil&tab=admin');
        exit;
    }

    // ──────────────────────────────────────────────────────────────
    // SUPPRESSION — Offre
    // ──────────────────────────────────────────────────────────────

    public function deleteOffer(): void {
        AuthController::requireRole(1);

        $offerId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$offerId) {
            $_SESSION['erreur'] = "Offre invalide.";
            header('Location: index.php?route=profil&tab=admin');
            exit;
        }

        if ($this->offerManager->delete($offerId)) {
            $_SESSION['success'] = "Offre supprimée avec succès.";
        } else {
            $_SESSION['erreur'] = "Erreur lors de la suppression de l'offre.";
        }

        header('Location: index.php?route=profil&tab=admin');
        exit;
    }

    // ──────────────────────────────────────────────────────────────
    // SUPPRESSION — Entreprise
    // ──────────────────────────────────────────────────────────────

    public function deleteEnterprise(): void {
        AuthController::requireRole(1);

        $enterpriseId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$enterpriseId) {
            $_SESSION['erreur'] = "Entreprise invalide.";
            header('Location: index.php?route=profil&tab=admin');
            exit;
        }

        if ($this->enterpriseManager->delete($enterpriseId)) {
            $_SESSION['success'] = "Entreprise supprimée avec succès.";
        } else {
            $_SESSION['erreur'] = "Erreur lors de la suppression de l'entreprise.";
        }

        header('Location: index.php?route=profil&tab=admin');
        exit;
    }
}
?>
