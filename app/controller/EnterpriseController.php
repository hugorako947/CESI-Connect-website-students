<?php
// app/controller/EnterpriseController.php

require_once '../app/models/EnterpriseManager.php';
require_once '../app/models/OfferManager.php';

class EnterpriseController {

    // Affiche les détails d'une entreprise
    public function details() {
        $enterpriseId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$enterpriseId) {
            $_SESSION['erreur'] = "Entreprise invalide.";
            header('Location: index.php?route=accueil');
            exit;
        }

        $enterpriseManager = new EnterpriseManager();
        $offerManager = new OfferManager();

        $enterprise = $enterpriseManager->getById($enterpriseId);
        if (!$enterprise) {
            $_SESSION['erreur'] = "Cette entreprise n'existe pas ou a été supprimée.";
            header('Location: index.php?route=accueil');
            exit;
        }

        $offers = $offerManager->getByEntreprise($enterpriseId);
        require_once '../app/views/entreprise-details.php';
    }
}
