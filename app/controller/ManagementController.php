<?php
// app/controller/ManagementController.php

require_once '/../models/EnterpriseManager.php';

class ManagementController {

    public function listEntreprises() {
        // 1. On appelle le manager
        $manager = new EnterpriseManager();
        
        // 2. On récupère les données de la BDD
        $entreprises = $manager->getAllEnterprises();
        
        // 3. On charge la vue (la variable $entreprises sera dispo dedans)
        require_once __DIR__ . '/../views/liste-entreprises.php';
    }
    
    // ... tes autres méthodes (entrepriseForm, etc.)
    public function listEntreprises() { require_once '../app/views/liste-entreprises.php'; }
    public function entrepriseForm() { require_once '../app/views/entreprise-form.php'; }
    public function offreForm() { require_once '../app/views/offre-form.php'; }
    public function suiviPilote() { require_once '../app/views/suivi-etudiants-pilote.php'; }
}
