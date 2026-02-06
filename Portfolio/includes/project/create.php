<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_project'])) {
    $cat_id = (int)$_POST['category_id'];
    if ($cat_id === 0 && !empty($_POST['new_category_label'])) {
        $new_label = mysqli_real_escape_string($link, $_POST['new_category_label']);
        // On crée la nouvelle catégorie
        $sql_new_cat = "INSERT INTO categories (label) VALUES ('$new_label')";
        if (mysqli_query($link, $sql_new_cat)) {
            // On récupère l'id de la catégorie qu'on vient de créer
            $cat_id = mysqli_insert_id($link);
        }
    }

    $title = mysqli_real_escape_string($link, $_POST['title']);
    $desc = mysqli_real_escape_string($link, $_POST['description']);
    $url = mysqli_real_escape_string($link, $_POST['project_link']);
    if ($cat_id > 0 && !empty($title)) {
        $sql_add_proj = "INSERT INTO projects (title, description, project_link, category_id) 
                         VALUES ('$title', '$desc', '$url', $cat_id)";
        
        if (mysqli_query($link, $sql_add_proj)) {
            $project_id = mysqli_insert_id($link);
            if (isset($_FILES['project_image']) && $_FILES['project_image']['error'] == 0) {
                $target_dir = "../images/"; // Dossier de destination
                $file_name = basename($_FILES["project_image"]["name"]);
                $target_file = $target_dir . $file_name;
                if (move_uploaded_file($_FILES["project_image"]["tmp_name"], $target_file)) {
                    $db_path = "images/" . $file_name;
                    $db_path_safe = mysqli_real_escape_string($link, $db_path);
                    $sql_img = "INSERT INTO project_images (image_url, project_id) VALUES ('$db_path_safe', $project_id)";
                    mysqli_query($link, $sql_img);
                }
            }
            $message = "Projet ajouté avec succès !";
        }
    } else {
        $message = "Erreur : Catégorie ou Titre manquant.";
    }
}
?>