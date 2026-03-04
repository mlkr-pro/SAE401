<?php
$sql_hero = "SELECT * FROM hero LIMIT 1";
$result_hero = mysqli_query($link, $sql_hero);
$hero = mysqli_fetch_assoc($result_hero);
?>