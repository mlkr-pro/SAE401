<?php
$sql_messages = "SELECT * FROM messages ORDER BY date_sent DESC";
$result_messages = mysqli_query($link, $sql_messages);
?>