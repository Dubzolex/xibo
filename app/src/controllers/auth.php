<?php

class Auth {

private $userModel;
private $sessionModel;

public function __construct($db) {
    $this->userModel = new User($db);
    $this->sessionModel = new Session($db);
}

public function connect($email, $password) {  
    if(empty($email) or empty($password)) {
        return [
            "success" => false,
            "message" => "Remplir tous les champs.",
        ];
    }

    try {
        $user = $this->userModel->getByEmail($email);
        if(!$user) {
            return [
                "success" => false,
                "message" => "Aucun utilisateur trouvé."
            ];
        }
        
        if (isset($user["password"]) && password_verify($password, $user['password'])) {
            $token = bin2hex(random_bytes(32));
            $date = new DateTime();
            $expiresAt = (clone $date)->modify("+7 days")->format("Y-m-d H:i:s");
            
            try {
                $session = [
                    "userId" => $user["id"] ?? throw new Exception("No user id."),
                    "token" => $token ?? throw new Exception("No token generate."),
                    "expiresAt" => $expiresAt
                ];
            
                $this->sessionModel->add($session);

            } catch (PDOException $e) {
                return [
                    "success" => false,
                    "message" => "Session perdu.",
                    "error" => $e->getMessage()
                ];
            }
        
            return [
                "success" => true,
                "message" => "Connexion réussie.",
                "data"=> [
                    "token" => $token
                ]
            ];
        }

        return [
            "success" => false,
            "message" => "Mot de passe incorrect.",
            "data" => [
                /*"send" => $password,
                "send_hash" => password_hash($password, PASSWORD_DEFAULT),
                "hash" => $user["password"]*/
            ]
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

public function verify($token, $critere) {
    try {
        $user = $this->userModel->verify($token);

        $role = $user["role_id"] ?? 0;

        return ($critere <= $role) && ($role <= 4);
        
    } catch(Exception $e) {
        return false;
    }
}

}

?>