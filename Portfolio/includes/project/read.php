<?php
$sql_projects = "
    SELECT 
        p.id, 
        p.title, 
        p.publish_date, 
        c.label AS category_name 
    FROM projects p
    LEFT JOIN categories c ON p.category_id = c.id
    ORDER BY p.publish_date DESC
";
$result_projects = mysqli_query($link, $sql_projects);
?>