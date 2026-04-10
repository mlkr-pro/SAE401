<?php
session_start();
require '../includes/db_config.php';
require '../includes/auth/security.php';

$message = "";
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'intro_updated') $message = "Introduction mise à jour avec succès !";
    if ($_GET['msg'] == 'hero_updated') $message = "En-tête mis à jour avec succès !";
    if ($_GET['msg'] == 'skill_added') $message = "Compétence ajoutée avec succès !";
    if ($_GET['msg'] == 'skill_deleted') $message = "Compétence supprimée avec succès !";
    if ($_GET['msg'] == 'deleted') $message = "Projet supprimé avec succès !";
    if ($_GET['msg'] == 'updated') $message = "Le projet a été mis à jour avec succès !";
    if ($_GET['msg'] == 'project_added') $message = "Projet ajouté avec succès !";
    if ($_GET['msg'] == 'message_replied') $message = "Réponse envoyée avec succès !";
}

// Inclusion du CRUD
// Hero
require_once '../includes/hero/update.php'; // Modifier
require_once '../includes/hero/read.php'; // Lire

// Intro
require_once '../includes/intro_section/update.php'; // Modifier
require_once '../includes/intro_section/read.php'; // Lire

// Compétences
require_once '../includes/skills/create.php'; // Ajouter
require_once '../includes/skills/update.php'; // Modifier
require_once '../includes/skills/read.php'; // Lire
require_once '../includes/skills/delete.php'; // Supprimer

// Porject
//require_once '../includes/project/create.php'; // Ajouter avec upload d'images
require_once '../includes/project/read.php'; // Lire les projets
require_once '../includes/project/delete.php'; // Supprimer
require_once '../includes/categories/read.php'; // Catégories

// Messages
require_once '../includes/messages/delete.php'; // Supprimer
require_once '../includes/messages/read.php'; // Lire

// Socials
// require_once '../includes/socials/delete.php'; // Supprimer
// require_once '../includes/socials/create.php'; // Ajouter
// require_once '../includes/socials/update.php'; // Modifier
// require_once '../includes/socials/read.php'; // Lire
require_once '../includes/socials/Social.php';
require_once '../includes/socials/controll.php';

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

            <button class="accordion-header">Boîte de réception (Messages)</button>
            <div class="accordion-panel">
                <div class="admin-block mt-2">
                    <header>
                        <h3>Messages reçus depuis le site</h3>
                    </header>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th class="w-15 text-left">Date</th>
                                    <th class="w-25 text-left">Expéditeur</th>
                                    <th class="w-50 text-left">Message</th>
                                    <th class="w-10 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (mysqli_num_rows($result_messages) > 0) {
                                    while ($msg = mysqli_fetch_assoc($result_messages)) {
                                        $date_formatee = date('d/m/Y H:i', strtotime($msg['date_sent']));
                                ?>
                                    <tr>
                                        <td class="text-left"><?php echo $date_formatee; ?></td>
                                        <td class="text-left">
                                            <strong><?php echo htmlspecialchars($msg['name']); ?></strong><br>
                                            <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>" class="link-muted"><?php echo htmlspecialchars($msg['email']); ?></a>
                                        </td>
                                        <td class="text-left text-small">
                                            <?php echo nl2br(htmlspecialchars($msg['message'])); ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="reply.php?id=<?php echo $msg['id']; ?>" class="icon solid fa-reply action-btn btn-reply" title="Répondre"></a>
                                            <a href="dashboard.php?delete_message=<?php echo $msg['id']; ?>" class="icon solid fa-trash action-btn btn-delete" onclick="return confirm('Supprimer ce message ?');" title="Supprimer"></a>
                                        </td>
                                    </tr>
                                <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-center'>Aucun message reçu.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <button class="accordion-header">Gestion de l'en-tête (Hero)</button>
            <div class="accordion-panel">
                <div class="admin-block mt-2">
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
                                <p class="file-hint">Actuelle : <?php echo htmlspecialchars($hero['profile_pic']); ?></p>
                            </div>
                            <div class="col-6 col-12-mobilep">
                                <label>Nouveau CV (Optionnel, format PDF)</label>
                                <input type="file" name="cv_link" accept=".pdf" />
                                <p class="file-hint">Actuel : <?php echo htmlspecialchars($hero['cv_link']); ?></p>
                            </div>
                            <div class="col-12 text-right mt-1">
                                <input type="submit" name="update_hero" value="Mettre à jour" class="btn-wide primary" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <button class="accordion-header">Gestion de l'introduction</button>
            <div class="accordion-panel">
                <div class="admin-block mt-2">
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

            <button class="accordion-header">Gestion des projets</button>
            <div class="accordion-panel">
                <div class="admin-block mt-2">
                    <header>
                        <h3>Mes projets existants</h3>
                    </header>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th class="w-15 text-left">Date</th>
                                    <th class="w-40 text-left">Titre</th>
                                    <th class="w-30 text-left">Catégorie</th>
                                    <th class="w-15 text-center">Action</th>
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
                                        <td class="text-left"><?php echo $date->format('d/m/Y'); ?></td>
                                        <td class="text-left"><strong><?php echo htmlspecialchars($projet['title']); ?></strong></td>
                                        <td class="text-left"><strong><?php echo htmlspecialchars($cat); ?></strong></td>
                                        <td class="text-center">
                                            <a href="edition.php?id=<?php echo $projet['id']; ?>" class="icon solid fa-pen action-btn btn-edit" title="Modifier"></a>
                                            <a href="dashboard.php?delete_project=<?php echo $projet['id']; ?>" class="icon solid fa-trash action-btn btn-delete" onclick="return confirm('Voulez-vous vraiment supprimer ce projet ?');"></a>
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
                    <form id="create-project-form" enctype="multipart/form-data">
                        <div class="row gtr-50">
                            
                            <div class="col-12">
                                <input type="text" name="title" placeholder="Titre du projet" required />
                            </div>

                            <div class="col-6 col-12-mobilep">
                                <select name="category_id" id="category_select" onchange="toggleCustomCategory()">
                                    <option value="">-- Choisir une catégorie --</option>
                                    <?php 
                                    if (mysqli_num_rows($result_cat) > 0) {
                                        mysqli_data_seek($result_cat, 0);
                                        while($cat = mysqli_fetch_assoc($result_cat)) {
                                            echo '<option value="'.$cat['id'].'">'.htmlspecialchars($cat['label']).'</option>';
                                        }
                                    }
                                    ?>
                                    <option value="0" class="option-new-cat">+ Nouvelle catégorie...</option>
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
                            <div class="col-12">
                                <label class="drop-label">Images du projet (Drag & Drop) - <span class="text-highlight">Format 16/9 recommandé</span></label>
                                <div id="drop-area" class="drop-area">
                                    <p><i class="icon solid fa-cloud-upload-alt fa-2x"></i><br>Glissez-déposez vos images ici<br>ou cliquez pour parcourir</p>
                                    <input type="file" id="fileElem" multiple accept="image/*" style="display:none;">
                                </div>
                                <p id="file-count" class="file-count"></p>
                                
                                <div id="preview-container" class="preview-grid"></div>
                            </div>
                            <div class="col-12 text-right mt-1">
                                <input type="submit" value="Publier le projet" class="btn-wide primary" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <button class="accordion-header">Gestion des compétences</button>
            <div class="accordion-panel">
                <div class="admin-block mt-2">
                    <header>
                        <h3>Mes compétences</h3>
                    </header>
                    <form method="post" action="dashboard.php">
                        <div class="table-wrapper">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="w-15 text-left">Ordre</th>
                                        <th class="w-45 text-left">Compétence</th>
                                        <th class="w-25 text-left">Niveau (%)</th>
                                        <th class="w-15 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (mysqli_num_rows($result_skills) > 0) {
                                        mysqli_data_seek($result_skills, 0);
                                        while ($skill = mysqli_fetch_assoc($result_skills)) {
                                    ?>
                                        <tr>
                                            <td class="text-left">
                                                <input type="text" name="skills[<?php echo $skill['id']; ?>][order]" value="<?php echo htmlspecialchars($skill['display_order']); ?>" pattern="^[0-9]+$" title="Veuillez saisir un nombre" required />
                                            </td>
                                            <td class="text-left">
                                                <input type="text" name="skills[<?php echo $skill['id']; ?>][name]" value="<?php echo htmlspecialchars($skill['skill_name']); ?>" required />
                                            </td>
                                            <td class="text-left">
                                                <input type="text" name="skills[<?php echo $skill['id']; ?>][level]" value="<?php echo htmlspecialchars($skill['level']); ?>" pattern="^(100|[1-9]?[0-9])$" required />
                                            </td>
                                            <td class="text-center">
                                                <a href="dashboard.php?delete_skill=<?php echo $skill['id']; ?>" class="icon solid fa-trash action-btn btn-delete" onclick="return confirm('Supprimer cette compétence ?');"></a>
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
            
            <button class="accordion-header">Gestion des réseaux sociaux</button>
            <div class="accordion-panel">
                <div class="admin-block mt-2">
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
                        if (!empty($socials)) {
                            foreach ($socials as $row) {
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
                                        <a href="dashboard.php?delete_social=<?php echo $row['id']; ?>" class="icon solid fa-trash action-btn btn-delete" onclick="return confirm('Voulez-vous vraiment supprimer ce réseau ?');" title="Supprimer"></a>
                                    </div>
                                </div>
                                <?php
                                }
                                } else {
                                    echo "<p class='text-center p-4'>Aucun réseau configuré.</p>";
                                }
                                ?>
                                
                                <?php if (!empty($socials)): ?>
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
                                        <div class="col-4 col-12-mobilep">
                                            <input type="text" name="new_name" placeholder="Nom (ex: github)" required />
                                        </div>
                                        <div class="col-8 col-12-mobilep">
                                            <input type="text" name="new_url" placeholder="Lien URL (ex: https://...)" required />
                                        </div>
                                        <div class="col-12 text-right mt-1">
                                            <input type="submit" name="add_social" value="Ajouter" class="btn-wide primary" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="footer">
                    <ul class="copyright">
                        <li>&copy; LE CAER MALO - Tout droit réservé.</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
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
                    panel.style.maxHeight = "5000px";
                } 
            });
        }

        // Gestion du champ "Nouvelle catégorie"
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

        // Gestion du drag and drop et preview via AJAX
        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('fileElem');
        const fileCount = document.getElementById('file-count');
        const previewContainer = document.getElementById('preview-container');
        const createForm = document.getElementById('create-project-form');

        let droppedFiles = []; 
        let coverIndex = 0;   

        dropArea.addEventListener('click', () => fileInput.click());

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, e => { e.preventDefault(); e.stopPropagation(); }, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, () => dropArea.classList.add('highlight'), false);
        });
        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, () => dropArea.classList.remove('highlight'), false);
        });

        dropArea.addEventListener('drop', handleDrop, false);
        fileInput.addEventListener('change', handleSelect, false);

        function handleDrop(e) { handleFiles(e.dataTransfer.files); }
        function handleSelect(e) { handleFiles(e.target.files); }

        function handleFiles(files) {
            files = [...files];
            const imageFiles = files.filter(file => file.type.startsWith('image/'));
            droppedFiles = [...droppedFiles, ...imageFiles];
            updatePreview();
        }

        function updatePreview() {
            previewContainer.innerHTML = '';
            fileCount.textContent = droppedFiles.length > 0 ? droppedFiles.length + " image(s) sélectionnée(s)" : "";

            droppedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);

                reader.onloadend = () => {
                    const div = document.createElement('div');
                    div.className = 'preview-card';
                    
                    const img = document.createElement('img');
                    img.src = reader.result;
                    img.className = 'preview-img';
                    
                    const removeBtn = document.createElement('button');
                    removeBtn.innerHTML = '&times;';
                    removeBtn.className = 'btn-remove-preview';
                    removeBtn.onclick = (e) => {
                        e.preventDefault();
                        removeDroppedFile(index);
                    };

                    const labelCover = document.createElement('label');
                    labelCover.className = 'label-cover';
                    
                    if(index === coverIndex) {
                        labelCover.classList.add('is-cover');
                        labelCover.innerHTML = '<i class="icon solid fa-star"></i> Couverture';
                    } else {
                        labelCover.innerHTML = '<i class="icon regular fa-star"></i> En couverture';
                    }
                    
                    labelCover.onclick = (e) => {
                        e.preventDefault();
                        coverIndex = index;
                        updatePreview(); 
                    };

                    div.appendChild(removeBtn);
                    div.appendChild(img);
                    div.appendChild(labelCover);
                    previewContainer.appendChild(div);
                };
            });
        }

        function removeDroppedFile(index) {
            droppedFiles.splice(index, 1);
            if (index === coverIndex) coverIndex = 0;
            else if (index < coverIndex) coverIndex--;
            updatePreview();
        }

        createForm.addEventListener('submit', function(e) {
            e.preventDefault(); 
            const formData = new FormData(createForm);
            formData.append('main_image_index', coverIndex);
            
            droppedFiles.forEach(file => {
                formData.append('project_images[]', file);
            });

            const submitBtn = createForm.querySelector('input[type="submit"]');
            submitBtn.value = "Publication en cours...";
            submitBtn.disabled = true;

            fetch('../includes/project/create.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    window.location.href = 'dashboard.php?msg=project_added';
                } else {
                    alert('Erreur : ' + data.message);
                    submitBtn.value = "Publier le projet";
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                alert('Erreur réseau ou serveur : ' + error);
                submitBtn.value = "Publier le projet";
                submitBtn.disabled = false;
            });
        });
    </script>

</body>
</html>