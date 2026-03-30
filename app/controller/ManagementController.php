<?php
// app/controller/ManagementController.php

// On inclut le manager d'entreprises
require_once __DIR__ . '/../models/EnterpriseManager.php';

class ManagementController {

    /**
     * Affiche la liste des entreprises (VUE ADMIN)
     */
    public function listEntreprises() {
        // 1. On appelle le manager
        $manager = new EnterpriseManager();
        
        // 2. On récupère les données de la BDD Cloud
        $entreprises = $manager->getAllEnterprises();
        
        // 3. On charge la vue
        require_once __DIR__ . '/../views/liste-entreprises.php';
    }

    /**
     * Affiche le formulaire pour ajouter une entreprise
     */
    public function entrepriseForm() {
        require_once __DIR__ . '/../views/entreprise-form.php';
    }

    /**
     * Affiche le formulaire pour ajouter une offre
     */
    public function offreForm() {
        require_once __DIR__ . '/../views/offre-form.php';
    }

    /**
     * Affiche le suivi des étudiants (pour les pilotes)
     */
    public function suiviPilote() {
        require_once __DIR__ . '/../views/suivi-etudiants-pilote.php';
    }
}