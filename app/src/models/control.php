<?php

class Control {

private $db;

public function __construct($database) {
    $this->db = $database;
}
private function getScreens() {
    $html = '
        <div class="fx-row jc-center p-20">
            <div class="fx-row container w-800 px-20 py-20 jc-between tools">
                <div class="fx-row gap-20">
                    <input id="email" type="email" placeholder="email" >
                    <input id="password" type="password" placeholder="password">
                </div>
                <button class="action" id="add-user">Add</button>
            </div>
        </div>
        <div id="content" class="fx-row jc-center grow"></div>';

    return [
        "success" => true,
        "html"=> $html,
        "data" => $this->db->getScreens()
    ];
}

private function getUsers() {
    $html = '
        <div class="fx-row jc-center p-20">
            <div class="fx-row container w-800 px-20 py-20 jc-between tools">
                <div class="fx-row gap-20">
                    <input id="email" type="email" placeholder="email" >
                    <input id="password" type="password" placeholder="password">
                </div>
                <button class="action" id="add-user">Add</button>
            </div>
        </div>
        <div id="content" class="fx-row jc-center grow"></div>';

    $users = $this->db->getUsers();

    $users = array_map(function($user) {
        return [
            "id"    => $user["id"] ?? $user["created_at"] ?? null,
            "name"  => $user["name"] ?? null,
            "email" => $user["email"] ?? null,
            "password" => $user["password"] ?? null
        ];
    }, $users['data']);

    
    return [
        "success" => true,
        "html"=> $html,
        "data" => $users
    ];
}

private function saveUser($data) {

    $data["password"] = bin2hex($data["password"]);
    return $this->db->addUser($data);
}








public function action($act, $params) {

    switch($act) {

        case "users":
            return $this->getUsers();
            break;


        case "add":
            return $this->saveUser($params);
            break;

        

        default:
            return [
                "success"=> false,
                "error"=> "control"
            ];
    }

}

}

?>