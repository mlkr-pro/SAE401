<?php
require 'includes/db_config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_projet = (int)$_GET['id'];

$sql_projet = "
    SELECT p.title, p.description, p.project_link, p.publish_date, c.label AS category_name
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

$sql_images = "SELECT image_url FROM project_images WHERE project_id = $id_projet ORDER BY is_main DESC, id ASC";
$result_images = mysqli_query($link, $sql_images);
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
    <link rel="stylesheet" href="assets/css/project.css" />
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
                    <div class="carousel-wrapper">
                        <?php if(count($images) > 1): ?>
                            <button class="carousel-btn prev-btn" id="prevBtn">&#10094;</button>
                        <?php endif; ?>
                        
                        <div class="project-carousel" id="carouselTrack">
                            <?php foreach ($images as $img_path): ?>
                                <div class="carousel-item">
                                    <img src="<?php echo htmlspecialchars($img_path); ?>" alt="Image du projet" />
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if(count($images) > 1): ?>
                            <button class="carousel-btn next-btn" id="nextBtn">&#10095;</button>
                        <?php endif; ?>
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
            <li>&copy; LE CAER MALO - Tout droit réservé.</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
        </ul>
    </div>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/browser.min.js"></script>
    <script src="assets/js/breakpoints.min.js"></script>
    <script src="assets/js/util.js"></script>
    <script src="assets/js/main.js"></script>

    <?php if(count($images) > 1): ?>
    <script>
        const track = document.getElementById('carouselTrack');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const items = document.querySelectorAll('.carousel-item');
        let autoScroll;

        function scrollToIndex(index) {
            track.scrollTo({ left: items[index].offsetLeft, behavior: 'smooth' });
        }

        function getNextIndex() {
            let currentIndex = Math.round(track.scrollLeft / track.clientWidth);
            return (currentIndex + 1) >= items.length ? 0 : currentIndex + 1;
        }

        function getPrevIndex() {
            let currentIndex = Math.round(track.scrollLeft / track.clientWidth);
            return (currentIndex - 1) < 0 ? items.length - 1 : currentIndex - 1;
        }

        nextBtn.addEventListener('click', () => {
            scrollToIndex(getNextIndex());
            resetAutoScroll();
        });

        prevBtn.addEventListener('click', () => {
            scrollToIndex(getPrevIndex());
            resetAutoScroll();
        });

        function startAutoScroll() {
            autoScroll = setInterval(() => scrollToIndex(getNextIndex()), 4000); // Défilement toutes les 4s
        }

        function resetAutoScroll() {
            clearInterval(autoScroll);
            startAutoScroll();
        }

        startAutoScroll();
    </script>
    <?php endif; ?>
</body>
</html>