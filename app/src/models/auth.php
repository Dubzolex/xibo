<?php

class Auth {

private $db;

public function __construct($database) {
    $this->db = $database;
}

public function connect($email, $password) {  
    $user = null;

    try {
        if(empty($email) or empty($password)) {
            return [
                "success" => false,
                "message" => "Remplir tous les champs.",
            ];
        }

        $user = $this->db->getUserByEmail($email);
        if(!$user["success"]) {
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
        
        if (password_verify((string)$password, $user['data']['password'] ?? null)) {
            $token = bin2hex(random_bytes(32));
            
            $date = new DateTime();
            
            $expiresAt = (clone $date)->modify("+7 days")->format("Y-m-d H:i:s");
            
            $session = [
                "user_id" => $user["data"]["id"] ?? null,
                "token" => $token ?? null,
                "expires_at" => $expiresAt
            ];
            

            $this->db->addSession($session);
        
            return [
                "success" => true,
                "message" => "Connexion réussie.",
                "data"=> [
                    "token" => $token
                    //"hash" => $user['data']['password'] ?? null
                ]
            ];

        } else {
            return [
                "success" => false,
                "message" => "Mot de passe incorrect.",
                "password" => $password,
                "user" => $user
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

public function disconnect($token = null) {
    return [];
}

public function verify($token, $role = 1) {
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

}

?>