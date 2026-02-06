<?php
session_start();
require '../includes/db_config.php';

$erreur = "";

require '../includes/auth/login_process.php';

?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Connexion - Back Office</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="stylesheet" href="../assets/css/main.css" />
        <link rel="stylesheet" href="../assets/css/admin.css" />
	</head>
	<body class="is-preload admin-login">

        <div id="main">
            <header class="major container medium">
                <h2>Espace Admin</h2>
                <p>Connexion au Portfolio</p>
            </header>

            <div class="box container medium">
                <?php if($erreur): ?>
                    <p class="msg-error"><?php echo $erreur; ?></p>
                <?php endif; ?>

                <form method="post" action="login.php">
                    <div class="row gtr-50">
                        <div class="col-12">
                            <label for="login">Identifiant</label>
                            <input type="text" name="login" id="login" placeholder="Votre identifiant" required />
                        </div>
                        <div class="col-12">
                            <label for="password">Mot de passe</label>
                            <input type="password" name="password" id="password" placeholder="Votre mot de passe" required />
                        </div>
                        <div class="col-12">
                            <ul class="actions special">
                                <li><input type="submit" name="connecter" value="Se connecter" /></li>
                                <li><a href="../index.php" class="button alt">Retour au site</a></li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script src="../assets/js/jquery.min.js"></script>
        <script src="../assets/js/browser.min.js"></script>
        <script src="../assets/js/breakpoints.min.js"></script>
        <script src="../assets/js/util.js"></script>
        <script src="../assets/js/main.js"></script>
    </body>
</html>