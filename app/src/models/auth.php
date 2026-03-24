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
        $user = $this->db->getSessionByToken($token);

        if (!isset($user["id"])) {
            return [
                "success" => false,
                "message" => "Token invalide.",
                "url" => "/login/",
            ];
        }

        $role = $user["role_id"] ?? null;

        return [
            "success"=> true,
            "message"=> "Utilisateur connecté.",
            "html" => $this->show($role),
            "data" => [
                "role" => $role,
                "user" => $user
            ]
        ];

    } catch(Exception $e) {
        return [
            "success" => false,
            "message" => "Problème serveur.",
            "error" => $e->getMessage(),
            "url" => "/login/",
            "data" => [
                "token" => $token
            ]
        ];
    }
}



public function show($role) {
    $home = '<a href="/home/"><h4>Home</h4></a>';
    $edit = '<a href="/roles/"><h4>Edit</h4></a>';
    $profil = '<a href="/login/account/"><h4>Profil</h4></a>';
    $admin = '<a href="/roles/manage/"><h4>Manage</h4></a>';

    if($role > 1) {
        return $home . $edit . $admin . $profil;
    }

    return $home . $edit . $profil;
}

}

?>