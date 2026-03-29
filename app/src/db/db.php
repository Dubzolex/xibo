<?php

class Database {

private $pdo = null;
private static $instance = null;
private $host = "db";
private $dbname = "digital_signage";
private $username = "user";
private $password = "password";

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

public function insert($table, $data) {
    $columns = array_keys($data);
    $placeholders = array_fill(0, count($columns), "?");

    $sql = "INSERT INTO `$table` (`" . implode("`, `", $columns) . "`) 
            VALUES (" . implode(", ", $placeholders) . ")";

    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute(array_values($data));
}

public function query($sql, $params = []) {
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

public function fetchAll($sql, $params = []) {
    return $this->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
}

public function fetch($sql, $params = []) {
    return $this->query($sql, $params)->fetch(PDO::FETCH_ASSOC);
}

public function delete($table, $id) {
    $sql = "DELETE FROM `$table` WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute(["id" => $id]);
}

public function update($table, $id, $data) {
    $setParts = [];
    $values = [];

    foreach ($data as $col => $val) {
        $setParts[] = "`$col` = ?";
        $values[] = $val;
    }
    $values[] = $id;

    $sql = "UPDATE `$table` SET " . implode(", ", $setParts) . " WHERE id = ?";
    $stmt = $this->pdo->prepare($sql);
    
    return $stmt->execute($values);
}

}

?>