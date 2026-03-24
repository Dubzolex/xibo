<?php

class Guard {

private $db;
private $auth;
private $media;
private $tool;

public function __construct($database) {
    $this->db = $database;
    //$this->auth = new Auth();
    //$this->media = new Media();
    //$this->tool = new Tool();
}
/*
private function authorizeScreensAndImages($token) {
    $v = $this->auth->verify($token, 1);
    if(!$v["success"]) {
        return $v;
    }

    $screens = [];
    try {
        $screens = $this->db->getScreens(); 

    } catch(Exception $e) {
        return [
            "success" => false,
            "message" => "Erreur lors de la récupération des écrans",
            "error" => $e.getMessage()
        ];
    }

    try {
        $results = [];

        foreach (transformScreens($screens) as $screen) {
            $screen["images"] = $this->media->get($screen["id"]);
            $results[] = $screen;
        }

        return [
            "success" => true,
            "message" => "Succès",
            "data"    => $results
        ];

    } catch(Exception $e) {
        return [
            "success" => false,
            "message" => "Erreur lors de la récupération des images.",
            "error" => $e.getMessage()
        ];
    }
}

private function authorizeScreens($token) {
    $v = $this->auth->verify($token, 1);
    if(!$v["success"]) {
        return $v;
    }

    $screens = [];
    try {
        if($session["data"]["role"] >= 3) {
            $screens = $this->db->getScreens();
        } else {
            $screens = $this->db->getScreensByToken($token); 
        }

        return [
            "success" => true,
            "message" => "",
            "data" => $this->transformScreen($screens)
        ];

    } catch(Exception $e) {
        return [
            "success" => true,
            "message" => "Erreur lors de la récupération des écrans.",
        ];
    }
}

private function authorizeImagesByScreenId($token, $screenId) {

    $screens = [];
    try {
        $screens = $this->authorizeScreens($token);

    } catch(Exception $e) {
        return [
            "success" => false,
            "error" => $e->getMessage()
        ];
    }

    foreach ($screens["data"] as $screen) {
        if ($screen["id"] == $screenId) {
            $result = $screen;
            break;
        }
    }

    if (!$result) {
        return [
            "success" => false,
            "message" => "Accès refusé.",
            "data"    => null
        ];
    }

    try {
        // Ajout des images à l'écran trouvé
        $html = null;

        return [
            "success" => true,
            "message" => "Succès",
            "data"    => [
                "images" => $this->media->get($result["id"]),
            ],
            "html" => $html
        ];

    } catch(Exception $e) {
        return [
            "success" => false,
            "message" => "Erreur lors de la récupération des images.",
            "error" => $e.getMessage()
        ];
    }
}

public function authorizeScreens($token) {
*/
}

?>