<?php
$host = 'mysql-lecaer.alwaysdata.net';
$user = 'lecaer';
$password = 'Nator.95';
$db = 'lecaer_back-office-sae401';

$link = mysqli_connect($host, $user, $password, $db);

if (!$link) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

mysqli_set_charset($link, "utf8");
?>