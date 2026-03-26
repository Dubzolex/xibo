<?php

class Session {

private $db;

public function __construct($db) {
    $this->db = $db;
}

private function transform($data) {
    $result = [];

    if(isset($data["token"])) {
        $result["token"] = $data["token"];
    }
    if(isset($data["userId"])) {
        $result["password"] = $data["userId"];
    }
    if(isset($data["connectedAt"])) {
        $result["connected_at"] = $data["connectedAt"];
    }
    if(isset($data["expiresAt"])) {
        $result["expires_at"] = $data["expiresAt"];
    }
    
    return $result;
}

public function add($data) {
    $result = $this->transform($data);
    
    return $this->db->insert("session", $result);
}

public function delete($id) {
    $this->db->delete("sessions", ["id"=> $id]);
}

public function getAll() {
    $sql = "SELECT sessions.*, users.name, users.email FROM sessions JOIN users ON users.id = sessions.user_id;";

    return $this->db->fetchAll($sql);
}

}

?>