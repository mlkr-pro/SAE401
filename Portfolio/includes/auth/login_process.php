<?php
if (isset($_POST['connecter'])) {
    $login = mysqli_real_escape_string($link, $_POST['login']);
    $pass = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$login'";
    $result = mysqli_query($link, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($pass, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            
            header("Location: dashboard.php");
            exit();
        } else {
            $erreur = "Mot de passe incorrect.";
        }
    } else {
        $erreur = "Identifiant inconnu.";
    }
}
?>