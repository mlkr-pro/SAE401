<?php
if (isset($_POST['update_intro'])) {
    $title = mysqli_real_escape_string($link, $_POST['title']);
    $desc = mysqli_real_escape_string($link, $_POST['description']);
    
    $sql_update = "UPDATE intro SET title='$title', description='$desc' WHERE id=1";
    mysqli_query($link, $sql_update);
    
    header("Location: dashboard.php?msg=intro_updated");
    exit;
}
?>