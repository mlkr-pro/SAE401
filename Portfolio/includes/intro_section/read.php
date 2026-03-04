<?php
$sql_intro = "SELECT * FROM intro LIMIT 1";
$result_intro = mysqli_query($link, $sql_intro);
$intro = mysqli_fetch_assoc($result_intro);
?>