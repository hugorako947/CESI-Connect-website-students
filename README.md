# CESI Connect

![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat&logo=javascript&logoColor=black)

Plateforme web de recherche de **stages, d'alternances et d'emplois** destinée aux étudiants de l'école d'ingénieurs **CESI**, campus de Nanterre (Île-de-France).

Les étudiants y consultent les offres des entreprises partenaires, les enregistrent en favoris, postulent en ligne et créent des alertes personnalisées. Les pilotes de promotion suivent l'avancement des recherches de leurs étudiants, et les administrateurs gèrent l'ensemble des contenus.

## Aperçu

<!-- Ajoute ici des captures d'écran du site, par exemple :
![Page d'accueil](docs/screenshots/accueil.png)
![Liste des offres](docs/screenshots/offres.png)
-->

## Contexte

Projet réalisé en équipe de 4 étudiants dans le cadre de notre formation à CESI. L'objectif était de concevoir de A à Z une application web complète, avec plusieurs types d'utilisateurs, une base de données relationnelle et une architecture MVC, sans framework.

## Fonctionnalités

Le site repose sur trois rôles, chacun avec son propre espace et ses droits d'accès.

### Étudiant

- Inscription, connexion et réinitialisation du mot de passe par lien sécurisé
- Recherche d'offres avec filtres : mot-clé, ville, domaine, compétence, niveau, durée, télétravail et rémunération minimale
- Résultats paginés, fiche détaillée de chaque offre et de chaque entreprise
- Liste de favoris (wishlist)
- Candidature en ligne avec dépôt du CV et de la lettre de motivation (PDF, DOC ou DOCX, 5 Mo maximum)
- Suivi de ses candidatures et de leur statut
- Alertes d'offres personnalisées selon ses critères, activables et désactivables, avec la liste des offres correspondantes
- Gestion du profil et suppression du compte

### Pilote de promotion

- Tableau de bord dédié
- Suivi des étudiants : favoris et candidatures de chacun
- Statistiques : candidatures par statut, offres les plus populaires, répartition des offres par type de contrat

### Administrateur

- Tableau de bord avec recherche globale
- Gestion des entreprises et des offres (création, modification, suppression)
- Gestion des utilisateurs

## Stack technique

| Côté | Technologies |
|---|---|
| Back-end | PHP orienté objet, architecture MVC maison, PDO |
| Base de données | MySQL / MariaDB |
| Front-end | HTML5, CSS3 (variables CSS, responsive), JavaScript vanilla |
| Outils | Git, GitHub |

## Points techniques

- **Architecture MVC sans framework** : un point d'entrée unique (`public/index.php`) fait office de routeur et transmet chaque requête au bon contrôleur.
- **Contrôle d'accès par rôle** : chaque page réservée vérifie le rôle de l'utilisateur connecté (administrateur, pilote ou étudiant).
- **Sécurité** :
  - requêtes préparées PDO contre les injections SQL ;
  - échappement des données affichées contre les failles XSS ;
  - mots de passe hachés avec bcrypt ;
  - jetons de réinitialisation générés de façon cryptographique, avec date d'expiration ;
  - fichiers déposés stockés hors du dossier public, avec contrôle de l'extension et de la taille.
- **Connexion à la base** centralisée via un Singleton.
- **Travail en équipe** : plus de 300 commits répartis entre les 4 membres.

## Structure du projet

```
CESI-Connect-website-students/
├── app/
│   ├── controller/   # Contrôleurs : logique métier et contrôle d'accès
│   ├── models/       # Accès aux données (PDO) et connexion à la base
│   └── views/        # Pages du site
├── public/
│   ├── index.php     # Point d'entrée unique et routeur
│   └── assets/       # CSS, JavaScript, images
├── uploads/          # CV et lettres de motivation (hors du dossier public)
└── database          # Script SQL de création et de peuplement de la base
```

## Installation en local

### Prérequis

- PHP 8 (recommandé)
- MySQL ou MariaDB
- Un environnement local comme XAMPP, WAMP ou MAMP (facultatif)

### Étapes

1. **Cloner le dépôt**

   ```bash
   git clone https://github.com/hugorako947/CESI-Connect-website-students.git
   cd CESI-Connect-website-students
   ```

2. **Créer la base de données** en important le script `database`, par exemple via phpMyAdmin ou en ligne de commande :

   ```bash
   mysql -u root -p < database
   ```

3. **Configurer la connexion** en renseignant l'hôte, le nom de la base, l'utilisateur et le mot de passe dans `app/models/database.php`.

4. **Lancer le site** avec le serveur intégré de PHP :

   ```bash
   php -S localhost:8000 -t public
   ```

   Puis ouvrir [http://localhost:8000](http://localhost:8000).

   Avec XAMPP ou WAMP, place plutôt le projet dans le dossier `htdocs` (ou `www`) et ouvre `http://localhost/CESI-Connect-website-students/public/`.

### Comptes et rôles

Un compte créé via la page d'inscription reçoit le rôle Étudiant. Pour tester les espaces Pilote ou Administrateur, modifie la colonne `id_role` de l'utilisateur dans la table `utilisateurs` : `1` pour Administrateur, `2` pour Pilote, `3` pour Étudiant.

## Équipe

| Membre | Rôle |
|---|---|
| [@hugorako947](https://github.com/hugorako947) | Développement du site et des nouvelles fonctionnalités, alimentation de la base de données |
| Daoud | À compléter |
| Baptiste023 | À compléter |
| Terry | À compléter |

## Licence

Projet réalisé dans un cadre pédagogique à CESI.
