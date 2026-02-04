<?php
session_start();
require '../includes/db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = "";

if (isset($_GET['delete'])) {
    $id_to_delete = (int)$_GET['delete'];
    $sql_delete = "DELETE FROM socials WHERE id = $id_to_delete";
    if (mysqli_query($link, $sql_delete)) {
        $message = "Réseau supprimé avec succès.";
    } else {
        $message = "Erreur lors de la suppression.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_social'])) {
    $new_name = mysqli_real_escape_string($link, $_POST['new_name']);
    $new_url = mysqli_real_escape_string($link, $_POST['new_url']);

    if (!empty($new_name) && !empty($new_url)) {
        $sql_add = "INSERT INTO socials (name, url) VALUES ('$new_name', '$new_url')";
        if (mysqli_query($link, $sql_add)) {
            $message = "Nouveau réseau ajouté !";
        } else {
            $message = "Erreur lors de l'ajout.";
        }
    } else {
        $message = "Veuillez remplir tous les champs pour l'ajout.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_socials'])) {
    if (isset($_POST['urls'])) {
        foreach ($_POST['urls'] as $id => $url) {
            $id = (int)$id;
            $url = mysqli_real_escape_string($link, $url);
            $sql_update = "UPDATE socials SET url='$url' WHERE id=$id";
            mysqli_query($link, $sql_update);
        }
        $message = "Modifications enregistrées !";
    }
}

$sql = "SELECT * FROM socials";
$result = mysqli_query($link, $sql);
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
        <p>Gère tes réseaux sociaux</p>
        <ul class="actions special">
            <li><a href="../index.php" class="button icon solid fa-home">Voir le site</a></li>
            <li><a href="logout.php" class="button icon solid fa-sign-out-alt">Déconnexion</a></li>
        </ul>
    </div>

    <div id="main">
        <div class="box container">
            
            <?php if($message) echo "<p class='msg-success'>$message</p>"; ?>

            <div class="admin-block">
                <header>
                    <h2>Mes Réseaux</h2>
                    <p>Modifie les liens ou supprime des réseaux existants.</p>
                </header>

                <form method="post" action="dashboard.php">
                    
                    <div class="row gtr-50 table-header">
                        <div class="col-3 col-12-mobilep">Réseau</div>
                        <div class="col-8 col-12-mobilep">Lien URL</div>
                        <div class="col-1 col-12-mobilep text-center">Action</div>
                    </div>

                    <?php
                    if (mysqli_num_rows($result) > 0) {
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
                    <p>Entre le nom exact de l'icône (ex: <em>instagram, twitter, facebook, dribbble</em>)</p>
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

    <div id="footer">
        <ul class="copyright">
            <li>&copy; Back-Office Malo.</li>
        </ul>
    </div>

</body>
</html>