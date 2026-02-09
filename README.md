<a id="readme-top"></a>

<div align="center">
  <h3 align="center">SAÉ 401 - Portfolio Dynamique & Back-Office</h3>

  <p align="center">
    Application web complète transformant un portfolio statique (HTML5 UP) en site dynamique administrable.
    <br />
    Le projet combine une gestion de sessions PHP sécurisée, un Back-Office complet et une base de données relationnelle.
    <br />
    <br />
    <a href="https://lecaer.alwaysdata.net/S4/SAE401_Portfolio/index.php"><strong>Voir le site en ligne »</strong></a>
  </p>
</div>

<details>
  <summary>Table des matières</summary>
  <ol>
    <li><a href="#a-propos-du-projet">À propos du projet</a></li>
    <li><a href="#acces-et-identifiants">Accès et Identifiants</a></li>
    <li><a href="#fonctionnalites">Fonctionnalités</a></li>
    <li><a href="#technologies">Technologies</a></li>
    <li><a href="#installation">Installation locale</a></li>
  </ol>
</details>

## À propos du projet

Ce projet a été réalisé durant le 4ème semestre du **BUT MMI** (SAÉ 401). L'objectif était de développer une architecture **Back-End** complète pour rendre un portfolio totalement administrable sans toucher au code source.

Il permet à l'administrateur de gérer ses projets, ses compétences et ses réseaux sociaux via une interface sécurisée, tout en conservant le design Front-End basé sur le template "Directive" de HTML5 UP.

> **Note :** Le développement respecte le principe DRY (Don't Repeat Yourself) avec une structure modulaire (dossier `includes/`) séparant la logique PHP de l'affichage HTML.

<p align="right">(<a href="#readme-top">retour en haut</a>)</p>

## Accès et Identifiants

Le projet est hébergé et accessible directement via votre navigateur.

**🔗 Lien Site Vitrine :** [https://lecaer.alwaysdata.net/S4/SAE401_Portfolio/index.php](https://lecaer.alwaysdata.net/S4/SAE401_Portfolio/index.php)

**🔗 Lien Administration :** [https://lecaer.alwaysdata.net/S4/SAE401_Portfolio/Admin/login.php](https://lecaer.alwaysdata.net/S4/SAE401_Portfolio/Admin/login.php)

Pour accéder à l'interface de gestion (Back Office), il faut s'authentifier :

* **Login** (Défini dans la BDD) **:** admin
* **Mot de passe** (Hashé en BDD via `password_verify`) **:** admin

<p align="right">(<a href="#readme-top">retour en haut</a>)</p>

## Fonctionnalités

Le projet propose un système CRUD complet et une gestion dynamique des médias :

### 🔐 Authentification & Sécurité
* **Login Sécurisé :** Vérification des identifiants avec hachage de mot de passe.
* **Protection de Route :** Redirection automatique si tentative d'accès au Dashboard sans session active.
* **Sécurité SQL :** Utilisation systématique de `mysqli_real_escape_string` contre les injections.

### 🗃️ Gestion des Projets (CRUD Avancé)
* **Tableau de bord :** Interface ergonomique avec système d'accordéons (JS) pour gérer les modules.
* **Ajout avec Upload :** Création de projets incluant l'upload et le renommage sécurisé des images sur le serveur.
* **Catégories Dynamiques :** Possibilité de choisir une catégorie existante ou d'en créer une nouvelle ("Custom Category") directement depuis le formulaire d'ajout.
* **Suppression Propre :** Suppression en BDD et nettoyage automatique du fichier image associé sur le serveur.

### 🌐 Gestion des Réseaux Sociaux
* **CRUD Rapide :** Ajout, modification et suppression des liens de contact (LinkedIn, GitHub, etc.) affichés en pied de page.

<p align="right">(<a href="#readme-top">retour en haut</a>)</p>

## Technologies

* ![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white) **(Native / MySQLi)**
* ![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white) **(Relationnel : Jointures)**
* ![HTML5](https://img.shields.io/badge/html5-%23E34F26.svg?style=for-the-badge&logo=html5&logoColor=white) **(Template HTML5 UP)**
* ![CSS3](https://img.shields.io/badge/css3-%231572B6.svg?style=for-the-badge&logo=css3&logoColor=white) **(Custom Admin CSS)**
* ![JavaScript](https://img.shields.io/badge/javascript-%23323330.svg?style=for-the-badge&logo=javascript&logoColor=%23F7DF1E) **(Logique Accordéons)**

<p align="right">(<a href="#readme-top">retour en haut</a>)</p>

## Installation

Pour lancer le projet localement :

1.  **Cloner le dépôt :**
    ```sh
    git clone [https://github.com/mlkr-pro/SAE401.git](https://github.com/mlkr-pro/SAE401.git)
    ```

2.  **Configuration de la Base de données :**
    * Le fichier de configuration `includes/db_config.php` gère la connexion.
    * Créez ce fichier dans le dossier `includes/` et adaptez vos identifiants :

    ```php
    <?php
    $link = mysqli_connect("localhost", "root", "", "name_db"); // Adaptez selon votre config

    if (!$link) {
        die("Erreur de connexion : " . mysqli_connect_error());
    }
    // mysqli_set_charset($link, "utf8"); // Optionnel selon config serveur
    ?>
    ```

3.  **Import SQL :**
    * Créez une base de données locale (ex: `portfolio_db`).
    * Importez le fichier `back-office-sae401.sql` (présent à la racine du dépôt) pour générer les tables.

4.  **Lancement :**
    * Placez le dossier dans votre serveur local (WAMP/MAMP/XAMPP).
    * Accédez à `http://localhost/Portfolio/` pour le visiteur.
    * Accédez à `http://localhost/Portfolio/Admin/` pour l'administration.

<p align="right">(<a href="#readme-top">retour en haut</a>)</p>
