<?php
if (isset($_GET['delete_project'])) {
    $id_proj = (int)$_GET['delete_project'];
    // Pour suppression des images dans la BDD
    $sql_img = "SELECT image_url FROM project_images WHERE project_id = $id_proj";
    $res_img = mysqli_query($link, $sql_img);

    // Suppression des images plus utilisé sur la BDD
    if ($res_img) {
        while($img = mysqli_fetch_assoc($res_img)) {
            $file_to_delete = "../" . $img['image_url'];
            if (file_exists($file_to_delete)) {
                unlink($file_to_delete);
            }
        }
    }

    $sql_del = "DELETE FROM projects WHERE id = $id_proj";
    
    if (mysqli_query($link, $sql_del)) {
        $message = "Projet supprimé avec succès.";
        header("Location: dashboard.php");
        exit;
    } else {
        $message = "Erreur lors de la suppression : ";
    }
}
?>