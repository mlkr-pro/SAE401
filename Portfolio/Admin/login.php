<?php
session_start();
require '../includes/db_config.php';

$erreur = "";

if (isset($_POST['connecter'])) {
    $login = mysqli_real_escape_string($link, $_POST['login']);
    $pass = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$login'";
    $result = mysqli_query($link, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($pass, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            
            header("Location: dashboard.php");
            exit();
        } else {
            $erreur = "Mot de passe incorrect.";
        }
    } else {
        $erreur = "Identifiant inconnu.";
    }
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Connexion - Back Office</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="stylesheet" href="../assets/css/main.css" />
	</head>
	<body class="is-preload">

		<div id="main">

				<header class="major container medium">
					<h2>Espace Membre</h2>
					<p>Identification requise pour accéder au Back-Office</p>
				</header>

				<div class="box container medium">
                    
                    <?php if($erreur): ?>
                        <p style="color: #ff4444; text-align: center; font-weight: bold;">
                            <?php echo $erreur; ?>
                        </p>
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
                                    <li><a href="index.php" class="button alt">Retour au site</a></li>
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