<?php
require_once '../db_config.php';

// Définit le type de réponse comme JSON
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title'])) {
    
    // Gérer la catégorie (existante ou nouvelle)
    $cat_id = (int)$_POST['category_id'];
    if ($cat_id === 0 && !empty($_POST['new_category_label'])) {
        $new_label = mysqli_real_escape_string($link, $_POST['new_category_label']);
        // Vérifier si elle n'existe pas déjà
        $sql_check = "SELECT id FROM categories WHERE label = '$new_label'";
        $res_check = mysqli_query($link, $sql_check);
        if ($row_cat = mysqli_fetch_assoc($res_check)) {
            $cat_id = $row_cat['id'];
        } else {
            $sql_new_cat = "INSERT INTO categories (label) VALUES ('$new_label')";
            if (mysqli_query($link, $sql_new_cat)) {
                $cat_id = mysqli_insert_id($link);
            }
        }
    }

    // Sécuriser les champs textes
    $title = mysqli_real_escape_string($link, $_POST['title']);
    $desc = mysqli_real_escape_string($link, $_POST['description']);
    $url = mysqli_real_escape_string($link, $_POST['project_link']);
    
    // Récupérer l'index de l'image de couverture (par défaut 0)
    $main_image_index = isset($_POST['main_image_index']) ? (int)$_POST['main_image_index'] : 0;

    // Validation de base
    if ($cat_id <= 0 || empty($title)) {
        echo json_encode(["status" => "error", "message" => "Catégorie ou Titre manquant."]);
        exit;
    }

    // Insérer le projet
    $sql_add_proj = "INSERT INTO projects (title, description, project_link, category_id) 
                    VALUES ('$title', '$desc', '$url', $cat_id)";
    
    if (mysqli_query($link, $sql_add_proj)) {
        $project_id = mysqli_insert_id($link);
        
        // Gérer les images multiples
        if (isset($_FILES['project_images'])) {
            $target_dir = "../../images/";
            $files_reorganized = [];
            foreach ($_FILES['project_images'] as $key => $all) {
                foreach ($all as $i => $val) {
                    $files_reorganized[$i][$key] = $val;
                }
            }

            foreach($files_reorganized as $i => $file) {
                if($file['error'] == 0) {
                    // Nom unique
                    $file_name = "proj_" . uniqid() . "_" . basename($file["name"]);
                    $target_file = $target_dir . $file_name;
                    
                    if (move_uploaded_file($file['tmp_name'], $target_file)) {
                        $db_path_safe = mysqli_real_escape_string($link, "images/" . $file_name); 
                        // Vérifie si cette image est la couverture
                        $is_main = ($i === $main_image_index) ? 1 : 0;
                        
                        $sql_img = "INSERT INTO project_images (image_url, project_id, is_main) VALUES ('$db_path_safe', $project_id, $is_main)";
                        mysqli_query($link, $sql_img);
                    }
                }
            }
        }
        echo json_encode(["status" => "success", "message" => "Projet créé avec succès."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Erreur SQL : " . mysqli_error($link)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Requête invalide."]);
}
?>