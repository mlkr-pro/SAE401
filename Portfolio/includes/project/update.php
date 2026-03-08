<?php
require_once '../db_config.php';

// Définit le type de réponse comme JSON
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_project'])) {
    
    $id_proj = (int)$_POST['id_project'];
    
    // Sécuriser les champs textes
    $title = mysqli_real_escape_string($link, $_POST['title']);
    $desc = mysqli_real_escape_string($link, $_POST['description']);
    $url = mysqli_real_escape_string($link, $_POST['project_link']);
    $cat_id = (int)$_POST['category_id'];

    // Mettre à jour les textes du projet
    $sql_update = "UPDATE projects SET title='$title', description='$desc', project_link='$url', category_id=$cat_id WHERE id=$id_proj";
    
    if (mysqli_query($link, $sql_update)) {
        
        // Gestion de l'image de couverture (is_main)
        if (isset($_POST['main_image'])) {
            $main_img_id = (int)$_POST['main_image'];
            // Retirer l'ancien statut main
            mysqli_query($link, "UPDATE project_images SET is_main = 0 WHERE project_id = $id_proj");
            // Définir le nouveau
            mysqli_query($link, "UPDATE project_images SET is_main = 1 WHERE id = $main_img_id AND project_id = $id_proj");
        }

        // Suppression des images sélectionnées
        if (isset($_POST['delete_images'])) {
            foreach ($_POST['delete_images'] as $del_id) {
                $del_id = (int)$del_id;
                
                // Ne pas supprimer si c'est la couverture actuelle, pour sécurité
                $sql_is_main = "SELECT is_main FROM project_images WHERE id = $del_id";
                $res_is_main = mysqli_query($link, $sql_is_main);
                if ($row_is_main = mysqli_fetch_assoc($res_is_main)) {
                    if ($row_is_main['is_main'] == 1) continue;
                }

                // Récupérer le chemin du fichier
                $res = mysqli_query($link, "SELECT image_url FROM project_images WHERE id = $del_id");
                if ($row = mysqli_fetch_assoc($res)) {
                    $file_path = "../../" . $row['image_url'];
                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                    // Supprimer de la base de données
                    mysqli_query($link, "DELETE FROM project_images WHERE id = $del_id");
                }
            }
        }

        // Ajout de nouvelles images
        if (isset($_FILES['project_images'])) {
            $target_dir = "../../images/";
            
            $files_reorganized = [];
            foreach ($_FILES['project_images'] as $key => $all) {
                foreach ($all as $i => $val) {
                    $files_reorganized[$i][$key] = $val;
                }
            }

            foreach($files_reorganized as $file) {
                if($file['error'] == 0) {
                    $file_name = "proj_" . uniqid() . "_" . basename($file["name"]);
                    $target_file = $target_dir . $file_name;
                    if (move_uploaded_file($file['tmp_name'], $target_file)) {
                        $db_path_safe = mysqli_real_escape_string($link, "images/" . $file_name);
                        // Les nouvelles images sont is_main = 0 par défaut en édition
                        mysqli_query($link, "INSERT INTO project_images (image_url, project_id, is_main) VALUES ('$db_path_safe', $id_proj, 0)");
                    }
                }
            }
        }
        echo json_encode(["status" => "success", "message" => "Projet mis à jour."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Erreur SQL : " . mysqli_error($link)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Requête invalide."]);
}
?>