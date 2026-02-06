<?php
function afficherProjets($link) {
    $sql = "
        SELECT 
            p.id, 
            p.title, 
            p.description, 
            c.label AS category_name,
            (SELECT image_url FROM project_images WHERE project_id = p.id LIMIT 1) AS main_image
        FROM projects p
        LEFT JOIN categories c ON p.category_id = c.id
        ORDER BY p.publish_date DESC
    ";

    $result = mysqli_query($link, $sql);

    if (mysqli_num_rows($result) > 0) {
        $compteur = 0; // Sert à savoir si on est à gauche ou à droite

        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $titre = htmlspecialchars($row['title']);
            $categorie = htmlspecialchars($row['category_name']);
            // Si pas d'image en BDD, on met une image par défaut
            $image = !empty($row['main_image']) ? htmlspecialchars($row['main_image']) : 'images/pic01.jpg';
            $description = htmlspecialchars($row['description']);
            $position = ($compteur % 2 == 0) ? 'left' : 'right';

            echo '
            <section class="feature ' . $position . '">
                <a href="projet.php?id=' . $id . '" class="image icon solid fa-code">
                    <img src="' . $image . '" alt="' . $titre . '" style="object-fit:cover; height:100%;" />
                </a>
                <div class="content">
                    <h3>' . $titre . ' <span style="font-size:0.6em; color:#888;">' . $categorie . '</span></h3>
                    <p>' . $description . '</p>
                    <a href="projet.php?id=' . $id . '" class="button small">Voir le détail</a>
                </div>
            </section>
            ';

            $compteur++; // On incrémente pour changer de côté
        }
    } else {
        echo '<p style="text-align:center; padding:20px;">Aucun projet à afficher pour le moment.</p>';
    }
}
?>