<?php
// Fonction de suppression (DELETE)
if (isset($_GET['delete'])) {
    $id_to_delete = (int)$_GET['delete'];
    $sql_delete = "DELETE FROM socials WHERE id = $id_to_delete";
    if (mysqli_query($link, $sql_delete)) {
        $message = "Réseau supprimé avec succès.";
        header("Location: dashboard.php");
        exit;
    } else {
        $message = "Erreur lors de la suppression.";
    }
}
?>