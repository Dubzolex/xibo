<?php

class Profil {

private $db;

public function __construct($database) {
    $this->db = $database;
}

public function get($token) {
    $result = null;
    try {
        $result = $this->db->getUserBySessionToken($token);
        if(!$result) {
            return [
                "success" => false,
                "message" => "Session invalide."
            ];
        }

    } catch(PDOException $e) {
        return [
            "success"=> false,
            "message"=> "Problème serveur.",
            "error" => $e->getMessage()
        ];
    }

    $change = $result["password_changed_at"] ?? null;
    $name = $result["name"] ?? null;

    if(empty($change)) {
        if(empty($name)) {
            return $this->credentials("onboarding");

        } else {
            return $this->credentials("password");

        }
    } else {
        if(empty($name)) {
            return $this->credentials("name");

        } else {
            return [
                "success" =>true,
                "data" => $result
            ];
        }
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
        </div>';
    
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
        <div class="fx-col ai-center">
            <button id="save" class="action">Save</button>
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
        return [
            "success" => $this->db->updateUserByToken($token, $data),
            "message" => "Profil enregistré."
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