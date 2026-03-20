<?php

class Init {

private $pdo = null;
private static $instance = null;
private $host = "db";
private $dbname = "xibo";
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
// CREATE TABLE
// ========================================

public function createTableScreens() {
    try {
        // Requête SQL de création de table
        $sql = "
            CREATE TABLE IF NOT EXISTS screens (
                id INT PRIMARY KEY AUTO_INCREMENT,
                name VARCHAR(255) NOT NULL,
                duration INT DEFAULT 10,
                is_running BOOLEAN DEFAULT FALSE,
                is_updating BOOLEAN DEFAULT FALSE,
                is_visible BOOLEAN DEFAULT TRUE,
                is_readable BOOLEAN DEFAULT FALSE,
                is_controlled BOOLEAN DEFAULT FALSE,
                format VARCHAR(255) DEFAULT NULL,
                description VARCHAR(255) DEFAULT NULL,
                user_id INT DEFAULT NULL
            );";
    
        $this->pdo->exec($sql);
        echo " Table screens créée avec succès. ";

    } catch (PDOException $e) {
        echo " Erreur création de table screens: " . $e->getMessage();
        return;
    }
        
    try {
        $sql = "INSERT INTO screens (name) VALUES ('Borne'), ('Reflex'), ('Fabrik'), ('IP');";
        $this->pdo->exec($sql);

        echo " ajout des ecrans. ";

    } catch (PDOException $e) {
        echo " Erreur ajout des ecrans : " . $e->getMessage();
    }
}

public function createTableUsers() {
    global $pdo;
    try {
        // Requête SQL de création de table
        $sql = "
            CREATE TABLE IF NOT EXISTS users (
                id INT PRIMARY KEY AUTO_INCREMENT ,
                name VARCHAR(255) DEFAULT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role_id INT DEFAULT 1,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                deleted_at DATETIME DEFAULT NULL,
                password_changed_at DATETIME DEFAULT NULL
            );";
    
        $pdo->exec($sql);
    
        echo " Table users créée avec succès. ";
    
    } catch (PDOException $e) {
        echo " Erreur creation table users: " . $e->getMessage();
    }
}

public function createTableRoles() {
    global $pdo;
    try {
        // Requête SQL de création de table
        $sql = "
            CREATE TABLE IF NOT EXISTS roles (
                id INT PRIMARY KEY AUTO_INCREMENT,
                role VARCHAR(255) NOT NULL
            );";
    
        $pdo->exec($sql);
    
        echo " Table users créée avec succès. ";
    
    } catch (PDOException $e) {
        echo " Erreur creation table roles : " . $e->getMessage();
        return;
    }

    try {
      /*  $sql = "INSERT INTO roles (id, role) VALUES (1, 'Viewer'), (2, 'Editor'), (3, 'Manager'), (4, 'Admin');";
        $stmt->exec($sql);
*/
        echo " ajout des roles. ";

    } catch (PDOException $e) {
        echo " Erreur ajout des roles: " . $e->getMessage();
    }

}

public function createTableSessions() {
    global $pdo;
    try {
        // Requête SQL de création de table
        $sql = "
            CREATE TABLE IF NOT EXISTS sessions (
                id INT PRIMARY KEY AUTO_INCREMENT,
                token VARCHAR(255) NOT NULL UNIQUE,
                user_id INT NOT NULL,
                connected_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                expires_at DATETIME NOT NULL,
                is_revoked BOOLEAN DEFAULT FALSE
            );";
    
        $pdo->exec($sql);
    
        echo " Table sessions créée avec succès. ";
    
    } catch (PDOException $e) {
        echo " Erreur creation table sessions: " . $e->getMessage();
    }
}

public function createTablePermissions() {
    try {
        // Requête SQL de création de table
        $sql = "
            CREATE TABLE IF NOT EXISTS permissions (
                id INT PRIMARY KEY AUTO_INCREMENT,
                user_id INT NOT NULL,
                screen_id INT NOT NULL,
                role_id INT DEFAULT 1,
                joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );";
    
        $this->pdo->exec($sql);
    
        echo " Table permissions créée avec succès. ";
    
    } catch (PDOException $e) {
        echo " Erreur creation table permissions: " . $e->getMessage();
    }
}

// ========================================
// FOREIGN KEY
// ========================================
/*

public function createForeignKey() {
    //
    try {
        $sql = "ALTER TABLE users ADD CONSTRAINT fk_users_role_id FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT ON UPDATE CASCADE;"
    
        $this->pdo->exec($sql);
    
        echo " Foreign Key users to role id créée avec succès. ";
    
    } catch (PDOException $e) {
        echo " Erreur Foreign Key users to role id: " . $e->getMessage();
    }

    try {
        $sql = "ALTER TABLE sessions ADD CONSTRAINT fk_sessions_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE;"
    
        $this->pdo->exec($sql);
    
        echo " Foreign Key sessions to user id créée avec succès. ";
    
    } catch (PDOException $e) {
        echo " Erreur Foreign Key sessions to user id: " . $e->getMessage();
    }

    try {
        $sql = "ALTER TABLE permissions ADD CONSTRAINT fk_permissions_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE;"
    
        $this->pdo->exec($sql);
    
        echo " Foreign Key permissions to user id créée avec succès. ";
    
    } catch (PDOException $e) {
        echo " Erreur Foreign Key permissions to user id: " . $e->getMessage();
    }

    try {
        $sql = "ALTER TABLE permissions ADD CONSTRAINT fk_permissions_screen_id FOREIGN KEY (screen_id) REFERENCES screens(id) ON DELETE CASCADE ON UPDATE CASCADE;"
    
        $this->pdo->exec($sql);
    
        echo " Foreign Key permissions to screen id créée avec succès. ";
    
    } catch (PDOException $e) {
        echo " Erreur Foreign Key permissions to screen id : " . $e->getMessage();
    }

    try {
        $sql = "ALTER TABLE screens ADD CONSTRAINT fk_screens_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE;"
    
        $this->pdo->exec($sql);
    
        echo " Foreign Key screens to user id créée avec succès. ";
    
    } catch (PDOException $e) {
        echo " Erreur Foreign Key screens to user id: " . $e->getMessage();
    }
}

*/


}