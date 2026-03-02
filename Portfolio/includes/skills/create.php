<?php
if (isset($_POST['add_skill'])) {
    $name = mysqli_real_escape_string($link, $_POST['skill_name']);
    $level = (int)$_POST['skill_level'];
    $order = (int)$_POST['skill_order'];
    
    if (!empty($name) && $level >= 0 && $level <= 100) {
        $sql = "INSERT INTO skills (skill_name, level, display_order) VALUES ('$name', $level, $order)";
        if (mysqli_query($link, $sql)) {
            header("Location: dashboard.php?msg=skill_added");
            exit;
        }
    }
}
?>