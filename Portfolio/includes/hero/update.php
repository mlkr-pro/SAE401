<?php
if (isset($_POST['update_hero'])) {
    $title = mysqli_real_escape_string($link, $_POST['title']);
    $subtitle = mysqli_real_escape_string($link, $_POST['subtitle']);
    $desc = mysqli_real_escape_string($link, $_POST['description']);
    $sql_update = "UPDATE hero SET title='$title', subtitle='$subtitle', description='$desc' WHERE id=1";
    mysqli_query($link, $sql_update);

    // Récupère les anciens chemins pour pouvoir supprimer les vieux fichiers du serveur
    $sql_old = "SELECT profile_pic, cv_link FROM hero WHERE id=1";
    $res_old = mysqli_query($link, $sql_old);
    $old_data = mysqli_fetch_assoc($res_old);

    // Upload de la nouvelle Photo de profil
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        if (!empty($old_data['profile_pic']) && file_exists("../" . $old_data['profile_pic'])) {
            unlink("../" . $old_data['profile_pic']);
        }
        // On génère un nom unique pour la nouvelle image et on la déplace 
        $ext = pathinfo($_FILES["profile_pic"]["name"], PATHINFO_EXTENSION);
        $pp_name = "profile_" . uniqid() . "." . $ext;
        $target_pp = "../images/" . $pp_name;
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_pp)) {
            $db_pp = "images/" . $pp_name;
            mysqli_query($link, "UPDATE hero SET profile_pic='$db_pp' WHERE id=1");
        }
    }

    // Upload du nouveau CV
    if (isset($_FILES['cv_link']) && $_FILES['cv_link']['error'] == 0) {
        if (!empty($old_data['cv_link']) && file_exists("../" . $old_data['cv_link'])) {
            unlink("../" . $old_data['cv_link']);
        }

        // On génère un nom unique pour le nouveau CV et on le déplace 
        $ext = pathinfo($_FILES["cv_link"]["name"], PATHINFO_EXTENSION);
        $cv_name = "cv_" . uniqid() . "." . $ext;
        $target_cv = "../images/" . $cv_name;
        if (move_uploaded_file($_FILES["cv_link"]["tmp_name"], $target_cv)) {
            $db_cv = "images/" . $cv_name;
            mysqli_query($link, "UPDATE hero SET cv_link='$db_cv' WHERE id=1");
        }
    }

    header("Location: dashboard.php?msg=hero_updated");
    exit;
}
?>