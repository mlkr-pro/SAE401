<?php
require_once '../db_config.php';

// Définit le type de réponse comme JSON
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title'])) {
    
    // Gérer la catégorie
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

    // Vérification des upload
    $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5 Mo maximum

    if (isset($_FILES['project_images'])) {
        foreach ($_FILES['project_images']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['project_images']['error'][$key] == 0) {
                // Limite de la taille
                if ($_FILES['project_images']['size'][$key] > $max_size) {
                    echo json_encode(["status" => "error", "message" => "Le fichier " . $_FILES['project_images']['name'][$key] . " dépasse 5Mo."]);
                    exit;
                }
                // Vérification stricte du type MIME
                $mime = mime_content_type($tmp_name);
                if (!in_array($mime, $allowed_mimes)) {
                    echo json_encode(["status" => "error", "message" => "Le format du fichier " . $_FILES['project_images']['name'][$key] . " n'est pas autorisé."]);
                    exit;
                }
            }
        }
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
                    // Renommage strict avec uniqid() et l'extension du fichier
                    $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
                    $file_name = "proj_" . uniqid() . "." . strtolower($ext);
                    $target_file = $target_dir . $file_name;
                    
                    if (move_uploaded_file($file['tmp_name'], $target_file)) {
                        $db_path_safe = mysqli_real_escape_string($link, "images/" . $file_name); 
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