<?php
$sql_skills = "SELECT * FROM skills ORDER BY display_order ASC, id ASC";
$result_skills = mysqli_query($link, $sql_skills);
?>