<?php
// app/controller/AuthController.php

class AuthController {

// Affiche le formulaire de connexion
public function showLoginForm() {
require_once '../app/views/connexion.php';
}

// Affiche le formulaire d'inscription
public function showRegisterForm() {
require_once '../app/views/inscription.php';
}

// Gère la déconnexion
public function logout() {
// On détruit la session
session_destroy();
// On redirige vers l'accueil
header('Location: index.php?route=accueil');
exit();
}
}

