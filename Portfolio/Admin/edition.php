<?php
session_start();
require '../includes/db_config.php';
require '../includes/auth/security.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id_proj = (int)$_GET['id'];
//require '../includes/project/update.php';

$sql_get = "SELECT * FROM projects WHERE id = $id_proj";
$res_get = mysqli_query($link, $sql_get);
$projet = mysqli_fetch_assoc($res_get);

if (!$projet) {
    header("Location: dashboard.php");
    exit;
}

$sql_cat = "SELECT * FROM categories ORDER BY label ASC";
$res_cat = mysqli_query($link, $sql_cat);

$sql_img = "SELECT * FROM project_images WHERE project_id = $id_proj";
$res_img = mysqli_query($link, $sql_img);
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
        <ul class="actions special">
            <li><a href="dashboard.php" class="button icon solid fa-arrow-left">Retour</a></li>
        </ul>
    </div>

    <div id="main">
        <div class="box container">
            <div class="admin-block">
                <header><h3>Édition : <?php echo htmlspecialchars($projet['title']); ?></h3></header>
                
                <?php if(isset($erreur)) echo "<p class='msg-error'>$erreur</p>"; ?>

                <form id="update-project-form" enctype="multipart/form-data">
                    <input type="hidden" name="id_project" value="<?php echo $id_proj; ?>" />

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

                        <div class="col-6 col-12-mobilep">
                            <label>Lien du projet (Optionnel)</label>
                            <input type="text" name="project_link" value="<?php echo htmlspecialchars($projet['project_link']); ?>" />
                        </div>

                        <div class="col-12">
                            <label>Description</label>
                            <textarea name="description" rows="6" required><?php echo htmlspecialchars($projet['description']); ?></textarea>
                        </div>

                        <div class="col-12">
                            <hr>
                            <label>Images actuelles (Sélectionnez la couverture ou supprimez)</label>
                            <div class="preview-grid">
                                <?php while($img = mysqli_fetch_assoc($res_img)): ?>
                                    <div class="preview-card">
                                        <img src="../<?php echo htmlspecialchars($img['image_url']); ?>" alt="" class="preview-img">
                                        <label class="label-cover <?php echo ($img['is_main'] == 1) ? 'is-cover' : ''; ?>">
                                            <input type="radio" name="main_image" value="<?php echo $img['id']; ?>" <?php echo ($img['is_main'] == 1) ? 'checked' : ''; ?>>
                                            Couverture
                                        </label>
                                        <label class="label-delete">
                                            <input type="checkbox" name="delete_images[]" value="<?php echo $img['id']; ?>">
                                            Supprimer
                                        </label>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>

                        <div class="col-12 mt-2">
                            <label class="drop-label">Ajouter de nouvelles images (Drag & Drop) - <span class="text-highlight">Format 16/9 recommandé</span></label>
                            <div id="drop-area" class="drop-area">
                                <p><i class="icon solid fa-cloud-upload-alt fa-2x"></i><br>Glissez-déposez ici</p>
                                <input type="file" id="fileElem" multiple accept="image/*" style="display:none;">
                            </div>
                            <p id="file-count" class="file-count"></p>
                            
                            <div id="new-images-preview" class="preview-grid"></div>
                        </div>

                        <div class="col-12 text-right mt-1">
                            <input type="submit" value="Enregistrer les modifications" class="btn-wide primary" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('fileElem');
        const fileCount = document.getElementById('file-count');
        const updateForm = document.getElementById('update-project-form');
        const newImagesPreview = document.getElementById('new-images-preview');

        let droppedFiles = [];

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
            updateNewImagesPreview();
        }

        function updateNewImagesPreview() {
            newImagesPreview.innerHTML = '';
            fileCount.textContent = droppedFiles.length > 0 ? droppedFiles.length + " nouvelle(s) image(s) sélectionnée(s)" : "";

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

                    const labelNew = document.createElement('span');
                    labelNew.className = 'label-new';
                    labelNew.innerHTML = '<i>Nouveau fichier</i>';

                    div.appendChild(removeBtn);
                    div.appendChild(img);
                    div.appendChild(labelNew);
                    newImagesPreview.appendChild(div);
                };
            });
        }

        function removeDroppedFile(index) {
            droppedFiles.splice(index, 1);
            updateNewImagesPreview();
        }

        updateForm.addEventListener('submit', function(e) {
            e.preventDefault(); 

            const formData = new FormData(updateForm);
            
            droppedFiles.forEach(file => {
                formData.append('project_images[]', file);
            });

            const submitBtn = updateForm.querySelector('input[type="submit"]');
            submitBtn.value = "Mise à jour en cours...";
            submitBtn.disabled = true;

            fetch('../includes/project/update.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    window.location.href = 'dashboard.php?msg=updated';
                } else {
                    alert('Erreur lors de la mise à jour : ' + data.message);
                    submitBtn.value = "Enregistrer les modifications";
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                alert('Erreur réseau ou serveur : ' + error);
                submitBtn.value = "Enregistrer les modifications";
                submitBtn.disabled = false;
            });
        });
    </script>
</body>
</html>