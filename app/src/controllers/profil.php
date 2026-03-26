<?php

class Profil {

private $userModel;
private $screenModel;

public function __construct($db) {
    $this->userModel = new User($db);
    $this->screenModel = new Screen($db);
}

public function get($token) {
    try {
        $user = $this->userModel->verify($token);
        if(empty($user)) {
            return [
                "success" => false,
                "message" => "Session invalide."
            ];
        }

        $change = $u["password_changed_at"] ?? null;
        $name = $u["name"] ?? null;

        if (empty($change) && empty($name)) {
            return $this->credentials("onboarding");
        }

        if (empty($change)) {
            return $this->credentials("password");
        } 
            
        if (empty($name)) {
            return $this->credentials("name");
        }

    } catch(PDOException $e) {
        return [
            "success"=> false,
            "message"=> "Problème serveur.",
            "error" => $e->getMessage()
        ];
    }

    try {
        $user = $this->userModel->getByToken($token);
        
        if(empty($user)) {
            return [
                "success" => false,
                "message" => "Session invalide."
            ];
        }

        $result = [
            "id" => $user["id"],
            "name" => $user["name"],
            "role" => $user["role"],
            "email" => $user["email"],
            "updatedAt" => $user["updated_at"],
            "screens" => explode(",", $user["label"]) ?? []
        ];

        return [
            "success" =>true,
            "data" => $result
        ];

    } catch(PDOException $e) {
        return [
            "success"=> false,
            "message"=> "Problème serveur.",
            "error" => $e->getMessage(),
            "token" => $token
        ];
    }
}

public function credentials($type) {
    switch($type) {
        case 'onboarding':
        case 'password': 
        case 'name':
            return [
                'url' => '../credentials/?edit=' . rawurlencode($type),
            ];
    }
}

public function edit($type) {
    $htmlTop = '
        <div class="fx-col ai-center">
            <h3>Change your profil.</h3>
        </div>
        <div class="fx-col gap-40">';
    
    $htmlName = '
        <div class="fx-row jc-center">
            <div class="fx-col gap-10">
                <div class="fx-row">Name :</div>
                <input type="text" id="input-name" name="name" />
            </div>
        </div>';
    
    $htmlPassword = '
        <div class="fx-row jc-center">
            <div class="fx-col gap-10">
                <div class="fx-row">Password :</div>
                <input type="password" id="input-password" name="password" />
            </div>
        </div>';
    
    $htmlBottom = '
        </div>
        <div class="fx-col gap-40 ai-center">
            <div id="status"></div>
            <div class="fx-col ai-center">
                <button id="save" class="action">Save</button>
            </div>
        </div>';
    
    switch($type) {
        case "onboarding":
            return [
                "html" => $htmlTop . $htmlName . $htmlPassword . $htmlBottom
            ];
            break;

        case "name":
            return [
                "html" => $htmlTop . $htmlName . $htmlBottom
            ];
            break;

        case "password":
            return [
                "html" => $htmlTop . $htmlPassword . $htmlBottom
            ];
            break;
        
    }
}

public function save($token, $data) {
    try {
        if(isset($data["password"])) {
            $data["passwordChangedAt"] = date("Y-m-d H:i:s");
        }    

        return [
            "success" => $this->userModel->updateByToken($token, $data),
            "message" => "Profil enregistré.",
            "data" => $data
        ]; 

    } catch (PDOException $e) {
        return [
            "success" => false,
            "message"=> "Problème serveur.",
            "error" => $e->getMessage()
        ];
    }
}

}

?>