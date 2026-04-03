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
     * Affiche le formulaire pour ajouter OU modifier une entreprise
     */
    public function entrepriseForm() {
        AuthController::requireRole(1);
        $enterprise = null;
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($id) {
            $manager = new EnterpriseManager();
            $enterprise = $manager->getById($id);
            if (!$enterprise) {
                $_SESSION['erreur'] = "Entreprise introuvable.";
                header('Location: index.php?route=gestion-entreprises');
                exit;
            }
        }
        require_once __DIR__ . '/../views/entreprise-form.php';
    }

    /**
     * Traite la sauvegarde (creation ou modification) d'une entreprise
     */
    public function saveEnterprise() {
        AuthController::requireRole(1);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=gestion-entreprises');
            exit;
        }

        $id          = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $nom         = trim($_POST['nom']         ?? '');
        $description = trim($_POST['description'] ?? '');
        $email       = trim($_POST['email']       ?? '');
        $tel         = trim($_POST['tel']         ?? '');

        if (empty($nom)) {
            $_SESSION['erreur'] = "Le nom de l'entreprise est obligatoire.";
            $redirect = $id ? "index.php?route=form-entreprise&id=$id" : "index.php?route=form-entreprise";
            header('Location: ' . $redirect);
            exit;
        }

        $data = [
            'nom'           => $nom,
            'description'   => $description,
            'email_contact' => $email,
            'telephone'     => $tel,
        ];

        $manager = new EnterpriseManager();

        if ($id) {
            $manager->update($id, $data);
            $_SESSION['success'] = "Entreprise modifiee avec succes.";
        } else {
            $manager->create($data);
            $_SESSION['success'] = "Entreprise ajoutee avec succes.";
        }

        header('Location: index.php?route=gestion-entreprises');
        exit;
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
