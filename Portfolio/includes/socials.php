<?php
function afficherSocials() {
    require 'includes/db_config.php';

    $sql = "SELECT * FROM socials";

    $result = mysqli_query($link, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $url = $row['url'];
            $name = $row['name'];

            echo "<li>
                    <a href='$url' class='icon brands fa-$name' target='_blank'>
                        <span class='label'>$name</span>
                    </a>
                  </li>";
        }
        mysqli_free_result($result);
    } else {
        echo "";
    }

    mysqli_close($link);
}
?>