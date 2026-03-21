<?php

header('Content-Type: application/json');

//ini_set('display_errors', 0);

require __DIR__ . "/../backend/db.php";
require __DIR__ . "/../models/auth.php";
require __DIR__ . "/../models/media.php";
require __DIR__ . "/../models/profil.php";
require __DIR__ . "/../models/guard.php";
require __DIR__ . "/../models/control.php";


$db = Database::getInstance();
$media = new Media($db);
$auth = new Auth($db);
$profil = new Profil($db);
$guard = new Guard($db);
$control = new Control($db);

$routes = [
    /* Module Auth */
    "AUTH_LOGIN" => function($req) use ($auth) {
        return $auth->connect($req["email"]  ?? $_GET["email"] ?? null, $req["password"]  ?? $_GET["password"]?? null);
    },

    "AUTH_LOGOUT" => function($req) use ($auth) {
            return $auth->disconnect();
    },

    "AUTH_VERIFY" => function($req) use ($auth) {
            return $auth->verify($req["token"] ?? $_GET["token"], 1);
    },

    "AUTH_VERIFY_EDITOR" => function($req) use ($auth) {
            return $auth->verify($req["token"] ?? $_GET["token"], 2);
    },

    "AUTH_VERIFY_MANAGER" => function($req) use ($auth) {
            return $auth->verify($req["token"] ?? $_GET["token"], 3);
    },

    "AUTH_VERIFY_ADMIN" => function($req) use ($auth) {
            return $auth->verify($req["token"] ?? $_GET["token"], 4);
    },


    /* Module Media */

    "MEDIA_ALL" => function($req) use ($media) {
        return $media->getAll();
    },

    "MEDIA_IMAGES_ID" => function($req) use ($media) {
        return $media->getById($req["screenId"] ?? $req["id"] ?? $_GET["id"]);
    },

    "MEDIA_EDIT" => function($req) use ($media) {
        return $media->edit($req["token"] ?? null);
    },

    "MEDIA_UPLOAD" => function($req) use ($media) {
        return $media->upload($req["screenId"] ?? $req["id"] ?? $_GET["id"], $req["file[]"]);
    },

    "MEDIA_DELETE" => function($req) use ($media) {
        return $media->delete($req["screenId"] ?? $req["id"] ?? $_GET["id"], $req["file[]"]);
    },


    /* Module Profil */

    "PROFIL_GET" => function($req) use ($profil) {
        return $profil->get($req["token"] ?? $_GET["token"]);
    },

    "PROFIL_EDIT" => function($req) use ($profil) {
        return $profil->edit($req["type"] ?? $_GET["type"]);
    },

    "PROFIL_SAVE" => function($req) use ($profil) {
        return $profil->save($req["token"] ?? $_GET["token"], $req["data"] ?? []);
    },


    /* Control Users */

    "CONTROL_GET_USER" => function($req) use ($control) {
        return $control->getUsers();
    },

    "CONTROL_ADD_USER" => function($req) use ($control) {
        return $control->addUser($req["data"]);
    },

    "CONTROL_UPDATE_USER" => function($req) use ($control) {
        return $control->updateUser($req["userId"] ?? $req["id"], $req["data"]);
    },

    "CONTROL_RESET_USER" => function($req) use ($control) {
        return $control->resetUser($req["userId"] ?? $req["id"] ?? $_GET["id"]);
    },

    /* Control Screens */

    "CONTROL_GET_SCREEN" => function($req) use ($control) {
        return $control->getScreens();
    },

    "CONTROL_ADD_SCREEN" => function($req) use ($control) {
        return $control->addScreen($req["data"]);
    },

    "CONTROL_UPDATE_SCREEN" => function($req) use ($control) {
        return $control->updateScreen($req["screenId"] ?? $req["id"], $req["data"]);
    },

    /* Control Permissions */

    "CONTROL_GET_PERMISSION" => function($req) use ($control) {
        return $control->getPermissions();
    },

    "CONTROL_ADD_PERMISSION" => function($req) use ($control) {
        return $control->addPermission($req["data"]);
    },

    "CONTROL_DELETE_PERMISSION" => function($req) use ($control) {
        return $control->deletePermission($req["permissionId"] ?? $req["id"]);
    },

    /* Control Permissions */

    "CONTROL_GET_SESSION" => function($req) use ($control) {
        return $control->getSessions();
    }






];

function api($routes) {
    $action = $_GET["action"] ?? null;

    if(!$action) {
        return [
            "success" => false,
            "message" => "Action non fournie.",
            "action" => $action
        ];
    }

    if(isset($routes[$action])) {
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);

        return $routes[$action]($json);
    }

    return [
        "success" => false,
        "message" => "Action non identifée.",
        "action" => $action
    ];
    
}

$result = api($routes);
echo json_encode($result);