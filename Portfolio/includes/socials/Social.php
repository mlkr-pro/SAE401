<?php
class Social {
    private $db;

    public function __construct($db_connection) {
        $this->db = $db_connection;
    }

    public function getAll() {
        $result = mysqli_query($this->db, "SELECT * FROM socials");
        $socials = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $socials[] = $row;
            }
        }
        return $socials;
    }

    public function add($name, $url) {
        $name = mysqli_real_escape_string($this->db, $name);
        $url = mysqli_real_escape_string($this->db, $url);
        if (!empty($name) && !empty($url)) {
            return mysqli_query($this->db, "INSERT INTO socials (name, url) VALUES ('$name', '$url')");
        }
        return false;
    }

    public function update($id, $url) {
        $id = (int)$id;
        $url = mysqli_real_escape_string($this->db, $url);
        return mysqli_query($this->db, "UPDATE socials SET url='$url' WHERE id=$id");
    }

    public function delete($id) {
        $id = (int)$id;
        return mysqli_query($this->db, "DELETE FROM socials WHERE id = $id");
    }
}
?>