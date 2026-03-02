<?php
if (isset($_GET['delete_skill'])) {
    $id_skill = (int)$_GET['delete_skill'];
    $sql = "DELETE FROM skills WHERE id = $id_skill";
    if (mysqli_query($link, $sql)) {
        header("Location: dashboard.php?msg=skill_deleted");
        exit;
    }
}
?>