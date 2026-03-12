<?php
// app/controller/EnterpriseController.php

class EnterpriseController {

    // Affiche les détails d'une entreprise
    public function details() {
        $enterpriseId = $_GET['id'] ?? null;
        // TODO: Récupérer les infos de l'entreprise $enterpriseId depuis la BDD
        require_once '../app/views/entreprise-details.php';
    }
}
