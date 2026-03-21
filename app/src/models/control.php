<?php

class Control {

private $db;

public function __construct($database) {
    $this->db = $database;
}
















/* users */

public function getUsers() {
    $html = '
        <div class="fx-row jc-center p-20">
            <div class="fx-row container w-800 px-20 py-20 jc-between tools gap-20 ai-center">
                <div class="fx-row gap-20 wrap">
                    <input id="email" type="email" placeholder="email">
                    <input id="password" type="password" placeholder="password">
                </div>
                <button class="action" id="add-user">Add</button>
            </div>
        </div>
        <div class="fx-row jc-center grow p-20">
            <div id="content-user" class="fx-col grow w-600 gap-40"></div>
        </div>';

    try {
        $users = $this->db->getUsers();

        $results = array_map(function($user) {
            return [
                "id"    => $user["id"] ?? null,
                "name"  => $user["name"] ?? null,
                "email" => $user["email"] ?? null,
            ];
        }, $users);
        
        return [
            "success" => true,
            "html"=> $html ?? null,
            "data" => $results ?? []
        ];

    } catch (PDOException $e) {
        return [
            "success" => false,
            "message" => "Problème serveur.",
            "error" => $e->getMessage()
        ];
    }
}


public function addUser(array $data) {
    $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
    return $this->db->addUser($data);
}


public function updateUser(int $userId, array $data) {
    try {
        if(isset($data["password"]) && $data["password"] != "") {
            $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
            $data["password_changed_at"] = date("Y-m-d H:i:s");
        }
        
        if($userId > 0) {
            return $this->db->updateUserById($userId, $data);
        }
            
        return [
            "success" => false,
            "message"=> "No screen id."
        ];

    } catch (PDOException $e) {
        return [
            "success" => false,
            "message" => "Problème serveur.",
            "error" => $e->getMessage()
        ];
    }
    
}


public function resetUser(int $id) {
    $new = bin2hex(random_bytes(6));
    $data = [
        "password" => password_hash($new, PASSWORD_DEFAULT),
        "password_changed_at" => null,
    ];

    if($id == null) {
            return [
            "success" => false,
            "message"=> "No user id."
        ];
    }

    try {    
        return [
            "success" => $this->db->updateUserById($id, $data),
            "alert" => "Nouveau mot de passe : " . $new
        ];

    } catch (PDOException $e) {
        return [
            "success" => false,
            "message" => "Problème serveur.",
            "error" => $e->getMessage()
        ];
    }
    
}





















/* screens */

public function getScreens() {
    $html = '
        <div class="fx-row jc-center p-20">
            <div class="fx-row container w-800 px-20 py-20 jc-between tools gap-20">
                <div class="fx-row gap-20">
                    <input id="name" type="text" placeholder="name">
                </div>
                <button class="action" id="add-screen">Add</button>
            </div>
        </div>
        <div class="fx-row jc-center grow p-20">
            <div id="content-screen" class="fx-col grow w-600 gap-40"></div>
        </div>';

    try {
        $screens = $this->db->getScreens();

        $results = array_map(function($e) {
            return [
                "id"    => $e["id"] ?? null,
                "label"  => $e["label"] ?? null,
                "location" => $e["location"] ?? null,
                "description" => $e["description"] ?? null,
                "format" => $e["format"] ?? null,
                "running" => $e["is_running"] ?? null,
                "updating" => $e["is_updating"] ?? null,
                "controlled" => $e["is_controlled"] ?? null
            ];
        }, $screens);

        return [
            "success" => true,
            "html"=> $html ?? null,
            "data" => $results
        ];

    } catch (PDOException $e) {
        return [
            "success"=> false,
            "message"=> "Problème serveur.",
            "error"=> $e->getMessage()
        ];
    }
}



public function addScreen($data) {
    try {
        return $this->db->addScreen($data);

    } catch (PDOException $e) {
        return [
            "success"=> false,
            "message"=> "Problème serveur.",
            "error"=> $e->getMessage()
        ];
    }
    
}

public function updateScreen($id, $data) {
    if($id == null) {
        return [
            "success" => false,
            "message"=> "No screen id."
        ];
    }

    try {
        return $this->db->updateScreenById($id, $data);

    } catch (PDOException $e) {
        return [
            "success"=> false,
            "message"=> "Problème serveur.",
            "error"=> $e->getMessage()
        ];
    }
    
}





public function getPermissions() {
    $html = '
        <div class="fx-row jc-center p-20">
            <div class="fx-row container w-800 px-20 py-20 jc-between tools gap-20">
                <div class="fx-row gap-20">
                <select id="select-user"></select>
                <slect id="select-screen></select>
                </div>
                <button class="action" id="add-permission">Add</button>
            </div>
        </div>
        <div class="fx-row jc-center grow p-20">
            <div id="content-permission" class="fx-col grow w-600 gap-40"></div>
        </div>';
    try {
        $permissions = $this->db->getPermissions();

        $results = array_map(function($p) {
            return [
                "id"    => $p["id"] ?? null,
                "name"  => $p["name"] ?? null,
                "email" => $p["email"] ?? null,
                "userId" => $p["user_id"] ?? null,
                "label" => $p["label"] ?? null,
                "screenId" => $p["screen_id"] ?? null
            ];
        }, $permissions);

        return [
            "success" => true,
            "html"=> $html,
            "data" => $results
        ];
    } catch (PDOException $e) {
        return [
            "success"=> false,
            "message"=> "Problème serveur.",
            "error"=> $e->getMessage()
        ];
    }
}

public function addPermission($data) {
    try {
        $result = [
            "user_id" => $data["userId"] ?? throw new Exception("No user id"),
            "screen_id" => $data["screenId"] ?? throw new Exception("No screen id"),
        ];

        try {
            return $this->db->addPermission($result);

        } catch (PDOException $e) {
            return [
                "success"=> false,
                "message"=> "Problème serveur.",
                "error"=> $e->getMessage()
            ];
        }

    } catch (Exception $e) {
        return [
            "success"=> false,
            "message"=> "Il manque des informations.",
            "error"=> $e->getMessage()
        ];
    }
}

public function deletePermission($id) {
    try {
        return $this->db->deletePermissionById($id);

    } catch (PDOException $e) {
        return [
            "success"=> false,
            "message"=> "Problème serveur.",
            "error"=> $e->getMessage()
        ];
    }
}

public function getSessions() {
    $html = '
        <div class="fx-row jc-center grow p-20">
            <div id="content-session" class="fx-col grow w-600 gap-10"></div>
        </div>';

    try {
        $sessions = $this->db->getSessions();

        $results = array_map(function($s) {
            return [
                "id"    => $s["id"] ?? null,
                "userId" => $s["user_id"] ?? null,
                "connectedAt" => $s["connected_at"] ?? null,
                "expiresAt" => $s["expires_at"] ?? null,
                "name" => $s["name"] ?? null,
                "email" => $s["email"] ?? null,
                "token" => $s["token"] ?? null
            ];
        }, $sessions);

        return [
            "success" => true,
            "html"=> $html,
            "data" => $results
        ];

    } catch (PDOException $e) {
        return [
            "success"=> false,
            "message"=> "Problème serveur.",
            "error"=> $e->getMessage()
        ];
    }
}

}



?>