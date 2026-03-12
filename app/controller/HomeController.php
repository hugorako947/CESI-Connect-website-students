<?php
// app/controller/HomeController.php

class HomeController {
    
    // Affiche la page d'accueil
    public function index() {
        require_once '../app/views/accueil.php'; 
    }

    // Affiche les mentions légales
    public function legalMentions() {
        require_once '../app/views/mentions-legales.php';
    }

    // Affiche la page de contact
    public function contact() {
        require_once '../app/views/contact.php';
    }
}