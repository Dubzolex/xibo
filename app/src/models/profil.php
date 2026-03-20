<?php

class Profil {

private $db;

public function __construct($database) {
    $this->db = $database;
}

public function get($token) {
    $result = $this->db->getUserBySessionToken($token);

    if(!$result) {
        return [
            "success" => false,
            "message" => "Session invalide."
        ];
    }

    $change = $result["password_changed_at"];
    $name = $result["name"];

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
            return $result;

        }
    }
}

public function credentials($type) {
    switch($type) {
        case 'onboarding':
        case 'password': 
        case 'name':
            return [
                'data' => [
                    'url' => '/login/credentials/?edit=' . rawurlencode($type),
                ],
            ];
            break;
        
        default:
            return [
            'data' => [
                'url' => '/login/credentials/?edit=' . rawurlencode($type),
            ],
        ];
    }
}

public function edit($type) {
    $htmlTop = `
        <div class="fx-col ai-center">
            <h3>Change your profil.</h3>
        </div>
    `;
    
    $htmlName = `
        <div class="fx-col gap-10">
            <div class="fx-row">Name :</div>
            <input type="text" id="name" required />
        </div>
    `;
    
    $htmlPassword = `
        <div class="fx-col gap-10">
            <div class="fx-row">Password :</div>
            <input type="password" id="password" required />
        </div>
    `;
    
    $htmlBottom = `
        <div class="fx-col ai-center">
            <button id="save" class="action">Save</button>
        </div>
    `;
    
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
    $result = $this->db->updateUserByToken($token, $data);

    if(!$result) {
        return [
            "success" => false,
            "message" => "Sauvegarde échouée."
        ];
    }

    return $result;
}

public function action($act, $params) {

    $token = $params["token"];
    if(empty($token)) {
        return [
            "success" => false,
            "message" => "Session invalide."
        ];
    }

    switch($act) {
        case "get":
            return $this->get($token);
            break;

        case "edit":
            return $this->edit($params["type"]);
            break;
        
        case "save":
            return $this->save($params["data"], $params["token"], );
            break;
    }

}

}

?>