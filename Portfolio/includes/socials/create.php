<?php
// Fonction d'ajout (CREATE)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_social'])) {
    $new_name = mysqli_real_escape_string($link, $_POST['new_name']);
    $new_url = mysqli_real_escape_string($link, $_POST['new_url']);

    if (!empty($new_name) && !empty($new_url)) {
        $sql_add = "INSERT INTO socials (name, url) VALUES ('$new_name', '$new_url')";
        if (mysqli_query($link, $sql_add)) {
            $message = "Nouveau réseau ajouté !";
        } else {
            $message = "Erreur lors de l'ajout.";
        }
    } else {
        $message = "Veuillez remplir tous les champs pour l'ajout.";
    }
}
?>