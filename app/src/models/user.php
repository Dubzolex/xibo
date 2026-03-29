<?php

class User {

private $db;

public function __construct($db) {
    $this->db = $db;
}

private function transform($data) {
    $result = [];

    if(isset($data["name"])) {
        $result["name"] = $data["name"];
    }
    if(isset($data["email"])) {
        $result["email"] = $data["email"];
    }
    if(isset($data["password"])) {
        $result["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
    }
    if(isset($data["passwordChangedAt"])) {
        $result["password_changed_at"] = $data["passwordChangedAt"];
    }
    if(isset($data["role"])) {
        $result["role_id"] = $data["role"];
    }
    
    return $result;
}

public function add($data) {
    $result = $this->transform($data);
    return $this->db->insert("users", $result);
}

public function update($id, $data) {
    $result = $this->transform($data);
    $this->db->update("users", $id, $result);
}

public function updateByToken($token, $data) {
    $req = $this->transform($data);

    $setParts = [];
    $values = [];
    foreach ($req as $col => $val) {
        $setParts[] = "u.`$col` = ?";
        $values[] = $val;
    }
    $values[] = $token;

    $sql = "UPDATE users u JOIN sessions s ON u.id = s.user_id SET " . implode(", ", $setParts) . " WHERE s.token = ?";

    $this->db->query($sql, $values);
}

public function delete($id) {
    $this->db->delete("users", ["id"=> $id]);
}

public function getAll() {
    $sql = "SELECT users.id, users.name, users.email, roles.role, created_at, updated_at, password_changed_at FROM users LEFT JOIN roles ON users.role_id = roles.id";

    return $this->db->fetchAll($sql);
}

public function get($id) {
    $sql = "SELECT users.id, users.name, users.email, roles.role, created_at, updated_at, password_changed_at FROM users LEFT JOIN roles ON users.role_id = roles.id WHERE users.id = :id";

    return $this->db->fetch($sql, ["id"=> $id]);
}

public function getByEmail(string $email) {
    $sql = "SELECT id, email password FROM users WHERE users.email = :email";

    return $this->db->fetch($sql, ["email" => $email]);
}

public function getByToken(string $token) {
    $sql = "SELECT u.id, u.name, u.email, r.role, u.updated_at, GROUP_CONCAT(e.label) AS label FROM users u JOIN roles r ON u.role_id = r.id JOIN sessions s ON u.id = s.user_id LEFT JOIN permissions p ON p.user_id = u.id LEFT JOIN screens e ON p.screen_id = e.id WHERE s.token = :token GROUP BY u.id;";

    return $this->db->fetch($sql, ["token" => $token]);
}

public function verify($token) {
    $sql = "SELECT u.id, u.password_changed_at, u.role_id, u.name FROM users u JOIN roles r ON u.role_id = r.id JOIN sessions s ON u.id = s.user_id WHERE s.expires_at > NOW() AND s.token = :token;";

    return $this->db->fetch($sql, ["token" => $token]);
}

}

?>