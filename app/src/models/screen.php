<?php

class Screen {

private $db;

public function __construct($db) {
    $this->db = $db;
}

private function transform($data) {
    $result = [];

    if(isset($data["label"])) {
        $result["label"] = $data["label"];
    }
    if(isset($data["description"])) {
        $result["description"] = $data["description"];
    }
    if(isset($data["format"])) {
        $result["format"] = $data["format"];
    }
    if(isset($data["visible"])) {
        $result["is_visible"] = $data["visible"];
    }
    if(isset($data["running"])) {
        $result["is_running"] = $data["running"];
    }
    if(isset($data["updating"])) {
        $result["is_updating"] = $data["updating"];
    }
    if(isset($data["controlled"])) {
        $result["is_controlled"] = $data["controlled"];
    }
    
    return $result;
}

public function add($data) {
    $result = $this->transform($data);
    return $this->db->insert("screens", $result);
}

public function update($id, $data) {
    $result = $this->transform($data);
    $this->db->update("screens", $id, $result);
}

public function delete($id) {
    $this->db->delete("screens", $id);
}

public function getAll() {
    $sql = "SELECT screens.*, users.name, users.email FROM screens LEFT JOIN users ON users.id = screens.user_id;";
    return $this->db->fetchAll($sql);
}

public function get($id) {
    $sql = "SELECT screens.*, users.name, users.email FROM screens LEFT JOIN users ON users.id = screens.user_id WHERE screens.id = :id;";

    return $this->db->fetch($sql, ["id"=> $id]);
}

public function getByToken($token) {
    $sql = "SELECT DISTINCT e.id, e.label FROM screens e JOIN permissions p ON e.id = p.screen_id JOIN users u ON p.user_id = u.id JOIN sessions s ON u.id = s.user_id WHERE s.token = :token";

    return $this->db->fetchAll($sql, ["token"=> $token]);
}

}

?>