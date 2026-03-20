<?php

header('Content-Type: application/json');

require __DIR__ . "/../backend/db.php";
require __DIR__ . "/../models/auth.php";
require __DIR__ . "/../models/media.php";
require __DIR__ . "/../models/profil.php";
require __DIR__ . "/../models/guard.php";
require __DIR__ . "/../models/control.php";





$db = new Database();
$media = new Media($db);
$auth = new Auth($db);
$profil = new Profil($db);
$guard = new Guard($db);
$control = new Control($db);

$routes = [
    /* Module Auth */
    "AUTH_LOGIN" => function($d) => use ($auth) {
        return $auth->connect($d["email"], $d["password"]);
    },

    "AUTH_DISCONNECT" => function($d) => use ($auth) {
            return $auth->disconnect();
    }

    "AUTH_VERIFY" => function($d) => use ($auth) {
            return $this->verify($d["token"], $d["role"]);
    }


    /* Module Media */

    "MEDIA_ALL" => function($d) => use ($media) {
        return $media->getAll()
    },

    "MEDIA_IMAGES_ID" => function($d) => use ($media) {
        return $media->getById($d["screenId"]);
    },

    "MEDIA_EDIT" => function($d) => use ($media) {
        return $media->edit($d["token"]);
    },

    "MEDIA_UPLOAD" function($d) => use ($media) {
        return $media->upload($d["screenId"], $d["file[]"]);
    },

    "MEDIA_DELETE" function($d) => use ($media) {
        return $media->delete($d["screenId"], $d["file[]"]);
    },


    /* Module Profil */

    "PROFIL_ACCOUNT" function($d) => use ($profil) {
        return $profil->get($d["token"]);
    },

    "PROFIL_EDIT" function($d) => use ($profil) {
        return $profil->edit($d["type"]);
    },

    "PROFIL_SAVE" function($d) => use ($profil) {
        return $profil->save($d["token"], $d["data"])
    },

];






function api() {
    $action = $_GET["action"] ?? null;

    if(isset($routes[$action])) {
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);

        return $routes[$action]($json);
    }   
    return [
        "success" => false,
        "message" => "Action non fournie."
    ]
}




$result = api();
echo json_encode($result);