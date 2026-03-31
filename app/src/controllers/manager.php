<?php

class Manager {

private $userModel;
private $screenModel;
private $sessionModel;
private $permissionModel;

public function __construct($db) {
    $this->userModel = new User($db);
    $this->screenModel = new Screen($db);
    $this->sessionModel = new Session($db);
    $this->permissionModel = new Permission($db);
}


/* users */

public function getUsers() {
    $html = '
        <div class="fx-row jc-center px-20">
            <div class="fx-row container w-600 p-20 jc-between tools gap-20 ai-center">
                <div class="fx-row gap-20 wrap">
                    <input id="email" type="email" placeholder="email">
                </div>
                <button class="action bg-green" id="add-user">Add</button>
            </div>
        </div>
        <div class="fx-row jc-center grow">
            <div id="content-user" class="fx-col w-1200"></div>
        </div>';

    try {
        $users = $this->userModel->getAll();

        $results = array_map(function($user) {
            return [
                "id"    => $user["id"] ?? null,
                "name"  => $user["name"] ?? null,
                "email" => $user["email"] ?? null,
                "role" => $user["role"] ?? null,
                "updatedAt" => $user["updated_at"] ?? null,
                "changedAt" => $user["password_changed_at"] ?? null,
                "password" => $user["password"] ?? null
            ];
        }, $users);
        
        return [
            "success" => true,
            "html"=> $html,
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
    try {
        $email = $data["email"] ?? throw new Exception("No email.");
        $pwd = bin2hex(random_bytes(6));
        $data["password"] = $pwd;

        return [
            "success" => $this->userModel->add($data),
            "alert" => "Mot de passe généré pour " . $email . " : " . $pwd
        ];

    } catch (PDOException $e) {
        return [
            "success" => false,
            "message" => "Problème serveur.",
            "error" => $e->getMessage()
        ];
    }
}

public function updateUser($id, $data) {
    if($id == null) {
        return [
            "success" => false,
            "message"=> "No user id."
        ];
    }

    try {
        return $this->userModel->update($id, $data);

    } catch (PDOException $e) {
        return [
            "success"=> false,
            "message"=> "Problème serveur. ici",
            "id" => $id,
            "error"=> $e->getMessage()
        ];
    }
    
}


public function resetUser(int $id) {
    if($id == null) {
            return [
            "success" => false,
            "message"=> "No user id."
        ];
    }

    try {
        $pwd = bin2hex(random_bytes(6));
        $data = [
            "password" => $pwd,
            "password_changed_at" => null,
        ];

        return [
            "success" => $this->userModel->reset($id, $data),
            "alert" => "Nouveau mot de passe : " . $pwd
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
        <div class="fx-row jc-center px-20">
            <div class="fx-row container w-600 px-20 py-20 jc-between tools gap-20">
                <div class="fx-row gap-20">
                    <input id="name" type="text" placeholder="label">
                </div>
                <button class="action bg-green" id="add-screen">Add</button>
            </div>
        </div>
        <div class="fx-row jc-center grow">
            <div id="content-screen" class="fx-col grow w-1200"></div>
        </div>';

    try {
        $elements = $this->screenModel->getAll();

        $results = array_map(function($e) {
            return [
                "id" => $e["id"] ?? null,
                "label"  => $e["label"] ?? null,
                "description" => $e["description"] ?? null,
                "format" => $e["format"] ?? null,
                "running" => $e["is_running"] ?? null,
                "updating" => $e["is_updating"] ?? null,
                "controlled" => $e["is_controlled"] ?? null,
                "visible" => $e["is_visible"] ?? null
            ];
        }, $elements);

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



public function addScreen($data) {
    try {
        return $this->screenModel->add($data);

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
        return $this->screenModel->update($id, $data);

    } catch (PDOException $e) {
        return [
            "success"=> false,
            "message"=> "Problème serveur.",
            "id" => $id,
            "error"=> $e->getMessage()
        ];
    }
    
}

/* permissions */
public function getPermissions() {
    $html = '
        <div class="fx-row jc-center px-20">
            <div class="fx-row container w-600 px-20 py-20 jc-between gap-20 wrap">
                <div class="fx-row gap-20 wrap jc-between">
                    <select id="select-user"></select>
                    <select id="select-screen"></select>
                </div>
                <button class="action bg-green" id="add-permission">Add</button>
            </div>
        </div>
        <div class="fx-row jc-center grow">
            <div id="content-permission" class="fx-col grow w-1000"></div>
        </div>';

    try {
        //
        $permissions = $this->permissionModel->getAll();
    
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
        $req = [
            "userId" => $data["userId"] ?? throw new Exception("No user id"),
            "screenId" => $data["screenId"] ?? throw new Exception("No screen id"),
        ];

        try {
            return $this->permissionModel->add($req);

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
    if($id == null) {
        return ["success"=> false, "message"=> "No permission id"];
    }

    try {
        return $this->permissionModel->delete($id);

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
        <div class="fx-row jc-center">
            <div id="content-session" class="fx-col w-1200"></div>
        </div>';

    try {
        $sessions = $this->sessionModel->getAll();

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