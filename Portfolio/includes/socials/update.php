<?php
// Fonction de modification (UPDATE)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_socials'])) {
    if (isset($_POST['urls'])) {
        foreach ($_POST['urls'] as $id => $url) {
            $id = (int)$id; // On force en entier pour éviter les problèmes
            $url = mysqli_real_escape_string($link, $url);
            $sql_update = "UPDATE socials SET url='$url' WHERE id=$id";
            mysqli_query($link, $sql_update);
        }
        $message = "Modifications enregistrées !";
    }
}
?>