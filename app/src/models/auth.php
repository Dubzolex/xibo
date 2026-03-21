<?php

class Auth {

private $db;

public function __construct($database) {
    $this->db = $database;
}

public function connect($email, $password) {  
    if(empty($email) or empty($password)) {
        return [
            "success" => false,
            "message" => "Remplir tous les champs.",
        ];
    }

    try {
        $user = $this->db->getUserByEmail($email);
        if(!$user) {
            return [
                "success" => false,
                "message" => "Aucun utilisateur trouvé."
            ];
        }
        
        if (isset($user["password"]) && password_verify((string)$password, $user['password'])) {
            $token = bin2hex(random_bytes(32));
            
            $date = new DateTime();
            $expiresAt = (clone $date)->modify("+7 days")->format("Y-m-d H:i:s");
            
            try {
                $session = [
                    "user_id" => $user["id"] ?? throw new Exception("No user id."),
                    "token" => $token ?? throw new Exception("No token generate."),
                    "expires_at" => $expiresAt
                ];
            
                $this->db->addSession($session);

            } catch (PDOException $e) {
                return [
                    "success"=> false,
                    "message" => "Session perdu.",
                    "error"=> $e->getMessage()
                ];
            }
        
            return [
                "success" => true,
                "message" => "Connexion réussie.",
                "data"=> [
                    "token" => $token
                    //"hash" => $user['password'] ?? null
                ]
            ];
        }

        return [
            "success" => false,
            "message" => "Mot de passe incorrect.",
            //"password" => $password,
            //"user" => $user
        ];

    } catch(PDOException $e) {
        return [
            "success" => false,
            "message" => "Problème serveur",
            "error" => $e->getMessage(),
        ];
    }
}

public function disconnect($token = null) {
    return [];
}

public function verify($token, $role = 1) {
    try {
        $user = $this->db->getUserByToken($token);

        if (!$user) {
            return [
                "success" => false,
                "message" => "Aucun utilisateur trouvé.",
                "alert" => "Token invalide.",
                "url" => "/login/"
            ];
        }

    } catch(Exception $e) {
        return [
            "success" => false,
            "message" => "Problème serveur.",
            "error" => $e->getMessage(),
            "alert" => "Token invalide.",
            "url" => "/login/"
        ];
    }
}


/*
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
*/
}

?>