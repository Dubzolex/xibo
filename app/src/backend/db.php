<?php

class Database {

private $pdo = null;
private static $instance = null;
private $host = "db";
private $dbname = "digital_signage";
private $username = "user";
private $password = "password";

// ========================================
// SINGLETON
// ========================================

private function __construct() {
    try {
        // Connexion à MariaDB via PDO
        $this->pdo = new PDO(
            "mysql:host=$this->host;dbname=$this->dbname;charset=utf8", 
            $this->username, $this->password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (PDOException $e) {
        die("Erreur de connexion: " . $e->getMessage());
    }
}

public static function getInstance() {
    if (self::$instance === null) {
        self::$instance = new self();
    }
    return self::$instance;
}

// ========================================
// ADD
// ========================================

private function addData($table, $data) {
    $columns = array_keys($data);
    $placeholders = array_fill(0, count($columns), "?");

    $sql = "INSERT INTO `$table` (`" . implode("`, `", $columns) . "`) 
            VALUES (" . implode(", ", $placeholders) . ")";

    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute(array_values($data));
}

public function addUser($data) {
    return $this->addData("users", $data);
}

public function addScreen($data) {
    return $this->addData("screens", $data);
}

public function addSession($data) {
    return $this->addData("sessions", $data);
}

public function addPermission($data) {
    return $this->addData("permissions", $data);
}


// ========================================
// UPDATE
// ========================================

public function updateUserById($userId, $data) {
    $allowed = ['name', 'email', 'password', 'password_changed_at'];
    $filteredData = array_intersect_key($data, array_flip($allowed));

    if (empty($filteredData)) return false;

    $setParts = [];
    $values = [];
    foreach ($filteredData as $col => $val) {
        $setParts[] = "`$col` = ?";
        $values[] = $val;
    }
    $values[] = $userId;

    $sql = "UPDATE users SET" . implode(", ", $setParts) . " WHERE users.id = ?";

    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute($values);
}

public function updateUserByToken($token, $data) {
    $allowed = ['name', 'email', 'password', 'password_changed_at'];
    $filteredData = array_intersect_key($data, array_flip($allowed));

    if (empty($filteredData)) return "salut cest moi";

    $setParts = [];
    $values = [];
    foreach ($filteredData as $col => $val) {
        $setParts[] = "u.`$col` = ?";
        $values[] = $val;
    }
    $values[] = $token;

    $sql = "UPDATE users u JOIN sessions s ON u.id = s.user_id SET " . implode(", ", $setParts) . " WHERE s.token = ?";

    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute($values);
}

public function updateScreenById($screenId, $data) {
    $setParts = [];
    $values = [];

    foreach ($data as $col => $val) {
        $setParts[] = "`$col` = ?";
        $values[] = $val;
    }
    $values[] = $screenId;
    $sql = "UPDATE screens SET " . implode(", ", $setParts) . " WHERE screens.id = ?";

    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute($values);
}



// ========================================
// DELETE
// ========================================

private function deleteById($table, $id) {
    $sql = "DELETE FROM `$table` WHERE id = :id";

    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute(["id" => $id]);
}

public function deletePermissionById($id) {
    return $this->deleteById("permissions", $id);
}


// ========================================
// GETS
// ========================================

public function getUsers() {
    $sql = "SELECT users.id, users.name, users.email, roles.role, created_at, updated_at, password_changed_at FROM users JOIN roles ON users.role_id = roles.id;";

    $stmt = $this->pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getScreens() {
    $sql = "SELECT screens.*, users.name, users.email FROM screens LEFT JOIN users ON users.id = screens.user_id;";

    $stmt = $this->pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getPermissions() {
    $sql = "SELECT permissions.*, users.name, users.email, screens.label FROM permissions LEFT JOIN users ON users.id = permissions.user_id LEFT JOIN screens ON screens.id = permissions.screen_id;";

    $stmt = $this->pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getSessions() {
    $sql = "SELECT sessions.*, users.name, users.email FROM sessions JOIN users ON users.id = sessions.user_id;";

    $stmt = $this->pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// ========================================
// GET BY
// ========================================

public function getUserByEmail($email) {
    $sql = "SELECT id, email, password FROM users WHERE users.email = :email";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(["email" => $email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function getUserBySessionToken($token) {
    $sql = "SELECT u.id, u.name, u.email, r.role, GROUP_CONCAT(e.label) AS label, u.updated_at FROM users u JOIN sessions s ON u.id = s.user_id JOIN roles r ON u.role_id = r.id LEFT JOIN permissions p ON p.user_id = u.id LEFT JOIN screens e ON p.screen_id = e.id WHERE s.expires_at > NOW() AND s.token = :token GROUP BY u.id, s.id;";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(["token" => $token]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function getScreensBySessionToken($token) {
    $sql = "SELECT DISTINCT e.id, e.label FROM screens e
        JOIN permissions p ON e.id = p.screen_id 
        JOIN users u ON p.user_id = u.id JOIN sessions s ON u.id = s.user_id WHERE s.expires_at > NOW() and s.token = :token";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(["token" => $token]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getSessionByToken($token) {
    $sql = "SELECT u.id, u.role_id, u.name, u.password_changed_at  FROM users u JOIN sessions s ON u.id = s.user_id WHERE s.expires_at > NOW() and s.token = :token";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(["token" => $token]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}





}

?>