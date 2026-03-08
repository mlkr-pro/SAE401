<?php
session_start();
require '../includes/db_config.php';
require '../includes/auth/security.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id_msg = (int)$_GET['id'];

// Traitement de l'envoi de la réponse
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_reply'])) {
    $to = $_POST['user_email'];
    $subject = "Réponse à votre message - Portfolio Malo";
    $reply_message = $_POST['reply_message'];
    
    $admin_email = "malolkr.pro@gmail.com";
    $website_email = "lecaer@alwaysdata.net";

    $headers = "From: Malo Le Caer <$website_email>\r\n";
    $headers .= "Reply-To: $admin_email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=utf-8\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    if (mail($to, $subject, $reply_message, $headers)) {
        header("Location: dashboard.php?msg=message_replied");
        exit;
    } else {
        $erreur = "Erreur lors de l'envoi de l'email par le serveur.";
    }
}

// Récupération des données du message
$sql_get = "SELECT * FROM messages WHERE id = $id_msg";
$res_get = mysqli_query($link, $sql_get);
$message_data = mysqli_fetch_assoc($res_get);

if (!$message_data) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Répondre au message</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../assets/css/main.css" />
    <link rel="stylesheet" href="../assets/css/admin.css" />
</head>
<body class="is-preload">
    <div id="header">
        <h1>Répondre</h1>
        <ul class="actions special">
            <li><a href="dashboard.php" class="button icon solid fa-arrow-left">Retour</a></li>
        </ul>
    </div>

    <div id="main">
        <div class="box container">
            <div class="admin-block">
                <header><h3>Message de <?php echo htmlspecialchars($message_data['name']); ?></h3></header>
                
                <?php if(isset($erreur)) echo "<p class='msg-error'>$erreur</p>"; ?>
                
                <div class="message-original-box">
                    <p><strong>Date :</strong> <?php echo date('d/m/Y H:i', strtotime($message_data['date_sent'])); ?><br>
                    <strong>Email :</strong> <?php echo htmlspecialchars($message_data['email']); ?></p>
                    <hr>
                    <p><em><?php echo nl2br(htmlspecialchars($message_data['message'])); ?></em></p>
                </div>

                <form method="post" action="">
                    <input type="hidden" name="user_email" value="<?php echo htmlspecialchars($message_data['email']); ?>">
                    <div class="row gtr-50">
                        <div class="col-12">
                            <label>Votre réponse (Email)</label>
                            <textarea name="reply_message" rows="8" required>Bonjour <?php echo htmlspecialchars($message_data['name']); ?>,&#10;&#10;Suite à votre message :&#10;&#10;&#10;Cordialement,&#10;LE CAER Malo.</textarea>
                        </div>
                        <div class="col-12 text-right mt-1">
                            <input type="submit" name="send_reply" value="Envoyer la réponse" class="btn-wide primary" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>