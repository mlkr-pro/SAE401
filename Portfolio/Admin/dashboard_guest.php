<?php
session_start();
require '../includes/db_config.php';

// Vérification de session visiteur
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}

// Inclusion de toutes les lectures (Read)
require '../includes/hero/read.php';
require '../includes/intro_section/read.php';
require '../includes/skills/read.php';
require '../includes/project/read.php';
require '../includes/categories/read.php';
require '../includes/messages/read.php';
require '../includes/socials/read.php';

$message = "Mode Visiteur : Lecture seule activée.";
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Dashboard (Lecture seule)</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../assets/css/main.css" />
    <link rel="stylesheet" href="../assets/css/admin.css" />
</head>
<body class="is-preload">
    
    <div class="guest-banner">MODE DÉMO : Vous êtes connecté en lecture seule. Les modifications sont désactivées.</div>

    <div id="header">
        <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
        <p>Interface de consultation</p>
        <ul class="actions special">
            <li><a href="../index.php" class="button icon solid fa-home">Voir le site</a></li>
            <li><a href="logout.php" class="button icon solid fa-sign-out-alt">Déconnexion</a></li>
        </ul>
    </div>

    <div id="main">
        <div class="box container">

            <button class="accordion-header">Boîte de réception (Messages) - Lecture seule</button>
            <div class="accordion-panel">
                <div class="admin-block mt-2">
                    <header>
                        <h3>Messages reçus depuis le site (Exemples)</h3>
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
                                <tr>
                                    <td class="text-left"><?php echo date('d/m/Y', strtotime('-1 day')); ?> 14:30</td>
                                    <td class="text-left">
                                        <strong>Jean Dupont</strong><br>
                                        <a href="#" class="link-muted">jean.dupont@entreprise.fr</a>
                                    </td>
                                    <td class="text-left text-small">
                                        Bonjour Malo,<br />J'ai beaucoup apprécié votre portfolio. Seriez-vous disponible pour un échange téléphonique la semaine prochaine concernant une opportunité de projet ?
                                    </td>
                                    <td class="text-center">
                                        <a href="#" onclick="alert('Action impossible en mode visiteur'); return false;" class="icon solid fa-reply action-btn btn-reply disabled-action" title="Répondre"></a>
                                        <a href="#" onclick="alert('Action impossible en mode visiteur'); return false;" class="icon solid fa-trash action-btn btn-delete disabled-action" title="Supprimer"></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left"><?php echo date('d/m/Y', strtotime('-3 days')); ?> 09:15</td>
                                    <td class="text-left">
                                        <strong>Agence Web Creative</strong><br>
                                        <a href="#" class="link-muted">contact@agence-creative.com</a>
                                    </td>
                                    <td class="text-left text-small">
                                        Félicitations pour vos derniers projets en développement web ! Votre profil correspond exactement au type de développeur que nous recherchons pour notre pôle digital.
                                    </td>
                                    <td class="text-center">
                                        <a href="#" onclick="alert('Action impossible en mode visiteur'); return false;" class="icon solid fa-reply action-btn btn-reply disabled-action" title="Répondre"></a>
                                        <a href="#" onclick="alert('Action impossible en mode visiteur'); return false;" class="icon solid fa-trash action-btn btn-delete disabled-action" title="Supprimer"></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <button class="accordion-header">Gestion de l'en-tête (Hero) - Lecture seule</button>
            <div class="accordion-panel">
                <div class="admin-block mt-2">
                    <header>
                        <h3>Modifier la présentation</h3>
                    </header>
                    <form onsubmit="alert('Action impossible en mode visiteur'); return false;">
                        <div class="row gtr-50">
                            <div class="col-12">
                                <label>Titre principal</label>
                                <input type="text" value="<?php echo htmlspecialchars($hero['title']); ?>" readonly />
                            </div>
                            <div class="col-12">
                                <label>Sous-titre (Ligne 1)</label>
                                <input type="text" value="<?php echo htmlspecialchars($hero['subtitle']); ?>" readonly />
                            </div>
                            <div class="col-12">
                                <label>Description (Lignes suivantes)</label>
                                <textarea rows="4" readonly><?php echo htmlspecialchars($hero['description']); ?></textarea>
                            </div>
                            <div class="col-6 col-12-mobilep">
                                <label>Nouvelle photo de profil (Optionnel)</label>
                                <input type="file" disabled />
                                <p class="file-hint">Actuelle : <?php echo htmlspecialchars($hero['profile_pic']); ?></p>
                            </div>
                            <div class="col-6 col-12-mobilep">
                                <label>Nouveau CV (Optionnel, format PDF)</label>
                                <input type="file" disabled />
                                <p class="file-hint">Actuel : <?php echo htmlspecialchars($hero['cv_link']); ?></p>
                            </div>
                            <div class="col-12 text-right mt-1">
                                <input type="submit" value="Mettre à jour (Désactivé)" class="btn-wide primary disabled-action" disabled />
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <button class="accordion-header">Gestion de l'introduction - Lecture seule</button>
            <div class="accordion-panel">
                <div class="admin-block mt-2">
                    <header>
                        <h3>Modifier l'introduction (Objectif)</h3>
                    </header>
                    <form onsubmit="alert('Action impossible en mode visiteur'); return false;">
                        <div class="row gtr-50">
                            <div class="col-12">
                                <label>Titre (Retours à la ligne autorisés)</label>
                                <textarea rows="3" readonly><?php echo htmlspecialchars($intro['title']); ?></textarea>
                            </div>
                            <div class="col-12">
                                <label>Description (Optionnelle)</label>
                                <textarea rows="3" readonly><?php echo htmlspecialchars($intro['description']); ?></textarea>
                            </div>
                            <div class="col-12 text-right mt-1">
                                <input type="submit" value="Mettre à jour (Désactivé)" class="btn-wide primary disabled-action" disabled />
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <button class="accordion-header">Gestion des projets - Lecture seule</button>
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
                                        $cat = $projet['category_name'] ? $projet['category_name'] : "Aucune";
                                ?>
                                    <tr>
                                        <td class="text-left"><?php echo date('d/m/Y', strtotime($projet['publish_date'])); ?></td>
                                        <td class="text-left"><strong><?php echo htmlspecialchars($projet['title']); ?></strong></td>
                                        <td class="text-left"><strong><?php echo htmlspecialchars($cat); ?></strong></td>
                                        <td class="text-center">
                                            <a href="#" onclick="alert('Action impossible en mode visiteur'); return false;" class="icon solid fa-pen action-btn btn-edit disabled-action" title="Modifier"></a>
                                            <a href="#" onclick="alert('Action impossible en mode visiteur'); return false;" class="icon solid fa-trash action-btn btn-delete disabled-action" title="Supprimer"></a>
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
                    <form onsubmit="alert('Action impossible en mode visiteur'); return false;">
                        <div class="row gtr-50">
                            <div class="col-12">
                                <input type="text" placeholder="Titre du projet" readonly />
                            </div>
                            <div class="col-6 col-12-mobilep">
                                <select disabled>
                                    <option>-- Choisir une catégorie --</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <textarea placeholder="Description du projet..." rows="4" readonly></textarea>
                            </div>
                            <div class="col-6 col-12-mobilep">
                                <input type="text" placeholder="Lien vers le site (http://...)" readonly />
                            </div>
                            <div class="col-12">
                                <label class="drop-label">Images du projet (Drag & Drop) - <span class="text-highlight">Format 16/9 recommandé</span></label>
                                <div class="drop-area disabled-action" onclick="alert('Action impossible en mode visiteur'); return false;">
                                    <p><i class="icon solid fa-cloud-upload-alt fa-2x"></i><br>Glissez-déposez vos images ici<br>ou cliquez pour parcourir</p>
                                </div>
                            </div>
                            <div class="col-12 text-right mt-1">
                                <input type="submit" value="Publier le projet (Désactivé)" class="btn-wide primary disabled-action" disabled />
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <button class="accordion-header">Gestion des compétences - Lecture seule</button>
            <div class="accordion-panel">
                <div class="admin-block mt-2">
                    <header>
                        <h3>Mes compétences</h3>
                    </header>
                    <form onsubmit="alert('Action impossible en mode visiteur'); return false;">
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
                                                <input type="text" value="<?php echo htmlspecialchars($skill['display_order']); ?>" readonly />
                                            </td>
                                            <td class="text-left">
                                                <input type="text" value="<?php echo htmlspecialchars($skill['skill_name']); ?>" readonly />
                                            </td>
                                            <td class="text-left">
                                                <input type="text" value="<?php echo htmlspecialchars($skill['level']); ?>" readonly />
                                            </td>
                                            <td class="text-center">
                                                <a href="#" onclick="alert('Action impossible en mode visiteur'); return false;" class="icon solid fa-trash action-btn btn-delete disabled-action"></a>
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
                                <input type="submit" value="Enregistrer les modifications (Désactivé)" class="btn-wide disabled-action" disabled />
                            </div>
                        </div>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="admin-block">
                    <header>
                        <h3>Ajouter une compétence</h3>
                    </header>
                    <form onsubmit="alert('Action impossible en mode visiteur'); return false;">
                        <div class="row gtr-50">
                            <div class="col-3 col-12-mobilep">
                                <input type="text" placeholder="Ordre" readonly />
                            </div>
                            <div class="col-5 col-12-mobilep">
                                <input type="text" placeholder="Nom (ex: HTML...)" readonly />
                            </div>
                            <div class="col-4 col-12-mobilep">
                                <input type="text" placeholder="Niveau en %" readonly />
                            </div>
                            <div class="col-12 text-right mt-1">
                                <input type="submit" value="Ajouter (Désactivé)" class="btn-wide primary disabled-action" disabled />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <button class="accordion-header">Gestion des réseaux sociaux - Lecture seule</button>
            <div class="accordion-panel">
                <div class="admin-block mt-2">
                    <header>
                        <h3>Modifier les réseaux existants</h3>
                    </header>
                    <form onsubmit="alert('Action impossible en mode visiteur'); return false;">
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
                                        <input type="text" value="<?php echo htmlspecialchars($row['url']); ?>" readonly />
                                    </div>
                                    <div class="col-1 col-12-mobilep text-center">
                                        <a href="#" onclick="alert('Action impossible en mode visiteur'); return false;" class="icon solid fa-trash action-btn btn-delete disabled-action" title="Supprimer"></a>
                                    </div>
                                </div>
                        <?php
                            }
                        } else {
                            echo "<p class='text-center p-4'>Aucun réseau configuré.</p>";
                        }
                        ?>
                        
                        <?php if (mysqli_num_rows($result) > 0): ?>
                        <div class="row">
                            <div class="col-12 text-right mt-2">
                                <input type="submit" value="Enregistrer les modifications (Désactivé)" class="btn-wide disabled-action" disabled />
                            </div>
                        </div>
                        <?php endif; ?>
                    </form>
                </div>
                <div class="admin-block">
                    <header>
                        <h3>Ajouter un nouveau réseau</h3>
                    </header>
                    <form onsubmit="alert('Action impossible en mode visiteur'); return false;">
                        <div class="row gtr-50">
                            <div class="col-6 col-12-mobilep">
                                <input type="text" placeholder="Nom (ex: instagram)" readonly />
                            </div>
                            <div class="col-6 col-12-mobilep">
                                <input type="text" placeholder="URL (https://...)" readonly />
                            </div>
                            <div class="col-12 text-right mt-1">
                                <input type="submit" value="Ajouter (Désactivé)" class="btn-wide disabled-action" disabled />
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
    </script>
</body>
</html>