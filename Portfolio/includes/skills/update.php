<?php
if (isset($_POST['update_skills']) && isset($_POST['skills'])) {
    foreach ($_POST['skills'] as $id => $data) {
        $id = (int)$id;
        $name = mysqli_real_escape_string($link, $data['name']);
        $level = (int)$data['level'];
        $order = (int)$data['order'];

        if ($level >= 0 && $level <= 100 && !empty($name)) {
            $sql = "UPDATE skills SET skill_name = '$name', level = $level, display_order = $order WHERE id = $id";
            mysqli_query($link, $sql);
        }
    }
    header("Location: dashboard.php?msg=skill_updated");
    exit;
}
?>