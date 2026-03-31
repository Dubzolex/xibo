<?php

class Permission {

private $db;

public function __construct($db) {
    $this->db = $db;
}

private function transform($data) {
    $result = [];

    if(isset($data["userId"])) {
        $result["user_id"] = $data["userId"];
    }
    if(isset($data["screenId"])) {
        $result["screen_id"] = $data["screenId"];
    }
    if(isset($data["roleId"])) {
        $result["role_id"] = $data["roleId"];
    }
    
    return $result;
}

public function add($data) {
    $result = $this->transform($data);
    return $this->db->insert("permissions", $result);
}

public function delete($id) {
    $this->db->delete("permissions", $id);
}

public function getAll() {
    $sql = "SELECT permissions.*, users.name, users.email, screens.label FROM permissions LEFT JOIN users ON users.id = permissions.user_id LEFT JOIN screens ON screens.id = permissions.screen_id;";

    return $this->db->fetchAll($sql);
}

}

?>