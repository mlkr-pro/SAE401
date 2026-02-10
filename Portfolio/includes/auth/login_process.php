<?php
session_start();
if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'guest') {
        header("Location: dashboard_guest.php");
    } else {
        header("Location: dashboard.php");
    }
    exit();
}

$erreur = "";

if (isset($_POST['connecter'])) {
    $login = mysqli_real_escape_string($link, $_POST['login']);
    $pass = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$login'";
    $result = mysqli_query($link, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($pass, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            
            if ($row['role'] === 'guest') {
                header("Location: dashboard_guest.php");
            } else {
                header("Location: dashboard.php");
            }
            exit();
        } else {
            $erreur = "Mot de passe incorrect.";
        }
    } else {
        $erreur = "Identifiant inconnu.";
    }
}
?>