<?php
require 'includes/db_config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_projet = (int)$_GET['id'];

$sql_projet = "
    SELECT 
        p.title, 
        p.description, 
        p.project_link, 
        p.publish_date, 
        c.label AS category_name
    FROM projects p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.id = $id_projet
";

$result_projet = mysqli_query($link, $sql_projet);
$projet = mysqli_fetch_assoc($result_projet);

if (!$projet) {
    header("Location: index.php");
    exit;
}

$sql_images = "SELECT image_url FROM project_images WHERE project_id = $id_projet";
$result_images = mysqli_query($link, $sql_images);

// On stocke les images dans un tableau pour les utiliser plus bas
$images = [];
while ($row_img = mysqli_fetch_assoc($result_images)) {
    $images[] = $row_img['image_url'];
}

?>

<!DOCTYPE HTML>
<html>
<head>
    <title><?php echo htmlspecialchars($projet['title']); ?> - Portfolio</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
</head>
<body class="is-preload">

    <div id="header">
        <span class="logo icon fa-paper-plane"></span>
        <h1>Mon Portfolio</h1>
        <p>Développeur Web & Designer</p>
    </div>

    <div id="main">

        <section class="one">
            <div class="container">
                <header class="major">
                    <h2><?php echo htmlspecialchars($projet['title']); ?></h2>
                    <p>
                        Catégorie : <strong><?php echo htmlspecialchars($projet['category_name']); ?></strong><br>
                        <em>Publié le <?php echo date("d/m/Y", strtotime($projet['publish_date'])); ?></em>
                    </p>
                </header>

                <?php if (!empty($images)): ?>
                    <div class="box alt">
                        <div class="row gtr-50 uniform">
                            <?php 
                            // Si on a une seule image, on l'affiche en grand
                            if (count($images) == 1): 
                            ?>
                                <div class="col-12">
                                    <span class="image fit"><img src="<?php echo htmlspecialchars($images[0]); ?>" alt="" /></span>
                                </div>
                            <?php 
                            // Si on en a plusieurs, on fait une grille
                            else: 
                                foreach ($images as $img_path):
                            ?>
                                <div class="col-6 col-12-mobilep">
                                    <span class="image fit"><img src="<?php echo htmlspecialchars($img_path); ?>" alt="" /></span>
                                </div>
                            <?php 
                                endforeach; 
                            endif; 
                            ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div style="text-align: left; margin-top: 2em;">
                    <p><?php echo nl2br(htmlspecialchars($projet['description'])); ?></p>
                </div>

                <div class="col-12" style="margin-top: 3em;">
                    <ul class="actions">
                        <li><a href="index.php" class="button">Retour à l'accueil</a></li>
                        
                        <?php if (!empty($projet['project_link'])): ?>
                            <li><a href="<?php echo htmlspecialchars($projet['project_link']); ?>" class="button primary" target="_blank">Voir le site en ligne</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

            </div>
        </section>

    </div>

    <div id="footer">
        <ul class="copyright">
            <li>&copy; Malo Portfolio. All rights reserved.</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
        </ul>
    </div>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/jquery.scrolly.min.js"></script>
    <script src="assets/js/jquery.scrollex.min.js"></script>
    <script src="assets/js/browser.min.js"></script>
    <script src="assets/js/breakpoints.min.js"></script>
    <script src="assets/js/util.js"></script>
    <script src="assets/js/main.js"></script>

</body>
</html>