<?php

class Role {

private $db;

public function __construct($db) {
    $this->db = $db;
}

private function transform($data) {
    $result = [];

    if(isset($data["roleId"])) {
        $result["role_id"] = $data["roleId"];
    }
    
    return $result;
}

public function add($data) {
    $result = $this->transform($data);
    return $this->db->insert("users", $result);
}

public function update($id, $data) {
    $result = $this->transform($data);
    $this->db->update("roles", $id, $result);
}

public function delete($id) {
    $this->db->delete("roles", ["id"=> $id]);
}

}

?>