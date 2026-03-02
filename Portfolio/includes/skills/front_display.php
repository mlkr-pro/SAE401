<?php
function afficherSkills($link) {
    $sql = "SELECT skill_name, level FROM skills ORDER BY display_order ASC, id ASC";
    $result = mysqli_query($link, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo '<div class="container medium" style="margin-bottom: 4em; text-align: center;">';
        echo '<h3 style="margin-bottom: 1em;">Mes Compétences</h3>';
        echo '<ul class="actions special" style="flex-wrap: wrap; justify-content: center; gap: 10px;">';
        
        while ($row = mysqli_fetch_assoc($result)) {
            $name = htmlspecialchars($row['skill_name']);
            $level = htmlspecialchars($row['level']);
            echo '<li style="padding: 0; margin: 0;"><span class="button small disabled" style="pointer-events: none; color: #ffffff; opacity: 1; border-color: #ccc;">' . $name . ' (' . $level . '%)</span></li>';
        }
        
        echo '</ul>';
        echo '</div>';
    }
}
?>