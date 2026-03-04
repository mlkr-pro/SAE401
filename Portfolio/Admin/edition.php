<?php
session_start();
require '../includes/db_config.php';
require '../includes/auth/security.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id_proj = (int)$_GET['id'];

require '../includes/project/update.php';

$sql_get = "
    SELECT p.*, (SELECT image_url FROM project_images WHERE project_id = p.id LIMIT 1) as image_url 
    FROM projects p WHERE p.id = $id_proj
";
$res_get = mysqli_query($link, $sql_get);
$projet = mysqli_fetch_assoc($res_get);

if (!$projet) {
    header("Location: dashboard.php");
    exit;
}

$sql_cat = "SELECT * FROM categories ORDER BY label ASC";
$res_cat = mysqli_query($link, $sql_cat);
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Modifier le projet</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../assets/css/main.css" />
    <link rel="stylesheet" href="../assets/css/admin.css" />
</head>
<body class="is-preload">
    <div id="header">
        <h1>Modifier un projet</h1>
        <p>Retour au tableau de bord</p>
        <ul class="actions special">
            <li><a href="dashboard.php" class="button icon solid fa-arrow-left">Retour</a></li>
        </ul>
    </div>

    <div id="main">
        <div class="box container">
            <div class="admin-block">
                <header>
                    <h3>Édition : <?php echo htmlspecialchars($projet['title']); ?></h3>
                </header>

                <?php if(isset($erreur)) echo "<p class='msg-error' style='color:red;'>$erreur</p>"; ?>

                <form method="post" action="" enctype="multipart/form-data">
                    <div class="row gtr-50">
                        <div class="col-12">
                            <label>Titre</label>
                            <input type="text" name="title" value="<?php echo htmlspecialchars($projet['title']); ?>" required />
                        </div>

                        <div class="col-6 col-12-mobilep">
                            <label>Catégorie</label>
                            <select name="category_id" required>
                                <?php 
                                while($cat = mysqli_fetch_assoc($res_cat)) {
                                    $selected = ($cat['id'] == $projet['category_id']) ? 'selected' : '';
                                    echo '<option value="'.$cat['id'].'" '.$selected.'>'.htmlspecialchars($cat['label']).'</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-12">
                            <label>Description</label>
                            <textarea name="description" rows="6" required><?php echo htmlspecialchars($projet['description']); ?></textarea>
                        </div>

                        <div class="col-6 col-12-mobilep">
                            <label>Lien du projet (Optionnel)</label>
                            <input type="text" name="project_link" value="<?php echo htmlspecialchars($projet['project_link']); ?>" />
                        </div>

                        <div class="col-6 col-12-mobilep">
                            <label>Image de couverture (Laisser vide pour garder l'actuelle)</label>
                            <input type="file" name="project_image" accept="image/*" />
                            <?php if(!empty($projet['image_url'])): ?>
                                <p style="font-size:0.8em; color:#888; margin-top:0.5em;">Actuelle : <?php echo htmlspecialchars($projet['image_url']); ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="col-12 text-right mt-1">
                            <input type="submit" name="update_project" value="Enregistrer les modifications" class="btn-wide primary" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>