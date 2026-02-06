<?php
// Récupération de toutes les catégories pour le menu déroulant
$sql_cat = "SELECT * FROM categories ORDER BY label ASC";
$result_cat = mysqli_query($link, $sql_cat);
?>