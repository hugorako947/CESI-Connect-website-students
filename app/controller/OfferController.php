<?php
// app/controller/OfferController.php

class OfferController {

    // Affiche la liste de toutes les offres
    public function list() {
        // TODO: Plus tard, récupérer toutes les offres de la BDD ici
        require_once '../app/views/liste-offres.php';
    }

    // Affiche les détails d'UNE seule offre
    public function details() {
        // On récupère l'ID de l'offre depuis l'URL (ex: ?route=details-offre&id=42)
        $offerId = $_GET['id'] ?? null;
        // TODO: Récupérer les infos de l'offre $offerId depuis la BDD
        require_once '../app/views/details-offre.php';
    }
    
    // Affiche le formulaire pour postuler
    public function showApplyForm() {
        $offerId = $_GET['id'] ?? null;
        // TODO: Charger les infos de l'offre pour afficher son titre dans le formulaire
        require_once '../app/views/postuler.php';
    }
}