<?php

class Auth {

private $db;

public function __construct($database) {
    $this->db = $database;
}

private function connect($email, $password) {  
    $user = null;

    try {
        if(empty($email) or empty($password)) {
            return [
                "success" => false,
                "message" => "Remplir tous les champs.",
            ];
        }

        $user = $this->db->getUserByEmail($email);
        if(!$user) {
            return [
                "success" => false,
                "message" => "Aucun utilisateur trouvé."
            ];
        }
        
    } catch(Exception $e) {
        return [
            "success" => false,
            "message" => "Connexion impossible.",
            "error" => $e->getMessage(),
        ];
    }
        
    try {
        if ($user && password_verify($password, $user['password'])) {
            $token = bin2hex(random_bytes(32));
            $date = new DateTime(); 
            $session = [
                "user_id" => $user["id"],
                "token" => $token,
                //"expires_at" => $date->modify("+7 days")->format("Y-m-d H:i:s")
            ];

            $this->db->addSession($session);
        
            return [
                "success" => true,
                "message" => "Connexion réussie.",
                "data"=> [
                    "token" => $token,
                    "hash" => $user['password']
                ]
            ];

        } else {
            return [
                "success" => false,
                "message" => "Mot de passe incorrect."
            ];
        }

    } catch (Exception $e) {
        return [
            "success"=> false,
            "message" => "Session perdu.",
            "error"=> $e->getMessage()
        ];
    }
}

function disconnect($token) {
    return null;
}

function verify($token, $role) {
    $user = null;
    
    try {
        $user = $this->db->getUserByToken($token);

        if (!$user) {
            return [
                "success" => false,
                "message" => "Aucun utilisateur trouvé.",
            ];
        }

    } catch(Exception $e) {
        return [
            "success" => false,
            "message" => "Erreur pour récupérer le compte",
            "error" => $e->getMessage()
        ];
    }

    try {
        if ((int)$user["role_id"] >= (int)$role) { 
            return [
                "success" => true,
                "message" => "Utilisateur autorisé.",
                "data" => [
                    "role" => $user["role_id"]
                ]
            ];

        } else {
            return [
                "success" => false,
                "message" => "Accès refusé.",
            ]; 
        }
           
    } catch(Exception $e) {
        return [
            "success" => false,
            "message" => "Session expirée.",
            "error" => $e->getMessage()
        ];
    }
}

public function action($act, $params) {

    switch($act) {

        case "connect":
            return $this->connect($params["email"], $params["password"]);
            break;

        case "disconnect":
            return $this->disconnect($params["token"]);
            break;

        case "verify":
            return $this->verify($params["token"], $params["role"]);
            break;

        default:
            return [
                //"alert" => "Un problème est survenu lors de l'authentification."
            ];
    }

}

}

?>