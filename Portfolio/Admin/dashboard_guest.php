<?php
session_start();
require '../includes/db_config.php';
// On n'utilise pas le security.php, on fait une vérif simple
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

require '../includes/socials/read.php'; 
require '../includes/project/read.php';
require '../includes/categories/read.php';

$message = "Mode Visiteur : Lecture seule activée.";
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Dashboard (Lecture Seule)</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../assets/css/main.css" />
    <link rel="stylesheet" href="../assets/css/admin.css" />
    <style>
        /* Petit bandeau pour prévenir */
        .guest-banner { background: #e67e22; color: white; text-align: center; padding: 10px; font-weight: bold; }
        .disabled-action { opacity: 0.6; cursor: not-allowed; }
    </style>
</head>
<body class="is-preload">
    
    <div class="guest-banner">MODE DÉMO : Vous êtes connecté en lecture seule.</div>

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
            
            <button class="accordion-header">Gestion des Projets (Lecture Seule)</button>
            <div class="accordion-panel">
                <div class="admin-block" style="margin-top: 2em;">
                    <header><h3>Mes Projets existants</h3></header>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 15%; text-align: left;">Date</th>
                                    <th style="width: 40%; text-align: left;">Titre</th>
                                    <th style="width: 30%; text-align: left;">Catégorie</th>
                                    <th style="width: 15%; text-align: center;">Action</th>
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
                                        <td style="text-align: center;">
                                            <a href="#" onclick="alert('Action impossible en mode visiteur'); return false;" class="icon solid fa-pen disabled-action" style="color:#4CAF50; margin-right: 15px; border-bottom:none;"></a>
                                            <a href="#" onclick="alert('Action impossible en mode visiteur'); return false;" class="icon solid fa-trash disabled-action" style="color:#ff4444; border-bottom:none;"></a>
                                        </td>
                                    </tr>
                                <?php
                                    }
                                } else { echo "<tr><td colspan='4'>Aucun projet.</td></tr>"; }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="admin-block">
                    <header><h3>Ajouter un nouveau projet</h3></header>
                    <form onsubmit="alert('Action impossible en mode visiteur'); return false;">
                        <div class="row gtr-50">
                            <div class="col-12"><input type="text" placeholder="Titre du projet" readonly /></div>
                            <div class="col-6 col-12-mobilep">
                                <select disabled><option>-- Choisir une catégorie --</option></select>
                            </div>
                            <div class="col-12"><textarea placeholder="Description..." rows="4" readonly></textarea></div>
                            <div class="col-12 text-right mt-1">
                                <input type=\"submit\" value="Publier (Désactivé)" class="btn-wide primary disabled-action" disabled />
                            </div>
                        </div>
                    </form>
                </div>
            </div>


            <button class="accordion-header">Gestion des Réseaux Sociaux (Lecture Seule)</button>
            <div class="accordion-panel">
                <div class="admin-block" style="margin-top: 2em;">
                    <header><h3>Modifier les réseaux existants</h3></header>
                    <form onsubmit="alert('Action impossible en mode visiteur'); return false;">
                        <div class="row gtr-50 table-header">
                            <div class="col-3">Réseau</div><div class="col-8">Lien URL</div><div class="col-1 text-center">Action</div>
                        </div>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            mysqli_data_seek($result, 0); 
                            while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                                <div class="row gtr-50 aln-middle social-row">
                                    <div class="col-3">
                                        <div class="network-label"><span class="icon brands fa-<?php echo htmlspecialchars($row['name']); ?>"></span> <?php echo ucfirst($row['name']); ?></div>
                                    </div>
                                    <div class="col-8">
                                        <input type="text" value="<?php echo htmlspecialchars($row['url']); ?>" readonly />
                                    </div>
                                    <div class="col-1 text-center">
                                        <a href="#" onclick="alert('Action impossible en mode visiteur'); return false;" class="icon solid fa-trash disabled-action" style="color:#ff4444"></a>
                                    </div>
                                </div>
                        <?php } } ?>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <div id="footer"><ul class="copyright"><li>&copy; Back-Office Malo.</li></ul></div>

    <script>
        var acc = document.getElementsByClassName("accordion-header");
        for (var i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                if (panel.style.maxHeight) { panel.style.maxHeight = null; panel.classList.remove("open"); } 
                else { panel.classList.add("open"); panel.style.maxHeight = "1500px"; } 
            });
        }
    </script>
</body>
</html>