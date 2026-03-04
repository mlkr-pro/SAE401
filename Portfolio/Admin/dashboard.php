<?php
session_start();
require '../includes/db_config.php';
require '../includes/auth/security.php';

$message = "";
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'intro_updated') $message = "Introduction mise à jour !";
    if ($_GET['msg'] == 'hero_updated') $message = "En-tête mis à jour !";
    if ($_GET['msg'] == 'skill_added') $message = "Compétence ajoutée !";
    if ($_GET['msg'] == 'skill_deleted') $message = "Compétence supprimée !";
    if ($_GET['msg'] == 'deleted') $message = "Projet supprimé avec succès !";
    if ($_GET['msg'] == 'updated') $message = "Le projet a été mis à jour avec succès !";
}

// Inclusion du CRUD
// Hero
require '../includes/hero/update.php'; // Modifier
require '../includes/hero/read.php'; // Lire

// Intro
require '../includes/intro_section/update.php'; // Modifier
require '../includes/intro_section/read.php'; // Lire

// Porject
require '../includes/project/create.php'; // Ajouter avec upload d'images
require '../includes/project/read.php'; // Lire les projets
require '../includes/project/delete.php'; // Supprimer
require '../includes/categories/read.php'; // Catégories

// Compétences
require '../includes/skills/create.php'; // Ajouter
require '../includes/skills/update.php'; // Modifier
require '../includes/skills/read.php'; // Lire
require '../includes/skills/delete.php'; // Supprimer

// Socials
require '../includes/socials/delete.php'; // Supprimer
require '../includes/socials/create.php'; // Ajouter
require '../includes/socials/update.php'; // Modifier
require '../includes/socials/read.php'; // Lire
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Dashboard Admin</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../assets/css/main.css" />
    <link rel="stylesheet" href="../assets/css/admin.css" />
</head>
<body class="is-preload">

    <div id="header">
        <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
        <p>Tableau de bord de gestion</p>
        <ul class="actions special">
            <li><a href="../index.php" class="button icon solid fa-home">Voir le site</a></li>
            <li><a href="logout.php" class="button icon solid fa-sign-out-alt">Déconnexion</a></li>
        </ul>
    </div>

    <div id="main">
        <div class="box container">
            <?php if($message) echo "<p class='msg-success'>$message</p>"; ?>

            <button class="accordion-header">Gestion de l'En-tête (Hero)</button>
            <div class="accordion-panel">
                <div class="admin-block" style="margin-top: 2em;">
                    <header>
                        <h3>Modifier la présentation</h3>
                    </header>
                    <form method="post" action="dashboard.php" enctype="multipart/form-data">
                        <div class="row gtr-50">
                            <div class="col-12">
                                <label>Titre principal</label>
                                <input type="text" name="title" value="<?php echo htmlspecialchars($hero['title']); ?>" required />
                            </div>
                            <div class="col-12">
                                <label>Sous-titre (Ligne 1)</label>
                                <input type="text" name="subtitle" value="<?php echo htmlspecialchars($hero['subtitle']); ?>" required />
                            </div>
                            <div class="col-12">
                                <label>Description (Lignes suivantes)</label>
                                <textarea name="description" rows="4" required><?php echo htmlspecialchars($hero['description']); ?></textarea>
                            </div>
                            <div class="col-6 col-12-mobilep">
                                <label>Nouvelle photo de profil (Optionnel)</label>
                                <input type="file" name="profile_pic" accept="image/*" />
                                <p style="font-size:0.8em; color:#888;">Actuelle : <?php echo htmlspecialchars($hero['profile_pic']); ?></p>
                            </div>
                            <div class="col-6 col-12-mobilep">
                                <label>Nouveau CV (Optionnel, format PDF)</label>
                                <input type="file" name="cv_link" accept=".pdf" />
                                <p style="font-size:0.8em; color:#888;">Actuel : <?php echo htmlspecialchars($hero['cv_link']); ?></p>
                            </div>
                            <div class="col-12 text-right mt-1">
                                <input type="submit" name="update_hero" value="Mettre à jour" class="btn-wide primary" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <button class="accordion-header">Gestion de l'Introduction</button>
            <div class="accordion-panel">
                <div class="admin-block" style="margin-top: 2em;">
                    <header>
                        <h3>Modifier l'introduction (Objectif)</h3>
                    </header>
                    <form method="post" action="dashboard.php">
                        <div class="row gtr-50">
                            <div class="col-12">
                                <label>Titre (Retours à la ligne autorisés)</label>
                                <textarea name="title" rows="3" required><?php echo htmlspecialchars($intro['title']); ?></textarea>
                            </div>
                            <div class="col-12">
                                <label>Description (Optionnelle)</label>
                                <textarea name="description" rows="3"><?php echo htmlspecialchars($intro['description']); ?></textarea>
                            </div>
                            <div class="col-12 text-right mt-1">
                                <input type="submit" name="update_intro" value="Mettre à jour" class="btn-wide primary" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <button class="accordion-header">Gestion des Projets</button>
            <div class="accordion-panel">
                <div class="admin-block" style="margin-top: 2em;">
                    <header>
                        <h3>Mes Projets existants</h3>
                    </header>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 15%; text-align: left;">Date</th>
                                    <th style="width: 40%; text-align: left;">Titre</th>
                                    <th style="width: 30%; text-align: left;">Catégorie</th>
                                    <th style="width: 15%; text-align:center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (mysqli_num_rows($result_projects) > 0) {
                                    while ($projet = mysqli_fetch_assoc($result_projects)) {
                                        $date = new DateTime($projet['publish_date']);
                                        $cat = $projet['category_name'] ? $projet['category_name'] : "Aucune";
                                ?>
                                    <tr>
                                        <td style="text-align: left;"><?php echo $date->format('d/m/Y'); ?></td>
                                        <td style="text-align: left;"><strong><?php echo htmlspecialchars($projet['title']); ?></strong></td>
                                        <td style="text-align: left;"><strong><?php echo htmlspecialchars($cat); ?></strong></td>
                                        <td style="text-align:center;">
                                            <a href="edition.php?id=<?php echo $projet['id']; ?>" class="icon solid fa-pen" style="color:#4CAF50; margin-right: 15px; border-bottom:none; text-decoration:none;" title="Modifier"></a>
                                            <a href="dashboard.php?delete_project=<?php echo $projet['id']; ?>" class="icon solid fa-trash" style="color:#ff4444; border-bottom:none; text-decoration:none;" onclick="return confirm('Voulez-vous vraiment supprimer ce projet ?');"></a>
                                        </td>
                                    </tr>
                                <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-center'>Aucun projet.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="admin-block">
                    <header>
                        <h3>Ajouter un nouveau projet</h3>
                    </header>
                    <form method="post" action="dashboard.php" enctype="multipart/form-data">
                        <div class="row gtr-50">
                            
                            <div class="col-12">
                                <input type="text" name="title" placeholder="Titre du projet" required />
                            </div>

                            <div class="col-6 col-12-mobilep">
                                <select name="category_id" id="category_select" onchange="toggleCustomCategory()">
                                    <option value="">-- Choisir une catégorie --</option>
                                    <?php 
                                    // On remplit le select avec les catégories existantes
                                    if (mysqli_num_rows($result_cat) > 0) {
                                        mysqli_data_seek($result_cat, 0);
                                        while($cat = mysqli_fetch_assoc($result_cat)) {
                                            echo '<option value="'.$cat['id'].'">'.htmlspecialchars($cat['label']).'</option>';
                                        }
                                    }
                                    ?>
                                    <option value="0" style="font-weight:bold; color:#e44c65;">+ Nouvelle catégorie...</option>
                                </select>
                            </div>

                            <div class="col-6 col-12-mobilep" id="custom_cat_container" style="display:none;">
                                <input type="text" name="new_category_label" placeholder="Nom de la nouvelle catégorie" />
                            </div>

                            <div class="col-12">
                                <textarea name="description" placeholder="Description du projet..." rows="4" required></textarea>
                            </div>

                            <div class="col-6 col-12-mobilep">
                                <input type="text" name="project_link" placeholder="Lien vers le site (http://...)" />
                            </div>
                            <div class="col-6 col-12-mobilep">
                                <label for="file" style="font-size:0.8em; color:#888;">Image de couverture</label>
                                <input type="file" name="project_image" accept="image/*" required />
                            </div>

                            <div class="col-12 text-right mt-1">
                                <input type="submit" name="add_project" value="Publier le projet" class="btn-wide primary" />
                            </div>
                        </div>
                    </form>
                </div>
            </div> 

            <button class="accordion-header">Gestion des Compétences</button>
            <div class="accordion-panel">
                <div class="admin-block" style="margin-top: 2em;">
                    <header>
                        <h3>Mes Compétences</h3>
                    </header>
                    <form method="post" action="dashboard.php">
                        <div class="table-wrapper">
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width: 15%; text-align: left;">Ordre</th>
                                        <th style="width: 45%; text-align: left;">Compétence</th>
                                        <th style="width: 25%; text-align: left;">Niveau (%)</th>
                                        <th style="width: 15%; text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (mysqli_num_rows($result_skills) > 0) {
                                        mysqli_data_seek($result_skills, 0);
                                        while ($skill = mysqli_fetch_assoc($result_skills)) {
                                    ?>
                                        <tr>
                                            <td style="text-align: left;">
                                                <input type="text" name="skills[<?php echo $skill['id']; ?>][order]" value="<?php echo htmlspecialchars($skill['display_order']); ?>" pattern="^[0-9]+$" title="Veuillez saisir un nombre" required />
                                            </td>
                                            <td style="text-align: left;">
                                                <input type="text" name="skills[<?php echo $skill['id']; ?>][name]" value="<?php echo htmlspecialchars($skill['skill_name']); ?>" required />
                                            </td>
                                            <td style="text-align: left;">
                                                <input type="text" name="skills[<?php echo $skill['id']; ?>][level]" value="<?php echo htmlspecialchars($skill['level']); ?>" pattern="^(100|[1-9]?[0-9])$" required />
                                            </td>
                                            <td style="text-align: center;">
                                                <a href="dashboard.php?delete_skill=<?php echo $skill['id']; ?>" class="icon solid fa-trash" style="color:#ff4444; border-bottom:none; text-decoration:none;" onclick="return confirm('Supprimer cette compétence ?');"></a>
                                            </td>
                                        </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='4' class='text-center'>Aucune compétence.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (mysqli_num_rows($result_skills) > 0): ?>
                        <div class="row">
                            <div class="col-12 text-right mt-2">
                                <input type="submit" name="update_skills" value="Enregistrer les modifications" class="btn-wide" />
                            </div>
                        </div>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="admin-block">
                    <header>
                        <h3>Ajouter une compétence</h3>
                    </header>
                    <form method="post" action="dashboard.php">
                        <div class="row gtr-50">
                            <div class="col-3 col-12-mobilep">
                                <input type="text" name="skill_order" placeholder="Ordre d'affichage" pattern="^[0-9]+$" title="Veuillez saisir un nombre" required />
                            </div>
                            <div class="col-5 col-12-mobilep">
                                <input type="text" name="skill_name" placeholder="Nom (ex: HTML...)" required />
                            </div>
                            <div class="col-4 col-12-mobilep">
                                <input type="text" name="skill_level" placeholder="Niveau en %" pattern="^(100|[1-9]?[0-9])$" required />
                            </div>
                            <div class="col-12 text-right mt-1">
                                <input type="submit" name="add_skill" value="Ajouter" class="btn-wide primary" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <button class="accordion-header">Gestion des Réseaux Sociaux</button>
            <div class="accordion-panel">
                <div class="admin-block" style="margin-top: 2em;">
                    <header>
                        <h3>Modifier les réseaux existants</h3>
                    </header>
                    <form method="post" action="dashboard.php">
                        <div class="row gtr-50 table-header">
                            <div class="col-3 col-12-mobilep">Réseau</div>
                            <div class="col-8 col-12-mobilep">Lien URL</div>
                            <div class="col-1 col-12-mobilep text-center">Action</div>
                        </div>

                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            mysqli_data_seek($result, 0); 
                            while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                                <div class="row gtr-50 aln-middle social-row">
                                    <div class="col-3 col-12-mobilep">
                                        <div class="network-label">
                                            <span class="icon brands fa-<?php echo htmlspecialchars($row['name']); ?>"></span>
                                            <?php echo ucfirst(htmlspecialchars($row['name'])); ?>
                                        </div>
                                    </div>
                                    <div class="col-8 col-12-mobilep">
                                        <input type="text" name="urls[<?php echo $row['id']; ?>]" value="<?php echo htmlspecialchars($row['url']); ?>" />
                                    </div>
                                    <div class="col-1 col-12-mobilep text-center">
                                        <a href="dashboard.php?delete=<?php echo $row['id']; ?>" 
                                           class="icon solid fa-trash action-btn" 
                                           onclick="return confirm('Voulez-vous vraiment supprimer ce réseau ?');"
                                           title="Supprimer">
                                        </a>
                                    </div>
                                </div>
                        <?php
                            }
                        } else {
                            echo "<p class='text-center' style='padding:2em;'>Aucun réseau configuré.</p>";
                        }
                        ?>
                        
                        <?php if (mysqli_num_rows($result) > 0): ?>
                        <div class="row">
                            <div class="col-12 text-right mt-2">
                                <input type="submit" name="update_socials" value="Enregistrer les modifications" class="btn-wide" />
                            </div>
                        </div>
                        <?php endif; ?>
                    </form>
                </div>
                <div class="admin-block">
                    <header>
                        <h3>Ajouter un nouveau réseau</h3>
                    </header>
                    <form method="post" action="dashboard.php">
                        <div class="row gtr-50">
                            <div class="col-6 col-12-mobilep">
                                <input type="text" name="new_name" placeholder="Nom (ex: instagram)" required />
                            </div>
            
                            <div class="col-6 col-12-mobilep">
                                <input type="text" name="new_url" placeholder="URL (https://...)" required />
                            </div>
            
                            <div class="col-12 text-right mt-1">
                                <input type="submit" name="add_social" value="Ajouter" class="btn-wide" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <div id="footer">
        <ul class="copyright">
            <li>&copy; Back-Office Malo.</li>
        </ul>
    </div>

    <script>
        var acc = document.getElementsByClassName("accordion-header");
        for (var i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                if (panel.style.maxHeight) {
                    panel.style.maxHeight = null;
                    panel.classList.remove("open");
                } else {
                    panel.classList.add("open");
                    panel.style.maxHeight = "1500px"; 
                } 
            });
        }

        // Gestion du champ "Nouvelle Catégorie"
        function toggleCustomCategory() {
            var selectBox = document.getElementById("category_select");
            var customInput = document.getElementById("custom_cat_container");
            var selectedValue = selectBox.value;
            if (selectedValue === "0") {
                customInput.style.display = "block";
                document.querySelector('input[name="new_category_label"]').required = true;
            } else {
                customInput.style.display = "none";
                document.querySelector('input[name="new_category_label"]').required = false;
            }
        }
    </script>

</body>
</html>