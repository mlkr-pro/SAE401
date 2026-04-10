<?php
require_once '../includes/socials/Social.php';
$socialManager = new Social($link);

// Traitement (Create et Update)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_social'])) {
        if ($socialManager->add($_POST['new_name'], $_POST['new_url'])) {
            $message = "Nouveau réseau ajouté !";
        }
    }
    if (isset($_POST['update_socials']) && isset($_POST['urls'])) {
        foreach ($_POST['urls'] as $id => $url) {
            $socialManager->update($id, $url);
        }
        $message = "Modifications enregistrées !";
    }
}

// Traitement (Delete)
if (isset($_GET['delete_social'])) {
    $socialManager->delete($_GET['delete_social']);
    header("Location: dashboard.php?msg=social_deleted");
    exit;
}

// Lecture (Read) pour l'affichage
$socials = $socialManager->getAll();
?>