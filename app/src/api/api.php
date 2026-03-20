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
    "AUTH_LOGIN" => function($d) use ($auth) {
       /* $d = [
            "email" => "enzo",
            "password" => "12345"
        ];*/



        return $auth->connect($d["email"] ?? null, $d["password"] ?? null);
    },

    "AUTH_LOGOUT" => function($d) use ($auth) {
            return $auth->disconnect();
    },

    "AUTH_VERIFY" => function($d) use ($auth) {
            return $auth->verify($d["token"], $d["role"]);
    },


    /* Module Media */

    "MEDIA_ALL" => function($d) use ($media) {
        return $media->getAll();
    },

    "MEDIA_IMAGES_ID" => function($d) use ($media) {
        return $media->getById($d["screenId"]);
    },

    "MEDIA_EDIT" => function($d) use ($media) {
        return $media->edit($d["token"]);
    },

    "MEDIA_UPLOAD" => function($d) use ($media) {
        return $media->upload($d["screenId"], $d["file[]"]);
    },

    "MEDIA_DELETE" => function($d) use ($media) {
        return $media->delete($d["screenId"], $d["file[]"]);
    },


    /* Module Profil */

    "PROFIL_ACCOUNT" => function($d) use ($profil) {
        return $profil->get($d["token"]);
    },

    "PROFIL_EDIT" => function($d) use ($profil) {
        return $profil->edit($d["type"]);
    },

    "PROFIL_SAVE" => function($d) use ($profil) {
        return $profil->save($d["token"], $d["data"]);
    },


    /* Control Profil */
    "CONTROL_GET_SCREEN" => function($d) use ($control) {
        return $control->getScreens();
    },

    "CONTROL_GET_USER" => function($d) use ($control) {
        return $control->getUsers();
    },

    "CONTROL_ADD_USER" => function($d) use ($control) {
        return $control->addUser($d);
    },

    "CONTROL_GET_SESSION" => function($d) use ($control) {
        return $control->getSession();
    }
];

function api($routes) {
    
    $action = $_GET["action"] ?? null;

    if(isset($routes[$action])) {
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);

        return $routes[$action]($json);
    }   
    return [
        "success" => false,
        "message" => "Action non fournie.",
        "action" => $action
    ];
}




$result = api($routes);
echo json_encode($result);