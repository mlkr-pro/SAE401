<?php
// Empêche l'utilisateur d'accéder au dashboard si il n'est pas connecté et le redirige vers la page login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Si on est sur dashboard.php MAIS qu'on est un invité on est redirigé vers l'autre dashboard
if (basename($_SERVER['PHP_SELF']) == 'dashboard.php' && $_SESSION['role'] === 'guest') {
    header("Location: dashboard_guest.php");
    exit;
}
?>