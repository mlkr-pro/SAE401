<?php
// Empêche l'utilisateur d'accéder au dashboard si il n'est pas connecté et le redirige vers la page login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>