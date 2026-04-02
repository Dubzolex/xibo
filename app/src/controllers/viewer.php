<?php

class Viewer {

private $screenModel;
private $mediaModel;
private $authModel;

public function __construct($db) {
    $this->screenModel = new Screen($db);
    $this->mediaModel = new Media($db);
    $this->authModel = new Auth($db);
}

public function get() {
    try {
        $elements = $this->screenModel->getAll();

        $results = array_map(function($item) {
            return [
                "id" => $item["id"] ?? null,
                "label" => $item["label"] ?? null,
                "running" => $item["is_running"] ?? null,
                "images" => $this->mediaModel->get($item["id"]) ?? [],
            ];
        }, $elements);

        return [
            "success" => true,
            "data" => $results
        ];

    } catch(Exception $e) {
        return [
            "success"=> false,
            "error"=> $e->getMessage(),
        ];
    }
}

public function show($token) {
    try {
        $home = '<a href="/home/"><h4>Home</h4></a>';
        $edit = '<a href="/roles/"><h4>Media</h4></a>';
        $profil = '<a href="/login/account/"><h4>Profile</h4></a>';
        $manage = '<a href="/roles/admin/"><h4>Admin</h4></a>';

       
        if($this->authModel->verify($token, 2)) {
            return [
                "html" => $home . $edit . $manage . $profil
            ];
        }
        if($this->authModel->verify($token, 1)) {
            return [
                "html" => $home . $edit . $profil
            ];
        }
        throw new Exception("Token invalide"); 

    } catch(Exception $e) {
        return [
            "html" => null,
            "error" => $e->getMessage()
        ];
    }
}

}

?>