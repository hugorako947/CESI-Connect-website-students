<?php
class ManagementController {
    public function listEntreprises() { require_once '../app/views/liste-entreprises.php'; }
    public function entrepriseForm() { require_once '../app/views/entreprise-form.php'; }
    public function offreForm() { require_once '../app/views/offre-form.php'; }
    public function suiviPilote() { require_once '../app/views/suivi-etudiants-pilote.php'; }
}