<?php
// app/controller/StudentController.php

class StudentController {

// Affiche la page de profil
public function profile() {
// TODO: Vérifier si l'utilisateur est bien connecté
require_once '../app/views/profil.php';
}

// Affiche la wishlist de l'étudiant
public function wishlist() {
// TODO: Vérifier si l'utilisateur est bien connecté
// TODO: Récupérer les offres de la wishlist depuis la BDD
require_once '../app/views/wishlist.php';
}

// Affiche les candidatures envoyées
public function applications() {
// TODO: Vérifier si l'utilisateur est bien connecté
// TODO: Récupérer les candidatures de l'étudiant depuis la BDD
require_once '../app/views/candidatures-envoyees.php';
}
}