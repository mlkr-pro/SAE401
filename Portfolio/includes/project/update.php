<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_project'])) {
    $title = mysqli_real_escape_string($link, $_POST['title']);
    $desc = mysqli_real_escape_string($link, $_POST['description']);
    $url = mysqli_real_escape_string($link, $_POST['project_link']);
    $cat_id = (int)$_POST['category_id'];
    $sql_update = "UPDATE projects SET title='$title', description='$desc', project_link='$url', category_id=$cat_id WHERE id=$id_proj";
    
    if (mysqli_query($link, $sql_update)) {
        
        if (isset($_FILES['project_image']) && $_FILES['project_image']['error'] == 0) {
            
            $sql_old_img = "SELECT image_url FROM project_images WHERE project_id = $id_proj LIMIT 1";
            $res_old_img = mysqli_query($link, $sql_old_img);
            if ($row_img = mysqli_fetch_assoc($res_old_img)) {
                $old_file = "../" . $row_img['image_url'];
                if (file_exists($old_file)) {
                    unlink($old_file);
                }
            }

            $ext = pathinfo($_FILES["project_image"]["name"], PATHINFO_EXTENSION);
            $file_name = "proj_" . uniqid() . "." . $ext;
            $target_file = "../images/" . $file_name;
            
            if (move_uploaded_file($_FILES["project_image"]["tmp_name"], $target_file)) {
                $db_path = "images/" . $file_name;
                $db_path_safe = mysqli_real_escape_string($link, $db_path);
                
                if (mysqli_num_rows($res_old_img) > 0) {
                    mysqli_query($link, "UPDATE project_images SET image_url='$db_path_safe' WHERE project_id=$id_proj");
                } else {
                    mysqli_query($link, "INSERT INTO project_images (image_url, project_id) VALUES ('$db_path_safe', $id_proj)");
                }
            }
        }
        
        header("Location: dashboard.php?msg=updated");
        exit;
    } else {
        $erreur = "Erreur SQL : " . mysqli_error($link);
    }
}
?>