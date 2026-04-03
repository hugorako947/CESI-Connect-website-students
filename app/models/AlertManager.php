<?php
// app/models/AlertManager.php

require_once 'Database.php';

/**
 * AlertManager - Gestion des alertes personnalisées
 * Permet aux utilisateurs de créer des alertes basées sur des critères de recherche
 */
class AlertManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Créer une nouvelle alerte
     */
    public function createAlert($userId, $data) {
        $query = "INSERT INTO alertes_offres 
                  (id_utilisateur, nom_alerte, mot_cle, ville, domaine, type_contrat, remuneration_min, duree_contrat, niveau_etude, teletravail, actif, date_creation)
                  VALUES (:user_id, :nom, :mot_cle, :ville, :domaine, :type_contrat, :remuneration_min, :duree_contrat, :niveau_etude, :teletravail, 1, NOW())";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':nom', $data['nom_alerte'], PDO::PARAM_STR);
        $stmt->bindParam(':mot_cle', $data['mot_cle'], PDO::PARAM_STR);
        $stmt->bindParam(':ville', $data['ville'], PDO::PARAM_STR);
        $stmt->bindParam(':domaine', $data['domaine'], PDO::PARAM_STR);
        $stmt->bindParam(':type_contrat', $data['type_contrat'], PDO::PARAM_STR);
        $stmt->bindParam(':remuneration_min', $data['remuneration_min'], PDO::PARAM_INT);
        $stmt->bindParam(':duree_contrat', $data['duree_contrat'], PDO::PARAM_STR);
        $stmt->bindParam(':niveau_etude', $data['niveau_etude'], PDO::PARAM_STR);
        $teletravail = ($data['teletravail'] !== '') ? (int) $data['teletravail'] : null;
        $stmt->bindValue(':teletravail', $teletravail, $teletravail === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        
        $stmt->execute();
        
        return (int) $this->db->lastInsertId();
    }

    /**
     * Récupérer toutes les alertes d'un utilisateur
     */
    public function getUserAlerts($userId) {
        $query = "SELECT * FROM alertes_offres 
                  WHERE id_utilisateur = :user_id 
                  ORDER BY date_creation DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer une alerte par son ID
     */
    public function getAlertById($alertId, $userId) {
        $query = "SELECT * FROM alertes_offres 
                  WHERE id = :alert_id AND id_utilisateur = :user_id 
                  LIMIT 1";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':alert_id', $alertId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Mettre à jour une alerte
     */
    public function updateAlert($alertId, $userId, $data) {
        $query = "UPDATE alertes_offres 
                  SET nom_alerte = :nom,
                      mot_cle = :mot_cle,
                      ville = :ville,
                      domaine = :domaine,
                      type_contrat = :type_contrat,
                      remuneration_min = :remuneration_min,
                      duree_contrat = :duree_contrat,
                      niveau_etude = :niveau_etude,
                      teletravail = :teletravail
                  WHERE id = :alert_id AND id_utilisateur = :user_id";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':nom', $data['nom_alerte'], PDO::PARAM_STR);
        $stmt->bindParam(':mot_cle', $data['mot_cle'], PDO::PARAM_STR);
        $stmt->bindParam(':ville', $data['ville'], PDO::PARAM_STR);
        $stmt->bindParam(':domaine', $data['domaine'], PDO::PARAM_STR);
        $stmt->bindParam(':type_contrat', $data['type_contrat'], PDO::PARAM_STR);
        $stmt->bindParam(':remuneration_min', $data['remuneration_min'], PDO::PARAM_INT);
        $stmt->bindParam(':duree_contrat', $data['duree_contrat'], PDO::PARAM_STR);
        $stmt->bindParam(':niveau_etude', $data['niveau_etude'], PDO::PARAM_STR);
        $teletravail = ($data['teletravail'] !== '') ? (int) $data['teletravail'] : null;
        $stmt->bindValue(':teletravail', $teletravail, $teletravail === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $stmt->bindParam(':alert_id', $alertId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * Activer/Désactiver une alerte
     */
    public function toggleAlert($alertId, $userId) {
        $query = "UPDATE alertes_offres 
                  SET actif = NOT actif 
                  WHERE id = :alert_id AND id_utilisateur = :user_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':alert_id', $alertId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * Supprimer une alerte
     */
    public function deleteAlert($alertId, $userId) {
        $query = "DELETE FROM alertes_offres 
                  WHERE id = :alert_id AND id_utilisateur = :user_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':alert_id', $alertId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * Récupérer les nouvelles offres correspondant aux critères d'une alerte
     * depuis la dernière vérification
     */
    public function getMatchingOffers($alertId, $userId, $limit = 10) {
        // Récupérer l'alerte
        $alert = $this->getAlertById($alertId, $userId);
        
        if (!$alert) {
            return [];
        }

        // Construire la requête de recherche
        $query = "SELECT o.*, e.nom AS entreprise_nom
                  FROM offres o
                  INNER JOIN entreprises e ON o.id_entreprise = e.id
                  WHERE 1=1";

        $bindings = [];

        // Filtrer par mot-clé
        if (!empty($alert['mot_cle'])) {
            $query .= " AND (o.titre LIKE :keyword OR o.description LIKE :keyword OR e.nom LIKE :keyword)";
            $bindings[':keyword'] = '%' . $alert['mot_cle'] . '%';
        }

        // Filtrer par ville
        if (!empty($alert['ville'])) {
            $query .= " AND o.Ville LIKE :ville";
            $bindings[':ville'] = '%' . $alert['ville'] . '%';
        }

        // Filtrer par domaine (colonne exacte)
        if (!empty($alert['domaine'])) {
            $query .= " AND LOWER(o.domaine) = LOWER(:domaine)";
            $bindings[':domaine'] = $alert['domaine'];
        }

        // Filtrer par type de contrat
        if (!empty($alert['type_contrat'])) {
            $query .= " AND o.Type_contrat = :type_contrat";
            $bindings[':type_contrat'] = $alert['type_contrat'];
        }

        // Filtrer par rémunération minimale
        if (!empty($alert['remuneration_min']) && $alert['remuneration_min'] > 0) {
            $query .= " AND o.remuneration >= :remuneration_min";
            $bindings[':remuneration_min'] = (int) $alert['remuneration_min'];
        }

        // Filtrer par durée de contrat
        if (!empty($alert['duree_contrat'])) {
            $query .= " AND LOWER(o.`Durée_contrat`) = LOWER(:duree_contrat)";
            $bindings[':duree_contrat'] = $alert['duree_contrat'];
        }

        // Filtrer par niveau d'étude
        if (!empty($alert['niveau_etude'])) {
            $query .= " AND LOWER(o.Niveau_etude) = LOWER(:niveau_etude)";
            $bindings[':niveau_etude'] = $alert['niveau_etude'];
        }

        // Filtrer par télétravail
        if (isset($alert['teletravail']) && $alert['teletravail'] !== null && $alert['teletravail'] !== '') {
            $query .= " AND o.Teletravail = :teletravail";
            $bindings[':teletravail'] = (int) $alert['teletravail'];
        }

        // Récupérer uniquement les offres récentes (publiées après la création de l'alerte)
        $query .= " AND o.date_publication > :date_alerte";
        $bindings[':date_alerte'] = $alert['date_creation'];

        // Trier par date de publication décroissante
        $query .= " ORDER BY o.date_publication DESC LIMIT :limit";

        $stmt = $this->db->prepare($query);
        
        foreach ($bindings as $key => $value) {
            $paramType = ($key === ':remuneration_min') ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $value, $paramType);
        }
        
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Compter le nombre de nouvelles offres pour une alerte
     */
    public function countMatchingOffers($alertId, $userId) {
        $alert = $this->getAlertById($alertId, $userId);
        
        if (!$alert) {
            return 0;
        }

        $query = "SELECT COUNT(*) 
                  FROM offres o
                  INNER JOIN entreprises e ON o.id_entreprise = e.id
                  WHERE o.date_publication > :date_alerte";

        $bindings = [':date_alerte' => $alert['date_creation']];

        if (!empty($alert['mot_cle'])) {
            $query .= " AND (o.titre LIKE :keyword OR o.description LIKE :keyword OR e.nom LIKE :keyword)";
            $bindings[':keyword'] = '%' . $alert['mot_cle'] . '%';
        }

        if (!empty($alert['ville'])) {
            $query .= " AND o.Ville LIKE :ville";
            $bindings[':ville'] = '%' . $alert['ville'] . '%';
        }

        if (!empty($alert['domaine'])) {
            $query .= " AND LOWER(o.domaine) = LOWER(:domaine)";
            $bindings[':domaine'] = $alert['domaine'];
        }

        if (!empty($alert['type_contrat'])) {
            $query .= " AND o.Type_contrat = :type_contrat";
            $bindings[':type_contrat'] = $alert['type_contrat'];
        }

        if (!empty($alert['remuneration_min']) && $alert['remuneration_min'] > 0) {
            $query .= " AND o.remuneration >= :remuneration_min";
            $bindings[':remuneration_min'] = (int) $alert['remuneration_min'];
        }

        if (!empty($alert['duree_contrat'])) {
            $query .= " AND LOWER(o.`Durée_contrat`) = LOWER(:duree_contrat)";
            $bindings[':duree_contrat'] = $alert['duree_contrat'];
        }

        if (!empty($alert['niveau_etude'])) {
            $query .= " AND LOWER(o.Niveau_etude) = LOWER(:niveau_etude)";
            $bindings[':niveau_etude'] = $alert['niveau_etude'];
        }

        if (isset($alert['teletravail']) && $alert['teletravail'] !== null && $alert['teletravail'] !== '') {
            $query .= " AND o.Teletravail = :teletravail";
            $bindings[':teletravail'] = (int) $alert['teletravail'];
        }

        $stmt = $this->db->prepare($query);
        
        foreach ($bindings as $key => $value) {
            $paramType = ($key === ':remuneration_min') ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $value, $paramType);
        }

        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    /**
     * Récupérer les statistiques de toutes les alertes d'un utilisateur
     */
    public function getAlertsWithCounts($userId) {
        $alerts = $this->getUserAlerts($userId);
        
        foreach ($alerts as &$alert) {
            $alert['nouvelles_offres'] = $this->countMatchingOffers($alert['id'], $userId);
        }
        
        return $alerts;
    }
}
?>
