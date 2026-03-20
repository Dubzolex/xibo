<?php

class Database {

private $pdo = null;
private static $instance = null;
private $host = "db";
private $dbname = "digital_signage";
private $username = "user";
private $password = "password";

// ========================================
// INIT
// ========================================

public function __construct() {
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

// ========================================
// ADD
// ========================================

private function addData($table, $data) {
    if (isset($data['id'])) {
        unset($data['id']);
    }

    $columns = array_keys($data);
    $placeholders = array_fill(0, count($columns), "?");
    $values = array_values($data);

    $sql = "INSERT INTO `$table` (`" . implode("`, `", $columns) . "`) 
            VALUES (" . implode(", ", $placeholders) . ")";

    try {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);

        return [
            "success" => true,
            "data" => [
                "id" => $this->pdo->lastInsertId()
            ]
        ];

    } catch (PDOException $e) {
        throw $e;
    }
}

public function addUser($data) {
    try {
        return $this->addData("users", $data);

    } catch(PDOException $e) {
        return [
            "success" => false,
            "message" => "Problème lors de l'ajout d'un utilisateur.",
            "error" => $e->getMessage()
        ];
    }
}

public function addScreen($data) {
    try {
        return $this->addData("screens", $data);

    } catch(PDOException $e) {
        return [
            "success" => false,
            "message" => "Problème lors de l'ajout d'un écran.",
            "error" => $e->getMessage()
        ];
    }
}

public function addSession($data) {
    try {
        return $this->addData("sessions", $data);

    } catch(PDOException $e) {
        return [
            "success" => false,
            "message" => "Problème lors de l'authentification.",
            "error" => $e->getMessage()
        ];
    }
}

public function addPermission($data) {
    try {
        return $this->addData("permissions", $data);

    } catch(PDOException $e) {
        return [
            "success" => false,
            "message" => "Problème lors de l'ajout d'un accès.",
            "error" => $e->getMessage()
        ];
    }
}


// ========================================
// UPDATE
// ========================================

private function updateUserById($userId, $data) {
    $setParts = [];
    $values = [];

    foreach ($data as $col => $val) {
        $setParts[] = "`$col` = ?";
        $values[] = $val;
    }
    $sql = "UPDATE users SET " . implode(", ", $setParts) . " WHEREE users.id = `$userId`";

    try {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);

        return [
            "success" => true,
            "message" => "Mise à jour effectué sur votre compte.",
        ];

    } catch (PDOException $e) {
        return [
            "success" => false,
            "error" => $e->getMessage()
        ];
    }
}

private function updateUserByToken($token, $data) {
    $setParts = [];
    $values = [];

    foreach ($data as $col => $val) {
        $setParts[] = "`$col` = ?";
        $values[] = $val;
    }
    $sql = "UPDATE users SET " . implode(", ", $setParts) . " JOIN sessions ON users.id = sessions.user_id WHERE sessions.token = `$token`";

    try {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);

        return [
            "success" => true,
            "message" => "Mise à jour effectué sur votre compte.",
            "data" => [
                "token" => $token
            ]
        ];

    } catch (PDOException $e) {
        throw $e;
    }
}

public function updateUser($data) {
    try {

        if (isset($data['id'])) {
            $userId = $data['id'];
            unset($data['id']);
            return $this->updateUserById($data, $userId);
        }
    
        if (isset($data['token'])) {
            $token = $data['token'];
            unset($data['token']);
            return $this->updateUserByToken($data, $token);
        }

        return [
            "success" => false,
            "message" => "Aucun paramètre de modification renseigné."
        ];

    } catch(PDOException $e) {
        return [
            "success" => false,
            "message" => "Problème de mise à jour de compte.",
            "error" => $e->getMessage()
        ];
    }
}

public function updateScreenById($data) {
    $screenId = null;

    if (isset($data['id'])) {
        $screenId = $data['id'];
        unset($data['id']);

    } else {
        return [
            "success" => false,
            "message" => "Sélectionnez un écran."
        ];
    }
    
    $setParts = [];
    $values = [];

    foreach ($data as $col => $val) {
        $setParts[] = "`$col` = ?";
        $values[] = $val;
    }
    $sql = "UPDATE screens SET " . implode(", ", $setParts) . " WHEREE screens.id = `$screenId`";

    try {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);

        return [
            "success" => true,
            "message" => "Mise à jour effectué sur l'écran.",
            "data" => [
                "id" => $screenId
            ]
        ];

    } catch (PDOException $e) {
        return [
            "success" => false,
            "error" => $e->getMessage(),
            "data" => [
                "id" => $screenId
            ]
        ];
    }
}


// ========================================
// DELETE
// ========================================

private function deleteById($table, $id) {
    try {
        $sql = "DELETE FROM `$table` WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(["id" => $id]);


    } catch (PDOException $e) {
        throw $e;
    }
}

public function deletePermissionById($data) {
    try {
        if (isset($data['id'])) {
            $id = $data['id'];
            $this->deleteById("permissions", $id);

            return [
                "success" => false,
                "message" => "Permission supprimé.",
                "data" => [
                    "id" => $id 
                ]
            ];
        }

        return [
            "success" => false,
            "message" => "Sélectionnez une permission."
        ];

    } catch(PDOException $e) {
        return [
            "success" => false,
            "message" => "Problème lors d'une suppression d'une permission."
        ];
    }
}


// ========================================
// GETS
// ========================================

private function getTable($table) {
    try {
        $sql = "SELECT * FROM `$table`;";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        throw $e;
    }
}

public function getUsers() {
    try {
        return [
            "success" => true,
            "data" => $this->getTable("users")
        ];

    } catch (PDOException $e) {
        return [
            "success" => false,
            "error" => $e->getMessage()
        ];
    }
}

public function getScreens() {
    try {
        return [
            "success" => true,
            "data" => $this->getTable("screens")
        ];

    } catch (PDOException $e) {
        return [
            "success" => false,
            "error" => $e->getMessage()
        ];
    }
}

public function getPermissions() {
    try {
        return [
            "success" => true,
            "data" => $this->getTable("permissions")
        ];

    } catch (PDOException $e) {
        return [
            "success" => false,
            "error" => $e->getMessage()
        ];
    }
}

public function getSessions() {
    try {
        return [
            "success" => true,
            "data" => $this->getTable("sessions")
        ];

    } catch (PDOException $e) {
        return [
            "success" => false,
            "error" => $e->getMessage()
        ];
    }
}


// ========================================
// GET BY
// ========================================

public function getUserByEmail($email) {
    try {
        $sql = "SELECT * FROM users WHERE users.email = :email";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(["email" => $email]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$result) {
            return [
                "success" =>  false,
            ];
        }

        return [
            "success" => true,
            "data" => $result
        ];
        
    } catch (PDOException $e) {
        return [
            "success" => false,
            "error" => $e->getMessage()
        ];
    }
}

public function getUserBySessionToken($token) {
    try {
        $sql = "SELECT * FROM users JOIN sessions ON users.id = sessions.id WHERE sessions.expires_at > NOW() and sessions.token = :token";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(["token" => $token]);

        return [
            "success" => true,
            "data" => $stmt->fetch(PDO::FETCH_ASSOC)
        ];
        
    } catch (PDOException $e) {
        return [
            "success" => false,
            "error" => $e->getMessage()
        ];
    }
}

public function getScreensBySessionToken($token) {
    try {
        $sql = "SELECT * FROM screens 
            JOIN permissions ON screens.id = permissions.screen_id 
            JOIN users ON permissions.user_id = users.id 
            JOIN sessions ON users.id = sessions.user_id WHERE sessions.expires_at > NOW() and sessions.token = :token";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(["token" => $token]);

        return [
            "success" => true,
            "data" => $stmt->fetch(PDO::FETCH_ASSOC)
        ];
        
    } catch (PDOException $e) {
        return [
            "success" => false,
            "error" => $e->getMessage()
        ];
    }
}

}

?>