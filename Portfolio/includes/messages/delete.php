<?php
if (isset($_GET['delete_message'])) {
    $id_msg = (int)$_GET['delete_message'];
    $sql_del = "DELETE FROM messages WHERE id = $id_msg";
    if (mysqli_query($link, $sql_del)) {
        header("Location: dashboard.php?msg=message_deleted");
        exit;
    }
}
?>