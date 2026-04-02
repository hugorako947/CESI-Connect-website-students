<?php
// app/controller/CandidatureController.php

require_once '../app/models/CandidatureManager.php';
require_once '../app/models/OfferManager.php';

/**
 * CandidatureController - Gestion des candidatures
 * Version adaptée pour Web4All
 */
class CandidatureController {
    private $candidatureManager;
    private $offerManager;
    private $uploadDir;

    /**
     * Constructeur - Initialise les managers et le dossier d'upload
     */
    public function __construct() {
        $this->candidatureManager = new CandidatureManager();
        $this->offerManager = new OfferManager();
        
        // Dossier d'upload HORS de public/ pour sécurité
        $this->uploadDir = dirname(__DIR__, 2) . '/uploads/candidatures/';
        
        // Créer le dossier s'il n'existe pas
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    /**
     * Afficher le formulaire de candidature
     */
    public function create() {
        // Vérifier que l'utilisateur est connecté
        AuthController::requireAuth();

        // Bloquer les pilotes (rôle 2)
        if (isset($_SESSION['user_role']) && (int) $_SESSION['user_role'] === 2) {
            $_SESSION['erreur'] = "Les pilotes ne peuvent pas candidater à une offre.";
            header('Location: index.php?route=offres');
            exit;
        }

        // Récupérer l'ID de l'offre
        $offerId = filter_input(INPUT_GET, 'offre', FILTER_VALIDATE_INT);

        if (!$offerId) {
            $_SESSION['erreur'] = "Offre invalide.";
            header('Location: index.php?route=offres');
            exit;
        }

        // Récupérer les détails de l'offre
        $offer = $this->offerManager->getById($offerId);

        if (!$offer) {
            $_SESSION['erreur'] = "Offre introuvable.";
            header('Location: index.php?route=offres');
            exit;
        }

        // Vérifier si l'utilisateur a déjà candidaté
        $userId = $_SESSION['user_id'];
        $hasApplied = $this->candidatureManager->hasApplied($userId, $offerId);

        if ($hasApplied) {
            $_SESSION['erreur'] = "Vous avez déjà candidaté pour cette offre.";
            header('Location: index.php?route=offre-details&id=' . $offerId);
            exit;
        }

        // Afficher le formulaire
        require_once '../app/views/candidater.php';
    }

    /**
     * Traiter la soumission du formulaire de candidature
     */
    public function store() {
        // Vérifier que l'utilisateur est connecté
        AuthController::requireAuth();

        // Bloquer les pilotes (rôle 2)
        if (isset($_SESSION['user_role']) && (int) $_SESSION['user_role'] === 2) {
            $_SESSION['erreur'] = "Les pilotes ne peuvent pas candidater à une offre.";
            header('Location: index.php?route=offres');
            exit;
        }

        // Vérifier que c'est une requête POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=offres');
            exit;
        }

        // Récupérer l'ID de l'offre
        $offerId = filter_input(INPUT_POST, 'offre_id', FILTER_VALIDATE_INT);
        $userId = $_SESSION['user_id'];

        // Validation
        $errors = [];

        if (!$offerId) {
            $errors[] = "Offre invalide.";
        }

        // Vérifier si l'utilisateur a déjà candidaté
        if ($offerId && $this->candidatureManager->hasApplied($userId, $offerId)) {
            $errors[] = "Vous avez déjà candidaté pour cette offre.";
        }

        // Valider les fichiers uploadés
        $cvPath = null;
        $lmPath = null;

        // Validation et upload du CV
        if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
            $cvValidation = $this->validateAndUploadFile($_FILES['cv'], 'cv', $userId, $offerId);
            
            if ($cvValidation['success']) {
                $cvPath = $cvValidation['path'];
            } else {
                $errors[] = $cvValidation['error'];
            }
        } else {
            $errors[] = "Le CV est obligatoire.";
        }

        // Validation et upload de la lettre de motivation
        if (isset($_FILES['lettre_motivation']) && $_FILES['lettre_motivation']['error'] === UPLOAD_ERR_OK) {
            $lmValidation = $this->validateAndUploadFile($_FILES['lettre_motivation'], 'lm', $userId, $offerId);
            
            if ($lmValidation['success']) {
                $lmPath = $lmValidation['path'];
            } else {
                $errors[] = $lmValidation['error'];
            }
        } else {
            $errors[] = "La lettre de motivation est obligatoire.";
        }

        // Si erreurs, retour au formulaire
        if (!empty($errors)) {
            $_SESSION['erreurs'] = $errors;
            header('Location: index.php?route=candidater&offre=' . $offerId);
            exit;
        }

        // Créer la candidature
        try {
            $candidatureId = $this->candidatureManager->create([
                'id_utilisateur' => $userId,
                'id_offre' => $offerId,
                'cv_path' => $cvPath,
                'lettre_motivation' => $lmPath,
                'statut' => 'En attente'
            ]);

            // Retirer l'offre de la wish-list si elle y était
            $this->offerManager->removeFromWishlist((int) $userId, (int) $offerId);

            $_SESSION['success'] = "Votre candidature a été envoyée avec succès !";
            header('Location: index.php?route=profil&tab=candidatures');
            exit;

        } catch (Exception $e) {
            // En cas d'erreur, supprimer les fichiers uploadés
            if ($cvPath && file_exists($this->uploadDir . $cvPath)) {
                unlink($this->uploadDir . $cvPath);
            }
            if ($lmPath && file_exists($this->uploadDir . $lmPath)) {
                unlink($this->uploadDir . $lmPath);
            }

            $_SESSION['erreur'] = "Erreur lors de l'enregistrement de la candidature.";
            header('Location: index.php?route=candidater&offre=' . $offerId);
            exit;
        }
    }

    /**
     * Valider et uploader un fichier de manière sécurisée
     */
    private function validateAndUploadFile($file, $type, $userId, $offerId) {
        // Extensions autorisées
        $allowedExtensions = ['pdf', 'doc', 'docx'];
        
        // Taille maximale : 5 Mo
        $maxSize = 5 * 1024 * 1024;

        // Vérifier la taille
        if ($file['size'] > $maxSize) {
            return [
                'success' => false,
                'error' => "Le fichier {$type} est trop volumineux (max 5 Mo)."
            ];
        }

        // Récupérer l'extension
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Vérifier l'extension
        if (!in_array($fileExtension, $allowedExtensions)) {
            return [
                'success' => false,
                'error' => "Format de fichier {$type} non autorisé. Formats acceptés : PDF, DOC, DOCX."
            ];
        }

        // Vérifier le type MIME
        $allowedMimes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedMimes)) {
            return [
                'success' => false,
                'error' => "Type MIME du fichier {$type} invalide."
            ];
        }

        // Générer un nom de fichier unique et sécurisé
        $uniqueId = uniqid('', true);
        $fileName = "{$type}_user{$userId}_offre{$offerId}_{$uniqueId}.{$fileExtension}";
        $filePath = $this->uploadDir . $fileName;

        // Déplacer le fichier uploadé
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return [
                'success' => true,
                'path' => $fileName
            ];
        } else {
            return [
                'success' => false,
                'error' => "Erreur lors de l'upload du fichier {$type}."
            ];
        }
    }

}
?>
