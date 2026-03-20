<?php

class Control {

private $db;

public function __construct($database) {
    $this->db = $database;
}
public function getScreens() {
    $html = '
        <div class="fx-row jc-center p-20">
            <div class="fx-row container w-800 px-20 py-20 jc-between tools gap-20">
                <div class="fx-row gap-20">
                    <input id="name" type="text" placeholder="name">
                </div>
                <button class="action" id="add-screen">Add</button>
            </div>
        </div>
        <div class="fx-row jc-center grow p-20">
            <div id="content-screen" class="fx-col grow w-600 gap-40"></div>
        </div>';

    $screen = $this->db->getScreens();

    $result = array_map(function($s) {
        return [
            "id"    => $s["id"] ?? $s["created_at"] ?? null,
            "name"  => $s["name"] ?? null,
            "running" => $s["is_running"] ?? null,
            "updating" => $s["is_updating"] ?? null,
            "controlled" => $s["is_controlled"] ?? null
        ];
    }, $screen['data']);

    return [
        "success" => true,
        "html"=> $html,
        "data" => $result
    ];
}

public function getUsers() {
    $html = '
        <div class="fx-row jc-center p-20">
            <div class="fx-row container w-800 px-20 py-20 jc-between tools gap-20 ai-center">
                <div class="fx-row gap-20 wrap">
                    <input id="email" type="email" placeholder="email" >
                    <input id="password" type="password" placeholder="password">
                </div>
                <button class="action" id="add-user">Add</button>
            </div>
        </div>
        <div class="fx-row jc-center grow p-20">
            <div id="content-user" class="fx-col grow w-600 gap-40"></div>
        </div>';

    $user = $this->db->getUsers();

    $result = array_map(function($user) {
        return [
            "id"    => $user["id"] ?? null,
            "name"  => $user["name"] ?? null,
            "email" => $user["email"] ?? null,
        ];
    }, $user['data']);
    
    return [
        "success" => true,
        "html"=> $html,
        "data" => $result
    ];
}

public function addUser($data) {
    $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
    return $this->db->addUser($data);
}



}



?>